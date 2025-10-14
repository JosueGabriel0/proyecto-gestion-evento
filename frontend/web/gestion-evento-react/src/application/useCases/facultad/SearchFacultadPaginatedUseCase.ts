import type { Facultad } from "../../../domain/entities/Facultad";
import type { IFacultadRepository } from "../../../domain/repositories/IFacultadRepository";
import type { PaginatedResponse } from "../../dtos/PaginatedResponse";

export class SearchFacultadPaginatedUseCase {
    private readonly iFacultadRepository: IFacultadRepository;

    constructor(iFacultadRepository: IFacultadRepository){
        this.iFacultadRepository = iFacultadRepository;
    }

    async execute(term: string, perPage?: number): Promise<PaginatedResponse<Facultad>>{
        return await this.iFacultadRepository.searchFacultadPaginated(term, perPage);
    }
}