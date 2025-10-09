import { Facultad } from "../../../domain/entities/Facultad";
import type { FacultadDTO } from "../FacultadDTO";

export class FacultadMapper {
    static toDomain(dto: FacultadDTO): Facultad {
        const facultad = new Facultad(dto.id, dto.nombre, dto.codigo ?? "", dto.foto ?? "", dto.filialId);
        return(facultad);
    }

    static toDTO(facultad: Facultad): FacultadDTO{
        const facultadDto = {
            id: facultad.id,
            nombre: facultad.nombre,
            codigo: facultad.codigo,
            foto: facultad.foto,
            filialId: facultad.filialId,
        }

        return(facultadDto);
    }
}