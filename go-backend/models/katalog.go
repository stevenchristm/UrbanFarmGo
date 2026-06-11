package models

import "time"

// KatalogTanaman merepresentasikan tabel katalog_tanamans dari database
type KatalogTanaman struct {
	ID               uint      `gorm:"column:id_tanaman;primaryKey;autoIncrement" json:"id_tanaman"`
	NamaTanaman      string    `gorm:"column:nama_tanaman;type:varchar(255);not null" json:"nama_tanaman"`
	SuhuMin          float64   `gorm:"column:suhu_min;not null" json:"suhu_min"`
	SuhuMax          float64   `gorm:"column:suhu_max;not null" json:"suhu_max"`
	CahayaJam        int       `gorm:"column:cahaya_jam;not null" json:"cahaya_jam"`
	HumidityAvg      float64   `gorm:"column:humidity_avg" json:"humidity_avg"`
	RainfallAvg      float64   `gorm:"column:rainfall_avg" json:"rainfall_avg"`
	CahayaMin        string    `gorm:"column:cahaya_min" json:"cahaya_min"`
	RentangSuhu      string    `gorm:"column:rentang_suhu" json:"rentang_suhu"`
	JarakTanamIdeal  string    `gorm:"column:jarak_tanam_ideal" json:"jarak_tanam_ideal"`
	DeskripsiEdukasi string    `gorm:"column:deskripsi_edukasi" json:"deskripsi_edukasi"`
	FotoTanaman      string    `gorm:"column:foto_tanaman" json:"foto_tanaman"`
	VideoID          string    `gorm:"column:video_id" json:"video_id"`
	CreatedAt        time.Time `json:"created_at"`
	UpdatedAt        time.Time `json:"updated_at"`
}

func (KatalogTanaman) TableName() string {
	return "katalog_tanamans"
}
