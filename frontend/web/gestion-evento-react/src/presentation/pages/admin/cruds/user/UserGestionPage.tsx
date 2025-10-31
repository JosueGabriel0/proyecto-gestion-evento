import { useState, useEffect, useMemo } from "react";
import { useNavigate } from "react-router-dom";
import Swal from "sweetalert2";

import ComponentCard from "../../../../components/common/ComponentCard";
import BasicTableOne from "../../../../components/BasicTables/BasicTableOne";
import PageBreadcrumb from "../../../../components/common/PageBreadCrumb";
import { EscuelaRepository } from "../../../../../infrastructure/repositories/EscuelaRepository";
import { EscuelaService } from "../../../../../application/services/EscuelaService";
import type { Escuela } from "../../../../../domain/entities/Escuela";
import { UserRepository } from "../../../../../infrastructure/repositories/UserRepository";
import { UserService } from "../../../../../application/services/UserService";
import type { User } from "../../../../../domain/entities/User";
import { RoleRepository } from "../../../../../infrastructure/repositories/RoleRepository";
import { RoleService } from "../../../../../application/services/RoleService";
import { TokenStorage } from "../../../../../infrastructure/repositories/TokenStorage";

const escuelaRepository = new EscuelaRepository();
const escuelaService = new EscuelaService(escuelaRepository);

const rolRepository = new RoleRepository();
const roleService = new RoleService(rolRepository);

const userRepository = new UserRepository();
const userService = new UserService(userRepository);

const tokenStorage = new TokenStorage();

