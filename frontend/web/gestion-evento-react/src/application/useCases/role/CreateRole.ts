import { Role } from "../../../domain/entities/Role";
import type { IRoleRepository } from "../../../domain/repositories/IRoleRepository";

export class CreateRole {
  private readonly roleRepository: IRoleRepository;

  constructor(roleRepository: IRoleRepository) {
    this.roleRepository = roleRepository;
  }

  async execute(role: Role, file?: File): Promise<Role> {
    return await this.roleRepository.createRole(role, file);
  }
}