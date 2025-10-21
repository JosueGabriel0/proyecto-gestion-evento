import type { IEscuelaRepository } from "../../../domain/repositories/IEscuelaRepository";

export class GetEscuelasPaginatedUseCase{
    private readonly iEscuelaRepository: IEscuelaRepository;

    constructor(iEscuelaRepository: IEscuelaRepository){
        this.iEscuelaRepository = iEscuelaRepository;
    }

    async execute(page: number, perPage?: number){
        return await this.iEscuelaRepository.paginateEscuela(page, perPage);
    }
}