package config

import (
	"log"

	"gorm.io/driver/mysql"
	"gorm.io/gorm"
)

var DB *gorm.DB

func ConnectDatabase() {
	// DSN (Data Source Name) format untuk MySQL: 
	// username:password@tcp(host:port)/dbname?charset=utf8mb4&parseTime=True&loc=Local
	// Catatan: Di production, selalu gunakan Environment Variables (.env) untuk menyimpan DSN ini.
	
	dsn := "root:@tcp(127.0.0.1:3306)/urbanfarm?charset=utf8mb4&parseTime=True&loc=Local"
	
	database, err := gorm.Open(mysql.Open(dsn), &gorm.Config{})
	if err != nil {
		log.Fatalf("Gagal terhubung ke database MySQL: %v", err)
	}

	DB = database
	log.Println("Koneksi ke database MySQL berhasil dibuat!")
}
