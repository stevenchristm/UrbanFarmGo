import React, { useState, useEffect, useRef } from 'react';
import { View, Text, StyleSheet, TextInput, TouchableOpacity, FlatList, KeyboardAvoidingView, Platform, Image, ActivityIndicator } from 'react-native';
import client from '../../src/api/client';
import { Ionicons } from '@expo/vector-icons';
import * as ImagePicker from 'expo-image-picker';

export default function AiScreen() {
  const [messages, setMessages] = useState<any[]>([]);
  const [text, setText] = useState('');
  const [image, setImage] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);
  const flatListRef = useRef<FlatList>(null);

  useEffect(() => {
    client.get('/ai/history')
      .then(res => setMessages(res.data.data || []))
      .catch(console.error);
  }, []);

  const pickImage = async () => {
    let result = await ImagePicker.launchImageLibraryAsync({
      mediaTypes: ['images'],
      allowsEditing: true,
      quality: 0.5,
      base64: true,
    });

    if (!result.canceled && result.assets[0].base64) {
      setImage(result.assets[0].base64);
    }
  };

  const sendMessage = async () => {
    if (!text.trim() && !image) return;

    const userMessage = text.trim();
    const userImage = image;

    // Tambah pesan lokal (optimistic UI)
    const newMsg = {
      user_id: 1, 
      message: userMessage,
      image: userImage,
      isUser: true 
    };
    setMessages(prev => [...prev, newMsg]);
    setText('');
    setImage(null);
    setLoading(true);

    try {
      const res = await client.post('/ai/chat', {
        message: userMessage,
        image: userImage
      });
      
      const botMsg = {
        message: res.data.response,
        isUser: false
      };
      setMessages(prev => [...prev, botMsg]);
    } catch (e) {
      console.error(e);
      setMessages(prev => [...prev, { message: "Error: Gagal menghubungi AI.", isUser: false }]);
    } finally {
      setLoading(false);
    }
  };

  const renderItem = ({ item }: { item: any }) => {
    const isUser = item.isUser !== undefined ? item.isUser : (item.response ? true : false); // Fallback for history format

    // Rendering history from DB which has message and response in one object
    if (!item.isUser && item.response) {
      return (
        <>
          <View style={[styles.bubbleWrap, styles.bubbleWrapRight]}>
            <View style={[styles.bubble, styles.bubbleRight]}>
              <Text style={[styles.msgText, styles.msgTextRight]}>{item.message}</Text>
            </View>
          </View>
          <View style={[styles.bubbleWrap, styles.bubbleWrapLeft]}>
            <View style={[styles.bubble, styles.bubbleLeft]}>
              <Text style={[styles.msgText, styles.msgTextLeft]}>{item.response}</Text>
            </View>
          </View>
        </>
      );
    }

    return (
      <View style={[styles.bubbleWrap, isUser ? styles.bubbleWrapRight : styles.bubbleWrapLeft]}>
        <View style={[styles.bubble, isUser ? styles.bubbleRight : styles.bubbleLeft]}>
          {!isUser && <Text style={styles.sender}>UrbanFarm AI</Text>}
          {item.image && (
            <Image source={{ uri: `data:image/jpeg;base64,${item.image}` }} style={styles.chatImage} />
          )}
          {item.message ? <Text style={[styles.msgText, isUser ? styles.msgTextRight : styles.msgTextLeft]}>{item.message}</Text> : null}
        </View>
      </View>
    );
  };

  return (
    <KeyboardAvoidingView style={styles.container} behavior={Platform.OS === 'ios' ? 'padding' : undefined} keyboardVerticalOffset={90}>
      <Text style={styles.title}>Asisten AI 🤖</Text>
      
      <FlatList
        ref={flatListRef}
        data={messages}
        keyExtractor={(item, index) => index.toString()}
        renderItem={renderItem}
        contentContainerStyle={styles.list}
        onContentSizeChange={() => flatListRef.current?.scrollToEnd({ animated: true })}
      />

      {loading && <ActivityIndicator color="#10b981" style={{ marginBottom: 10 }} />}

      {image && (
        <View style={styles.previewContainer}>
          <Image source={{ uri: `data:image/jpeg;base64,${image}` }} style={styles.previewImg} />
          <TouchableOpacity style={styles.previewClose} onPress={() => setImage(null)}>
            <Ionicons name="close-circle" size={24} color="#ef4444" />
          </TouchableOpacity>
        </View>
      )}

      <View style={styles.inputBar}>
        <TouchableOpacity style={styles.iconBtn} onPress={pickImage}>
          <Ionicons name="camera" size={24} color="#64748b" />
        </TouchableOpacity>
        <TextInput
          style={styles.input}
          placeholder="Tanya soal tanaman..."
          value={text}
          onChangeText={setText}
        />
        <TouchableOpacity style={[styles.sendBtn, (!text.trim() && !image) ? {opacity: 0.5} : {}]} onPress={sendMessage} disabled={!text.trim() && !image}>
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
  chatImage: { width: 200, height: 200, borderRadius: 8, marginBottom: 8 },
  previewContainer: { padding: 10, flexDirection: 'row', backgroundColor: '#fff' },
  previewImg: { width: 60, height: 60, borderRadius: 8 },
  previewClose: { position: 'absolute', top: 5, left: 60 },
  inputBar: { flexDirection: 'row', padding: 12, backgroundColor: '#ffffff', borderTopWidth: 1, borderTopColor: '#f1f5f9', alignItems: 'center' },
  iconBtn: { padding: 8, marginRight: 4 },
  input: { flex: 1, backgroundColor: '#f1f5f9', borderRadius: 20, paddingHorizontal: 16, paddingVertical: 10, fontSize: 15, marginRight: 10 },
  sendBtn: { backgroundColor: '#10b981', width: 44, height: 44, borderRadius: 22, justifyContent: 'center', alignItems: 'center' }
});
