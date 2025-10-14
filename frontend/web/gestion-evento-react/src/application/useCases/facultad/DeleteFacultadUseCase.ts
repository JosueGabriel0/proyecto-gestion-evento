import type { IFacultadRepository } from "../../../domain/repositories/IFacultadRepository";

export class DeleteFacultadUseCase{
    private readonly iFacultadRepository: IFacultadRepository;

    constructor(iFacultadRepository: IFacultadRepository){
        this.iFacultadRepository = iFacultadRepository;
    }

    async execute(id: number): Promise<void>{
        await this.iFacultadRepository.deleteFacultad(id);
    }
}