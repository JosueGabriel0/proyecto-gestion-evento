// infrastructure/config/axiosClient.ts
import axios from "axios";
import { API_CONFIG } from "./ApiConfig";

export const AxiosClient = axios.create({
  baseURL: API_CONFIG.baseURL,
  timeout: API_CONFIG.timeout,
  headers: API_CONFIG.headers,
});

// 🔹 Opcional: interceptores para auth o logs
AxiosClient.interceptors.request.use(
  (config) => {
    // ejemplo: inyectar token JWT si existe
    const token = localStorage.getItem("authToken");
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

AxiosClient.interceptors.response.use(
  (response) => response,
  (error) => {
    console.error("API error:", error);
    return Promise.reject(error);
  }
);