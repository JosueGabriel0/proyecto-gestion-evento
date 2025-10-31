import type { Facultad } from "../../../domain/entities/Facultad";
import type { IFacultadRepository } from "../../../domain/repositories/IFacultadRepository";

export class GetFacultadesByFilialIdUseCase {
    private readonly iFacultadRepository: IFacultadRepository;

    constructor(iFacultadRepository: IFacultadRepository){
        this.iFacultadRepository = iFacultadRepository;
    }

    async execute(id: number): Promise<Facultad[]>{
        return await this.iFacultadRepository.getFacultadesByFilialId(id);
    }
}