import { FilialMapper } from "../../application/dtos/mappers/FilialMapper";
import type { PaginatedResponse } from "../../application/dtos/PaginatedResponse";
import type { Filial } from "../../domain/entities/Filial";
import type { IFilialRepository } from "../../domain/repositories/IFilialRepository";
import { AxiosClient } from "../config/AxiosClient";

export class FilialRepository implements IFilialRepository {
    private readonly endpoint = "/filiales";

    async getFiliales(): Promise<Filial[]> {
        const response = await AxiosClient.get(this.endpoint);
        return response.data.map((dto: any) => FilialMapper.toDomain(dto));
    }

    async getFilialById(id: number): Promise<Filial> {
        const response = await AxiosClient.get(`${this.endpoint}/${id}`);
        return FilialMapper.toDomain(response.data);
    }

    async createFilial(filial: Filial, file?: File): Promise<Filial> {
        const formData = new FormData();
        formData.append("nombre", filial.nombre);

        if (filial.direccion) formData.append("direccion", filial.direccion);
        if (filial.telefono) formData.append("telefono", filial.telefono);
        if (filial.email) formData.append("email", filial.email);

        if (file) {
            formData.append("foto", file);
        }

        const response = await AxiosClient.post(this.endpoint, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            }
        });

        return FilialMapper.toDomain(response.data);
    }

    async updateFilial(filial: Filial, file?: File): Promise<Filial> {
        const formData = new FormData();
        formData.append("nombre", filial.nombre);

        if (filial.direccion) formData.append("direccion", filial.direccion);
        if (filial.telefono) formData.append("telefono", filial.telefono);
        if (filial.email) formData.append("email", filial.email);

        if (file) {
            formData.append("foto", file);
        }

        const response = await AxiosClient.post(`${this.endpoint}/${filial.id}`, formData, {
            headers: {
                "Content-Type": "multipart/form-data"
            }
        });

        return FilialMapper.toDomain(response.data);
    }

    async deleteFilial(id: number): Promise<void> {
        await AxiosClient.delete(`${this.endpoint}/${id}`);
    }

    async getFilialesPaginated(page?: number, perPage?: number): Promise<PaginatedResponse<Filial>> {
        const response = await AxiosClient.get(
            `${this.endpoint}/paginated?page=${page ?? 1}&per_page=${perPage ?? 10}`
        );

        const paginatedData = response.data;

        return {
            current_page: paginatedData.current_page,
            per_page: paginatedData.per_page,
            total: paginatedData.total,
            data: paginatedData.data.map((dto: any) => FilialMapper.toDomain(dto)),
        };
    }

    async searchFilialesPaginated(term: string, perPage?: number): Promise<PaginatedResponse<Filial>> {
        const response = await AxiosClient.get(
            `${this.endpoint}/search?q=${term}&per_page=${perPage ?? 10}`
        );

        const paginatedData = response.data;

        return {
            current_page: paginatedData.current_page,
            per_page: paginatedData.per_page,
            total: paginatedData.total,
            data: paginatedData.data.map((dto: any) => FilialMapper.toDomain(dto)),
        };
    }
}