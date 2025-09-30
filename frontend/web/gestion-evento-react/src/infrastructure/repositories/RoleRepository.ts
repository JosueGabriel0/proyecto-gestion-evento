// infrastructure/repositories/RoleRepository.ts
import { axiosClient } from "../config/axiosClient";
import type { IRoleRepository } from "../../domain/repositories/IRoleRepository";
import { Role } from "../../domain/entities/Role";
import { RoleMapper } from "../../application/dtos/mappers/RoleMapper";
import type { PaginatedResponse } from "../../application/dtos/PaginatedResponse";

export class RoleRepository implements IRoleRepository {
  private readonly endpoint = "/roles";

  async getRoles(): Promise<Role[]> {
    const response = await axiosClient.get(this.endpoint);
    return response.data.map((dto: any) => RoleMapper.toDomain(dto));
  }

  async getRoleById(id: number): Promise<Role> {
    const response = await axiosClient.get(`${this.endpoint}/${id}`);
    return RoleMapper.toDomain(response.data);
  }

  async createRole(role: Role): Promise<Role> {
    const dto = RoleMapper.toDTO(role);
    const response = await axiosClient.post(this.endpoint, dto);
    return RoleMapper.toDomain(response.data);
  }

  async updateRole(role: Role): Promise<Role> {
    const dto = RoleMapper.toDTO(role);
    const response = await axiosClient.put(`${this.endpoint}/${role.id}`, dto);
    return RoleMapper.toDomain(response.data);
  }

  async deleteRole(id: number): Promise<void> {
    await axiosClient.delete(`${this.endpoint}/${id}`);
  }

  // 👇 nuevos métodos
  async getRolesPaginated(
    page: number = 1,
    perPage: number = 10
  ): Promise<PaginatedResponse<Role>> {
    const response = await axiosClient.get(
      `${this.endpoint}/paginated?page=${page}&per_page=${perPage}`
    );

    return {
      ...response.data,
      data: response.data.data.map((dto: any) => RoleMapper.toDomain(dto)),
    };
  }

  async searchRoles(term: string, perPage: number = 10): Promise<PaginatedResponse<Role>> {
    const response = await axiosClient.get(
      `${this.endpoint}/search?q=${encodeURIComponent(term)}&per_page=${perPage}`
    );
    return {
      ...response.data,
      data: response.data.data.map((dto: any) => RoleMapper.toDomain(dto)),
    };
  }
}