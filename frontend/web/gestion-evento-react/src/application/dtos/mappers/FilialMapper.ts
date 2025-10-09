import { Filial } from "../../../domain/entities/Filial";
import type { FilialDTO } from "../FilialDTO";

export class FilialMapper{
    static toDomain(dto: FilialDTO): Filial{
        return new Filial(dto.id, dto.nombre, dto.direccion, dto.telefono, dto.email, dto.foto)
    }

    static toDTO(filial: Filial): FilialDTO{
        return (
            {
                id: filial.id,
                nombre: filial.nombre,
                direccion: filial.direccion,
                telefono: filial.telefono,
                email: filial.email,
                foto: filial.foto,
            }
        )
    }
}