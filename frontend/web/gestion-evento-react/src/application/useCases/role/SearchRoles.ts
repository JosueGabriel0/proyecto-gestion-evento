import { Role } from "../../../domain/entities/Role";
import type { IRoleRepository } from "../../../domain/repositories/IRoleRepository";
import type { PaginatedResponse } from "../../dtos/PaginatedResponse";

export class SearchRoles {
    private readonly roleRepository: IRoleRepository;
    constructor(roleRepository: IRoleRepository) {
        this.roleRepository = roleRepository;
    }

    async execute(term: string, perPage: number = 10): Promise<PaginatedResponse<Role>> {
        return await this.roleRepository.searchRoles(term, perPage);
    }
}