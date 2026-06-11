package websocket

import (
	"encoding/json"
	"log"
	"net/http"
	"sync"
	"urbangrow/config"
	"urbangrow/models"

	"github.com/gin-gonic/gin"
	"github.com/gorilla/websocket"
)

var upgrader = websocket.Upgrader{
	CheckOrigin: func(r *http.Request) bool {
		// Mengizinkan semua origin untuk development
		// Saat production, ganti dengan origin frontend Anda
		return true 
	},
}

// Client merepresentasikan user yang terhubung via WebSocket
type Client struct {
	Conn   *websocket.Conn
	UserID uint // (Opsional) bisa diisi saat autentikasi token
}

var (
	clients   = make(map[*Client]bool)
	clientsMu sync.Mutex
)

// WSPayload merepresentasikan struktur JSON yang diterima dan dikirim
type WSPayload struct {
	Type string          `json:"type"` // "chat" atau "notification"
	Data json.RawMessage `json:"data"` // Data mentah (bisa struct chat atau notif)
}

// ChatPayload untuk type "chat"
type ChatPayload struct {
	UserID  uint   `json:"user_id"`
	Message string `json:"message"`
	Image   string `json:"image,omitempty"`
}

// NotificationPayload untuk type "notification"
type NotificationPayload struct {
	UserID uint   `json:"user_id"`
	Title  string `json:"title"`
	Body   string `json:"body"`
	Type   string `json:"type"`
}

// HandleConnections adalah Gin handler untuk endpoint WebSocket (contoh: /ws)
func HandleConnections(c *gin.Context) {
	ws, err := upgrader.Upgrade(c.Writer, c.Request, nil)
	if err != nil {
		log.Printf("Gagal melakukan upgrade ke WebSocket: %v", err)
		return
	}
	defer ws.Close()

	client := &Client{Conn: ws}
	
	// Register client baru
	clientsMu.Lock()
	clients[client] = true
	clientsMu.Unlock()

	log.Println("Koneksi WebSocket baru berhasil dibuat")

	// Infinite loop untuk mendengarkan pesan dari client
	for {
		var payload WSPayload
		err := ws.ReadJSON(&payload)
		if err != nil {
			log.Printf("Error parsing JSON atau client terputus: %v", err)
			
			// Hapus client yang terputus
			clientsMu.Lock()
			delete(clients, client)
			clientsMu.Unlock()
			break
		}

		// Tangani pesan berdasarkan tipenya
		handlePayload(client, payload)
	}
}

func handlePayload(client *Client, payload WSPayload) {
	switch payload.Type {
	case "chat":
		var chatData ChatPayload
		if err := json.Unmarshal(payload.Data, &chatData); err != nil {
			log.Printf("Format data chat tidak valid: %v", err)
			return
		}
		
		// 1. Simpan pesan chat ke database
		chatMsg := models.ChatMessage{
			UserID:  chatData.UserID,
			Message: chatData.Message,
			Image:   chatData.Image,
		}
		if err := config.DB.Create(&chatMsg).Error; err != nil {
			log.Printf("Gagal menyimpan pesan chat ke DB: %v", err)
			return
		}

		// 2. Broadcast (sebar) pesan ke semua client yang terhubung
		// Anda juga bisa query join ke tabel User untuk mendapatkan Nama/Logo sebelum broadcast
		broadcastMessage(WSPayload{
			Type: "chat",
			Data: payload.Data, // Untuk kesederhanaan, kita teruskan data yang sama
		})

	case "notification":
		var notifData NotificationPayload
		if err := json.Unmarshal(payload.Data, &notifData); err != nil {
			log.Printf("Format data notifikasi tidak valid: %v", err)
			return
		}

		// 1. Simpan notifikasi ke database
		notif := models.Notification{
			UserID: notifData.UserID,
			Title:  notifData.Title,
			Body:   notifData.Body,
			Type:   notifData.Type,
		}
		if err := config.DB.Create(&notif).Error; err != nil {
			log.Printf("Gagal menyimpan notifikasi ke DB: %v", err)
			return
		}

		// 2. Broadcast (bisa disesuaikan agar hanya broadcast ke UserID spesifik nantinya)
		broadcastMessage(WSPayload{
			Type: "notification",
			Data: payload.Data,
		})

	default:
		log.Printf("Tipe payload tidak dikenali: %s", payload.Type)
	}
}

// broadcastMessage mengirimkan pesan ke seluruh client
func broadcastMessage(message WSPayload) {
	clientsMu.Lock()
	defer clientsMu.Unlock()

	for client := range clients {
		err := client.Conn.WriteJSON(message)
		if err != nil {
			log.Printf("Gagal mengirim pesan ke client, menutup koneksi: %v", err)
			client.Conn.Close()
			delete(clients, client)
		}
	}
}

// GetChatMessages mengambil histori chat komunitas
func GetChatMessages(c *gin.Context) {
	var messages []models.ChatMessage
	if err := config.DB.Order("created_at asc").Limit(100).Find(&messages).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal mengambil pesan"})
		return
	}
	c.JSON(http.StatusOK, gin.H{"data": messages})
}
