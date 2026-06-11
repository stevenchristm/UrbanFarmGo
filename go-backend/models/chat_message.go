package models

import (
	"time"
)

// ChatMessage merepresentasikan tabel chat_messages di database.
type ChatMessage struct {
	ID        uint      `gorm:"primaryKey" json:"id"`
	UserID    uint      `gorm:"not null" json:"user_id"`
	Message   string    `gorm:"type:text;not null" json:"message"`
	Image     string    `gorm:"type:varchar(255)" json:"image"` // Menyimpan path gambar jika ada
	CreatedAt time.Time `json:"created_at"`
	UpdatedAt time.Time `json:"updated_at"`

	// Relasi ke User (Belongs To)
	User *User `gorm:"foreignKey:UserID" json:"user,omitempty"`
}
