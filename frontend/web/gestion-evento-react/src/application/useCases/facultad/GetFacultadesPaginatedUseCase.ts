import type { PaginatedResponse } from "../../../application/dtos/PaginatedResponse";
import type { Facultad } from "../../../domain/entities/Facultad";
import type { IFacultadRepository } from "../../../domain/repositories/IFacultadRepository";

export class GetFacultadesPaginatedUseCase {
    private readonly repository: IFacultadRepository;

    constructor(repository: IFacultadRepository) {
        this.repository = repository;
    }

    async execute(page?: number, perPage?: number): Promise<PaginatedResponse<Facultad>> {
        return await this.repository.getPaginated(page, perPage);
    }
}
