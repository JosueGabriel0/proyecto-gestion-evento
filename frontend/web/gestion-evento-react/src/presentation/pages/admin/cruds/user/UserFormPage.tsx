import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import ElectricBorder from "../../../../components/actions/ElectricBorder";
import PageBreadcrumb from "../../../../components/common/PageBreadCrumb";
import Loading from "../../../../components/loaders/Loading";
import Loading2 from "../../../../components/loaders/Loading2";
import { UserRepository } from "../../../../../infrastructure/repositories/UserRepository";
import { UserService } from "../../../../../application/services/UserService";
import type { User } from "../../../../../domain/entities/User";
import UserForm from "../../../../components/form/user/UserForm";

const userRepository = new UserRepository();
const userService = new UserService(userRepository);

export default function UserFormPage() {
  const navigate = useNavigate();
  const { id } = useParams();
  const [user, setUser] = useState<User | undefined>();
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
      userService
        .getUserById(Number(id))
        .then((data) => setUser(data))
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
          pageTitle={id ? "Editar Usuario" : "Crear Usuario"}
          pageBack="Cancelar"
          routeBack="super-admin-usuarios"
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
          <UserForm
            initialUser={user}
            onSuccess={() => {
              console.log("onSucces ejecutando, navegando...");
              navigate("/super-admin-usuarios");
            }}
          />
        </ElectricBorder>
      </div>
    </div>
  );
}