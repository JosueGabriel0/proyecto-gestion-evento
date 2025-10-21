import { Escuela } from "../../../domain/entities/Escuela";
import type { EscuelaDTO } from "../EscuerlaDTO";

export class EscuelaMapper {
    static toDomain(dto: EscuelaDTO): Escuela {
        const escuela = new Escuela(dto.id, dto.nombre, dto.codigo, dto.foto ?? "", dto.facultadId);
        return escuela;
    }

    static toDTO(escuela: Escuela): EscuelaDTO{
        const escuelaDTO = {
            id: escuela.id, 
            nombre: escuela.nombre,
            codigo: escuela.codigo,
            facultadId: escuela.facultadId,
            foto: escuela.foto
        };

        return (escuelaDTO);
    }
}