import type { PaginatedResponse } from "../../application/dtos/PaginatedResponse";
import type { Facultad } from "../entities/Facultad";

export interface IFacultadRepository {
    getFacultades(): Promise<Facultad[]>;
    getFacultadById(id: number): Promise<Facultad>;
    postFacultad(facultad: Facultad): Promise<Facultad>;
    putFacultad(facultad: Facultad): Promise<Facultad>;
    listFacultadPaginated(page?: string, perPage?: string): Promise<PaginatedResponse<Facultad>>;
    searchFacultadPaginated(term: string, perPage?: string): Promise<PaginatedResponse<Facultad>>
}