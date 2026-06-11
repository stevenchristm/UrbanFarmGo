package models

import (
	"time"
)

// User merepresentasikan tabel users di database.
type User struct {
	ID              uint      `gorm:"primaryKey;column:id_user" json:"id_user"` // Menyesuaikan dengan schema Laravel sebelumnya
	Nama            string    `gorm:"type:varchar(255);not null" json:"nama"`
	Name            string    `gorm:"type:varchar(255)" json:"name"`
	Email           string    `gorm:"type:varchar(255);uniqueIndex;not null" json:"email"`
	Password        string    `gorm:"type:varchar(255);not null" json:"-"` // "-" menyembunyikan field ini di JSON response
	KoordinatLokasi string    `gorm:"type:varchar(255)" json:"koordinat_lokasi"`
	Role            string    `gorm:"type:varchar(50);default:'user'" json:"role"`
	Logo            string    `gorm:"type:varchar(255)" json:"logo"`
	ChatClearedAt   *time.Time `json:"chat_cleared_at"`
	CreatedAt       time.Time `json:"created_at"`
	UpdatedAt       time.Time `json:"updated_at"`

	// Relasi: Satu User memiliki banyak ChatMessages dan Notifications
	ChatMessages  []ChatMessage  `gorm:"foreignKey:UserID;references:ID" json:"chat_messages,omitempty"`
	Notifications []Notification `gorm:"foreignKey:UserID;references:ID" json:"notifications,omitempty"`
}
