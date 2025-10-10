<<<<<<< HEAD
export default function EscuelaFormPage(){
    return(
        <div></div>
    )
}
=======
import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import ElectricBorder from "../../../../components/actions/ElectricBorder";
import PageBreadcrumb from "../../../../components/common/PageBreadCrumb";
import Loading from "../../../../components/loaders/Loading";
import Loading2 from "../../../../components/loaders/Loading2";
import type { Escuela } from "../../../../../domain/entities/Escuela";
import { EscuelaRepository } from "../../../../../infrastructure/repositories/EscuelaRepository";
import { EscuelaService } from "../../../../../application/services/EscuelaService";
import EscuelaForm from "../../../../components/form/escuela/EscuelaForm";

// Instancias del repositorio y servicio
const escuelaRepository = new EscuelaRepository();
const escuelaService = new EscuelaService(escuelaRepository);

export default function EscuelaFormPage() {
  const navigate = useNavigate();
  const { id } = useParams();
  const [escuela, setEscuela] = useState<Escuela | undefined>();
  const [loading, setLoading] = useState(true);
  const [isDark, setIsDark] = useState(false);

  // Detecta modo oscuro de Tailwind
  useEffect(() => {
    const observer = new MutationObserver(() => {
      setIsDark(document.documentElement.classList.contains("dark"));
    });

    observer.observe(document.documentElement, { attributes: true });
    setIsDark(document.documentElement.classList.contains("dark"));

    return () => observer.disconnect();
  }, []);

  // Carga los datos de la escuela si hay un ID (modo edición)
  useEffect(() => {
    if (id) {
      escuelaService
        .getEscuelaById(Number(id))
        .then((data) => setEscuela(data))
        .catch(console.error)
        .finally(() => setLoading(false));
    } else {
      setLoading(false);
    }
  }, [id]);

  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-screen">
        {isDark ? <Loading2 /> : <Loading />}
      </div>
    );
  }

  return (
    <div>
      {/* 🧭 Breadcrumb */}
      <PageBreadcrumb
        pageTitle={id ? "Editar Escuela" : "Crear Escuela"}
        pageBack="Cancelar"
        routeBack="super-admin-escuelas"
      />

      {/* 📦 Formulario */}
      <div className="max-w-2xl mx-auto mt-10">
        <ElectricBorder
          color="#7df9ff"
          speed={1}
          chaos={0.5}
          thickness={100}
          style={{ borderRadius: 10 }}
        >
          <EscuelaForm
            initialEscuela={escuela}
            onSuccess={() => navigate("/super-admin-escuelas")} // 👉 Redirige al listado
          />
        </ElectricBorder>
      </div>
    </div>
  );
}
>>>>>>> temp-recuperacion}
