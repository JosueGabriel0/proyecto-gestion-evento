import { useState } from "react";
import { useNavigate } from "react-router-dom";
import Swal from "sweetalert2";

import ComponentCard from "../../../../components/common/ComponentCard";
import BasicTableOne from "../../../../components/BasicTables/BasicTableOne";
import PageBreadcrumb from "../../../../components/common/PageBreadCrumb";

import type { Escuela } from "../../../../../domain/entities/Escuela";
import { EscuelaRepository } from "../../../../../infrastructure/repositories/EscuelaRepository";
import { EscuelaService } from "../../../../../application/services/EscuelaService";

// 🔹 Instanciamos el repositorio y servicio
const escuelaRepository = new EscuelaRepository();
const escuelaService = new EscuelaService(escuelaRepository);

export default function EscuelaGestionPage() {
  const [searchTerm, setSearchTerm] = useState("");
  const navigate = useNavigate();

  // 🔹 Cargar datos paginados o por búsqueda
  const fetchEscuelas = async (page: number, perPage: number, term?: string) => {
    try {
      if (term && term.trim()) {
        return await escuelaService.searchEscuelaPaginated(term, perPage);
      }
      return await escuelaService.getEscuelasPaginated(page, perPage);
    } catch (error) {
      console.error("Error al obtener las escuelas:", error);
      Swal.fire("Error", "No se pudieron cargar las escuelas.", "error");
      return { data: [], current_page: 1, per_page: 10, total: 0 };
    }
  };

  // 🔹 Eliminar escuela con confirmación
  const handleDelete = async (escuela: Escuela) => {
    const result = await Swal.fire({
      title: "¿Eliminar Escuela?",
      text: `Se eliminará la escuela "${escuela.nombre}"`,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar",
    });

    if (!result.isConfirmed) return;

    try {
      await escuelaService.deleteEscuela(escuela.id);
      Swal.fire("Eliminado", "La escuela ha sido eliminada.", "success");
      window.location.reload();
    } catch (error) {
      Swal.fire("Error", "No se pudo eliminar la escuela.", "error");
    }
  };

  // 🔹 Columnas de la tabla
  const columns = [
    { key: "id", label: "ID" },
    { key: "nombre", label: "Nombre" },
    { key: "codigo", label: "Código" },
    { key: "facultad_id", label: "Facultad ID" },
    {
      key: "foto",
      label: "Foto",
      render: (escuela: Escuela) =>
        escuela.foto ? (
          <img
            src={escuela.foto}
            alt={escuela.nombre}
            className="w-10 h-10 rounded-full object-cover"
          />
        ) : (
          <span className="italic text-gray-400">Sin foto</span>
        ),
    },
  ];

  return (
    <div className="p-6 space-y-6">
      {/* 🔹 Migas de pan (breadcrumb) */}
      <PageBreadcrumb
        pageTitle="Gestión de Escuelas"
        pageBack="Inicio"
        routeBack="dashboard-super-admin"
      />

      {/* 🔹 Contenedor de la tabla */}
      <ComponentCard
        title="Tabla de Escuelas"
        onSearch={(term) => setSearchTerm(term)}
        onAdd={() => navigate("/super-admin-escuelas/new")}
      >
        <BasicTableOne<Escuela>
          columns={columns}
          fetchData={fetchEscuelas}
          searchTerm={searchTerm}
          onEdit={(escuela) => navigate(`/super-admin-escuelas/edit/${escuela.id}`)}
          onDelete={handleDelete}
        />
      </ComponentCard>
    </div>
  );
}
