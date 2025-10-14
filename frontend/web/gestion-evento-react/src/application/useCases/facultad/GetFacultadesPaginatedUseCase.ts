import type { IFacultadRepository } from "../../../domain/repositories/IFacultadRepository";

export class GetFacultadesPaginatedUseCase {
    private readonly iFacultadRepository: IFacultadRepository;

    constructor(iFacultadRepository: IFacultadRepository){
        this.iFacultadRepository = iFacultadRepository;
    }

    async execute(page: number, perPage?: number){
        return await this.iFacultadRepository.listFacultadPaginated(page, perPage);
    }
}