// ================================
// Tipos auxiliares
// ================================

// Credenciales del login
export interface LoginCredentials {
  email: string;
  password: string;
}

// Respuesta esperada del login
export interface LoginResponse {
  accessToken: string;
  refreshToken: string;
  role: string;
}

// Payload que se espera en el JWT
export interface JWTPayload {
  nombreRol?: string;
  idUsuario?: string;
  idInscripcion?: string;
  [key: string]: any; // Para no romper si vienen más campos
}

// ================================
// Funciones de almacenamiento
// ================================

export const saveToken = (token: string): void => {
  localStorage.setItem("authToken", token);
};

export const getToken = (): string | null => {
  return localStorage.getItem("authToken");
};

export const saveRefreshToken = (refreshToken: string): void => {
  localStorage.setItem("refreshToken", refreshToken);
};

export const getRefreshToken = (): string | null => {
  return localStorage.getItem("refreshToken");
};

export const isAuthenticated = (): boolean => {
  const token = getToken();
  return !!token;
};

// ================================
// Funciones de autenticación
// ================================

export const login = async (credentials: LoginCredentials): Promise<string> => {
  try {
    const response = await fetch(
      `${process.env.REACT_APP_API_BASE_URL}/auth/login`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(credentials),
      }
    );

    if (!response.ok) {
      throw new Error(`Login failed: ${response.statusText}`);
    }

    const data: LoginResponse = await response.json();
    saveToken(data.accessToken);
    saveRefreshToken(data.refreshToken);
    localStorage.setItem("userRole", data.role);

    return data.accessToken;
  } catch (error) {
    console.error("Error logging in:", error);
    throw error;
  }
};

export const getShortLivedToken = async (): Promise<string> => {
  const refreshToken = getRefreshToken();
  if (!refreshToken) {
    throw new Error("No refresh token available");
  }

  try {
    const response = await fetch("http://localhost:9090/auth/refresh", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ refreshToken }),
    });

    if (!response.ok) {
      throw new Error(`Token refresh failed: ${response.statusText}`);
    }

    const data: { accessToken: string } = await response.json();
    saveToken(data.accessToken);
    return data.accessToken;
  } catch (error) {
    console.error("Error getting short-lived token:", error);
    throw error;
  }
};

// ================================
// Funciones utilitarias de usuario
// ================================

const decodeToken = (): JWTPayload | null => {
  const token = getToken();
  if (!token) return null;

  try {
    const payload = JSON.parse(atob(token.split(".")[1])) as JWTPayload;
    return payload;
  } catch (error) {
    console.error("Error decoding token:", error);
    return null;
  }
};

export const getUserRole = (): string | null => {
  const payload = decodeToken();
  return payload?.nombreRol || null;
};

export const getUserId = (): string | null => {
  const payload = decodeToken();
  return payload?.idUsuario || null;
};

export const getInscripcionId = (): string | null => {
  const payload = decodeToken();
  return payload?.idInscripcion || null;
};

// ================================
// Validación y logout
// ================================

export const validateToken = async (): Promise<boolean> => {
  const token = getToken();
  if (!token) {
    return false;
  }

  try {
    const response = await fetch("http://localhost:9090/auth/validate", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ token }),
    });

    if (!response.ok) {
      throw new Error(`Token validation failed: ${response.statusText}`);
    }

    return true;
  } catch (error) {
    console.error("Error validating token:", error);
    return false;
  }
};

export const logout = (): void => {
  localStorage.removeItem("authToken");
  localStorage.removeItem("refreshToken");
  localStorage.removeItem("userRole");
};