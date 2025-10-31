import type { Escuela } from "../../domain/entities/Escuela";
import type { IEscuelaRepository } from "../../domain/repositories/IEscuelaRepository";
import type { PaginatedResponse } from "../dtos/PaginatedResponse";
import { CreateEscuelaUseCase } from "../useCases/escuela/CreateEscuelaUseCase";
import { DeleteEscuelaUseCase } from "../useCases/escuela/DeleteEscuelaUseCase";
import { GetAllEscuelasByFacultadIdUseCase } from "../useCases/escuela/GetAllEscuelasByFacultadIdUseCase";
import { GetEscuelaByIdUseCase } from "../useCases/escuela/GetEscuelaByIdUseCase";
import { GetEscuelasPaginatedUseCase } from "../useCases/escuela/GetEscuelasPaginatedUseCase";
import { GetEscuelasUseCase } from "../useCases/escuela/GetEscuelasUseCase";
import { SearchEscuelaPaginatedUseCase } from "../useCases/escuela/SearchEscuelaPaginatedUseCase";
import { UpdateEscuelaUseCase } from "../useCases/escuela/UpdateEscuelaUseCase";

export class EscuelaService {
    private readonly createEscuelaUseCase: CreateEscuelaUseCase;
    private readonly deleteEscuelaUseCase: DeleteEscuelaUseCase;
    private readonly getEscuelaByIdUseCase: GetEscuelaByIdUseCase;
    private readonly getEscuelasPaginatedUseCase: GetEscuelasPaginatedUseCase;
    private readonly getEscuelasUseCase: GetEscuelasUseCase;
    private readonly searchEscuelaPaginatedUseCase: SearchEscuelaPaginatedUseCase;
    private readonly updateEscuelaUseCase: UpdateEscuelaUseCase;
    private readonly getAllEscuelasByFacultadIdUseCase: GetAllEscuelasByFacultadIdUseCase;

    constructor(iEscuelaRepository: IEscuelaRepository) {
        this.createEscuelaUseCase = new CreateEscuelaUseCase(iEscuelaRepository);
        this.deleteEscuelaUseCase = new DeleteEscuelaUseCase(iEscuelaRepository);
        this.getEscuelaByIdUseCase = new GetEscuelaByIdUseCase(iEscuelaRepository);
        this.getEscuelasPaginatedUseCase = new GetEscuelasPaginatedUseCase(iEscuelaRepository);
        this.getEscuelasUseCase = new GetEscuelasUseCase(iEscuelaRepository);
        this.searchEscuelaPaginatedUseCase = new SearchEscuelaPaginatedUseCase(iEscuelaRepository);
        this.updateEscuelaUseCase = new UpdateEscuelaUseCase(iEscuelaRepository);
        this.getAllEscuelasByFacultadIdUseCase = new GetAllEscuelasByFacultadIdUseCase(iEscuelaRepository);
    }

    async createEscuela(escuela: Escuela, file?: File): Promise<Escuela> {
        return await this.createEscuelaUseCase.execute(escuela, file);
    }

    async deleteEscuela(id: number): Promise<void> {
        return await this.deleteEscuelaUseCase.execute(id);
    }

    async getEscuelaById(id: number): Promise<Escuela> {
        return await this.getEscuelaByIdUseCase.execute(id);
    }

    async getEscuelasPaginated(page: number, perPage?: number): Promise<PaginatedResponse<Escuela>> {
        return await this.getEscuelasPaginatedUseCase.execute(page, perPage);
    }

    async getEscuelas(): Promise<Escuela[]> {
        return await this.getEscuelasUseCase.execute();
    }

    async getEscuelasByFacultad(id: number): Promise<Escuela[]> {
        return await this.getAllEscuelasByFacultadIdUseCase.execute(id);
    }

    async searchEscuelaPaginated(term: string, perPage?: number): Promise<PaginatedResponse<Escuela>> {
        return await this.searchEscuelaPaginatedUseCase.execute(term, perPage);
    }

    async updateEscuela(escuela: Escuela, file?: File): Promise<Escuela> {
        return await this.updateEscuelaUseCase.execute(escuela, file);
    }
}