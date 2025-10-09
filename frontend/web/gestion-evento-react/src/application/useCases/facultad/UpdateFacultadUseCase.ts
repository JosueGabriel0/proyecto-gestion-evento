import type { Facultad } from "../../../domain/entities/Facultad";
import type { IFacultadRepository } from "../../../domain/repositories/IFacultadRepository";

export class UpdateFacultadUseCase {
    private readonly repository: IFacultadRepository;

    constructor(repository: IFacultadRepository) {
        this.repository = repository;
    }

    async execute(facultad: Facultad, file?: File): Promise<Facultad> {
        return await this.repository.update(facultad, file);
    }
}
