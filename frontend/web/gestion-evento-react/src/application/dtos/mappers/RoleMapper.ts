import { Role } from "../../../domain/entities/Role";
import type { RoleDTO } from "../RoleDTO";

export class RoleMapper {
  static toDomain(dto: RoleDTO): Role {
    return new Role(dto.id, dto.nombre, dto.foto);
  }

  static toDTO(role: Role): RoleDTO {
    return {
      id: role.id,
      nombre: role.nombre,
      foto: role.foto
    };
  }
}