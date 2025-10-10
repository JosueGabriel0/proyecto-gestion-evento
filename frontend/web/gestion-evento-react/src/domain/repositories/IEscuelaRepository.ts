import type { PaginatedResponse } from "../../application/dtos/PaginatedResponse";
import type { Escuela } from "../entities/Escuela";

export interface IEscuelaRepository {
  getEscuelas(): Promise<Escuela[]>;
  getEscuelaById(id: number): Promise<Escuela>;
  createEscuela(escuela: Escuela, file?: File): Promise<Escuela>;
  updateEscuela(escuela: Escuela, file?: File): Promise<Escuela>;
  deleteEscuela(id: number): Promise<void>;
  getEscuelasPaginated(page?: number, perPage?: number): Promise<PaginatedResponse<Escuela>>;
  searchEscuelasPaginated(term: string, perPage?: number): Promise<PaginatedResponse<Escuela>>;
<<<<<<< HEAD
}
=======
}
>>>>>>> temp-recuperacion}
