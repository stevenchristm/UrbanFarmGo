package models

import (
	"time"
)

// Space merepresentasikan lahan yang didaftarkan pengguna
type Space struct {
	ID                 uint      `gorm:"column:id_lahan;primaryKey;autoIncrement" json:"id"`
	UserID             uint      `gorm:"column:id_user;not null" json:"user_id"` // ID User pemilik lahan
	NamaLahan          string    `gorm:"column:nama_lahan;type:varchar(255);not null" json:"nama_lahan"`
	LokasiLahan        string    `gorm:"column:lokasi_lahan;type:varchar(255);not null" json:"lokasi_lahan"`
	LuasLahan          float64   `gorm:"column:luas_lahan;not null" json:"luas_lahan"`
	SuhuRataRata       float64   `gorm:"column:suhu_lahan;not null" json:"suhu_rata_rata"`
	SinarMatahariJam   float64   `gorm:"column:cahaya_lahan;not null" json:"sinar_matahari_jam"`
	
	// Hasil Rekomendasi AI Gemini
	TanamanRekomendasi string    `gorm:"type:text" json:"tanaman_rekomendasi"` // Array tanaman di-serialize ke JSON string
	KapasitasTanaman   int       `json:"kapasitas_tanaman"`
	EstimasiPanenHari  int       `json:"estimasi_panen_hari"`
	
	CreatedAt          time.Time `json:"created_at"`
	UpdatedAt          time.Time `json:"updated_at"`

	// Relasi ke tabel User
	User *User `gorm:"foreignKey:UserID" json:"user,omitempty"`
}
