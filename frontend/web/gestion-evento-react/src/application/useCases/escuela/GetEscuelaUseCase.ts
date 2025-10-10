import type { Escuela } from "../../../domain/entities/Escuela";
import type { IEscuelaRepository } from "../../../domain/repositories/IEscuelaRepository";

export class GetEscuelaUseCase {
    private readonly iEscuelaRepository: IEscuelaRepository;

    constructor(iEscuelaRepository: IEscuelaRepository){
        this.iEscuelaRepository = iEscuelaRepository;
    }

    async execute(): Promise<Escuela[]>{
        return await this.iEscuelaRepository.getEscuelas();
    }
}