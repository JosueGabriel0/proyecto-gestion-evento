import type { Facultad } from "../../../domain/entities/Facultad";
import type { IFacultadRepository } from "../../../domain/repositories/IFacultadRepository";

export class GetFacultadByIdUseCase {
    private readonly repository: IFacultadRepository;

    constructor(repository: IFacultadRepository) {
        this.repository = repository;
    }

    async execute(id: number): Promise<Facultad> {
        return await this.repository.getById(id);
    }
}
