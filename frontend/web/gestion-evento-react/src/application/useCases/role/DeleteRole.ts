import type { IRoleRepository } from "../../../domain/repositories/IRoleRepository";

export class DeleteRole {
  private readonly roleRepository: IRoleRepository;
  
  constructor(roleRepository: IRoleRepository) {
    this.roleRepository = roleRepository;
  }

  async execute(id: number): Promise<void> {
    return await this.roleRepository.deleteRole(id);
  }
}