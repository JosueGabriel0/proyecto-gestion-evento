import { User } from "../../../domain/entities/User";
import type { UserDTO, PersonaDTO } from "../UserDTO";

export class UserMapper {
  static fromDTO(dto: UserDTO): User {
    return new User({
      id: dto.id,
      email: dto.email,
      password: "", // no se envía desde backend
      escuelaId: dto.escuela_id,
      roleId: undefined,
      persona: dto.persona ? this.mapPersona(dto.persona) : undefined,
      // 👇 Aquí está la corrección:
      role: dto.role
        ? { id: 0, nombre: dto.role } // role viene como string
        : { id: 0, nombre: "SIN_ROL" },
      alumno: dto.alumno
        ? { codigoUniversitario: dto.alumno.codigo_universitario }
        : undefined,
      jurado: dto.jurado
        ? { especialidad: dto.jurado.especialidad }
        : undefined,
      ponente: dto.ponente
        ? { biografia: dto.ponente.biografia }
        : undefined,
      createdAt: dto.created_at ? new Date(dto.created_at) : undefined,
      updatedAt: dto.updated_at ? new Date(dto.updated_at) : undefined,
    });
  }

  private static mapPersona(dto: PersonaDTO) {
    return {
      nombres: dto.nombres,
      apellidos: dto.apellidos,
      tipoDocumento: dto.tipo_documento,
      numeroDocumento: dto.numero_documento,
      telefono: dto.telefono,
      direccion: dto.direccion,
      pais: dto.pais,
      religion: dto.religion,
      correoElectronico: dto.correo_electronico,
      correoInstitucional: dto.correo_institucional,
      fotoPerfil: dto.foto_perfil || undefined,
      fechaNacimiento: new Date(dto.fecha_nacimiento),
    };
  }
}