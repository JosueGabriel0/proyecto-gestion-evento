import { BrowserRouter, Routes, Route } from "react-router-dom";
import HomePage from "../pages/general/dashboard/home/HomePage";
import LoginPage from "../pages/general/auth/LoginPage";
import AdminDashboardPage from "../pages/general/dashboard/admin/adminDashboardPage";

const AppRouter = () => {
    return (
        <Routes>
            {/* 🌍 Públicas */}
            <Route path="/" element={<HomePage />} />
            <Route path="/login" element={<LoginPage />} />
            <Route path="/dashboard-super-admin" element={<LoginPage />} />
            <Route path="/dashboard-admin" element={<AdminDashboardPage />} />
            {/*
        <Route path="/email" element={<EmailPage />} />
        <Route path="/restablecimiento-contrasenia" element={<RestablecerContraseniaPage />} />
        <Route path="/cambiar-contrasenia/:token" element={<CambiarContraseniaPage />} />
        <Route path="/unauthorized" element={<UnauthorizedPage />} />
*/}

            {/* 👨‍💼 ADMINISTRADOR */}
            {/*
                <Route
                    path="/dashboard-administrador"
                    element={
                        <RoleProtectedRoute allowedRoles={["ADMINISTRADOR"]}>
                            <AdministradorDashboardPage />
                        </RoleProtectedRoute>
                    }
                />
                <Route
                    path="/roles"
                    element={
                        <RoleProtectedRoute allowedRoles={["ADMINISTRADOR"]}>
                            <ListRolPage />
                        </RoleProtectedRoute>
                    }
                />
                <Route
                    path="/add-rol"
                    element={
                        <RoleProtectedRoute allowedRoles={["ADMINISTRADOR"]}>
                            <AddRolPage />
                        </RoleProtectedRoute>
                    }
                />
                */}
            {/* ...idem para usuarios, personas, docentes, estudiantes, inscripciones */}

            {/* ❌ 404 */}
            <Route path="*" element={<h2>404 - Página no encontrada</h2>} />
        </Routes>
    );
};

export default AppRouter;