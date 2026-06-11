package controllers

import (
	"bytes"
	"encoding/json"
	"fmt"
	"io"
	"net/http"
	"os"
	"strings"
	"urbangrow/config"
	"urbangrow/models"

	"github.com/gin-gonic/gin"
)

type ReqRecommendCrop struct {
	LokasiLahan      string  `json:"lokasi_lahan" binding:"required"`
	LuasLahan        float64 `json:"luas_lahan" binding:"required"`
	SuhuRataRata     float64 `json:"suhu_rata_rata" binding:"required"`
	SinarMatahariJam float64 `json:"sinar_matahari_jam" binding:"required"`
}

type ReqAiChat struct {
	Message string `json:"message"`
	Image   string `json:"image"` // base64
}

// OpenWeather Fetcher
func getWeatherContext(lat, lon, city string) string {
	apiKey := os.Getenv("OPENWEATHER_API_KEY")
	var url string
	if lat != "" && lon != "" {
		url = fmt.Sprintf("https://api.openweathermap.org/data/2.5/forecast?lat=%s&lon=%s&appid=%s&units=metric&lang=id", lat, lon, apiKey)
	} else {
		if city == "" {
			city = "Malang"
		}
		url = fmt.Sprintf("https://api.openweathermap.org/data/2.5/forecast?q=%s&appid=%s&units=metric&lang=id", city, apiKey)
	}

	resp, err := http.Get(url)
	if err != nil || resp.StatusCode != 200 {
		return "Data cuaca tidak tersedia."
	}
	defer resp.Body.Close()

	var data map[string]interface{}
	json.NewDecoder(resp.Body).Decode(&data)

	list, ok := data["list"].([]interface{})
	if !ok || len(list) == 0 {
		return "Data cuaca tidak tersedia."
	}

	current := list[0].(map[string]interface{})
	main := current["main"].(map[string]interface{})
	weather := current["weather"].([]interface{})[0].(map[string]interface{})

	temp := main["temp"].(float64)
	desc := weather["description"].(string)

	return fmt.Sprintf("Cuaca saat ini: %.1f°C, %s.", temp, desc)
}

func callGeminiAPI(prompt string, base64Image string) (string, error) {
	apiKey := os.Getenv("GEMINI_API_KEY")
	if apiKey == "" {
		return "", fmt.Errorf("GEMINI_API_KEY tidak ditemukan di .env")
	}

	url := fmt.Sprintf("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=%s", apiKey)

	var parts []map[string]interface{}
	if base64Image != "" {
		// Asumsi image adalah jpeg/png
		parts = append(parts, map[string]interface{}{
			"inline_data": map[string]interface{}{
				"mime_type": "image/jpeg",
				"data":      base64Image,
			},
		})
	}
	parts = append(parts, map[string]interface{}{
		"text": prompt,
	})

	requestBody := map[string]interface{}{
		"contents": []map[string]interface{}{
			{
				"parts": parts,
			},
		},
	}

	bodyBytes, _ := json.Marshal(requestBody)
	resp, err := http.Post(url, "application/json", bytes.NewBuffer(bodyBytes))
	if err != nil {
		return "", err
	}
	defer resp.Body.Close()

	rawBody, _ := io.ReadAll(resp.Body)
	if resp.StatusCode != http.StatusOK {
		return "", fmt.Errorf("Gemini API error: %s", string(rawBody))
	}

	var geminiResp struct {
		Candidates []struct {
			Content struct {
				Parts []struct {
					Text string `json:"text"`
				} `json:"parts"`
			} `json:"content"`
		} `json:"candidates"`
	}

	json.Unmarshal(rawBody, &geminiResp)
	if len(geminiResp.Candidates) == 0 || len(geminiResp.Candidates[0].Content.Parts) == 0 {
		return "", fmt.Errorf("Respons kosong dari AI")
	}

	return strings.TrimSpace(geminiResp.Candidates[0].Content.Parts[0].Text), nil
}

