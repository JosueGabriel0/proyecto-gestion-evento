import type { PaginatedResponse } from "../../application/dtos/PaginatedResponse";
import type { Facultad } from "../entities/Facultad";

export interface IFacultadRepository {
    getFacultades(): Promise<Facultad[]>;
    getFacultadesByFilialId(id: number): Promise<Facultad[]>;
    getFacultadById(id: number): Promise<Facultad>;
    postFacultad(facultad: Facultad, file?: File): Promise<Facultad>;
    putFacultad(facultad: Facultad, file?: File): Promise<Facultad>;
    listFacultadPaginated(page?: number, perPage?: number): Promise<PaginatedResponse<Facultad>>;
    searchFacultadPaginated(term: string, perPage?: number): Promise<PaginatedResponse<Facultad>>;
    deleteFacultad(id: number): Promise<void>;
}