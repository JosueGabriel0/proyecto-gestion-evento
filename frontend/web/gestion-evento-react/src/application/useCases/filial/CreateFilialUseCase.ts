import type { Filial } from "../../../domain/entities/Filial";
import type { IFilialRepository } from "../../../domain/repositories/IFilialRepository";

export class CreateFilialUseCase{
    private readonly ifilialRepository: IFilialRepository;

    constructor(ifilialRepository: IFilialRepository){
        this.ifilialRepository = ifilialRepository;
    }

    async execute(filial: Filial, file?: File): Promise<Filial>{
        return await this.ifilialRepository.createFilial(filial, file);
    }
}