package controllers

import (
	"net/http"
	"urbangrow/config"
	"urbangrow/models"

	"github.com/gin-gonic/gin"
)

// GetKatalog mengambil semua data katalog tanaman
func GetKatalog(c *gin.Context) {
	var katalog []models.KatalogTanaman
	if err := config.DB.Find(&katalog).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal mengambil data katalog."})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"message": "Data katalog berhasil diambil",
		"data":    katalog,
	})
}
