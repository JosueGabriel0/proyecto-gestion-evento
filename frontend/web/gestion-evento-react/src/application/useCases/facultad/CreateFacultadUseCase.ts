import { Facultad } from "../../../domain/entities/Facultad";
import type { IFacultadRepository } from "../../../domain/repositories/IFacultadRepository";

export class CreateFacultadUseCase {
    private readonly repository: IFacultadRepository;

    constructor(repository: IFacultadRepository) {
        this.repository = repository;
    }

    async createFacultad(facultadData: {
        id: number;
        nombre: string;
        codigo: string;
        foto: string | null;
        filialId: number;
    }, file?: File): Promise<any> {
        const facultad = new Facultad(
            facultadData.id,
            facultadData.nombre,
            facultadData.codigo,
            facultadData.foto,
            facultadData.filialId
        );
        return await this.execute(facultad, file);
    }
    async execute(facultad: Facultad, file?: File): Promise<Facultad> {
        return await this.repository.create(facultad, file);
    }
}
