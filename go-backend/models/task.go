package models

import "time"

// Task merepresentasikan tabel tugas (alur kerja) harian
type Task struct {
	ID          uint      `gorm:"primaryKey" json:"id"`
	UserID      uint      `gorm:"column:id_user;not null" json:"user_id"`
	SpaceID     uint      `gorm:"column:id_lahan;not null" json:"space_id"` // Terhubung ke lahan spesifik
	JudulTugas  string    `gorm:"type:varchar(255);not null" json:"judul_tugas"`
	Deskripsi   string    `gorm:"type:text" json:"deskripsi"`
	Kategori    string    `gorm:"type:varchar(100)" json:"kategori"` // misal: "Penyiraman", "Pemupukan"
	Status      string    `gorm:"type:varchar(50);default:'pending'" json:"status"` // 'pending' atau 'completed'
	JadwalTugas time.Time `json:"jadwal_tugas"` // Kapan tugas ini harus dikerjakan
	CreatedAt   time.Time `json:"created_at"`
	UpdatedAt   time.Time `json:"updated_at"`

	// Relasi ke User dan Space (tanpa auto-FK agar tidak konflik dengan Laravel)
	User  *User  `gorm:"foreignKey:UserID" json:"-"`
	Space *Space `gorm:"foreignKey:SpaceID;constraint:OnUpdate:CASCADE,OnDelete:CASCADE" json:"-"`
}
