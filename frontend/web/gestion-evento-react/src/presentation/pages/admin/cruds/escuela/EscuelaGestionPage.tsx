import { useState, useEffect, useMemo } from "react";
import { useNavigate } from "react-router-dom";
import Swal from "sweetalert2";

import ComponentCard from "../../../../components/common/ComponentCard";
import BasicTableOne from "../../../../components/BasicTables/BasicTableOne";
import PageBreadcrumb from "../../../../components/common/PageBreadCrumb";
import { FacultadRepository } from "../../../../../infrastructure/repositories/FacultadRepository";
import { FacultadService } from "../../../../../application/services/FacultadService";
import type { Facultad } from "../../../../../domain/entities/Facultad";
import { EscuelaRepository } from "../../../../../infrastructure/repositories/EscuelaRepository";
import { EscuelaService } from "../../../../../application/services/EscuelaService";
import type { Escuela } from "../../../../../domain/entities/Escuela";
import { TokenStorage } from "../../../../../infrastructure/repositories/TokenStorage";

const facultadRepository = new FacultadRepository();
const facultadService = new FacultadService(facultadRepository);

const escuelaRepository = new EscuelaRepository();
const escuelaService = new EscuelaService(escuelaRepository);

const tokenStorage = new TokenStorage();

export default function EscuelaGestionPage() {
  const [searchTerm, setSearchTerm] = useState("");
  const [facultades, setFacultades] = useState<Facultad[]>([]);
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
    const loadFacultades = async () => {
      try {
        const data = await facultadService.getFacultades();
        setFacultades(data);
      } catch (error) {
        console.error("Error cargando facultades:", error);
      }
    };
    loadFacultades();
  }, []);

  // 🔹 API de datos para la tabla
  const fetchEscuelas = async (page: number, perPage: number, term?: string) => {
    if (term && term.trim()) {
      return await escuelaService.searchEscuelaPaginated(term, perPage);
    }
    return await escuelaService.getEscuelasPaginated(page, perPage);
  };

  // 🔹 Confirmación y eliminación con SweetAlert2
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

    if (result.isConfirmed) {
      try {
        await escuelaService.deleteEscuela(escuela.id);
        Swal.fire("Eliminado", "La escuela ha sido eliminada.", "success");
        setRefresh((prev) => prev + 1);
      } catch (error) {
        Swal.fire("Error", "No se pudo eliminar la escuela.", "error");
      }
    }
  };

  // 🔹 Configuración de columnas
  const columns = [
    { key: "id", label: "ID" },
    { key: "nombre", label: "Nombre" },
    { key: "codigo", label: "Código" },
    {
      key: "facultadId",
      label: "Facultad",
      render: (escuela: Escuela) => {
        const facultad = facultades.find((f) => f.id === escuela.facultadId);
        console.log(`Id de busqueda: ${escuela.facultadId}`);
        return facultad ? facultad.nombre : <span className="text-gray-400 italic">Sin facultad</span>;
      },
    },
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
      <PageBreadcrumb
        pageTitle="Gestión de Escuela"
        pageBack="Inicio"
        routeBack={routeBack}
      />

      <ComponentCard
        title="Tabla de Escuelas"
        placeHolder="Buscar Escuela..."
        onSearch={(term) => setSearchTerm(term)}
        onAdd={() => navigate("/super-admin-escuelas/new")}
      >
        <BasicTableOne<Escuela>
          columns={columns}
          fetchData={fetchEscuelas}
          searchTerm={searchTerm}
          refreshTrigger={refresh}
          onEdit={(escuela) => navigate(`/super-admin-escuelas/edit/${escuela.id}`)}
          onDelete={handleDelete}
        />
      </ComponentCard>
    </div>
  );
}