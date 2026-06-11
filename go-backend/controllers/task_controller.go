package controllers

import (
	"net/http"
	"urbangrow/config"
	"time"

	"github.com/gin-gonic/gin"
)

// Struktur khusus untuk mapping hasil join Laravel penjadwalan
type TaskResponse struct {
	ID          uint   `json:"id"`
	Title       string `json:"title"`
	Description string `json:"description"`
	Status      string `json:"status"`
}

// GetTasks mengambil tugas hari ini dari tabel penjadwalans dan penjadwalan_details
func GetTasks(c *gin.Context) {
	userID, exists := c.Get("user_id")
	if !exists {
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Unauthorized"})
		return
	}

	var results []TaskResponse

	// Query langsung (raw query) untuk mensimulasikan logika JadwalController Laravel
	// 1. Ambil semua Penjadwalan milik user
	// 2. Hitung selisih hari dari tanggal_tanam ke hari ini
	// 3. Ambil PenjadwalanDetail yang sesuai dengan hari_ke tersebut
	// 4. Cek LogPerawatan apakah sudah selesai
	query := `
		SELECT 
			pd.id as id,
			pd.kegiatan as title,
			pd.deskripsi as description,
			IF(lp.id IS NOT NULL, 'completed', 'pending') as status
		FROM penjadwalans p
		JOIN penjadwalan_details pd ON p.id = pd.penjadwalan_id
		LEFT JOIN log_perawatans lp ON p.id = lp.penjadwalan_id AND DATE(lp.tanggal_selesai) = CURDATE()
		WHERE p.user_id = ? AND pd.hari_ke = DATEDIFF(CURDATE(), p.tanggal_tanam) + 1
	`

	if err := config.DB.Raw(query, userID).Scan(&results).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal mengambil data tugas."})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"message": "Daftar tugas berhasil diambil",
		"data":    results,
	})
}

// CompleteTask memasukkan baris ke log_perawatans untuk menandai tugas selesai
func CompleteTask(c *gin.Context) {
	userID, exists := c.Get("user_id")
	if !exists {
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Unauthorized"})
		return
	}

	detailID := c.Param("id")

	// Cari detail tugas untuk mendapatkan penjadwalan_id dan hari_ke
	type DetailInfo struct {
		PenjadwalanID uint
		HariKe        int
		UserID        uint
	}
	var info DetailInfo

	query := `
		SELECT pd.penjadwalan_id, pd.hari_ke, p.user_id
		FROM penjadwalan_details pd
		JOIN penjadwalans p ON pd.penjadwalan_id = p.id
		WHERE pd.id = ?
	`
	if err := config.DB.Raw(query, detailID).Scan(&info).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Tugas tidak ditemukan."})
		return
	}

	if info.UserID != userID.(uint) {
		c.JSON(http.StatusForbidden, gin.H{"error": "Anda tidak berhak menyelesaikan tugas ini."})
		return
	}

	// Insert ke log_perawatans
	insertQuery := `
		INSERT INTO log_perawatans (penjadwalan_id, step, status, tanggal_selesai, created_at, updated_at)
		VALUES (?, 1, 'completed', ?, ?, ?)
	`
	now := time.Now()
	if err := config.DB.Exec(insertQuery, info.PenjadwalanID, now.Format("2006-01-02"), now, now).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal menyimpan log perawatan."})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"message": "Tugas berhasil diselesaikan!",
	})
}
