import type { Filial } from "../../../domain/entities/Filial";
import type { IFilialRepository } from "../../../domain/repositories/IFilialRepositoy";

export class GetFilialesUseCase {
    private readonly iFilialRepository: IFilialRepository;

    constructor(iFilialRepository: IFilialRepository){
        this.iFilialRepository = iFilialRepository;
    }

    async execute(): Promise<Filial[]>{
        return await this.iFilialRepository.getFiliales();
    }
}