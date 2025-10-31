// infrastructure/config/apiConfig.ts

const baseUrl = import.meta.env.VITE_API_BASE_URL;

export const API_CONFIG = {
  baseURL: `${baseUrl}/api`, // 👈 ajusta según tu backend
  timeout: 15000, // opcional
  headers: {
    "Content-Type": "application/json",
  },
};