import type { IEscuelaRepository } from "../../../domain/repositories/IEscuelaRepository";

export class DeleteEscuelaUseCase{
    private readonly iEscuelaRepository: IEscuelaRepository;

    constructor(iEscuelaRepository: IEscuelaRepository){
        this.iEscuelaRepository = iEscuelaRepository;
    }

    async execute(id: number): Promise<void>{
        return await this.iEscuelaRepository.deleteEscuela(id);
    }
}