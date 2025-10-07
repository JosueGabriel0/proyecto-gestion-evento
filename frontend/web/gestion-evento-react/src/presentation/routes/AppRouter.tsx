import { Routes, Route } from "react-router-dom";
import HomePage from "../pages/general/dashboard/home/HomePage";
import LoginPage from "../pages/general/auth/LoginPage";
import AdminDashboardPage from "../pages/admin/dashboard/AdminDashboardPage";
import AppLayout from "../layout/AppLayout";
import RoleProtectedRoute from "./RoleProtectedRoute";
import RoleGestionPage from "../pages/admin/cruds/role/RoleGestionPage";
import RoleFormPage from "../pages/admin/cruds/role/RoleFormPage";
import Page404 from "../pages/general/Page404";
import FilialGestionPage from "../pages/superAdmin/cruds/filial/FilialGestionPage";
import FilialFormPage from "../pages/superAdmin/cruds/filial/FilialFormPage";

const AppRouter = () => {
    return (
        <Routes>
            {/* 🌍 Públicas */}
            <Route path="/" element={<HomePage />} />
            <Route path="/login" element={<LoginPage />} />

            <Route element={<AppLayout />}>

                <Route path="/dashboard-super-admin" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN"]}>
                        <AdminDashboardPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/super-admin-filiales" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN"]}>
                        <FilialGestionPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/filiales/new" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN"]}>
                        <FilialFormPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/filiales/edit/:id" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN"]}>
                        <FilialFormPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/dashboard-admin" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_ADMIN"]}>
                        <AdminDashboardPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/admin-roles" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_ADMIN"]}>
                        <RoleGestionPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/roles/new" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_ADMIN"]}>
                        <RoleFormPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/roles/edit/:id" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_ADMIN"]}>
                        <RoleFormPage />
                    </RoleProtectedRoute>
                } />
            </Route>

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
            <Route path="*" element={<div className="flex items-center justify-center min-h-screen"><Page404 /></div>} />
        </Routes>
    );
};

export default AppRouter;