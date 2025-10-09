import type { IFacultadRepository } from "../../../domain/repositories/IFacultadRepository";

export class DeleteFacultadUseCase {
    private readonly repository: IFacultadRepository;

    constructor(repository: IFacultadRepository) {
        this.repository = repository;
    }

    async execute(id: number): Promise<void> {
        return await this.repository.delete(id);
    }
}
