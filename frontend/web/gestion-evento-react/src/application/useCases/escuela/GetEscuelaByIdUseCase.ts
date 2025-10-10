import type { Escuela } from "../../../domain/entities/Escuela";
import type { IEscuelaRepository } from "../../../domain/repositories/IEscuelaRepository";

export class GetEscuelaByIdUseCase {
    private readonly iEscuelaRepository: IEscuelaRepository;

    constructor(iEscuelaRepository: IEscuelaRepository){
        this.iEscuelaRepository = iEscuelaRepository;
    }

    async execute(id: number): Promise<Escuela>{
        return await this.iEscuelaRepository.getEscuelaById(id);
    }
}