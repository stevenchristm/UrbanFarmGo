package models

import (
	"time"
)

// Notification merepresentasikan tabel notifications di database.
type Notification struct {
	ID        uint      `gorm:"primaryKey" json:"id"`
	UserID    uint      `gorm:"not null" json:"user_id"`
	Title     string    `gorm:"type:varchar(255);not null" json:"title"`
	Body      string    `gorm:"type:text;not null" json:"body"`
	IsRead    bool      `gorm:"default:false" json:"is_read"`
	Type      string    `gorm:"type:varchar(100)" json:"type"` // Contoh: "penyiraman", "pemupukan"
	CreatedAt time.Time `json:"created_at"`
	UpdatedAt time.Time `json:"updated_at"`

	// Relasi ke User (Belongs To)
	User *User `gorm:"foreignKey:UserID" json:"user,omitempty"`
}
