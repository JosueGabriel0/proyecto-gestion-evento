import { EscuelaMapper } from "../../application/dtos/mappers/EscuelaMapper";
import type { PaginatedResponse } from "../../application/dtos/PaginatedResponse";
import type { Escuela } from "../../domain/entities/Escuela";
import type { IEscuelaRepository } from "../../domain/repositories/IEscuelaRepository";
import { AxiosClient } from "../config/AxiosClient";

export class EscuelaRepository implements IEscuelaRepository {
  private readonly endpoint = "/escuelas";

  async getEscuelas(): Promise<Escuela[]> {
    const response = await AxiosClient.get(this.endpoint);
    return response.data.map((dto: any) => EscuelaMapper.toDomain(dto));
  }

  async getEscuelaById(id: number): Promise<Escuela> {
    const response = await AxiosClient.get(`${this.endpoint}/${id}`);
    return EscuelaMapper.toDomain(response.data);
  }

  async createEscuela(escuela: Escuela, file?: File): Promise<Escuela> {
    const formData = new FormData();
    formData.append("nombre", escuela.nombre);
    formData.append("codigo", escuela.codigo);
    formData.append("facultad_id", escuela.facultad_id.toString());

    if (file) {
      formData.append("foto", file);
    }

    const response = await AxiosClient.post(this.endpoint, formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });

    return EscuelaMapper.toDomain(response.data);
  }

  async updateEscuela(escuela: Escuela, file?: File): Promise<Escuela> {
    const formData = new FormData();
    formData.append("nombre", escuela.nombre);
    formData.append("codigo", escuela.codigo);
    formData.append("facultad_id", escuela.facultad_id.toString());

    if (file) {
      formData.append("foto", file);
    }

    const response = await AxiosClient.post(
      `${this.endpoint}/${escuela.id}`,
      formData,
      {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      }
    );

    return EscuelaMapper.toDomain(response.data);
  }

  async deleteEscuela(id: number): Promise<void> {
    await AxiosClient.delete(`${this.endpoint}/${id}`);
  }

  async getEscuelasPaginated(
    page?: number,
    perPage?: number
  ): Promise<PaginatedResponse<Escuela>> {
    const response = await AxiosClient.get(
      `${this.endpoint}/paginated?page=${page ?? 1}&per_page=${perPage ?? 10}`
    );

    const paginatedData = response.data;

    return {
      current_page: paginatedData.current_page,
      per_page: paginatedData.per_page,
      total: paginatedData.total,
      data: paginatedData.data.map((dto: any) => EscuelaMapper.toDomain(dto)),
    };
  }

  async searchEscuelasPaginated(
    term: string,
    perPage?: number
  ): Promise<PaginatedResponse<Escuela>> {
    const response = await AxiosClient.get(
      `${this.endpoint}/search?q=${term}&per_page=${perPage ?? 10}`
    );

    const paginatedData = response.data;

    return {
      current_page: paginatedData.current_page,
      per_page: paginatedData.per_page,
      total: paginatedData.total,
      data: paginatedData.data.map((dto: any) => EscuelaMapper.toDomain(dto)),
    };
  }
}