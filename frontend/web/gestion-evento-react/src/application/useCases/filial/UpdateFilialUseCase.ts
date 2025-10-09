import type { Filial } from "../../../domain/entities/Filial";
import type { IFilialRepository } from "../../../domain/repositories/IFilialRepository";

export class UpdateFilialUseCase {
    private readonly iFilialRepository: IFilialRepository;

    constructor(iFilialRepository: IFilialRepository){
        this.iFilialRepository = iFilialRepository;
    }

    async execute(filial: Filial, file?: File): Promise<Filial>{
        return await this.iFilialRepository.updateFilial(filial, file);
    }
}