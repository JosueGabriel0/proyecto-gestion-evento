import type { Filial } from "../../domain/entities/Filial";
import type { IFilialRepository } from "../../domain/repositories/IFilialRepository";
import type { PaginatedResponse } from "../dtos/PaginatedResponse";
import { CreateFilialUseCase } from "../useCases/filial/CreateFilialUseCase";
import { DeleteFilialUseCase } from "../useCases/filial/DeleteFilialUseCase";
import { GetFilialByIdUseCase } from "../useCases/filial/GetFilialByIdUseCase";
import { GetFilialesPaginatedUseCase } from "../useCases/filial/GetFilialesPaginatedUseCase";
import { GetFilialesUseCase } from "../useCases/filial/GetFilialesUseCase";
import { SearchFilialPaginatedUseCase } from "../useCases/filial/SearchFilialPaginatedUseCase";
import { UpdateFilialUseCase } from "../useCases/filial/UpdateFilialUseCase";

export class FilialService {
    private readonly createFilialUseCase: CreateFilialUseCase;
    private readonly deleteFilialUseCase: DeleteFilialUseCase;
    private readonly getFilialByIdUseCase: GetFilialByIdUseCase;
    private readonly getFilialesPaginatedUseCase: GetFilialesPaginatedUseCase;
    private readonly getFilialesUseCase: GetFilialesUseCase;
    private readonly searchFilialPaginatedUseCase: SearchFilialPaginatedUseCase;
    private readonly updateFilialUseCase: UpdateFilialUseCase;

    constructor(iFilialRepository: IFilialRepository) {
        this.createFilialUseCase = new CreateFilialUseCase(iFilialRepository);
        this.deleteFilialUseCase = new DeleteFilialUseCase(iFilialRepository);
        this.getFilialByIdUseCase = new GetFilialByIdUseCase(iFilialRepository);
        this.getFilialesPaginatedUseCase = new GetFilialesPaginatedUseCase(iFilialRepository);
        this.getFilialesUseCase = new GetFilialesUseCase(iFilialRepository);
        this.searchFilialPaginatedUseCase = new SearchFilialPaginatedUseCase(iFilialRepository);
        this.updateFilialUseCase = new UpdateFilialUseCase(iFilialRepository);
    }

    async createFilial(filial: Filial, file?: File): Promise<Filial>{
        return await this.createFilialUseCase.execute(filial, file);
    }

    async deleteFilial(id: number): Promise<void>{
        return await this.deleteFilialUseCase.execute(id);
    }

    async getFilialById(id: number): Promise<Filial>{
        return await this.getFilialByIdUseCase.execute(id);
    }

    async getFilialesPaginated(page: number, perPage: number): Promise<PaginatedResponse<Filial>>{
        return await this.getFilialesPaginatedUseCase.execute(page, perPage);
    }

    async getFiliales(): Promise<Filial[]>{
        return await this.getFilialesUseCase.execute();
    }

    async searchFilialPaginated(term: string, perPage: number): Promise<PaginatedResponse<Filial>>{
        return await this.searchFilialPaginatedUseCase.execute(term, perPage);
    }

    async updateFilial(filial: Filial, file?: File): Promise<Filial>{
        return await this.updateFilialUseCase.execute(filial, file);
    }
}