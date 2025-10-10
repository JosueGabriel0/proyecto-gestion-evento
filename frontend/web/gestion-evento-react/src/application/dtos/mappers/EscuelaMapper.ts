import { Escuela } from "../../../domain/entities/Escuela";
import type { EscuelaDTO } from "../EscuelaDTO.ts";

export class EscuelaMapper {
  static toDomain(dto: EscuelaDTO): Escuela {
    return new Escuela(
      dto.id,
      dto.nombre,
      dto.codigo,
      dto.facultad_id,
      dto.foto
    );
  }

  static toDTO(escuela: Escuela): EscuelaDTO {
    return {
      id: escuela.id,
      nombre: escuela.nombre,
      codigo: escuela.codigo,
      facultad_id: escuela.facultad_id,
      foto: escuela.foto,
    };
  }
}