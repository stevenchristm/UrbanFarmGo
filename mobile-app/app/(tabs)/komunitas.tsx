import React, { useState, useEffect, useRef } from 'react';
import { View, Text, StyleSheet, TextInput, TouchableOpacity, FlatList, KeyboardAvoidingView, Platform } from 'react-native';
import client, { BASE_URL } from '../../src/api/client';
import { Ionicons } from '@expo/vector-icons';
import AsyncStorage from '@react-native-async-storage/async-storage';

export default function KomunitasScreen() {
  const [messages, setMessages] = useState<any[]>([]);
  const [text, setText] = useState('');
  const ws = useRef<WebSocket | null>(null);
  const flatListRef = useRef<FlatList>(null);
  const [userId, setUserId] = useState<number>(1); // Dummy id for now

  useEffect(() => {
    // Ambil histori chat
    client.get('/chat/messages')
      .then(res => setMessages(res.data.data || []))
      .catch(console.error);

    // Buka koneksi WebSocket
    const wsUrl = BASE_URL.replace('http', 'ws').replace('/api', '/ws');
    ws.current = new WebSocket(wsUrl);

    ws.current.onopen = () => {
      console.log("WebSocket connected");
    };

    ws.current.onmessage = (e) => {
      try {
        const payload = JSON.parse(e.data);
        if (payload.type === 'chat') {
          setMessages(prev => [...prev, payload.data]);
        }
      } catch (err) {}
    };

    return () => {
      ws.current?.close();
    };
  }, []);

  const sendMessage = () => {
    if (!text.trim() || !ws.current) return;

    const payload = {
      type: 'chat',
      data: {
        user_id: userId,
        message: text.trim()
      }
    };
    ws.current.send(JSON.stringify(payload));
    setText('');
  };

  const renderItem = ({ item }: { item: any }) => {
    const isMe = item.user_id === userId;
    return (
      <View style={[styles.bubbleWrap, isMe ? styles.bubbleWrapRight : styles.bubbleWrapLeft]}>
        <View style={[styles.bubble, isMe ? styles.bubbleRight : styles.bubbleLeft]}>
          {!isMe && <Text style={styles.sender}>Petani #{item.user_id}</Text>}
          <Text style={[styles.msgText, isMe ? styles.msgTextRight : styles.msgTextLeft]}>{item.message}</Text>
        </View>
      </View>
    );
  };

  return (
    <KeyboardAvoidingView style={styles.container} behavior={Platform.OS === 'ios' ? 'padding' : undefined} keyboardVerticalOffset={90}>
      <Text style={styles.title}>Komunitas 💬</Text>
      
      <FlatList
        ref={flatListRef}
        data={messages}
        keyExtractor={(item, index) => index.toString()}
        renderItem={renderItem}
        contentContainerStyle={styles.list}
        onContentSizeChange={() => flatListRef.current?.scrollToEnd({ animated: true })}
      />

      <View style={styles.inputBar}>
        <TextInput
          style={styles.input}
          placeholder="Tulis pesan..."
          value={text}
          onChangeText={setText}
        />
        <TouchableOpacity style={styles.sendBtn} onPress={sendMessage}>
          <Ionicons name="send" size={20} color="#fff" />
        </TouchableOpacity>
      </View>
    </KeyboardAvoidingView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f8fafc' },
  title: { fontSize: 24, fontWeight: 'bold', color: '#0f172a', margin: 20, marginTop: 60, marginBottom: 10 },
  list: { paddingHorizontal: 16, paddingBottom: 20 },
  bubbleWrap: { marginVertical: 4, flexDirection: 'row' },
  bubbleWrapLeft: { justifyContent: 'flex-start' },
  bubbleWrapRight: { justifyContent: 'flex-end' },
  bubble: { maxWidth: '80%', padding: 12, borderRadius: 16 },
  bubbleLeft: { backgroundColor: '#ffffff', borderBottomLeftRadius: 4 },
  bubbleRight: { backgroundColor: '#10b981', borderBottomRightRadius: 4 },
  sender: { fontSize: 12, color: '#10b981', fontWeight: 'bold', marginBottom: 4 },
  msgText: { fontSize: 15 },
  msgTextLeft: { color: '#0f172a' },
  msgTextRight: { color: '#ffffff' },
  inputBar: { flexDirection: 'row', padding: 12, backgroundColor: '#ffffff', borderTopWidth: 1, borderTopColor: '#f1f5f9', alignItems: 'center' },
  input: { flex: 1, backgroundColor: '#f1f5f9', borderRadius: 20, paddingHorizontal: 16, paddingVertical: 10, fontSize: 15, marginRight: 10 },
  sendBtn: { backgroundColor: '#10b981', width: 44, height: 44, borderRadius: 22, justifyContent: 'center', alignItems: 'center' }
});
