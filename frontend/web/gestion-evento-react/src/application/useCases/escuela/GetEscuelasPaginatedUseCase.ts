import type { Escuela } from "../../../domain/entities/Escuela";
import type { IEscuelaRepository } from "../../../domain/repositories/IEscuelaRepository";
import type { PaginatedResponse } from "../../dtos/PaginatedResponse";

export class GetEscuelasPaginatedUseCase{
    private readonly iEscuelaRepository: IEscuelaRepository;

    constructor(iEscuelaRepository: IEscuelaRepository){
        this.iEscuelaRepository = iEscuelaRepository;
    }

    async execute(page: number, perPage?: number): Promise<PaginatedResponse<Escuela>>{
        return await this.iEscuelaRepository.paginateEscuela(page, perPage);
    }
}