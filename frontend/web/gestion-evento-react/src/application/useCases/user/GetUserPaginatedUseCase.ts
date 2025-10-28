import type { User } from "../../../domain/entities/User";
import type { IUserRepository } from "../../../domain/repositories/IUserRepository";
import type { PaginatedResponse } from "../../dtos/PaginatedResponse";

export class GetUsersPaginatedUseCase{
    private readonly iUserRepository: IUserRepository;

    constructor(iUserRepository: IUserRepository){
        this.iUserRepository = iUserRepository;
    }

    async execute(role: string, page: number, perPage?: number): Promise<PaginatedResponse<User>>{
        return await this.iUserRepository.paginateUser(role, page, perPage);
    }
}