import type { User } from "../../domain/entities/User";
import type { IUserRepository } from "../../domain/repositories/IUserRepository";
import type { PaginatedResponse } from "../dtos/PaginatedResponse";
import { CreateUserUseCase } from "../useCases/user/CreateUserUseCase";
import { DeleteUserUseCase } from "../useCases/user/DeleteUserUseCase";
import { GetUserByIdUseCase } from "../useCases/user/GetUserByIdUseCase";
import { GetUsersPaginatedUseCase } from "../useCases/user/GetUserPaginatedUseCase";
import { SearchUserPaginatedUseCase } from "../useCases/user/SearchUserPaginatedUseCase";
import { UpdateUserUseCase } from "../useCases/user/UpdateUserUseCase";

export class UserService {
    private readonly createUserUseCase: CreateUserUseCase;
    private readonly deleteUserUseCase: DeleteUserUseCase;
    private readonly getUserByIdUseCase: GetUserByIdUseCase;
    private readonly getUsersPaginatedUseCase: GetUsersPaginatedUseCase;
    private readonly searchUserPaginatedUseCase: SearchUserPaginatedUseCase;
    private readonly updateUserUseCase: UpdateUserUseCase;

    constructor(iUserRepository: IUserRepository){
        this.createUserUseCase = new CreateUserUseCase(iUserRepository);
        this.deleteUserUseCase = new DeleteUserUseCase(iUserRepository);
        this.getUserByIdUseCase = new GetUserByIdUseCase(iUserRepository);
        this.getUsersPaginatedUseCase = new GetUsersPaginatedUseCase(iUserRepository);
        this.searchUserPaginatedUseCase = new SearchUserPaginatedUseCase(iUserRepository);
        this.updateUserUseCase = new UpdateUserUseCase(iUserRepository);
    }

    async createUser(user: User, file?: File): Promise<User>{
        return await this.createUserUseCase.execute(user, file);
    }

    async deleteUser(id: number): Promise<void>{
        return await this.deleteUserUseCase.execute(id);
    }

    async getUserById(id:number): Promise<User>{
        return await this.getUserByIdUseCase.execute(id);
    }

    async getUsersPaginated(role: string, page: number, perPage?: number): Promise<PaginatedResponse<User>>{
        return await this.getUsersPaginatedUseCase.execute(role, page, perPage);
    }

    async searchUserPaginated(term: string, role: string, page: number, perPage?: number): Promise<PaginatedResponse<User>>{
        return await this.searchUserPaginatedUseCase.execute(term, role, page, perPage);
    }

    async updateUser(user: User, file?: File): Promise<User>{
        return await this.updateUserUseCase.execute(user, file);
    }
}