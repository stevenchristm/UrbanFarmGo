import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, FlatList, ActivityIndicator, RefreshControl, TouchableOpacity } from 'react-native';
import client from '../../src/api/client';
import { Ionicons } from '@expo/vector-icons';

export default function TasksScreen() {
  const [tasks, setTasks] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  const fetchTasks = async () => {
    try {
      const res = await client.get('/tasks');
      setTasks(res.data.data || []);
    } catch (e) {
      console.error(e);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  useEffect(() => {
    fetchTasks();
  }, []);

  const completeTask = async (id: number) => {
    try {
      await client.put(`/tasks/${id}/complete`);
      fetchTasks();
    } catch (e) {
      console.error(e);
    }
  };

  const onRefresh = () => {
    setRefreshing(true);
    fetchTasks();
  };

  const renderItem = ({ item }: { item: any }) => (
    <View style={styles.card}>
      <View style={styles.info}>
        <Text style={styles.taskTitle}>{item.title}</Text>
        <Text style={styles.taskDesc}>{item.description}</Text>
      </View>
      {item.status === 'pending' ? (
        <TouchableOpacity style={styles.btn} onPress={() => completeTask(item.id)}>
          <Ionicons name="checkmark-circle-outline" size={28} color="#10b981" />
        </TouchableOpacity>
      ) : (
        <Ionicons name="checkmark-circle" size={28} color="#10b981" />
      )}
    </View>
  );

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Alur Kerja 🗓️</Text>
      {loading ? (
        <ActivityIndicator size="large" color="#10b981" style={{ marginTop: 50 }} />
      ) : (
        <FlatList
          data={tasks}
          keyExtractor={(item) => item.id?.toString() || Math.random().toString()}
          renderItem={renderItem}
          contentContainerStyle={styles.list}
          refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
          ListEmptyComponent={<Text style={styles.emptyText}>Tidak ada jadwal perawatan.</Text>}
        />
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f8fafc' },
  title: { fontSize: 24, fontWeight: 'bold', color: '#0f172a', margin: 20, marginTop: 60 },
  list: { paddingHorizontal: 20, paddingBottom: 20 },
  card: { backgroundColor: '#ffffff', borderRadius: 16, padding: 16, marginBottom: 16, flexDirection: 'row', alignItems: 'center', elevation: 2, shadowColor: '#000', shadowOffset: { width: 0, height: 1 }, shadowOpacity: 0.1, shadowRadius: 4 },
  info: { flex: 1, marginRight: 16 },
  taskTitle: { fontSize: 16, fontWeight: 'bold', color: '#0f172a', marginBottom: 4 },
  taskDesc: { fontSize: 14, color: '#64748b' },
  btn: { padding: 4 },
  emptyText: { textAlign: 'center', color: '#64748b', marginTop: 50, fontSize: 16 }
});
