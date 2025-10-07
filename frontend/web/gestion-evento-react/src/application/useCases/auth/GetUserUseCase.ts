import type { ITokenStorage } from "../../../domain/repositories/ITokenStorage";
import type { User } from "../../../domain/entities/Auth";

export class GetUserUseCase {
    private readonly storage: ITokenStorage;
    constructor(storage: ITokenStorage) {
        this.storage = storage;
    }

    execute(): User | null {
        return this.storage.getUser();
    }
}