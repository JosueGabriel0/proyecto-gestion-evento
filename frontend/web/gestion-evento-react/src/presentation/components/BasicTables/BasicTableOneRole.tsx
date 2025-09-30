import { useEffect, useState } from "react";
import {
  Table,
  TableBody,
  TableCell,
  TableHeader,
  TableRow,
} from "../ui/table";

import { Role } from "../../../domain/entities/Role";
import { RoleRepository } from "../../../infrastructure/repositories/RoleRepository";
import { RoleService } from "../../../application/services/RoleService";
import SplitText from "../text/SplitText";
import EditButton from "../actions/EditButton";
import DeleteButton from "../actions/DeleteButton";
import type { PaginatedResponse } from "../../../application/dtos/PaginatedResponse";

// Inicializa el repositorio y el servicio
const roleRepository = new RoleRepository();
const roleService = new RoleService(roleRepository);

export default function BasicTableOneRole() {
  const [roles, setRoles] = useState<Role[]>([]);
  const [pagination, setPagination] = useState<{
    current_page: number;
    per_page: number;
    total: number;
  }>({ current_page: 1, per_page: 10, total: 0 });
  const [loading, setLoading] = useState(true);

  const fetchRoles = async (page = 1, perPage = 10) => {
    try {
      setLoading(true);

      // 👇 Ahora enviamos page y perPage al backend
      const response: PaginatedResponse<Role> =
        await roleService.getRolesPaginated(page, perPage);

      setRoles(response.data);
      setPagination({
        current_page: response.current_page,
        per_page: response.per_page,
        total: response.total,
      });
    } catch (error) {
      console.error("Error al obtener roles:", error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchRoles(pagination.current_page, pagination.per_page);
  }, []);

  if (loading) {
    return (
      <div className="p-4 text-gray-600 dark:text-gray-300">

        <SplitText
          text="Cargando roles..."
          className="text-lg font-semibold text-center"
          delay={5}
          duration={0.6}
          ease="power3.out"
          splitType="chars"
          from={{ opacity: 0, y: 40 }}
          to={{ opacity: 1, y: 0 }}
          threshold={0.1}
          rootMargin="-100px"
          textAlign="center"
        />
      </div>
    );
  }

  const totalPages = Math.ceil(pagination.total / pagination.per_page);

  return (
    <div className="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
      <div className="max-w-full overflow-x-auto">
        <Table>
          {/* Encabezado de la tabla */}
          <TableHeader className="border-b border-gray-100 dark:border-white/[0.05]">
            <TableRow>
              <TableCell
                isHeader
                className="px-5 py-3 font-medium text-gray-500 text-start text-theme-xs dark:text-gray-400"
              >
                <SplitText
                  text="ID"
                  className="text-sm"
                  delay={100}
                  duration={0.6}
                  ease="power3.out"
                  splitType="chars"
                  from={{ opacity: 0, y: 40 }}
                  to={{ opacity: 1, y: 0 }}
                  threshold={0.1}
                  rootMargin="-100px"
                  textAlign="start"
                />
              </TableCell>
              <TableCell
                isHeader
                className="px-5 py-3 font-medium text-gray-500 text-start text-theme-xs dark:text-gray-400"
              >
                <SplitText
                  text="Nombre"
                  className="text-sm"
                  delay={100}
                  duration={0.6}
                  ease="power3.out"
                  splitType="chars"
                  from={{ opacity: 0, y: 40 }}
                  to={{ opacity: 1, y: 0 }}
                  threshold={0.1}
                  rootMargin="-100px"
                  textAlign="start"
                />
              </TableCell>
              <TableCell
                isHeader
                className="px-5 py-3 font-medium text-gray-500 text-start text-theme-xs dark:text-gray-400"
              >
                <SplitText
                  text="Foto"
                  className="text-sm"
                  delay={100}
                  duration={0.6}
                  ease="power3.out"
                  splitType="chars"
                  from={{ opacity: 0, y: 40 }}
                  to={{ opacity: 1, y: 0 }}
                  threshold={0.1}
                  rootMargin="-100px"
                  textAlign="start"
                />
              </TableCell>
              <TableCell
                isHeader
                className="px-5 py-3 font-medium text-gray-500 text-center text-theme-xs dark:text-gray-400"
              >
                <SplitText
                  text="Acciones"
                  className="text-sm"
                  delay={100}
                  duration={0.6}
                  ease="power3.out"
                  splitType="chars"
                  from={{ opacity: 0, y: 40 }}
                  to={{ opacity: 1, y: 0 }}
                  threshold={0.1}
                  rootMargin="-100px"
                  textAlign="start"
                />
              </TableCell>
            </TableRow>
          </TableHeader>

          {/* Cuerpo de la tabla */}
          <TableBody className="divide-y divide-gray-100 dark:divide-white/[0.05]">
            {roles.map((role) => (
              <TableRow key={role.id}>
                <TableCell className="px-5 py-4 sm:px-6 text-start text-gray-900 dark:text-white">
                  {role.id}
                </TableCell>
                <TableCell className="px-5 py-4 sm:px-6 text-start text-gray-900 dark:text-white">
                  {role.nombre}
                </TableCell>
                <TableCell className="px-5 py-4 sm:px-6 text-start text-gray-900 dark:text-white">
                  {role.foto ? (
                    <img
                      src={role.foto}
                      alt={role.nombre}
                      className="w-10 h-10 rounded-full object-cover"
                    />
                  ) : (
                    <span className="text-gray-400 italic">
                      Sin foto
                    </span>
                  )}
                </TableCell>
                <TableCell className="px-5 py-4 sm:px-6 text-center">
                  <div className="flex justify-center items-center gap-2">
                    <EditButton />
                    <DeleteButton />
                  </div>
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </div>
      {/* 👇 Paginación al final */}
      <div className="flex items-center justify-between border-t border-gray-200 dark:border-white/10 px-4 py-3 sm:px-6">
        {/* 📱 Mobile: solo Previous / Next */}
        <div className="flex flex-1 justify-between sm:hidden">
          <button
            disabled={pagination.current_page === 1}
            onClick={() => fetchRoles(pagination.current_page - 1, pagination.per_page)}
            className="relative inline-flex items-center rounded-md border border-gray-300 dark:border-white/10 
                 bg-white dark:bg-white/5 px-4 py-2 text-sm font-medium 
                 text-gray-700 dark:text-gray-200 
                 hover:bg-gray-100 dark:hover:bg-white/10 
                 disabled:opacity-50"
          >
            Anterior
          </button>
          <button
            disabled={pagination.current_page === totalPages}
            onClick={() => fetchRoles(pagination.current_page + 1, pagination.per_page)}
            className="relative ml-3 inline-flex items-center rounded-md border border-gray-300 dark:border-white/10 
                 bg-white dark:bg-white/5 px-4 py-2 text-sm font-medium 
                 text-gray-700 dark:text-gray-200 
                 hover:bg-gray-100 dark:hover:bg-white/10 
                 disabled:opacity-50"
          >
            Siguiente
          </button>
        </div>

        {/* 💻 Desktop: numeración con botones */}
        <div className="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
          <div>
            <p className="text-sm text-gray-700 dark:text-gray-300">
              Mostrando{" "}
              <span className="font-medium">
                {(pagination.current_page - 1) * pagination.per_page + 1}
              </span>{" "}
              a{" "}
              <span className="font-medium">
                {Math.min(pagination.current_page * pagination.per_page, pagination.total)}
              </span>{" "}
              de <span className="font-medium">{pagination.total}</span> resultados
            </p>
          </div>

          <div>
            <nav
              aria-label="Pagination"
              className="isolate inline-flex -space-x-px rounded-md"
            >
              {/* Botón Previous */}
              <button
                disabled={pagination.current_page === 1}
                onClick={() => fetchRoles(pagination.current_page - 1, pagination.per_page)}
                className="relative inline-flex items-center rounded-l-md px-2 py-2 
                     text-gray-500 dark:text-gray-400 
                     border border-gray-300 dark:border-gray-700
                     bg-white dark:bg-transparent
                     hover:bg-gray-100 dark:hover:bg-white/5
                     focus:z-20 disabled:opacity-50"
              >
                <span className="sr-only">Anterior</span>
                <svg
                  viewBox="0 0 20 20"
                  fill="currentColor"
                  aria-hidden="true"
                  className="size-5"
                >
                  <path
                    fillRule="evenodd"
                    d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                    clipRule="evenodd"
                  />
                </svg>
              </button>

              {/* Botones de páginas dinámicos */}
              {Array.from({ length: totalPages }, (_, i) => i + 1).map((page) => (
                <button
                  key={page}
                  onClick={() => fetchRoles(page, pagination.per_page)}
                  aria-current={page === pagination.current_page ? "page" : undefined}
                  className={`relative inline-flex items-center px-4 py-2 text-sm font-semibold focus:z-20 border
              ${page === pagination.current_page
                      ? "z-10 bg-indigo-500 text-white border-indigo-500"
                      : "text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-white/5"
                    }`}
                >
                  {page}
                </button>
              ))}

              {/* Botón Next */}
              <button
                disabled={pagination.current_page === totalPages}
                onClick={() => fetchRoles(pagination.current_page + 1, pagination.per_page)}
                className="relative inline-flex items-center rounded-r-md px-2 py-2 
                     text-gray-500 dark:text-gray-400 
                     border border-gray-300 dark:border-gray-700
                     bg-white dark:bg-transparent
                     hover:bg-gray-100 dark:hover:bg-white/5
                     focus:z-20 disabled:opacity-50"
              >
                <span className="sr-only">Siguiente</span>
                <svg
                  viewBox="0 0 20 20"
                  fill="currentColor"
                  aria-hidden="true"
                  className="size-5"
                >
                  <path
                    fillRule="evenodd"
                    d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                    clipRule="evenodd"
                  />
                </svg>
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>
  );
}