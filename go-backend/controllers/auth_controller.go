package controllers

import (
	"net/http"
	"os"
	"time"

	"github.com/gin-gonic/gin"
	"github.com/golang-jwt/jwt/v5"
)

// Login simulasi endpoint untuk menghasilkan token (di production ini akan mengecek database)
func Login(c *gin.Context) {
	// Pada aplikasi nyata, Anda akan menerima JSON (email & password) dan memvalidasinya di database
	// Untuk demo ini, kita asumsikan login berhasil untuk user dengan ID = 1
	userID := uint(1) 
	secretKey := []byte(os.Getenv("JWT_SECRET"))

	// Buat claims JWT
	claims := jwt.MapClaims{
		"user_id": userID,
		"exp":     time.Now().Add(time.Hour * 72).Unix(), // Token berlaku selama 72 jam
	}

	token := jwt.NewWithClaims(jwt.SigningMethodHS256, claims)
	tokenString, err := token.SignedString(secretKey)

	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal generate token"})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"message": "Login berhasil",
		"token":   tokenString,
	})
}
