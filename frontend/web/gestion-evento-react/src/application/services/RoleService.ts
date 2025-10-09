import { Role } from "../../domain/entities/Role";
import type { IRoleRepository } from "../../domain/repositories/IRoleRepository";

import { CreateRole } from "../../application/useCases/role/CreateRole";
import { DeleteRole } from "../../application/useCases/role/DeleteRole";
import { GetRoleById } from "../../application/useCases/role/GetRoleById";
import { GetRoles } from "../../application/useCases/role/GetRoles";
import { UpdateRole } from "../../application/useCases/role/UpdateRole";
import type { PaginatedResponse } from "../dtos/PaginatedResponse";
import { GetRolesPaginated } from "../useCases/role/GetRolesPaginated";
import { SearchRoles } from "../useCases/role/SearchRoles";

export class RoleService {
  private readonly createRoleUC: CreateRole;
  private readonly deleteRoleUC: DeleteRole;
  private readonly getRoleByIdUC: GetRoleById;
  private readonly getRolesUC: GetRoles;
  private readonly updateRoleUC: UpdateRole;
  private readonly getRolesPaginatedUC: GetRolesPaginated;
  private readonly searchRolesUC: SearchRoles;

  constructor(roleRepository: IRoleRepository) {
    this.createRoleUC = new CreateRole(roleRepository);
    this.deleteRoleUC = new DeleteRole(roleRepository);
    this.getRoleByIdUC = new GetRoleById(roleRepository);
    this.getRolesUC = new GetRoles(roleRepository);
    this.updateRoleUC = new UpdateRole(roleRepository);
    this.getRolesPaginatedUC = new GetRolesPaginated(roleRepository);
    this.searchRolesUC = new SearchRoles(roleRepository);
  }

  async createRole(role: Role, file?: File): Promise<Role> {
    return await this.createRoleUC.execute(role, file);
  }

  async updateRole(role: Role, file?: File): Promise<Role> {
    return await this.updateRoleUC.execute(role, file);
  }

  async deleteRole(id: number): Promise<void> {
    return await this.deleteRoleUC.execute(id);
  }

  async getRoleById(id: number): Promise<Role> {
    return await this.getRoleByIdUC.execute(id);
  }

  async getRoles(): Promise<Role[]> {
    return await this.getRolesUC.execute();
  }

  async getRolesPaginated(
    page: number = 1,
    perPage: number = 10
  ): Promise<PaginatedResponse<Role>> {
    return await this.getRolesPaginatedUC.execute(page, perPage);
  }

  async searchRoles(term: string, perPage: number = 10): Promise<PaginatedResponse<Role>> {
    return await this.searchRolesUC.execute(term, perPage);
  }
}