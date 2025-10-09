import type { PaginatedResponse } from "../../application/dtos/PaginatedResponse";
import type { Facultad } from "../entities/Facultad";

export interface IFacultadRepository {
    getAll(): Promise<Facultad[]>;
    getById(id: number): Promise<Facultad>;
    create(facultad: Facultad, file?: File): Promise<Facultad>;
    update(facultad: Facultad, file?: File): Promise<Facultad>;
    delete(id: number): Promise<void>;
    getPaginated(page?: number, perPage?: number): Promise<PaginatedResponse<Facultad>>;
    searchPaginated(term: string, perPage?: number): Promise<PaginatedResponse<Facultad>>;
}