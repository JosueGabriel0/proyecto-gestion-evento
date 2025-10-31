import type { Facultad } from "../../domain/entities/Facultad";
import type { IFacultadRepository } from "../../domain/repositories/IFacultadRepository";
import type { PaginatedResponse } from "../dtos/PaginatedResponse";
import { CreateFacultadUseCase } from "../useCases/facultad/CreateFacultadUseCase";
import { DeleteFacultadUseCase } from "../useCases/facultad/DeleteFacultadUseCase";
import { GetFacultadByIdUseCase } from "../useCases/facultad/GetFacultadByIdUseCase";
import { GetFacultadesByFilialIdUseCase } from "../useCases/facultad/GetFacultadesByFilialIdUseCase";
import { GetFacultadesPaginatedUseCase } from "../useCases/facultad/GetFacultadesPaginatedUseCase";
import { GetFacultadesUseCase } from "../useCases/facultad/GetFacultadesUseCase";
import { SearchFacultadPaginatedUseCase } from "../useCases/facultad/SearchFacultadPaginatedUseCase";
import { UpdateFacultadUseCase } from "../useCases/facultad/UpdateFacultadUseCase";

export class FacultadService {
    private readonly createFacultadUseCase: CreateFacultadUseCase;
    private readonly deleteFacultadUseCase: DeleteFacultadUseCase;
    private readonly getFacultadByIdUseCase: GetFacultadByIdUseCase;
    private readonly getFacultadesPaginatedUseCase: GetFacultadesPaginatedUseCase;
    private readonly getFacultadesUseCase: GetFacultadesUseCase;
    private readonly searchFacultadPaginatedUseCase: SearchFacultadPaginatedUseCase;
    private readonly updateFacultadUseCase: UpdateFacultadUseCase;
    private readonly getFacultadesByFilialId: GetFacultadesByFilialIdUseCase;

    constructor(iFacultadRepository: IFacultadRepository){
        this.createFacultadUseCase = new CreateFacultadUseCase(iFacultadRepository);
        this.deleteFacultadUseCase = new DeleteFacultadUseCase(iFacultadRepository);
        this.getFacultadByIdUseCase = new GetFacultadByIdUseCase(iFacultadRepository);
        this.getFacultadesPaginatedUseCase = new GetFacultadesPaginatedUseCase(iFacultadRepository);
        this.getFacultadesUseCase = new GetFacultadesUseCase(iFacultadRepository);
        this.searchFacultadPaginatedUseCase = new SearchFacultadPaginatedUseCase(iFacultadRepository);
        this.updateFacultadUseCase = new UpdateFacultadUseCase(iFacultadRepository);
        this.getFacultadesByFilialId = new GetFacultadesByFilialIdUseCase(iFacultadRepository);
    }

    async createFacultad(facultad: Facultad, file?: File): Promise<Facultad>{
        return await this.createFacultadUseCase.execute(facultad, file);
    }

    async deleteFacultad(id:number): Promise<void>{
        await this.deleteFacultadUseCase.execute(id);
    }

    async getFacultadById(id:number): Promise<Facultad>{
        return await this.getFacultadByIdUseCase.execute(id);
    }

    async getFacultadesPaginated(page: number, perPage?: number): Promise<PaginatedResponse<Facultad>>{
        return await this.getFacultadesPaginatedUseCase.execute(page, perPage);
    }

    async getFacultades(): Promise<Facultad[]>{
        return await this.getFacultadesUseCase.execute();
    }

    async getFacultadesByFilial(id: number): Promise<Facultad[]>{
        return await this.getFacultadesByFilialId.execute(id);
    }

    async searchFacultadPaginated(term: string, perPage?: number): Promise<PaginatedResponse<Facultad>>{
        return await this.searchFacultadPaginatedUseCase.execute(term, perPage);
    }

    async putFacultad(facultad: Facultad, file?: File): Promise<Facultad>{
        return await this.updateFacultadUseCase.execute(facultad, file);
    }
}