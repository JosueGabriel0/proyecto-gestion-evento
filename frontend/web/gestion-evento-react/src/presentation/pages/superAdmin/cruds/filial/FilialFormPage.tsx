import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import RoleForm from "../../../../components/form/role/RoleForm";
import ElectricBorder from "../../../../components/actions/ElectricBorder";
import PageBreadcrumb from "../../../../components/common/PageBreadCrumb";
import Loading from "../../../../components/loaders/Loading";
import Loading2 from "../../../../components/loaders/Loading2";
import type { Filial } from "../../../../../domain/entities/Filial";
import { FilialRepository } from "../../../../../infrastructure/repositories/FilialRepository";
import { FilialService } from "../../../../../application/services/FilialService";
import FilialForm from "../../../../components/form/filial/FilialForm";

const filialRepository = new FilialRepository();
const filialService = new FilialService(filialRepository);

export default function FilialFormPage() {
  const navigate = useNavigate();
  const { id } = useParams();
  const [filial, setFilial] = useState<Filial | undefined>();
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
      filialService
        .getFilialById(Number(id))
        .then((data) => setFilial(data))
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
          pageTitle={id ? "Editar Filial" : "Crear Filial"}
          pageBack="Cancelar"
          routeBack="super-admin-filiales"
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
          <FilialForm
            initialFilial={filial}
            onSuccess={() => navigate("/super-admin-filiales")} // 👉 Redirige al listado
          />
        </ElectricBorder>
      </div>
    </div>
  );
}