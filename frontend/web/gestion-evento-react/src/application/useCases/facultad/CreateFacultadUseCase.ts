import type { Facultad } from "../../../domain/entities/Facultad";
import type { IFacultadRepository } from "../../../domain/repositories/IFacultadRepository";

export class CreateFacultadUseCase {
    private readonly iFacultadRepository: IFacultadRepository;

    constructor(iFacultadRepository: IFacultadRepository){
        this.iFacultadRepository = iFacultadRepository;
    }

    async execute(facultad: Facultad, file?: File): Promise<Facultad>{
        return await this.iFacultadRepository.postFacultad(facultad, file);
    }
}