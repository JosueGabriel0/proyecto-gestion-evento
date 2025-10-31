import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import ElectricBorder from "../../../../components/actions/ElectricBorder";
import PageBreadcrumb from "../../../../components/common/PageBreadCrumb";
import Loading from "../../../../components/loaders/Loading";
import Loading2 from "../../../../components/loaders/Loading2";
import { FacultadRepository } from "../../../../../infrastructure/repositories/FacultadRepository";
import { FacultadService } from "../../../../../application/services/FacultadService";
import type { Facultad } from "../../../../../domain/entities/Facultad";
import FacultadForm from "../../../../components/form/facultad/FacultadForm";

const facultadRepository = new FacultadRepository();
const facultadService = new FacultadService(facultadRepository);

export default function FacultadFormPage() {
  const navigate = useNavigate();
  const { id } = useParams();
  const [facultad, setFacultad] = useState<Facultad | undefined>();
  const [loading, setLoading] = useState(true);
  const [isDark, setIsDark] = useState(false);

  useEffect(() => {
    // Detecta si Tailwind aplica "dark" al <html>
    const observer = new MutationObserver(() => {
      setIsDark(document.documentElement.classList.contains("dark"));
    });

    observer.observe(document.documentElement, { attributes: true });

    // Estado inicial
    setIsDark(document.documentElement.classList.contains("dark"));

    return () => observer.disconnect();
  }, []);

  useEffect(() => {
    if (id) {
      facultadService
        .getFacultadById(Number(id))
        .then((data) => setFacultad(data))
        .catch(console.error)
        .finally(() => setLoading(false));
    } else {
      setLoading(false);
    }
  }, [id]);

  if (loading) {
    return <div className="flex items-center justify-center min-h-screen">
      {isDark ? <Loading2 /> : <Loading />}
    </div>
  }

  return (
    <div>
      <div>
        <PageBreadcrumb
          pageTitle={id ? "Editar Facultad" : "Crear Facultad"}
          pageBack="Cancelar"
          routeBack="super-admin-facultades"
        />
      </div>

      <div className="max-w-2xl mx-auto mt-10">
        <ElectricBorder
          color="#7df9ff"
          speed={1}
          chaos={0.5}
          thickness={100}
          style={{ borderRadius: 10 }}
        >
          <FacultadForm
            initialFacultad={facultad}
            onSuccess={() => navigate("/super-admin-facultades")} // 👉 Redirige al listado
          />
        </ElectricBorder>
      </div>
    </div>
  );
}