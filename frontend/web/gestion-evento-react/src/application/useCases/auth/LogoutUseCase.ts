import type { ITokenStorage } from "../../../domain/repositories/ITokenStorage";

export class LogoutUseCase {
    private readonly storage: ITokenStorage;
    constructor(storage: ITokenStorage) {
        this.storage = storage;
    }

    execute(): void {
        this.storage.clear();
    }
}