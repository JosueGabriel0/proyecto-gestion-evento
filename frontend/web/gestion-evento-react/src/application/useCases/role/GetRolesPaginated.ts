import type { IRoleRepository } from "../../../domain/repositories/IRoleRepository";
import type { Role } from "../../../domain/entities/Role";
import type { PaginatedResponse } from "../../dtos/PaginatedResponse";

export class GetRolesPaginated {
    private readonly roleRepository: IRoleRepository;
  constructor(roleRepository: IRoleRepository) {
    this.roleRepository = roleRepository;
  }

  async execute(
    page: number,
    perPage: number
  ): Promise<PaginatedResponse<Role>> {
    return await this.roleRepository.getRolesPaginated(page, perPage);
  }
}