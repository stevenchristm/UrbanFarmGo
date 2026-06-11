package main

import (
	"log"
	"urbangrow/config"
	"urbangrow/controllers"
	"urbangrow/middleware"
	"urbangrow/models"
	"urbangrow/websocket"

	"github.com/gin-gonic/gin"
	"github.com/joho/godotenv"
)

func main() {
	// 0. Load variable dari file .env (karena kita run dari go-backend, letak .env di parent folder)
	errEnv := godotenv.Load("../.env")
	if errEnv != nil {
		log.Println("Peringatan: File .env tidak ditemukan, menggunakan environment OS.")
	}

	// 1. Inisialisasi Koneksi Database
	config.ConnectDatabase()

	// 2. Auto Migrate untuk semua tabel yang dikelola oleh Go.
	// Ini akan membuat tabel baru (tasks) dan menambahkan kolom yang kurang
	// (misal: kolom 'image' di chat_messages) tanpa menghapus data yang sudah ada.
	err := config.DB.AutoMigrate(
		&models.Task{},
		&models.Space{},
		&models.ChatMessage{},
		&models.Notification{},
	)
	if err != nil {
		log.Fatalf("Failed to migrate database: %v", err)
	}
	log.Println("Database migration completed!")

	// 3. Inisialisasi Gin Router
	r := gin.Default()

	// 4. Test Route
	r.GET("/api/ping", func(c *gin.Context) {
		c.JSON(200, gin.H{
			"message": "pong",
			"status":  "UrbanGrow Go API is running smoothly!",
		})
	})

	// Endpoint Publik
	r.POST("/api/login", controllers.Login)

	// Grup Endpoint yang Dilindungi oleh JWT Middleware
	protected := r.Group("/api")
	protected.Use(middleware.JWTAuthMiddleware())
	{
		// Rekomendasi & Manajemen Lahan
		protected.POST("/recommend", controllers.RecommendCrop)
		protected.POST("/spaces", controllers.CreateSpace)
		protected.GET("/spaces", controllers.GetSpaces)
		protected.GET("/dashboard", controllers.GetDashboardSummary)
		
		// Katalog / Edukasi
		protected.GET("/katalog", controllers.GetKatalog)

		// Manajemen Tugas / Alur Kerja Harian
		protected.GET("/tasks", controllers.GetTasks)
		protected.PUT("/tasks/:id/complete", controllers.CompleteTask)

		// Komunitas (Chat History)
		protected.GET("/chat/messages", websocket.GetChatMessages)

		// Asisten AI
		protected.GET("/ai/history", controllers.GetAiHistory)
		protected.POST("/ai/chat", controllers.AiChat)
		protected.DELETE("/ai/clear", controllers.ClearAiHistory)
	}

	// Endpoint WebSocket Terpadu (Unified)
	r.GET("/ws", websocket.HandleConnections)

	// 5. Jalankan Server
	log.Println("Server is running on port 8080")
	if err := r.Run(":8080"); err != nil {
		log.Fatalf("Failed to run server: %v", err)
	}
}
