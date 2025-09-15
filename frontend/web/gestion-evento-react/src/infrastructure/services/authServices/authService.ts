import axios from "axios";

const baseUrl = import.meta.env.VITE_API_BASE_URL;

// ================================
// Tipos de datos
// ================================

export interface LoginCredentials {
  email: string;
  password: string;
}

export interface LoginResponse {
  token: string;
  user: {
    idUsuario: number;
    role: string;
    email: string;
  };
}

export type UserRole =
  | "ROLE_SUPER_ADMIN"
  | "ROLE_ADMIN"
  | "ROLE_PONENTE"
  | "ROLE_JURADO"
  | "ROLE_ESTUDIANTE"
  | "ROLE_ADMIN"
  | null;

// ================================
// Manejo de almacenamiento
// ================================

export const saveToken = (token: string): void => {
  localStorage.setItem("authToken", token);
};

export const getToken = (): string | null => {
  return localStorage.getItem("authToken");
};

export const isAuthenticated = (): boolean => {
  return !!getToken();
};

// ================================
// Autenticación con axios
// ================================

export const login = async (credentials: LoginCredentials): Promise<string> => {
  try {
    const { data } = await axios.post<LoginResponse>(
      `${baseUrl}/api/login`,
      credentials,
      {
        headers: { "Content-Type": "application/json" },
      }
    );

    // Guardar token y datos del usuario
    saveToken(data.token);
    localStorage.setItem("userRole", data.user.role);
    localStorage.setItem("userId", data.user.idUsuario.toString());
    localStorage.setItem("userEmail", data.user.email);

    return data.token;
  } catch (error) {
    console.error("Error logging in:", error);
    throw error;
  }
};

// ================================
// Funciones utilitarias
// ================================

export const getUserRole = (): UserRole => {
  return (localStorage.getItem("userRole") as UserRole) ?? null;
};

export const getUserId = (): string | null => {
  return localStorage.getItem("userId");
};

export const getUserEmail = (): string | null => {
  return localStorage.getItem("userEmail");
};

// ================================
// Validación y logout
// ================================

export const validateToken = async (): Promise<boolean> => {
  const token = getToken();
  if (!token) return false;

  try {
    await axios.post(
      `${baseUrl}/auth/validate`,
      { token },
      { headers: { "Content-Type": "application/json" } }
    );

    return true;
  } catch (error) {
    console.error("Error validating token:", error);
    return false;
  }
};

export const logout = (): void => {
  localStorage.removeItem("authToken");
  localStorage.removeItem("userRole");
  localStorage.removeItem("userId");
  localStorage.removeItem("userEmail");
};