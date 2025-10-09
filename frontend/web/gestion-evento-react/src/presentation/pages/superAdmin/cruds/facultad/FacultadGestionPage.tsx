import { useState } from "react";
import { useNavigate } from "react-router-dom";
import Swal from "sweetalert2";
import { FacultadRepository } from "../../../../../infrastructure/repositories/FacultadRepository";
import { FacultadService } from "../../../../../application/services/FacultadService";
import type { Facultad } from "../../../../../domain/entities/Facultad";
import type { Column } from "../../../../components/BasicTables/BasicTableOne";
import PageBreadcrumb from "../../../../components/common/PageBreadCrumb";
import ComponentCard from "../../../../components/common/ComponentCard";
import BasicTableOne from "../../../../components/BasicTables/BasicTableOne";


const facultadRepository = new FacultadRepository();
const facultadService = new FacultadService(facultadRepository);

export default function FacultadGestionPage() {
  const [searchTerm, setSearchTerm] = useState("");
  const navigate = useNavigate();

  const fetchFacultades = async (page: number, perPage: number, term?: string) => {
    if (term && term.trim()) {
      return await facultadService.searchFacultadPaginated(term, perPage);
    }
    return await facultadService.getFacultadesPaginated(page, perPage);
  };

  const handleDelete = async (facultad: Facultad) => {
    const result = await Swal.fire({
      title: "¿Eliminar Facultad?",
      text: `Se eliminará la facultad "${facultad.nombre}"`,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar",
    });

    if (result.isConfirmed) {
      try {
        await facultadService.deleteFacultad(facultad.id);
        Swal.fire("Eliminado", "La facultad ha sido eliminada correctamente.", "success");
        window.location.reload();
      } catch (error) {
        Swal.fire("Error", "No se pudo eliminar la facultad.", "error");
      }
    }
  };

  // TIPADO explícito de columns para evitar errores
  const columns: Column<Facultad>[] = [
    { key: "id", label: "ID" },
    { key: "nombre", label: "Nombre" },
    { key: "codigo", label: "Código" },
    {
      key: "foto",
      label: "Foto",
      render: (fac) =>
        fac.foto ? (
          <img src={fac.foto} alt={fac.nombre} className="w-10 h-10 rounded-full object-cover" />
        ) : (
          <span className="italic text-gray-400">Sin foto</span>
        ),
    },
    { key: "filialId", label: "Filial ID" },
  ];

  return (
    <div className="p-6 space-y-6">
      <PageBreadcrumb
        pageTitle="Gestión de Facultades"
        pageBack="Inicio"
        routeBack="dashboard-super-admin"
      />

      <ComponentCard
        title="Tabla de Facultades"
        onSearch={(term) => setSearchTerm(term)}
        onAdd={() => navigate("/facultades/new")}
      >
        <BasicTableOne<Facultad>
          columns={columns}
          fetchData={fetchFacultades}
          searchTerm={searchTerm}
          onEdit={(facultad) => navigate(`/facultades/edit/${facultad.id}`)}
          onDelete={handleDelete}
        />
      </ComponentCard>
    </div>
  );
}
