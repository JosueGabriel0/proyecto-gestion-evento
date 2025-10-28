import type { User } from "../../../domain/entities/User";
import type { IUserRepository } from "../../../domain/repositories/IUserRepository";

export class CreateUserUseCase{
    private readonly iUserRepository: IUserRepository;

    constructor(iUserRepository: IUserRepository){
        this.iUserRepository = iUserRepository;
    }

    async execute(user: User, file?: File): Promise<User>{
        return await this.iUserRepository.postUser(user, file);
    }
}