func callGroqAPI(prompt string) (string, error) {
	apiKey := os.Getenv("GROQ_API_KEY")
	if apiKey == "" {
		return "", fmt.Errorf("GROQ_API_KEY tidak ditemukan di .env")
	}

	url := "https://api.groq.com/openai/v1/chat/completions"

	requestBody := map[string]interface{}{
		"model": "llama-3.3-70b-versatile",
		"messages": []map[string]interface{}{
			{
				"role":    "user",
				"content": prompt,
			},
		},
	}

	bodyBytes, _ := json.Marshal(requestBody)
	req, err := http.NewRequest("POST", url, bytes.NewBuffer(bodyBytes))
	if err != nil {
		return "", err
	}

	req.Header.Set("Authorization", "Bearer "+apiKey)
	req.Header.Set("Content-Type", "application/json")

	client := &http.Client{}
	resp, err := client.Do(req)
	if err != nil {
		return "", err
	}
	defer resp.Body.Close()

	rawBody, _ := io.ReadAll(resp.Body)
	if resp.StatusCode != http.StatusOK {
		return "", fmt.Errorf("Groq API error: %s", string(rawBody))
	}

	var groqResp struct {
		Choices []struct {
			Message struct {
				Content string `json:"content"`
			} `json:"message"`
		} `json:"choices"`
	}

	if err := json.Unmarshal(rawBody, &groqResp); err != nil {
		return "", err
	}

	if len(groqResp.Choices) == 0 {
		return "", fmt.Errorf("Respons kosong dari Groq AI")
	}

	return strings.TrimSpace(groqResp.Choices[0].Message.Content), nil
}

func RecommendCrop(c *gin.Context) {
	var req ReqRecommendCrop
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Format input tidak valid."})
		return
	}

	prompt := fmt.Sprintf(`Berikan rekomendasi tanaman pertanian perkotaan terbaik untuk lokasi %s, luas lahan %.2f m2, suhu %.2f C, sinar matahari %.2f jam/hari.
Kembalikan hasilnya dalam format JSON dengan field:
- "tanaman_rekomendasi": array string nama tanaman (minimal 3)
- "kapasitas_tanaman": integer estimasi jumlah bibit yang muat
- "estimasi_panen_hari": integer estimasi siklus panen tercepat dalam hari`,
		req.LokasiLahan, req.LuasLahan, req.SuhuRataRata, req.SinarMatahariJam)

	responseText, err := callGroqAPI(prompt)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}

	// Buang markdown block jika ada
	firstBrace := strings.Index(responseText, "{")
	lastBrace := strings.LastIndex(responseText, "}")
	if firstBrace != -1 && lastBrace != -1 && lastBrace > firstBrace {
		responseText = responseText[firstBrace : lastBrace+1]
	}
	
	var result map[string]interface{}
	json.Unmarshal([]byte(responseText), &result)
	c.JSON(http.StatusOK, result)
}

func GetAiHistory(c *gin.Context) {
	userID, _ := c.Get("user_id")
	var chats []models.Chat
	config.DB.Where("user_id = ?", userID).Order("created_at asc").Limit(50).Find(&chats)
	c.JSON(http.StatusOK, gin.H{"data": chats})
}

func AiChat(c *gin.Context) {
	userID, _ := c.Get("user_id")
	var req ReqAiChat
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Invalid request"})
		return
	}

	weatherCtx := getWeatherContext("", "", "Malang") // bisa dinamis nanti
	sysPrompt := "Anda adalah Pakar Pertanian UrbanFarm. " + weatherCtx + "\nTugas Anda membantu petani. Jawab dengan ramah dan solutif."
	
	userPrompt := req.Message
	if req.Image != "" {
		userPrompt = "User mengirim gambar. " + userPrompt
	}

	fullPrompt := sysPrompt + "\n\n" + userPrompt

	var aiReply string
	var err error
	if req.Image != "" {
		aiReply, err = callGeminiAPI(fullPrompt, req.Image)
	} else {
		aiReply, err = callGroqAPI(fullPrompt)
	}

	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "AI Error: " + err.Error()})
		return
	}

	// Simpan ke DB
	chat := models.Chat{
		UserID:   userID.(uint),
		Message:  req.Message,
		Response: aiReply,
	}
	config.DB.Create(&chat)

	c.JSON(http.StatusOK, gin.H{"response": aiReply, "chat": chat})
}

func ClearAiHistory(c *gin.Context) {
	userID, _ := c.Get("user_id")
	config.DB.Where("user_id = ?", userID).Delete(&models.Chat{})
	c.JSON(http.StatusOK, gin.H{"message": "History cleared"})
}
