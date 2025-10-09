import { Facultad } from "../../../domain/entities/Facultad";
import type { FacultadDTO } from "../FacultadDTO";

export class FacultadMapper {
  static toDomain(dto: FacultadDTO): Facultad {
    return new Facultad(
      dto.id,
      dto.nombre,
      dto.codigo,
      dto.foto ?? null,   // ← evita undefined
      dto.filial_id       // ← backend usa "_"
    );
  }

  static toDTO(facultad: Facultad): FacultadDTO {
    return {
      id: facultad.id,
      nombre: facultad.nombre,
      codigo: facultad.codigo,
      foto: facultad.foto ?? null,
      filial_id: facultad.filialId, // ← convertimos a backend format
    };
  }
}