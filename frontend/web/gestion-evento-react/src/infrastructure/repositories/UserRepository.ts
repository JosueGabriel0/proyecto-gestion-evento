import { UserMapper } from "../../application/dtos/mappers/UserMapper";
import type { PaginatedResponse } from "../../application/dtos/PaginatedResponse";
import type { User } from "../../domain/entities/User";
import type { IUserRepository } from "../../domain/repositories/IUserRepository";
import { AxiosClient } from "../config/AxiosClient";

export class UserRepository implements IUserRepository {
    private readonly endpoint = "/users";

    async paginateUser(role: string, page: number, perPage?: number): Promise<PaginatedResponse<User>> {
        const response = await AxiosClient.get(`${this.endpoint}/paginated?role=${role}&page=${page}&per_page=${perPage}`);
        const paginatedData = response.data;

        return ({
            current_page: paginatedData.current_page,
            per_page: paginatedData.per_page,
            total: paginatedData.total,
            data: paginatedData.data.map((dto: any) => UserMapper.fromDTO(dto)),
        });
    }

    async searchUserPaginated(term: string, role: string, page: number, perPage?: number): Promise<PaginatedResponse<User>> {
        const response = await AxiosClient.get(`${this.endpoint}/search?term=${term}&role=${role}&page=${page}&per_page=${perPage}`);
        const paginatedData = response.data;
        return ({
            current_page: paginatedData.current_page,
            per_page: paginatedData.per_page,
            total: paginatedData.total,
            data: paginatedData.data.map((dto: any) => UserMapper.fromDTO(dto)),
        });
    }

    async getUserById(id: number): Promise<User> {
        const response = await AxiosClient.get(`${this.endpoint}/${id}`);
        return UserMapper.fromDTO(response.data);
    }

    async putUser(user: User, file?: File): Promise<User> {
        if (!user.getId()) {
            throw new Error("El usuario debe tener un ID para poder actualizarse.");
        }

        const formData = new FormData();

        // ======================
        // 📧 Datos del usuario
        // ======================
        formData.append("email", user.getEmail());
        if (user.getPassword()) formData.append("password", user.getPassword());
        formData.append("escuela_id", user.getEscuelaId().toString());
        formData.append("role", user.getRole()?.nombre || "");

        // ======================
        // 👤 Datos personales
        // ======================
        const persona = user.getPersona();
        if (persona) {
            formData.append("nombres", persona.nombres || "");
            formData.append("apellidos", persona.apellidos || "");
            formData.append("tipo_documento", persona.tipoDocumento || "");
            formData.append("numero_documento", persona.numeroDocumento || "");
            formData.append("telefono", persona.telefono || "");
            formData.append("direccion", persona.direccion || "");
            formData.append("pais", persona.pais || "");
            formData.append("religion", persona.religion || "");
            formData.append("correo_electronico", persona.correoElectronico || "");
            formData.append("correo_institucional", persona.correoInstitucional || "");
            formData.append(
                "fecha_nacimiento",
                persona.fechaNacimiento
                    ? persona.fechaNacimiento.toISOString().split("T")[0]
                    : ""
            );
        }

        // ======================
        // 🎓 Datos específicos
        // ======================
        if (user.getAlumno()) {
            formData.append(
                "codigo_universitario",
                user.getAlumno()?.codigoUniversitario || ""
            );
        }

        if (user.getJurado()) {
            formData.append("especialidad", user.getJurado()?.especialidad || "");
        }

        if (user.getPonente()) {
            formData.append("biografia", user.getPonente()?.biografia || "");
        }

        // ======================
        // 🖼️ Imagen (si se envía)
        // ======================
        if (file) {
            formData.append("foto_perfil", file);
        }

        // ======================
        // 🚀 Petición al backend (simulando PUT)
        // ======================
        const response = await AxiosClient.post(
            `${this.endpoint}/${user.getId()}?_method=PUT`,
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            }
        );

        return UserMapper.fromDTO(response.data);
    }

    async postUser(user: User, file?: File): Promise<User> {
        const formData = new FormData();

        formData.append("email", user.getEmail());
        if (user.getPassword()) formData.append("password", user.getPassword());
        formData.append("escuela_id", user.getEscuelaId().toString());
        formData.append("role", user.getRole()?.nombre || "");

        const persona = user.getPersona();
        if (persona) {
            formData.append("nombres", persona.nombres || "");
            formData.append("apellidos", persona.apellidos || "");
            formData.append("tipo_documento", persona.tipoDocumento || "");
            formData.append("numero_documento", persona.numeroDocumento || "");
            formData.append("telefono", persona.telefono || "");
            formData.append("direccion", persona.direccion || "");
            formData.append("pais", persona.pais || "");
            formData.append("religion", persona.religion || "");
            formData.append("correo_electronico", persona.correoElectronico || "");
            formData.append("correo_institucional", persona.correoInstitucional || "");
            formData.append(
                "fecha_nacimiento",
                persona.fechaNacimiento
                    ? persona.fechaNacimiento.toISOString().split("T")[0]
                    : ""
            );
        }

        if (user.getAlumno()) {
            formData.append(
                "codigo_universitario",
                user.getAlumno()?.codigoUniversitario || ""
            );
        }

        if (user.getJurado()) {
            formData.append("especialidad", user.getJurado()?.especialidad || "");
        }

        if (user.getPonente()) {
            formData.append("biografia", user.getPonente()?.biografia || "");
        }

        if (file) {
            formData.append("foto_perfil", file);
        }

        const response = await AxiosClient.post(`${this.endpoint}`, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });

        return UserMapper.fromDTO(response.data); // tu backend devuelve { data: { ...user } }
    }


    async deleteUser(id: number): Promise<void> {
        await AxiosClient.delete(`${this.endpoint}/${id}`);
    }
}