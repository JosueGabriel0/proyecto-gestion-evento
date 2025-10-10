import type { Escuela } from "../../../domain/entities/Escuela";
import type { IEscuelaRepository } from "../../../domain/repositories/IEscuelaRepository";

export class CreateEscuelaUseCase{
    private readonly iEscuelaRepository: IEscuelaRepository;

    constructor(iEscuelaRepository: IEscuelaRepository){
        this.iEscuelaRepository = iEscuelaRepository;
    }

    async execute(escuela: Escuela, file?: File): Promise<Escuela>{
        return await this.iEscuelaRepository.createEscuela(escuela, file);
    }
}