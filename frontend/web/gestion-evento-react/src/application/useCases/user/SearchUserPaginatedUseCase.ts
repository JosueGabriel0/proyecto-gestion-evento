import type { User } from "../../../domain/entities/User";
import type { IUserRepository } from "../../../domain/repositories/IUserRepository";
import type { PaginatedResponse } from "../../dtos/PaginatedResponse";

export class SearchUserPaginatedUseCase{
    private readonly iUserRepository: IUserRepository;

    constructor(iUserRepository: IUserRepository){
        this.iUserRepository = iUserRepository;
    }

    async execute(term: string, role: string, page: number, perPage?: number): Promise<PaginatedResponse<User>>{
        return await this.iUserRepository.searchUserPaginated(term, role, page, perPage);
    }
}