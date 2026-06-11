import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';

// Replace with your computer's actual local IP address
// The Go backend runs on port 8080
export const BASE_URL = 'http://192.168.100.10:8080/api';

const client = axios.create({
  baseURL: BASE_URL,
});

// Request interceptor to automatically attach the JWT token
client.interceptors.request.use(
  async (config) => {
    try {
      const token = await AsyncStorage.getItem('userToken');
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
      }
    } catch (e) {
      console.error('Error fetching token from AsyncStorage', e);
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

export default client;
