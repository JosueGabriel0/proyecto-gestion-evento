import React, { useState, useCallback, useEffect } from "react";
import { Link, useLocation } from "react-router";
import {
  BoxCubeIcon,
  ChevronDownIcon,
  GridIcon,
  HorizontaLDots,
} from "../icons";
import { useSidebar } from "../context/SidebarContext";
import SidebarWidget from "./SidebarWidget";
import { TokenStorage } from "../../infrastructure/repositories/TokenStorage";

type NavItem = {
  name: string;
  icon: React.ReactNode;
  path?: string;
  subItems?: { name: string; path: string }[];
};

const getMenuItemsByRole = (role: string): NavItem[] => {
  const base: NavItem[] = [
    {
      icon: <GridIcon />,
      name: "Dashboard",
      subItems: [{ name: "Inicio", path: "/" }],
    },
  ];

  if (role === "ROLE_SUPER_ADMIN") {
    base.push({
      icon: <BoxCubeIcon />,
      name: "Gestionar",
      subItems: [
        { name: "Filiales", path: "/super-filiales" },
        { name: "Roles", path: "/super-admin-roles" },
        { name: "Facultades", path: "/super-admin-facultades" },
        { name: "Escuelas", path: "/super-admin-escuelas" },
        { name: "Usuarios", path: "/super-admin-usuarios" },
      ],
    });
  }

  if (role === "ROLE_ADMIN") {
    base.push({
      icon: <BoxCubeIcon />,
      name: "Gestionar",
      subItems: [
        { name: "Roles", path: "/super-admin-roles" },
        { name: "Facultades", path: "/super-admin-facultades" },
        { name: "Escuelas", path: "/super-admin-escuelas" },
        { name: "Usuarios", path: "/super-admin-usuarios" },
      ],
    });
  }
  
  return base;
};

const AppSidebar: React.FC = () => {
  const { isExpanded, isMobileOpen, isHovered, setIsHovered } = useSidebar();
  const location = useLocation();
  const [role, setRole] = useState<string>("");

  useEffect(() => {
    const storage = new TokenStorage();
    const user = storage.getUser();
    if (user) setRole(user.role);
  }, []);

  const navItems = getMenuItemsByRole(role);
  const [openSubmenu, setOpenSubmenu] = useState<number | null>(null);

  const isActive = useCallback(
    (path?: string) => path && location.pathname === path,
    [location.pathname]
  );

  const toggleSubmenu = (index: number) =>
    setOpenSubmenu((prev) => (prev === index ? null : index));

  const renderMenuItems = (items: NavItem[]) => (
    <ul className="flex flex-col gap-4">
      {items.map((nav, index) => (
        <li key={nav.name}>
          {nav.subItems ? (
            <>
              <button
                onClick={() => toggleSubmenu(index)}
                className={`flex items-center w-full p-2 rounded 
                  hover:bg-gray-100 dark:hover:bg-gray-800 transition
                  text-gray-900 dark:text-white
                  ${openSubmenu === index
                    ? "bg-gray-200 dark:bg-gray-700"
                    : ""
                  }
                `}
              >
                <span className="!mr-3 !flex !items-center !justify-center !text-[24px]">
                  {nav.icon}
                </span>
                {(isExpanded || isHovered || isMobileOpen) && (
                  <span className="ml-1">{nav.name}</span>
                )}
                {(isExpanded || isHovered || isMobileOpen) && (
                  <ChevronDownIcon
                    className={`ml-auto w-5 h-5 transition-transform duration-300 ${openSubmenu === index ? "rotate-180" : ""
                      }`}
                  />
                )}
              </button>

              {/* Submenú */}
              <div
                className={`overflow-hidden transition-all duration-300 ${openSubmenu === index ? "max-h-96 mt-2" : "max-h-0"
                  }`}
              >
                <ul className="pl-9 space-y-1">
                  {nav.subItems.map((sub) => (
                    <li key={sub.name}>
                      <Link
                        to={sub.path}
                        className={`block p-2 rounded transition 
                          hover:bg-gray-100 dark:hover:bg-gray-800
                          text-gray-900 dark:text-white
                          ${isActive(sub.path)
                            ? "font-semibold text-blue-600 dark:text-blue-400"
                            : ""
                          }`}
                      >
                        {sub.name}
                      </Link>
                    </li>
                  ))}
                </ul>
              </div>
            </>
          ) : nav.path ? (
            <Link
              to={nav.path}
              className={`flex items-center w-full p-2 rounded transition
                hover:bg-gray-100 dark:hover:bg-gray-800
                text-gray-900 dark:text-white
                ${isActive(nav.path)
                  ? "font-semibold text-blue-600 dark:text-blue-400"
                  : ""
                }`}
            >
              <span className="mr-3 w-7 h-7 flex items-center justify-center">
                {nav.icon}
              </span>
              {(isExpanded || isHovered || isMobileOpen) && (
                <span className="ml-1">{nav.name}</span>
              )}
            </Link>
          ) : null}
        </li>
      ))}
    </ul>
  );

  return (
    <aside
      className={`fixed top-0 left-0 h-screen bg-white dark:bg-gray-900 border-r 
        border-gray-200 dark:border-gray-800 flex flex-col transition-all duration-300 z-50
        ${isExpanded || isHovered || isMobileOpen ? "w-72" : "w-20"}
        ${isMobileOpen ? "translate-x-0" : "-translate-x-full"} lg:translate-x-0`}
      onMouseEnter={() => !isExpanded && setIsHovered(true)}
      onMouseLeave={() => setIsHovered(false)}
    >
      {/* Logo */}
      <div
        className={`py-8 flex ${!isExpanded && !isHovered ? "justify-center" : "justify-start"
          }`}
      >
        <Link to="/">
          {/* Logo modo claro */}
          <div className="ml-10">
            <img
              src="/images/logoo-light.png"
              alt="Logo claro"
              className="block dark:hidden transition-all duration-300"
              width={isExpanded || isHovered || isMobileOpen ? 150 : 32}
              height={40}
            />
            <div className="">
              {/* Logo modo oscuro */}
              <img
                src="/images/logoo.png"
                alt="Logo oscuro"
                className="hidden dark:block transition-all duration-300"
                width={isExpanded || isHovered || isMobileOpen ? 150 : 32}
                height={40}
              />
            </div>
          </div>
        </Link>
      </div>

      {/* Menú */}
      <div className="flex-1 overflow-y-auto">
        <nav className="px-3">
          <h2
            className={`!mb-4 !text-xs !uppercase !flex !leading-[20px] !text-gray-500 !dark:text-gray-400
              ${!isExpanded && !isHovered ? "lg:justify-center" : "justify-start"}
            `}
          >
            {isExpanded || isHovered || isMobileOpen ? "Menú" : <HorizontaLDots />}
          </h2>
          {renderMenuItems(navItems)}
        </nav>

      </div>
    </aside>
  );
};

export default AppSidebar;