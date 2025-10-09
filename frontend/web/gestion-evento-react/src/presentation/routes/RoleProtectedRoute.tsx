import { Navigate } from "react-router-dom";
import { authService } from "../../application/services/AuthServiceInstance";
import type { JSX } from "react";

interface Props {
  children: JSX.Element;
  allowedRoles: string[];
}

const RoleProtectedRoute = ({ children, allowedRoles }: Props) => {
  const user = authService.getUser();

  if (!user) return <Navigate to="/login" replace />;

  const role = user.role.replace(/\s+/g, "_");
  if (!allowedRoles.includes(role)) {
    return <Navigate to="/unauthorized" replace />;
  }

  return children;
};

export default RoleProtectedRoute;