import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, FlatList, ActivityIndicator, RefreshControl, TouchableOpacity, Modal, TextInput, Alert } from 'react-native';
import client from '../../src/api/client';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons } from '@expo/vector-icons';
import AsyncStorage from '@react-native-async-storage/async-storage';

export default function SpacesScreen() {
  const [spaces, setSpaces] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  // Modal State
  const [modalVisible, setModalVisible] = useState(false);
  const [formData, setFormData] = useState({
    nama_lahan: '',
    lokasi_lahan: '',
    luas_lahan: '',
    suhu_rata_rata: '',
    sinar_matahari_jam: ''
  });
  const [submitting, setSubmitting] = useState(false);

  const fetchSpaces = async () => {
    try {
      const res = await client.get('/spaces');
      setSpaces(res.data.data || []);
    } catch (e) {
      console.error(e);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  useEffect(() => {
    fetchSpaces();
  }, []);

  const onRefresh = () => {
    setRefreshing(true);
    fetchSpaces();
  };

  const handleAddSpace = async () => {
    if (!formData.nama_lahan || !formData.lokasi_lahan || !formData.luas_lahan) {
      Alert.alert('Error', 'Harap isi semua kolom wajib.');
      return;
    }

    setSubmitting(true);
    try {
      // Decode JWT payload token
      const token = await AsyncStorage.getItem('userToken');
      let userId = 1; // Default fallback
      if (token) {
        const payload = JSON.parse(atob(token.split('.')[1]));
        if (payload.user_id) userId = payload.user_id;
      }

      await client.post('/spaces', {
        user_id: userId,
        nama_lahan: formData.nama_lahan,
        lokasi_lahan: formData.lokasi_lahan,
        luas_lahan: parseFloat(formData.luas_lahan),
        suhu_rata_rata: parseFloat(formData.suhu_rata_rata) || 28,
        sinar_matahari_jam: parseFloat(formData.sinar_matahari_jam) || 8
      });
      
      Alert.alert('Sukses', 'Lahan berhasil ditambahkan dan dianalisis oleh AI!');
      setModalVisible(false);
      setFormData({
        nama_lahan: '',
        lokasi_lahan: '',
        luas_lahan: '',
        suhu_rata_rata: '',
        sinar_matahari_jam: ''
      });
      onRefresh();
    } catch (e: any) {
      console.error(e);
      Alert.alert('Error', e.response?.data?.error || 'Terjadi kesalahan');
    } finally {
      setSubmitting(false);
    }
  };

  const renderItem = ({ item }: { item: any }) => (
    <View style={styles.card}>
      <LinearGradient colors={['#e0f2fe', '#f0f9ff']} style={styles.cardHeader}>
        <Ionicons name="leaf" size={24} color="#0284c7" />
        <Text style={styles.cardTitle}>{item.nama_lahan}</Text>
      </LinearGradient>
      <View style={styles.cardBody}>
        <Text style={styles.cardText}><Text style={styles.bold}>Lokasi:</Text> {item.lokasi_lahan}</Text>
        <Text style={styles.cardText}><Text style={styles.bold}>Luas:</Text> {item.luas_lahan} m²</Text>
        <Text style={styles.cardText}><Text style={styles.bold}>Kapasitas:</Text> {item.kapasitas_tanaman} Tanaman</Text>
      </View>
    </View>
  );

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Area Lahan 🌿</Text>
      {loading ? (
        <ActivityIndicator size="large" color="#10b981" style={{ marginTop: 50 }} />
      ) : (
        <FlatList
          data={spaces}
          keyExtractor={(item) => item.id?.toString() || Math.random().toString()}
          renderItem={renderItem}
          contentContainerStyle={styles.list}
          refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
          ListEmptyComponent={<Text style={styles.emptyText}>Belum ada data lahan.</Text>}
        />
      )}

      {/* Floating Action Button */}
      <TouchableOpacity style={styles.fab} onPress={() => setModalVisible(true)}>
        <Ionicons name="add" size={30} color="#fff" />
      </TouchableOpacity>

      {/* Modal Tambah Lahan */}
      <Modal visible={modalVisible} animationType="slide" transparent={true}>
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <Text style={styles.modalTitle}>Tambah Lahan Baru</Text>
            <Text style={styles.modalSubtitle}>Sistem AI kami akan memberikan rekomendasi terbaik untuk lahan Anda.</Text>

            <TextInput style={styles.input} placeholder="Nama Lahan (mis: Kebun Atap)" value={formData.nama_lahan} onChangeText={(t) => setFormData({...formData, nama_lahan: t})} />
            <TextInput style={styles.input} placeholder="Lokasi (mis: Malang, ID)" value={formData.lokasi_lahan} onChangeText={(t) => setFormData({...formData, lokasi_lahan: t})} />
            <TextInput style={styles.input} placeholder="Luas Lahan (m²)" keyboardType="numeric" value={formData.luas_lahan} onChangeText={(t) => setFormData({...formData, luas_lahan: t})} />
            <View style={{flexDirection: 'row', gap: 10}}>
              <TextInput style={[styles.input, {flex: 1}]} placeholder="Suhu Rata-rata (°C)" keyboardType="numeric" value={formData.suhu_rata_rata} onChangeText={(t) => setFormData({...formData, suhu_rata_rata: t})} />
              <TextInput style={[styles.input, {flex: 1}]} placeholder="Sinar Matahari (Jam)" keyboardType="numeric" value={formData.sinar_matahari_jam} onChangeText={(t) => setFormData({...formData, sinar_matahari_jam: t})} />
            </View>

            <View style={styles.modalActions}>
              <TouchableOpacity style={styles.btnCancel} onPress={() => setModalVisible(false)} disabled={submitting}>
                <Text style={styles.btnCancelText}>Batal</Text>
              </TouchableOpacity>
              <TouchableOpacity style={styles.btnSubmit} onPress={handleAddSpace} disabled={submitting}>
                {submitting ? <ActivityIndicator color="#fff" /> : <Text style={styles.btnSubmitText}>Daftarkan Lahan</Text>}
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f8fafc' },
  title: { fontSize: 24, fontWeight: 'bold', color: '#0f172a', margin: 20, marginTop: 60 },
  list: { paddingHorizontal: 20, paddingBottom: 100 },
  card: { backgroundColor: '#ffffff', borderRadius: 16, marginBottom: 16, overflow: 'hidden', elevation: 2, shadowColor: '#000', shadowOffset: { width: 0, height: 1 }, shadowOpacity: 0.1, shadowRadius: 4 },
  cardHeader: { flexDirection: 'row', alignItems: 'center', padding: 16, borderBottomWidth: 1, borderBottomColor: '#f1f5f9' },
  cardTitle: { fontSize: 18, fontWeight: 'bold', color: '#0f172a', marginLeft: 12 },
  cardBody: { padding: 16 },
  cardText: { fontSize: 15, color: '#475569', marginBottom: 8 },
  bold: { fontWeight: '600', color: '#334155' },
  emptyText: { textAlign: 'center', color: '#64748b', marginTop: 50, fontSize: 16 },
  fab: { position: 'absolute', right: 20, bottom: 20, width: 60, height: 60, borderRadius: 30, backgroundColor: '#10b981', justifyContent: 'center', alignItems: 'center', elevation: 5, shadowColor: '#000', shadowOffset: { width: 0, height: 2 }, shadowOpacity: 0.25, shadowRadius: 3.84 },
  modalOverlay: { flex: 1, backgroundColor: 'rgba(0,0,0,0.5)', justifyContent: 'flex-end' },
  modalContent: { backgroundColor: '#fff', borderTopLeftRadius: 24, borderTopRightRadius: 24, padding: 24, minHeight: '60%' },
  modalTitle: { fontSize: 22, fontWeight: 'bold', color: '#0f172a', marginBottom: 8 },
  modalSubtitle: { fontSize: 14, color: '#64748b', marginBottom: 20 },
  input: { backgroundColor: '#f1f5f9', borderRadius: 12, padding: 16, marginBottom: 16, fontSize: 16, color: '#0f172a' },
  modalActions: { flexDirection: 'row', justifyContent: 'space-between', marginTop: 10 },
  btnCancel: { flex: 1, padding: 16, borderRadius: 12, alignItems: 'center', marginRight: 8, backgroundColor: '#f1f5f9' },
  btnCancelText: { color: '#64748b', fontSize: 16, fontWeight: '600' },
  btnSubmit: { flex: 1, padding: 16, borderRadius: 12, alignItems: 'center', marginLeft: 8, backgroundColor: '#10b981' },
  btnSubmitText: { color: '#fff', fontSize: 16, fontWeight: '600' }
});
