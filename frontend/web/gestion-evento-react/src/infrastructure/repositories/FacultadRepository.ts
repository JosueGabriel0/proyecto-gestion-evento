import { FacultadMapper } from "../../application/dtos/mappers/FacultadMapper";
import type { PaginatedResponse } from "../../application/dtos/PaginatedResponse";
import { Facultad } from "../../domain/entities/Facultad";
import type { IFacultadRepository } from "../../domain/repositories/IFacultadRepository";
import { AxiosClient } from "../config/AxiosClient";

export class FacultadRepository implements IFacultadRepository {
    private readonly endpoint = "/facultades";

    async getFacultades(): Promise<Facultad[]> {
        const response = await AxiosClient.get(this.endpoint);
        return response.data.map((dto: any) => FacultadMapper.toDomain(dto));
    }

    async getFacultadesByFilialId(id: number): Promise<Facultad[]>{
        const response = await AxiosClient.get(`${this.endpoint}/filial/${id}`);
        return response.data.map((dto: any) => FacultadMapper.toDomain(dto));
    }

    async getFacultadById(id: number): Promise<Facultad> {
        const response = await AxiosClient.get(`${this.endpoint}/${id}`);
        return FacultadMapper.toDomain(response.data);
    }

    async postFacultad(facultad: Facultad, file: File): Promise<Facultad> {
        const formData = new FormData();
        formData.append("nombre", facultad.nombre);
        formData.append("codigo", facultad.codigo);
        formData.append("filial_id", facultad.filialId.toString());
        if (file) formData.append("foto", file);

        const response = await AxiosClient.post(this.endpoint, formData, {
            headers: {
                "Content-Type": "mutipart/form-data",
            }
        });

        return FacultadMapper.toDomain(response.data);
    }
    async putFacultad(facultad: Facultad, file?: File): Promise<Facultad> {
        const formData = new FormData();
        formData.append("nombre", facultad.nombre);
        formData.append("codigo", facultad.codigo);
        formData.append("filial_id", facultad.filialId.toString());
        if (file) formData.append("foto", file);

        const response = await AxiosClient.post(`${this.endpoint}/${facultad.id}`, formData, {
            headers: {
                "Content-Type": "mutipart/form-data",
            }
        });

        return FacultadMapper.toDomain(response.data);
    }

    async listFacultadPaginated(page?: number, perPage?: number): Promise<PaginatedResponse<Facultad>> {
        const response = await AxiosClient.get(`${this.endpoint}/paginated?page=${page}&per_page${perPage ?? 10}`);

        const paginatedData = response.data;
        return (
            {
                current_page: paginatedData.current_page,
                per_page: paginatedData.per_page,
                total: paginatedData.total,
                data: paginatedData.data.map((dto: any) => FacultadMapper.toDomain(dto)),
            }
        );
    }

    async searchFacultadPaginated(term: string, perPage?: number): Promise<PaginatedResponse<Facultad>> {
        const response = await AxiosClient.get(`${this.endpoint}/paginated?q=${term}&per_page=${perPage ?? 10}`);

        const paginatedData = response.data;
        return (
            {
                current_page: paginatedData.current_page,
                per_page: paginatedData.per_page,
                total: paginatedData.total,
                data: paginatedData.data.map((dto: any) => FacultadMapper.toDomain(dto)),
            }
        )
    }

    async deleteFacultad(id: number): Promise<void>{
        await AxiosClient.delete(`${this.endpoint}/${id}`);
    }
}