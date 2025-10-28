import type { IUserRepository } from "../../../domain/repositories/IUserRepository";

export class DeleteUserUseCase{
    private readonly iUserRepository: IUserRepository;

    constructor(iUserRepository: IUserRepository){
        this.iUserRepository = iUserRepository;
    }

    async execute(id: number): Promise<void>{
        return await this.iUserRepository.deleteUser(id);
    }
}