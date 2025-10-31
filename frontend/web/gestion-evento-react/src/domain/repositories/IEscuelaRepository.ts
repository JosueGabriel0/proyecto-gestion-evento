import type { PaginatedResponse } from "../../application/dtos/PaginatedResponse";
import type { Escuela } from "../entities/Escuela";

export interface IEscuelaRepository{
    getAllEscuelas(): Promise<Escuela[]>;
    getAllEscuelasByFacultadId(id: number): Promise<Escuela[]>;
    getEscuelaById(id: number): Promise<Escuela>;
    postEscuela(escuela: Escuela, file?: File): Promise<Escuela>;
    putEscuela(escuela: Escuela, file?: File): Promise<Escuela>;
    deleteEscuela(id: number): Promise<void>;
    searchEscuelaPaginated(term: string, perPage?: number): Promise<PaginatedResponse<Escuela>>;
    paginateEscuela(page: number, perPage?: number): Promise<PaginatedResponse<Escuela>>;
}