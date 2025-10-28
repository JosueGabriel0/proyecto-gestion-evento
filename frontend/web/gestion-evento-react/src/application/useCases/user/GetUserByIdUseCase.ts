import type { User } from "../../../domain/entities/User";
import type { IUserRepository } from "../../../domain/repositories/IUserRepository";

export class GetUserByIdUseCase {
    private readonly iUserRepository: IUserRepository;

    constructor(iUserRepository: IUserRepository){
        this.iUserRepository = iUserRepository;
    }

    async execute(id: number): Promise<User>{
        return await this.iUserRepository.getUserById(id);
    }
}