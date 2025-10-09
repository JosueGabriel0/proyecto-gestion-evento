import type { Filial } from "../../../domain/entities/Filial";
import type { IFilialRepository } from "../../../domain/repositories/IFilialRepository";

export class GetFilialByIdUseCase {
    private readonly iFilialRepository: IFilialRepository;

    constructor(iFilialRepository: IFilialRepository){
        this.iFilialRepository = iFilialRepository;
    }

    async execute(id: number): Promise<Filial>{
        return await this.iFilialRepository.getFilialById(id);
    }
}