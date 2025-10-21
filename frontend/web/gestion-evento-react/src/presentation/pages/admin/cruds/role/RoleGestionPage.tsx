import { useState } from "react";
import { useNavigate } from "react-router-dom";
import Swal from "sweetalert2";

import ComponentCard from "../../../../components/common/ComponentCard";
import BasicTableOne from "../../../../components/BasicTables/BasicTableOne";
import { Role } from "../../../../../domain/entities/Role";
import { RoleRepository } from "../../../../../infrastructure/repositories/RoleRepository";
import { RoleService } from "../../../../../application/services/RoleService";
import PageBreadcrumb from "../../../../components/common/PageBreadCrumb";

const roleRepository = new RoleRepository();
const roleService = new RoleService(roleRepository);

export default function RoleGestionPage() {
  const [searchTerm, setSearchTerm] = useState("");
  const navigate = useNavigate();

  // 🔹 API de datos para la tabla
  const fetchRoles = async (page: number, perPage: number, term?: string) => {
    if (term && term.trim()) {
      return await roleService.searchRoles(term, perPage);
    }
    return await roleService.getRolesPaginated(page, perPage);
  };

  // 🔹 Confirmación y eliminación con SweetAlert2
  const handleDelete = async (role: Role) => {
    const result = await Swal.fire({
      title: "¿Eliminar Rol?",
      text: `Se eliminará el rol "${role.nombre}"`,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar",
    });

    if (result.isConfirmed) {
      try {
        await roleService.deleteRole(role.id);
        Swal.fire("Eliminado", "El rol ha sido eliminado.", "success");
        window.location.reload(); // 👉 recarga lista
      } catch (error) {
        Swal.fire("Error", "No se pudo eliminar el rol.", "error");
      }
    }
  };

  // 🔹 Configuración de columnas
  const columns = [
    { key: "id", label: "ID" },
    { key: "nombre", label: "Nombre" },
    {
      key: "foto",
      label: "Foto",
      render: (role: Role) =>
        role.foto ? (
          <img
            src={role.foto}
            alt={role.nombre}
            className="w-10 h-10 rounded-full object-cover"
          />
        ) : (
          <span className="italic text-gray-400">Sin foto</span>
        ),
    },
  ];

  return (
    <div className="p-6 space-y-6">
      {/* 🔹 Breadcrumb arriba */}
      <PageBreadcrumb
        pageTitle="Gestión de Roles"
        pageBack="Inicio"
        routeBack="dashboard-admin"
      />

      {/* 🔹 Tabla de roles */}
      <ComponentCard
        title="Tabla de Roles"
        placeHolder="Buscar Rol........"
        onSearch={(term) => setSearchTerm(term)}
        onAdd={() => navigate("/super&admin-roles/new")}
      >
        <BasicTableOne<Role>
          columns={columns}
          fetchData={fetchRoles}
          searchTerm={searchTerm}
          onEdit={(role) => navigate(`/super&admin-roles/edit/${role.id}`)}
          onDelete={handleDelete}
        />
      </ComponentCard>
    </div>
  );
}