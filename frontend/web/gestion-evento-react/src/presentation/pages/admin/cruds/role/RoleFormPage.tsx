import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import RoleForm from "../../../../components/form/role/RoleForm";
import { RoleRepository } from "../../../../../infrastructure/repositories/RoleRepository";
import { RoleService } from "../../../../../application/services/RoleService";
import { Role } from "../../../../../domain/entities/Role";
import ElectricBorder from "../../../../components/actions/ElectricBorder";
import PageBreadcrumb from "../../../../components/common/PageBreadCrumb";
import Loading from "../../../../components/loaders/Loading";
import Loading2 from "../../../../components/loaders/Loading2";

const roleRepository = new RoleRepository();
const roleService = new RoleService(roleRepository);

export default function RoleFormPage() {
  const navigate = useNavigate();
  const { id } = useParams();
  const [role, setRole] = useState<Role | undefined>();
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
      roleService
        .getRoleById(Number(id))
        .then((data) => setRole(data))
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
          pageTitle={id ? "Editar Rol" : "Crear Rol"}
          pageBack="Cancelar"
          routeBack="super-admin-roles"
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
          <RoleForm
            initialRole={role}
            onSuccess={() => navigate("/super-admin-roles")} // 👉 Redirige al listado
          />
        </ElectricBorder>
      </div>
    </div>
  );
}