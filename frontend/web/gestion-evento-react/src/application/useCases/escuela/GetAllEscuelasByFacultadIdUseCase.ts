import type { Escuela } from "../../../domain/entities/Escuela";
import type { IEscuelaRepository } from "../../../domain/repositories/IEscuelaRepository";

export class GetAllEscuelasByFacultadIdUseCase {
    private readonly iEscuelaRepository: IEscuelaRepository;

    constructor(iEscuelaRepository: IEscuelaRepository){
        this.iEscuelaRepository = iEscuelaRepository;
    }

    async execute(id: number): Promise<Escuela[]>{
        return await this.iEscuelaRepository.getAllEscuelasByFacultadId(id);
    }
}