import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, FlatList, ActivityIndicator, Image } from 'react-native';
import client from '../../src/api/client';

export default function KatalogScreen() {
  const [katalog, setKatalog] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    client.get('/katalog')
      .then(res => setKatalog(res.data.data || []))
      .catch(console.error)
      .finally(() => setLoading(false));
  }, []);

  const renderItem = ({ item }: { item: any }) => (
    <View style={styles.card}>
      <View style={styles.imgPlaceholder}>
        <Text style={{fontSize: 40}}>🌱</Text>
      </View>
      <View style={styles.info}>
        <Text style={styles.titleText}>{item.nama_tanaman}</Text>
        <Text style={styles.descText} numberOfLines={2}>{item.deskripsi_edukasi}</Text>
        <Text style={styles.detailText}>Suhu: {item.suhu_min}°C - {item.suhu_max}°C</Text>
        <Text style={styles.detailText}>Cahaya: {item.cahaya_jam} jam</Text>
      </View>
    </View>
  );

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Edukasi Bibit 📚</Text>
      {loading ? (
        <ActivityIndicator size="large" color="#10b981" style={{ marginTop: 50 }} />
      ) : (
        <FlatList
          data={katalog}
          keyExtractor={(item) => item.id_tanaman?.toString() || Math.random().toString()}
          renderItem={renderItem}
          contentContainerStyle={styles.list}
        />
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f8fafc' },
  title: { fontSize: 24, fontWeight: 'bold', color: '#0f172a', margin: 20, marginTop: 60 },
  list: { paddingHorizontal: 20, paddingBottom: 20 },
  card: { backgroundColor: '#ffffff', borderRadius: 16, marginBottom: 16, overflow: 'hidden', elevation: 2, shadowColor: '#000', shadowOffset: { width: 0, height: 1 }, shadowOpacity: 0.1, shadowRadius: 4, flexDirection: 'row' },
  imgPlaceholder: { width: 100, backgroundColor: '#e2e8f0', justifyContent: 'center', alignItems: 'center' },
  info: { padding: 16, flex: 1 },
  titleText: { fontSize: 18, fontWeight: 'bold', color: '#0f172a', marginBottom: 4 },
  descText: { fontSize: 14, color: '#475569', marginBottom: 8 },
  detailText: { fontSize: 12, color: '#64748b', fontWeight: '500' }
});
