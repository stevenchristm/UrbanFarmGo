package controllers

import (
	"encoding/json"
	"fmt"
	"log"
	"net/http"
	"strings"
	"urbangrow/config"
	"urbangrow/models"

	"github.com/gin-gonic/gin"
)

// ReqCreateSpace adalah input form "Daftarkan Lahan" dari frontend
type ReqCreateSpace struct {
	UserID           uint    `json:"user_id" binding:"required"`
	NamaLahan        string  `json:"nama_lahan" binding:"required"`
	LokasiLahan      string  `json:"lokasi_lahan" binding:"required"`
	LuasLahan        float64 `json:"luas_lahan" binding:"required"`
	SuhuRataRata     float64 `json:"suhu_rata_rata" binding:"required"`
	SinarMatahariJam float64 `json:"sinar_matahari_jam" binding:"required"`
}

// CreateSpace meregistrasikan lahan baru dan langsung meminta rekomendasi AI Gemini
func CreateSpace(c *gin.Context) {
	var req ReqCreateSpace
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Format input tidak valid. Pastikan semua kolom terisi."})
		return
	}

	// 1. Simpan data awal lahan ke MySQL (GORM otomatis aman dari SQL Injection)
	space := models.Space{
		UserID:           req.UserID,
		NamaLahan:        req.NamaLahan,
		LokasiLahan:      req.LokasiLahan,
		LuasLahan:        req.LuasLahan,
		SuhuRataRata:     req.SuhuRataRata,
		SinarMatahariJam: req.SinarMatahariJam,
	}
	if err := config.DB.Create(&space).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal menyimpan data lahan ke database."})
		return
	}

	// Ambil dataset dari database untuk konteks AI
	var katalog []models.KatalogTanaman
	config.DB.Find(&katalog)
	var listTanaman []string
	for _, k := range katalog {
		listTanaman = append(listTanaman, k.NamaTanaman)
	}
	datasetTanaman := strings.Join(listTanaman, ", ")

	// 2. Panggil API AI dengan konteks dataset
	prompt := fmt.Sprintf(`Analisis dan berikan rekomendasi tanaman pertanian perkotaan terbaik untuk:
Lokasi: %s, Luas Lahan: %.2f m2, Suhu: %.2f derajat Celcius, Sinar Matahari: %.2f jam/hari.

PENTING: Pilih tanaman HANYA dari dataset berikut ini: %s.

Kembalikan hasilnya dalam format JSON HANYA (tanpa markdown atau kalimat pembuka) dengan field:
- "tanaman_rekomendasi": array string nama tanaman (minimal 3)
- "kapasitas_tanaman": integer estimasi jumlah bibit
- "estimasi_panen_hari": integer estimasi siklus panen dalam hari`,
		req.LokasiLahan, req.LuasLahan, req.SuhuRataRata, req.SinarMatahariJam, datasetTanaman)

	responseText, err := callGroqAPI(prompt)
	if err != nil {
		log.Printf("Groq API error: %v", err)
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal mendapatkan rekomendasi dari AI."})
		return
	}

	// Buang markdown block jika ada
	firstBrace := strings.Index(responseText, "{")
	lastBrace := strings.LastIndex(responseText, "}")
	if firstBrace != -1 && lastBrace != -1 && lastBrace > firstBrace {
		responseText = responseText[firstBrace : lastBrace+1]
	}

	var aiResult struct {
		TanamanRekomendasi []string `json:"tanaman_rekomendasi"`
		KapasitasTanaman   int      `json:"kapasitas_tanaman"`
		EstimasiPanenHari  int      `json:"estimasi_panen_hari"`
	}
	if err := json.Unmarshal([]byte(responseText), &aiResult); err != nil {
		log.Printf("Gagal parse JSON dari Gemini: %s", responseText)
		c.JSON(http.StatusInternalServerError, gin.H{
			"error":        "Format respons AI tidak valid.",
			"raw_response": responseText,
		})
		return
	}

	// 4. Update lahan dengan hasil rekomendasi AI
	tanamanBytes, _ := json.Marshal(aiResult.TanamanRekomendasi)
	space.TanamanRekomendasi = string(tanamanBytes)
	space.KapasitasTanaman = aiResult.KapasitasTanaman
	space.EstimasiPanenHari = aiResult.EstimasiPanenHari

	if err := config.DB.Save(&space).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal menyimpan hasil AI ke database."})
		return
	}

	// 5. Kembalikan respons sukses
	c.JSON(http.StatusCreated, gin.H{
		"message": "Lahan berhasil didaftarkan dan dianalisis oleh AI!",
		"data":    space,
		"ai":      aiResult,
	})
}

// GetDashboardSummary mengambil ringkasan data untuk halaman Dashboard
func GetDashboardSummary(c *gin.Context) {
	var totalSpaces int64
	var totalKapasitas int64
	var recentSpaces []models.Space

	config.DB.Model(&models.Space{}).Count(&totalSpaces)
	config.DB.Table("spaces").Select("COALESCE(SUM(kapasitas_tanaman), 0)").Row().Scan(&totalKapasitas)

	if err := config.DB.Order("created_at desc").Limit(5).Find(&recentSpaces).Error; err != nil {
		log.Printf("Error GetDashboardSummary Find recentSpaces: %v", err)
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal mengambil data lahan."})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"summary": gin.H{
			"total_lahan":     totalSpaces,
			"total_kapasitas": totalKapasitas,
		},
		"recent_spaces": recentSpaces,
	})
}

// GetSpaces mengambil semua lahan milik user yang sedang login
func GetSpaces(c *gin.Context) {
	userID, exists := c.Get("user_id")
	if !exists {
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Unauthorized"})
		return
	}

	var spaces []models.Space
	if err := config.DB.Where("id_user = ?", userID).Order("created_at desc").Find(&spaces).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal mengambil data lahan."})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"message": "Data lahan berhasil diambil",
		"data":    spaces,
	})
}
