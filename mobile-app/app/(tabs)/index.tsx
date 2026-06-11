import React, { useState, useCallback } from 'react';
import { StyleSheet, View, Text, ScrollView, TouchableOpacity, ActivityIndicator, RefreshControl, FlatList, Dimensions, Image } from 'react-native';
import { useFocusEffect } from 'expo-router';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons } from '@expo/vector-icons';
import client from '../../src/api/client';

const { width } = Dimensions.get('window');

// Format date like "Senin, 10 Juni 2026"
const getFormattedDate = () => {
  const options: Intl.DateTimeFormatOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  return new Date().toLocaleDateString('id-ID', options);
};

export default function DashboardScreen() {
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [tasks, setTasks] = useState<any[]>([]);
  const [summary, setSummary] = useState({ total_lahan: 0, total_kapasitas: 0 });
  const [recentSpaces, setRecentSpaces] = useState<any[]>([]);
  
  const fetchDashboardData = async () => {
    try {
      const [dashRes, tasksRes] = await Promise.all([
        client.get('/dashboard'),
        client.get('/tasks')
      ]);
      setSummary(dashRes.data.summary);
      setRecentSpaces(dashRes.data.recent_spaces || []);
      setTasks(tasksRes.data.data || []);
    } catch (error) {
      console.error('Error fetching dashboard data:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  useFocusEffect(
    useCallback(() => {
      fetchDashboardData();
    }, [])
  );

  const onRefresh = async () => {
    setRefreshing(true);
    await fetchDashboardData();
    setRefreshing(false);
  };

  const completeTask = async (id: number) => {
    try {
      await client.put(`/tasks/${id}/complete`);
      const res = await client.get('/tasks');
      setTasks(res.data.data);
    } catch (e) {
      console.error(e);
    }
  };

  if (loading) {
    return (
      <View style={styles.center}>
        <ActivityIndicator size="large" color="#0066ff" />
      </View>
    );
  }

  // Dummy weather for premium look
  const weather = { temp: 28, desc: 'Cerah Berawan', humidity: 65, wind: 12 };

  return (
    <ScrollView 
      style={styles.container} 
      showsVerticalScrollIndicator={false}
      refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
    >
      {/* Header & Greeting */}
      <View style={styles.headerContainer}>
        <Text style={styles.greetingText}>Halo, Petani Urban <Text style={{fontSize: 24}}>👋</Text></Text>
        <Text style={styles.dateSubtitle}>{getFormattedDate()} · Pusat kendali aktif.</Text>
      </View>

      {/* Station Capsule (Weather) */}
      <View style={styles.weatherCardWrapper}>
        <LinearGradient
          colors={['rgba(255,255,255,0.9)', 'rgba(255,255,255,0.6)']}
          style={styles.weatherCard}
        >
          {/* Main Weather */}
          <View style={styles.weatherMain}>
            <LinearGradient colors={['#38bdf8', '#3b82f6']} style={styles.weatherIconBox}>
              <Ionicons name="partly-sunny" size={32} color="#fff" />
            </LinearGradient>
            <View>
              <View style={styles.liveIndicator}>
                <View style={styles.liveDot} />
                <Text style={styles.liveText}>LIVE</Text>
                <Text style={styles.locationText}><Ionicons name="location" size={10} /> Malang, ID</Text>
              </View>
              <Text style={styles.tempText}>{weather.temp}<Text style={styles.tempUnit}>°C</Text></Text>
              <Text style={styles.weatherDesc}>{weather.desc}</Text>
            </View>
          </View>
          {/* Sub Stats */}
          <View style={styles.weatherSubStats}>
            <View style={styles.statItem}>
              <Text style={styles.statLabel}><Ionicons name="water" size={12} /> Humidity</Text>
              <Text style={styles.statValue}>{weather.humidity}%</Text>
            </View>
            <View style={styles.statItem}>
              <Text style={styles.statLabel}><Ionicons name="swap-horizontal" size={12} /> Wind</Text>
              <Text style={styles.statValue}>{weather.wind} km/h</Text>
            </View>
          </View>
        </LinearGradient>
      </View>

      {/* KPI Strip */}
      <ScrollView horizontal showsHorizontalScrollIndicator={false} contentContainerStyle={styles.kpiContainer}>
        <View style={styles.kpiCard}>
          <View style={[styles.kpiIconWrapper, { backgroundColor: 'rgba(16, 185, 129, 0.1)' }]}>
            <Ionicons name="leaf" size={24} color="#10b981" />
          </View>
          <Text style={styles.kpiValue}>{summary.total_lahan}</Text>
          <Text style={styles.kpiLabel}>Lahan Terkelola</Text>
        </View>
        
        <View style={styles.kpiCard}>
          <View style={[styles.kpiIconWrapper, { backgroundColor: 'rgba(245, 158, 11, 0.1)' }]}>
            <Ionicons name="rose" size={24} color="#f59e0b" />
          </View>
          <Text style={styles.kpiValue}>{summary.total_kapasitas}</Text>
          <Text style={styles.kpiLabel}>Vigor Tanaman</Text>
        </View>

        <View style={styles.kpiCard}>
          <View style={[styles.kpiIconWrapper, { backgroundColor: 'rgba(59, 130, 246, 0.1)' }]}>
            <Ionicons name="people" size={24} color="#3b82f6" />
          </View>
          <Text style={styles.kpiValue}>1</Text>
          <Text style={styles.kpiLabel}>Petani Aktif</Text>
        </View>
      </ScrollView>

      {/* Active Cultivation (Plant Cards) */}
      <View style={styles.sectionHeader}>
        <Text style={styles.sectionTitle}>Active Cultivation</Text>
        <Text style={styles.sectionSubtitle}>Penjadwalan & Lahan · {recentSpaces.length} lahan aktif</Text>
      </View>

      {recentSpaces.length > 0 ? (
        recentSpaces.map((s) => (
          <View key={s.id} style={styles.plantCard}>
            <View style={styles.plantImageContainer}>
              <LinearGradient colors={['rgba(0,0,0,0.6)', 'transparent']} style={styles.imageOverlay} />
              <View style={styles.plantPlaceholder}>
                <Text style={{ fontSize: 50 }}>🌱</Text>
              </View>
              <View style={styles.plantTagsTop}>
                <View style={styles.tagPhase}>
                  <Text style={styles.tagPhaseText}>Tahap Pertumbuhan</Text>
                </View>
                <View style={styles.tagLocation}>
                  <Ionicons name="location" size={12} color="#fff" />
                  <Text style={styles.tagLocationText}>{s.lokasi_lahan}</Text>
                </View>
              </View>
              <Text style={styles.plantTitle}>{s.nama_lahan}</Text>
            </View>
            <View style={styles.plantBody}>
              <View style={styles.progressRow}>
                <Text style={styles.progressTextMain}>Estimasi Panen: {s.estimasi_panen_hari} hari</Text>
              </View>
              <View style={styles.progressBarBg}>
                <LinearGradient colors={['#10b981', '#047857']} style={[styles.progressBarFill, { width: '40%' }]} />
              </View>

              <View style={styles.taskBox}>
                <View style={styles.taskBoxLeft}>
                  <View style={styles.taskIconBg}>
                    <Ionicons name="checkmark-circle" size={20} color="#10b981" />
                  </View>
                  <View>
                    <Text style={styles.taskBoxLabel}>TUGAS HARI INI</Text>
                    <Text style={styles.taskBoxTitle}>Monitor Rutin Ekosistem</Text>
                  </View>
                </View>
                <TouchableOpacity style={styles.taskBoxBtn}>
                  <Ionicons name="chevron-forward" size={20} color="#fff" />
                </TouchableOpacity>
              </View>
            </View>
          </View>
        ))
      ) : (
        <View style={styles.emptyCard}>
          <View style={styles.emptyIconBg}>
            <Ionicons name="leaf" size={40} color="#9ca3af" />
          </View>
          <Text style={styles.emptyTitle}>Belum Ada Lahan Aktif</Text>
          <Text style={styles.emptyDesc}>Mulai blueprint tanam pertama Anda hari ini untuk memantau pertumbuhan menggunakan AI.</Text>
        </View>
      )}

      {/* Your Tasks List */}
      <View style={styles.sectionHeader}>
        <Text style={styles.sectionTitle}>Your Tasks</Text>
      </View>
      <View style={styles.tasksContainer}>
        {tasks.map(item => (
          <View key={item.id} style={styles.taskItemCard}>
            <View style={{ flex: 1 }}>
              <Text style={styles.taskItemTitle}>{item.judul_tugas} <Text style={styles.taskStatus}>({item.status})</Text></Text>
            </View>
            {item.status !== 'completed' && (
              <TouchableOpacity onPress={() => completeTask(item.id)} style={styles.taskCompleteBtn}>
                <Ionicons name="checkmark" size={20} color="#fff" />
              </TouchableOpacity>
            )}
          </View>
        ))}
      </View>
      <View style={{ height: 40 }} />
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f8fafc',
  },
  center: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#f8fafc',
  },
  headerContainer: {
    paddingHorizontal: 24,
    paddingTop: 60,
    paddingBottom: 20,
  },
  greetingText: {
    fontSize: 28,
    fontWeight: '800',
    color: '#0f172a',
    letterSpacing: -0.5,
  },
  dateSubtitle: {
    fontSize: 14,
    color: '#64748b',
    marginTop: 6,
    fontWeight: '500',
  },
  weatherCardWrapper: {
    paddingHorizontal: 24,
    marginBottom: 24,
  },
  weatherCard: {
    borderRadius: 24,
    padding: 20,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    borderWidth: 1,
    borderColor: 'rgba(255,255,255,0.8)',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 10 },
    shadowOpacity: 0.05,
    shadowRadius: 20,
    elevation: 4,
  },
  weatherMain: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  weatherIconBox: {
    width: 60,
    height: 60,
    borderRadius: 16,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 16,
    shadowColor: '#3b82f6',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 8,
    elevation: 5,
  },
  liveIndicator: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 4,
  },
  liveDot: {
    width: 8,
    height: 8,
    backgroundColor: '#3b82f6',
    borderRadius: 4,
    marginRight: 6,
  },
  liveText: {
    fontSize: 10,
    fontWeight: '700',
    color: '#3b82f6',
    letterSpacing: 1,
  },
  locationText: {
    fontSize: 10,
    color: '#64748b',
    marginLeft: 8,
  },
  tempText: {
    fontSize: 32,
    fontWeight: '800',
    color: '#0f172a',
    letterSpacing: -1,
  },
  tempUnit: {
    fontSize: 18,
    color: '#64748b',
    fontWeight: '600',
  },
  weatherDesc: {
    fontSize: 12,
    color: '#64748b',
    marginTop: 2,
    fontWeight: '500',
  },
  weatherSubStats: {
    borderLeftWidth: 1,
    borderLeftColor: 'rgba(0,0,0,0.05)',
    paddingLeft: 20,
    gap: 12,
  },
  statItem: {},
  statLabel: {
    fontSize: 12,
    color: '#64748b',
    fontWeight: '600',
    marginBottom: 2,
  },
  statValue: {
    fontSize: 16,
    fontWeight: '700',
    color: '#0f172a',
  },
  kpiContainer: {
    paddingHorizontal: 24,
    paddingBottom: 30,
    gap: 16,
  },
  kpiCard: {
    backgroundColor: 'rgba(255,255,255,0.9)',
    borderRadius: 20,
    padding: 16,
    width: width * 0.35,
    borderWidth: 1,
    borderColor: 'rgba(255,255,255,1)',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.03,
    shadowRadius: 8,
    elevation: 2,
  },
  kpiIconWrapper: {
    width: 40,
    height: 40,
    borderRadius: 12,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 16,
  },
  kpiValue: {
    fontSize: 22,
    fontWeight: '800',
    color: '#0f172a',
  },
  kpiLabel: {
    fontSize: 12,
    color: '#64748b',
    fontWeight: '500',
    marginTop: 4,
  },
  sectionHeader: {
    paddingHorizontal: 24,
    marginBottom: 16,
  },
  sectionTitle: {
    fontSize: 20,
    fontWeight: '800',
    color: '#0f172a',
  },
  sectionSubtitle: {
    fontSize: 13,
    color: '#64748b',
    marginTop: 4,
  },
  plantCard: {
    marginHorizontal: 24,
    marginBottom: 20,
    backgroundColor: '#fff',
    borderRadius: 24,
    overflow: 'hidden',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 8 },
    shadowOpacity: 0.04,
    shadowRadius: 16,
    elevation: 3,
  },
  plantImageContainer: {
    height: 180,
    backgroundColor: '#e2e8f0',
    position: 'relative',
    justifyContent: 'center',
    alignItems: 'center',
  },
  imageOverlay: {
    position: 'absolute',
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    zIndex: 1,
  },
  plantPlaceholder: {
    width: '100%',
    height: '100%',
    backgroundColor: '#d1fae5',
    justifyContent: 'center',
    alignItems: 'center',
  },
  plantTagsTop: {
    position: 'absolute',
    top: 16,
    left: 16,
    right: 16,
    flexDirection: 'row',
    justifyContent: 'space-between',
    zIndex: 2,
  },
  tagPhase: {
    backgroundColor: 'rgba(255,255,255,0.9)',
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 20,
  },
  tagPhaseText: {
    fontSize: 10,
    fontWeight: '700',
    color: '#10b981',
  },
  tagLocation: {
    backgroundColor: 'rgba(0,0,0,0.5)',
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 20,
    flexDirection: 'row',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: 'rgba(255,255,255,0.1)',
  },
  tagLocationText: {
    color: '#fff',
    fontSize: 10,
    fontWeight: '600',
    marginLeft: 4,
  },
  plantTitle: {
    position: 'absolute',
    bottom: 16,
    left: 16,
    zIndex: 2,
    fontSize: 22,
    fontWeight: '800',
    color: '#fff',
    textShadowColor: 'rgba(0,0,0,0.3)',
    textShadowOffset: { width: 0, height: 2 },
    textShadowRadius: 4,
  },
  plantBody: {
    padding: 20,
  },
  progressRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 10,
  },
  progressTextMain: {
    fontSize: 12,
    fontWeight: '700',
    color: '#10b981',
  },
  progressBarBg: {
    height: 8,
    backgroundColor: '#f1f5f9',
    borderRadius: 4,
    marginBottom: 20,
    overflow: 'hidden',
  },
  progressBarFill: {
    height: '100%',
    borderRadius: 4,
  },
  taskBox: {
    backgroundColor: '#f8fafc',
    borderRadius: 16,
    padding: 12,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    borderWidth: 1,
    borderColor: '#e2e8f0',
  },
  taskBoxLeft: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  taskIconBg: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: 'rgba(16, 185, 129, 0.1)',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  taskBoxLabel: {
    fontSize: 10,
    fontWeight: '800',
    color: '#94a3b8',
    letterSpacing: 0.5,
  },
  taskBoxTitle: {
    fontSize: 14,
    fontWeight: '700',
    color: '#0f172a',
    marginTop: 2,
  },
  taskBoxBtn: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: '#10b981',
    justifyContent: 'center',
    alignItems: 'center',
    shadowColor: '#10b981',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 8,
    elevation: 4,
  },
  emptyCard: {
    marginHorizontal: 24,
    marginBottom: 30,
    padding: 30,
    borderWidth: 2,
    borderColor: '#e2e8f0',
    borderStyle: 'dashed',
    borderRadius: 24,
    alignItems: 'center',
    backgroundColor: 'rgba(255,255,255,0.5)',
  },
  emptyIconBg: {
    width: 80,
    height: 80,
    borderRadius: 40,
    backgroundColor: '#f1f5f9',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 16,
  },
  emptyTitle: {
    fontSize: 18,
    fontWeight: '800',
    color: '#0f172a',
    marginBottom: 8,
  },
  emptyDesc: {
    fontSize: 13,
    color: '#64748b',
    textAlign: 'center',
    lineHeight: 20,
  },
  tasksContainer: {
    paddingHorizontal: 24,
    gap: 12,
  },
  taskItemCard: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#fff',
    padding: 16,
    borderRadius: 16,
    borderWidth: 1,
    borderColor: 'rgba(0,0,0,0.02)',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.02,
    shadowRadius: 8,
    elevation: 2,
  },
  taskItemTitle: {
    fontSize: 14,
    fontWeight: '600',
    color: '#0f172a',
  },
  taskStatus: {
    fontSize: 12,
    color: '#64748b',
    fontWeight: '500',
  },
  taskCompleteBtn: {
    backgroundColor: '#10b981',
    width: 36,
    height: 36,
    borderRadius: 18,
    justifyContent: 'center',
    alignItems: 'center',
  },
});
