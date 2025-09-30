// application/use-cases/GetRoles.ts

import { Role } from "../../../domain/entities/Role";
import type{ IRoleRepository } from "../../../domain/repositories/IRoleRepository";

export class GetRoles {
  private readonly roleRepository: IRoleRepository;
  constructor(roleRepository: IRoleRepository) {
    this.roleRepository = roleRepository;
  }

  async execute(): Promise<Role[]> {
    return await this.roleRepository.getRoles();
  }
}