export default function UserGestionPage() {
    const [searchTerm, setSearchTerm] = useState("");
    const [escuelas, setEscuelas] = useState<Escuela[]>([]);
    const [roles, setRoles] = useState<string[]>([]);
    const [roleFilter, setRoleFilter] = useState("ROLE_SUPER_ADMIN");
    const navigate = useNavigate();

    const [refresh, setRefresh] = useState(0);

    const user = tokenStorage.getUser();
      const userRole = user?.role ?? null;
    
       const routeBack = useMemo(() => {
        switch (userRole) {
          case "ROLE_SUPER_ADMIN":
            return "super-dashboard";
          case "ROLE_ADMIN":
            return "admin-dashboard";
          default:
            return "dashboard"; // fallback genérico
        }
      }, [userRole]);

    // 🔹 Cargar filiales (para mostrar los nombres)
    useEffect(() => {
        const loadEscuelas = async () => {
            try {
                const data = await escuelaService.getEscuelas();
                setEscuelas(data);
            } catch (error) {
                console.error("Error cargando escuelas:", error);
            }
        };
        const loadRoles = async () => {
            try {
                const data = await roleService.getRoles();
                setRoles(data.map((r) => r.nombre));
            } catch (error) {
                console.error("Error cargando roles:", error);
            }
        }
        loadEscuelas();
        loadRoles();
    }, []);

    // 🔹 API de datos para la tabla
    const fetchUsers = async (page: number, perPage: number, search?: string) => {
        if (search && search.trim()) {
            return await userService.searchUserPaginated(search, roleFilter, page, perPage);
        }
        return await userService.getUsersPaginated(roleFilter, page, perPage);
    };

    // 🔹 Confirmación y eliminación con SweetAlert2
    const handleDelete = async (user: User) => {
        const result = await Swal.fire({
            title: "¿Eliminar Usuario?",
            text: `Se eliminará el usuario "${user.getEmail()}"`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        });

        if (result.isConfirmed) {
            try {
                if (!user.getId()) {
                    Swal.fire("Error", "El usuario no tiene un ID válido.", "error");
                    return;
                }

                await userService.deleteUser(user.getId()!);

                Swal.fire("Eliminado", "El usuario ha sido eliminado.", "success");
                setRefresh((prev) => prev + 1);
            } catch (error) {
                Swal.fire("Error", "No se pudo eliminar el usuario.", "error");
            }
        }
    };

    const getColumnsByRole = (roleFilter: string) => {
        const baseColumns = [
            { key: "id", label: "ID" },
            { key: "email", label: "Email" },
            {
                key: "escuelaId",
                label: "Escuela",
                render: (user: User) => {
                    const escuela = escuelas.find((e) => e.id === user.getEscuelaId());
                    return escuela ? escuela.nombre : <span className="text-gray-400 italic">Sin escuela</span>;
                },
            },
            {
                key: "role",
                label: "Role",
                render: (user: User) =>
                    `${user.getRole()?.nombre ?? ""}`,
            },
            {
                key: "nombres",
                label: "Nombres",
                render: (user: User) =>
                    `${user.getPersona()?.nombres ?? ""} ${user.getPersona()?.apellidos ?? ""}`,
            },
            {
                key: "tipoDocumento",
                label: "Tipo de documento",
                render: (user: User) =>
                    `${user.getPersona()?.tipoDocumento ?? ""}`,
            },
            {
                key: "numeroDocumento",
                label: "Numero de documento",
                render: (user: User) =>
                    `${user.getPersona()?.numeroDocumento ?? ""}`,
            },
            {
                key: "telefono",
                label: "Telefono",
                render: (user: User) =>
                    `${user.getPersona()?.telefono ?? ""}`,
            },
            {
                key: "direccion",
                label: "Direccion",
                render: (user: User) =>
                    `${user.getPersona()?.direccion ?? ""}`,
            },
            {
                key: "pais",
                label: "Pais",
                render: (user: User) =>
                    `${user.getPersona()?.pais ?? ""}`,
            },
            {
                key: "religion",
                label: "Religion",
                render: (user: User) =>
                    `${user.getPersona()?.religion ?? ""}`,
            },
            {
                key: "correoElectronico",
                label: "Correo electronico",
                render: (user: User) =>
                    `${user.getPersona()?.correoElectronico ?? ""}`,
            },
            {
                key: "correoInstitucional",
                label: "Correo institucional",
                render: (user: User) =>
                    `${user.getPersona()?.correoInstitucional ?? ""}`,
            },
            {
                key: "fechaNacimiento",
                label: "Fecha de nacimiento",
                render: (user: User) => {
                    const fecha = user.getPersona()?.fechaNacimiento;
                    if (!fecha) return "—";

                    const dateObj = new Date(fecha);
                    const año = dateObj.getFullYear();
                    const mes = String(dateObj.getMonth() + 1).padStart(2, "0");
                    const dia = String(dateObj.getDate()).padStart(2, "0");

                    return `${año}-${mes}-${dia}`;
                },
            },
            {
                key: "foto",
                label: "Foto",
                render: (user: User) =>
                    user.getPersona()?.fotoPerfil ? (
                        <img
                            src={user.getPersona()?.fotoPerfil}
                            alt={user.getEmail()}
                            className="w-10 h-10 rounded-full object-cover"
                        />
                    ) : (
                        <span className="italic text-gray-400">Sin foto</span>
                    ),
            },
        ];

        // 🔹 Columnas específicas por rol
        switch (roleFilter) {
            case "ROLE_JURADO":
                return [
                    ...baseColumns,
                    {
                        key: "especialidad",
                        label: "Especialidad",
                        render: (user: User) => user.getJurado()?.especialidad ?? "—",
                    },
                ];

            case "ROLE_PONENTE":
                return [
                    ...baseColumns,
                    {
                        key: "tema",
                        label: "Tema de Ponencia",
                        render: (user: User) => user.getPonente()?.biografia ?? "—",
                    },
                ];

            case "ROLE_ALUMNO":
                return [
                    ...baseColumns,
                    {
                        key: "codigo",
                        label: "Código de Alumno",
                        render: (user: User) => user.getAlumno()?.codigoUniversitario ?? "—",
                    },
                ];

            case "ROLE_ADMIN":
            case "ROLE_SUPER_ADMIN":
            default:
                // 🔹 Admines no muestran datos específicos (solo base)
                return baseColumns;
        }
    };

    const columns = getColumnsByRole(roleFilter);

    return (
        <div className="p-6 space-y-6">
            <PageBreadcrumb
                pageTitle="Gestión de Usuarios"
                pageBack="Inicio"
                routeBack={routeBack}
            />

            <ComponentCard
                title="Tabla de Usuarios"
                placeHolder="Buscar Usuario..."
                onSearch={(term) => setSearchTerm(term)}
                onAdd={() => navigate("/super-admin-usuarios/new")}
                filter={true}
                filterPlaceHolder="Filtrar Usuarios"
                filterOptions={roles}
                filterOnSelect={(rol) => setRoleFilter(rol)}
                defaultValue="ROLE_SUPER_ADMIN"
            >
                <BasicTableOne<User>
                    key={roleFilter}
                    columns={columns}
                    fetchData={fetchUsers}
                    searchTerm={searchTerm}
                    refreshTrigger={refresh}
                    onEdit={(user) => navigate(`/super-admin-usuarios/edit/${user.id}`)}
                    onDelete={handleDelete}
                />
            </ComponentCard>
        </div>
    );
}