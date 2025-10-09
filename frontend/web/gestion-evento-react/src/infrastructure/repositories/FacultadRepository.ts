import { FacultadMapper } from "../../application/dtos/mappers/FacultadMapper";
import type { PaginatedResponse } from "../../application/dtos/PaginatedResponse";
import type { Facultad } from "../../domain/entities/Facultad";
import type { IFacultadRepository } from "../../domain/repositories/IFacultadRepository";
import { AxiosClient } from "../config/AxiosClient";


export class FacultadRepository implements IFacultadRepository {
    private readonly endpoint = "/facultades";

    async getAll(): Promise<Facultad[]> {
        const response = await AxiosClient.get(this.endpoint);
        return response.data.map((dto: any) => FacultadMapper.toDomain(dto));
    }

    async getById(id: number): Promise<Facultad> {
        const response = await AxiosClient.get(`${this.endpoint}/${id}`);
        return FacultadMapper.toDomain(response.data);
    }

    async create(facultad: Facultad, file?: File): Promise<Facultad> {
        const formData = new FormData();
        formData.append("nombre", facultad.nombre);
        formData.append("codigo", facultad.codigo);
        formData.append("filial_id", String(facultad.filialId));
        if (facultad.foto) formData.append("foto", facultad.foto as any);
        if (file) formData.append("foto", file);

        const response = await AxiosClient.post(this.endpoint, formData, {
            headers: { "Content-Type": "multipart/form-data" }
        });

        return FacultadMapper.toDomain(response.data);
    }

    async update(facultad: Facultad, file?: File): Promise<Facultad> {
        const formData = new FormData();
        formData.append("nombre", facultad.nombre);
        formData.append("codigo", facultad.codigo);
        formData.append("filial_id", String(facultad.filialId));
        if (facultad.foto) formData.append("foto", facultad.foto as any);
        if (file) formData.append("foto", file);

        const response = await AxiosClient.post(`${this.endpoint}/${facultad.id}`, formData, {
            headers: { "Content-Type": "multipart/form-data" }
        });

        return FacultadMapper.toDomain(response.data);
    }

    async delete(id: number): Promise<void> {
        await AxiosClient.delete(`${this.endpoint}/${id}`);
    }

    async getPaginated(page?: number, perPage?: number): Promise<PaginatedResponse<Facultad>> {
        const response = await AxiosClient.get(`${this.endpoint}/paginated?page=${page}&per_page=${perPage}`);
        return {
            ...response.data,
            data: response.data.data.map((dto: any) => FacultadMapper.toDomain(dto)),
        };
    }

    async searchPaginated(term: string, perPage?: number): Promise<PaginatedResponse<Facultad>> {
        const response = await AxiosClient.get(`${this.endpoint}/search?q=${term}&per_page=${perPage}`);
        return {
            ...response.data,
            data: response.data.data.map((dto: any) => FacultadMapper.toDomain(dto)),
        };
    }
}
