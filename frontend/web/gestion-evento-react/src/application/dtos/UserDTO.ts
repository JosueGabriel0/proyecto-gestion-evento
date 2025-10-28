// src/dtos/UserDTO.ts

export interface PersonaDTO {
  nombres: string;
  apellidos: string;
  tipo_documento: string;
  numero_documento: string;
  telefono?: string;
  direccion?: string;
  pais?: string;
  religion?: string;
  correo_electronico: string;
  correo_institucional?: string;
  foto_perfil?: string | null;
  fecha_nacimiento: string; // ISO string
}

export interface AlumnoDTO {
  codigo_universitario: string;
}

export interface JuradoDTO {
  especialidad: string;
}

export interface PonenteDTO {
  biografia: string;
}

export interface UserDTO {
  id: number;
  email: string;
  escuela_id: number;
  role: string;
  persona?: PersonaDTO | null;
  alumno?: AlumnoDTO | null;
  jurado?: JuradoDTO | null;
  ponente?: PonenteDTO | null;
  created_at: string; // ISO string
  updated_at: string; // ISO string
}