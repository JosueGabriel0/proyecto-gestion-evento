import type { Filial } from "../../../domain/entities/Filial";
import type { IFilialRepository } from "../../../domain/repositories/IFilialRepository";
import type { PaginatedResponse } from "../../dtos/PaginatedResponse";

export class GetFilialesPaginatedUseCase{
    private readonly iFilialRepository: IFilialRepository;

    constructor(iFilialRepository: IFilialRepository){
        this.iFilialRepository = iFilialRepository;
    }

    async execute(page: number, perPage: number): Promise<PaginatedResponse<Filial>>{
        return await this.iFilialRepository.getFilialesPaginated(page, perPage);
    }
}