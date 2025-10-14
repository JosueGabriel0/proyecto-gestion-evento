import type { Facultad } from "../../../domain/entities/Facultad";
import type { IFacultadRepository } from "../../../domain/repositories/IFacultadRepository";

export class GetFacultadesUseCase {
    private readonly iFacultadRepository: IFacultadRepository;

    constructor(iFacultadRepository: IFacultadRepository){
        this.iFacultadRepository = iFacultadRepository;
    }

    async execute(): Promise<Facultad[]>{
        return await this.iFacultadRepository.getFacultades();
    }
}