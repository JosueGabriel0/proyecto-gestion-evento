import { useState, useEffect, useMemo } from "react";
import { useNavigate } from "react-router-dom";
import Swal from "sweetalert2";

import ComponentCard from "../../../../components/common/ComponentCard";
import BasicTableOne from "../../../../components/BasicTables/BasicTableOne";
import PageBreadcrumb from "../../../../components/common/PageBreadCrumb";
import { FacultadRepository } from "../../../../../infrastructure/repositories/FacultadRepository";
import { FacultadService } from "../../../../../application/services/FacultadService";
import { FilialRepository } from "../../../../../infrastructure/repositories/FilialRepository";
import { FilialService } from "../../../../../application/services/FilialService";
import type { Facultad } from "../../../../../domain/entities/Facultad";
import type { Filial } from "../../../../../domain/entities/Filial";
import { TokenStorage } from "../../../../../infrastructure/repositories/TokenStorage";

const facultadRepository = new FacultadRepository();
const facultadService = new FacultadService(facultadRepository);

const filialRepository = new FilialRepository();
const filialService = new FilialService(filialRepository);

const tokenStorage = new TokenStorage();

export default function FacultadGestionPage() {
  const [searchTerm, setSearchTerm] = useState("");
  const [filiales, setFiliales] = useState<Filial[]>([]);
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
    const loadFiliales = async () => {
      try {
        const data = await filialService.getFiliales();
        setFiliales(data);
      } catch (error) {
        console.error("Error cargando filiales:", error);
      }
    };
    loadFiliales();
  }, []);

  // 🔹 API de datos para la tabla
  const fetchFacultades = async (page: number, perPage: number, term?: string) => {
    if (term && term.trim()) {
      return await facultadService.searchFacultadPaginated(term, perPage);
    }
    return await facultadService.getFacultadesPaginated(page, perPage);
  };

  // 🔹 Confirmación y eliminación con SweetAlert2
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
        Swal.fire("Eliminado", "La facultad ha sido eliminada.", "success");
        setRefresh((prev) => prev + 1);
      } catch (error) {
        Swal.fire("Error", "No se pudo eliminar la facultad.", "error");
      }
    }
  };

  // 🔹 Configuración de columnas
  const columns = [
    { key: "id", label: "ID" },
    { key: "nombre", label: "Nombre" },
    { key: "codigo", label: "Código" },
    {
      key: "filialId",
      label: "Filial",
      render: (facultad: Facultad) => {
        const filial = filiales.find((f) => f.id === facultad.filialId);
        console.log(`Id de busqueda: ${facultad.filialId}`);
        return filial ? filial.nombre : <span className="text-gray-400 italic">Sin filial</span>;
      },
    },
    {
      key: "foto",
      label: "Foto",
      render: (facultad: Facultad) =>
        facultad.foto ? (
          <img
            src={facultad.foto}
            alt={facultad.nombre}
            className="w-10 h-10 rounded-full object-cover"
          />
        ) : (
          <span className="italic text-gray-400">Sin foto</span>
        ),
    },
  ];

  return (
    <div className="p-6 space-y-6">
      <PageBreadcrumb
        pageTitle="Gestión de Facultades"
        pageBack="Inicio"
        routeBack={routeBack}
      />

      <ComponentCard
        title="Tabla de Facultades"
        placeHolder="Buscar Facultad..."
        onSearch={(term) => setSearchTerm(term)}
        onAdd={() => navigate("/super-admin-facultades/new")}
      >
        <BasicTableOne<Facultad>
          columns={columns}
          fetchData={fetchFacultades}
          searchTerm={searchTerm}
          refreshTrigger={refresh}
          onEdit={(facultad) => navigate(`/super-admin-facultades/edit/${facultad.id}`)}
          onDelete={handleDelete}
        />
      </ComponentCard>
    </div>
  );
}