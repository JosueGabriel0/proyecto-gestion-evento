import type { Escuela } from "../../domain/entities/Escuela";
import type { IEscuelaRepository } from "../../domain/repositories/IEscuelaRepository";
import type { PaginatedResponse } from "../dtos/PaginatedResponse";

import { CreateEscuelaUseCase } from "../useCases/escuela/CreateEscuelaUseCase";
import { DeleteEscuelaUseCase } from "../useCases/escuela/DeleteEscuelaUseCase";
import { GetEscuelaByIdUseCase } from "../useCases/escuela/GetEscuelaByIdUseCase";
import { GetEscuelaPaginatedUseCase } from "../useCases/escuela/GetEscuelaPaginatedUseCase";
import { GetEscuelaUseCase } from "../useCases/escuela/GetEscuelaUseCase";
import { SearchEscuelaPaginatedUseCase } from "../useCases/escuela/SearchEscuelaPaginatedUseCase";
import { UpdateEscuelaUseCase } from "../useCases/escuela/UpdateEscuelaUseCase";

export class EscuelaService {
  private readonly createEscuelaUseCase: CreateEscuelaUseCase;
  private readonly deleteEscuelaUseCase: DeleteEscuelaUseCase;
  private readonly getEscuelaByIdUseCase: GetEscuelaByIdUseCase;
  private readonly getEscuelaPaginatedUseCase: GetEscuelaPaginatedUseCase;
  private readonly getEscuelaUseCase: GetEscuelaUseCase;
  private readonly searchEscuelaPaginatedUseCase: SearchEscuelaPaginatedUseCase;
  private readonly updateEscuelaUseCase: UpdateEscuelaUseCase;

  constructor(iEscuelaRepository: IEscuelaRepository) {
    this.createEscuelaUseCase = new CreateEscuelaUseCase(iEscuelaRepository);
    this.deleteEscuelaUseCase = new DeleteEscuelaUseCase(iEscuelaRepository);
    this.getEscuelaByIdUseCase = new GetEscuelaByIdUseCase(iEscuelaRepository);
    this.getEscuelaPaginatedUseCase = new GetEscuelaPaginatedUseCase(iEscuelaRepository);
    this.getEscuelaUseCase = new GetEscuelaUseCase(iEscuelaRepository);
    this.searchEscuelaPaginatedUseCase = new SearchEscuelaPaginatedUseCase(iEscuelaRepository);
    this.updateEscuelaUseCase = new UpdateEscuelaUseCase(iEscuelaRepository);
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

  async getEscuelasPaginated(page: number, perPage: number): Promise<PaginatedResponse<Escuela>> {
    return await this.getEscuelaPaginatedUseCase.execute(page, perPage);
  }

  async getEscuelas(): Promise<Escuela[]> {
    return await this.getEscuelaUseCase.execute();
  }

  async searchEscuelaPaginated(term: string, perPage: number): Promise<PaginatedResponse<Escuela>> {
    return await this.searchEscuelaPaginatedUseCase.execute(term, perPage);
  }

  async updateEscuela(escuela: Escuela, file?: File): Promise<Escuela> {
    return await this.updateEscuelaUseCase.execute(escuela, file);
  }
<<<<<<< HEAD
}
=======
}
>>>>>>> temp-recuperacion}
