import type { PaginatedResponse } from "../../application/dtos/PaginatedResponse";
import type { Filial } from "../entities/Filial";

export interface IFilialRepository{
    getFiliales(): Promise<Filial[]>
    getFilialById(id: number): Promise<Filial>
    createFilial(filial: Filial, file?: File): Promise<Filial>
    updateFilial(filial: Filial, file?: File): Promise<Filial>
    deleteFilial(id: number): Promise<void>
    getFilialesPaginated(page?: number, perPage?: number): Promise<PaginatedResponse<Filial>>
    searchFilialesPaginated(term: string, perPage?: number): Promise<PaginatedResponse<Filial>>
}