import { Navigate } from "react-router-dom";
import { isAuthenticated, getUserRole } from "../../infrastructure/services/authServices/authService";
import type { JSX } from "react";

interface Props {
    children: JSX.Element;
    allowedRoles: string[];
}

const RoleProtectedRoute = ({ children, allowedRoles }: Props) => {
    if (!isAuthenticated()) return <Navigate to="/login" replace />;

    const role = getUserRole();

    if (!role || !allowedRoles.includes(role)) {
        return <Navigate to="/unauthorized" replace />;
    }


    return children;
};

export default RoleProtectedRoute;