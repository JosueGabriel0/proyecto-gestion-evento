// application/use-cases/GetRoleById.ts

import { Role } from "../../../domain/entities/Role";
import type { IRoleRepository } from "../../../domain/repositories/IRoleRepository";

export class GetRoleById {
  private readonly roleRepository: IRoleRepository;

  constructor(roleRepository: IRoleRepository) {
    this.roleRepository = roleRepository;
  }

  async execute(id: number): Promise<Role> {
    return await this.roleRepository.getRoleById(id);
  }
}
