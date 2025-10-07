// infrastructure/repositories/RoleRepository.ts
import { AxiosClient } from "../config/AxiosClient";
import type { IRoleRepository } from "../../domain/repositories/IRoleRepository";
import { Role } from "../../domain/entities/Role";
import { RoleMapper } from "../../application/dtos/mappers/RoleMapper";
import type { PaginatedResponse } from "../../application/dtos/PaginatedResponse";

export class RoleRepository implements IRoleRepository {
  private readonly endpoint = "/roles";

  async getRoles(): Promise<Role[]> {
    const response = await AxiosClient.get(this.endpoint);
    return response.data.map((dto: any) => RoleMapper.toDomain(dto));
  }

  async getRoleById(id: number): Promise<Role> {
    const response = await AxiosClient.get(`${this.endpoint}/${id}`);
    return RoleMapper.toDomain(response.data);
  }

  async createRole(role: Role, file?: File): Promise<Role> {
    const formData = new FormData();
    formData.append("nombre", role.nombre);

    if (file) {
      formData.append("foto", file); // archivo real
    }

    const response = await AxiosClient.post(this.endpoint, formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });

    return RoleMapper.toDomain(response.data);
  }

  async updateRole(role: Role, file?: File): Promise<Role> {
    const formData = new FormData();
    formData.append("nombre", role.nombre);

    if (file) {
      formData.append("foto", file);
    }

    // 👈 Aquí NO usamos _method ni PUT, solo POST
    const response = await AxiosClient.post(`${this.endpoint}/${role.id}`, formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });

    return RoleMapper.toDomain(response.data);
  }

  async deleteRole(id: number): Promise<void> {
    await AxiosClient.delete(`${this.endpoint}/${id}`);
  }

  // 👇 nuevos métodos
  async getRolesPaginated(
    page: number = 1,
    perPage: number = 10
  ): Promise<PaginatedResponse<Role>> {
    const response = await AxiosClient.get(
      `${this.endpoint}/paginated?page=${page}&per_page=${perPage}`
    );

    return {
      ...response.data,
      data: response.data.data.map((dto: any) => RoleMapper.toDomain(dto)),
    };
  }

  async searchRoles(term: string, perPage: number = 10): Promise<PaginatedResponse<Role>> {
    const response = await AxiosClient.get(
      `${this.endpoint}/search?q=${encodeURIComponent(term)}&per_page=${perPage}`
    );
    return {
      ...response.data,
      data: response.data.data.map((dto: any) => RoleMapper.toDomain(dto)),
    };
  }
}