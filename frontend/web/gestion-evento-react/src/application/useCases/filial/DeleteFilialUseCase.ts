import type { IFilialRepository } from "../../../domain/repositories/IFilialRepository";

export class DeleteFilialUseCase{
    private readonly iFilialRepository: IFilialRepository;
    constructor(iFilialRepository: IFilialRepository){
        this.iFilialRepository = iFilialRepository;
    }

    async execute(id: number): Promise<void> {
        return await this.iFilialRepository.deleteFilial(id);
    }
}