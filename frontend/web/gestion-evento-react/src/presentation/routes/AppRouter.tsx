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
import FacultadGestionPage from "../pages/admin/cruds/facultad/FacultadGestionPage";
import FacultadFormPage from "../pages/admin/cruds/facultad/FacultadFormPage";
import EscuelaGestionPage from "../pages/admin/cruds/escuela/EscuelaGestionPage";
import EscuelaFormPage from "../pages/admin/cruds/escuela/EscuelaFormPage";
import UserGestionPage from "../pages/admin/cruds/user/UserGestionPage";
import UserFormPage from "../pages/admin/cruds/user/UserFormPage";

const AppRouter = () => {
    return (
        <Routes>
            {/* 🌍 Públicas */}
            <Route path="/" element={<HomePage />} />
            <Route path="/login" element={<LoginPage />} />

            <Route element={<AppLayout />}>

                <Route path="/super-dashboard" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN"]}>
                        <AdminDashboardPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/super-filiales" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN"]}>
                        <FilialGestionPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/super-filiales/new" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN"]}>
                        <FilialFormPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/super-filiales/edit/:id" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN"]}>
                        <FilialFormPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/admin-dashboard" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_ADMIN"]}>
                        <AdminDashboardPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/super-admin-roles" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN", "ROLE_ADMIN"]}>
                        <RoleGestionPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/super-admin-roles/new" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN", "ROLE_ADMIN"]}>
                        <RoleFormPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/super-admin-roles/edit/:id" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN", "ROLE_ADMIN"]}>
                        <RoleFormPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/super-admin-facultades" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN", "ROLE_ADMIN"]}>
                        <FacultadGestionPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/super-admin-facultades/new" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN", "ROLE_ADMIN"]}>
                        <FacultadFormPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/super-admin-facultades/edit/:id" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN", "ROLE_ADMIN"]}>
                        <FacultadFormPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/super-admin-escuelas" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN", "ROLE_ADMIN"]}>
                        <EscuelaGestionPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/super-admin-escuelas/new" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN", "ROLE_ADMIN"]}>
                        <EscuelaFormPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/super-admin-escuelas/edit/:id" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN", "ROLE_ADMIN"]}>
                        <EscuelaFormPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/super-admin-usuarios" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN", "ROLE_ADMIN"]}>
                        <UserGestionPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/super-admin-usuarios/new" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN", "ROLE_ADMIN"]}>
                        <UserFormPage />
                    </RoleProtectedRoute>
                } />

                <Route path="/super-admin-usuarios/edit/:id" element={
                    <RoleProtectedRoute allowedRoles={["ROLE_SUPER_ADMIN", "ROLE_ADMIN"]}>
                        <UserFormPage />
                    </RoleProtectedRoute>
                } />
            </Route>

            <Route path="*" element={<div className="flex items-center justify-center min-h-screen"><Page404 /></div>} />
        </Routes>
    );
};

export default AppRouter;