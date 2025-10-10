import type { Filial } from "../../../domain/entities/Filial";
import type { IFilialRepository } from "../../../domain/repositories/IFilialRepository";
import type { PaginatedResponse } from "../../dtos/PaginatedResponse";

export class SearchFilialPaginatedUseCase{
    private readonly iFilialRepository: IFilialRepository;

    constructor(iFilialRepository: IFilialRepository){
        this.iFilialRepository = iFilialRepository;
    }

    async execute(term: string, perPage: number): Promise<PaginatedResponse<Filial>>{
        return await this.iFilialRepository.searchFilialesPaginated(term, perPage);
    }
}