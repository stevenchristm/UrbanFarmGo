package models

import "time"

// Chat merepresentasikan riwayat percakapan AI Assistant
type Chat struct {
	ID        uint      `gorm:"primaryKey;autoIncrement" json:"id"`
	UserID    uint      `gorm:"column:user_id;not null" json:"user_id"`
	Message   string    `gorm:"column:message;type:text" json:"message"`
	Response  string    `gorm:"column:response;type:text" json:"response"`
	Image     string    `gorm:"column:image" json:"image"`
	CreatedAt time.Time `json:"created_at"`
	UpdatedAt time.Time `json:"updated_at"`
}

func (Chat) TableName() string {
	return "chats"
}
