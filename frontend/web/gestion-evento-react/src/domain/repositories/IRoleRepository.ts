import type { PaginatedResponse } from "../../application/dtos/PaginatedResponse";
import { Role } from "../entities/Role";

export interface IRoleRepository {
  getRoles(): Promise<Role[]>;
  getRoleById(id: number): Promise<Role>;
  createRole(role: Role): Promise<Role>;
  updateRole(role: Role): Promise<Role>;
  deleteRole(id: number): Promise<void>;
  getRolesPaginated(page?: number, perPage?: number): Promise<PaginatedResponse<Role>>;
  searchRoles(term: string, perPage?: number): Promise<PaginatedResponse<Role>>;
}