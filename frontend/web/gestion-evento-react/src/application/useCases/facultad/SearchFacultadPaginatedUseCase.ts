import type { PaginatedResponse } from "../../../application/dtos/PaginatedResponse";
import type { Facultad } from "../../../domain/entities/Facultad";
import type { IFacultadRepository } from "../../../domain/repositories/IFacultadRepository";

export class SearchFacultadPaginatedUseCase {
    private readonly repository: IFacultadRepository;

    constructor(repository: IFacultadRepository) {
        this.repository = repository;
    }

    async execute(term: string, perPage?: number): Promise<PaginatedResponse<Facultad>> {
        return await this.repository.searchPaginated(term, perPage);
    }
}
