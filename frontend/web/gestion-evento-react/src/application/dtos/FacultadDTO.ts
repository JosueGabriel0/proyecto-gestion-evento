export interface FacultadDTO {
  id: number;
  nombre: string;
  codigo: string;
  foto?: string | null;   // 👈 puede ser string o null o undefined
  filial_id: number;
}