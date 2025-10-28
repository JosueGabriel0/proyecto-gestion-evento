import type { User } from "../../../domain/entities/User";
import type { IUserRepository } from "../../../domain/repositories/IUserRepository";

export class UpdateUserUseCase {
    private readonly iUserRepository: IUserRepository;

    constructor(iUserRepository: IUserRepository){
        this.iUserRepository = iUserRepository;
    }

    async execute(user: User, file?: File): Promise<User>{
        return await this.iUserRepository.putUser(user, file);
    }
}