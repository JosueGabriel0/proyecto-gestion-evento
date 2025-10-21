import type { Escuela } from "../../../domain/entities/Escuela";
import type { IEscuelaRepository } from "../../../domain/repositories/IEscuelaRepository";

export class GetEscuelasUseCase{
    private readonly iEscuelaRepository: IEscuelaRepository;

    constructor(iEscuelRepository: IEscuelaRepository){
        this.iEscuelaRepository = iEscuelRepository;
    }

    async execute(): Promise<Escuela[]>{
        return await this.iEscuelaRepository.getAllEscuelas();
    }
}