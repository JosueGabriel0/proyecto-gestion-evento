import type { PaginatedResponse } from "../../application/dtos/PaginatedResponse";
import type { User } from "../entities/User";

export interface IUserRepository{
    paginateUser(role: string, page: number, perPage?: number): Promise<PaginatedResponse<User>>;
    searchUserPaginated(term: string, role: string, page: number, perPage?: number): Promise<PaginatedResponse<User>>;
    getUserById(id: number): Promise<User>;
    putUser(user: User, file?: File): Promise<User>;
    postUser(user: User, file?: File): Promise<User>;
    deleteUser(id: number): Promise<void>;
}