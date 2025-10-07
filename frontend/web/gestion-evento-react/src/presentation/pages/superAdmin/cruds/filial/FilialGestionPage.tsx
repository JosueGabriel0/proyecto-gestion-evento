import { useState } from "react";
import { useNavigate } from "react-router-dom";
import Swal from "sweetalert2";

import ComponentCard from "../../../../components/common/ComponentCard";
import BasicTableOne from "../../../../components/BasicTables/BasicTableOne";
import { Role } from "../../../../../domain/entities/Role";
import { FilialRepository } from "../../../../../infrastructure/repositories/FilialRepository";
import { FilialService } from "../../../../../application/services/FilialService";
import PageBreadcrumb from "../../../../components/common/PageBreadCrumb";
import type { Filial } from "../../../../../domain/entities/Filial";

const filialRepository = new FilialRepository();
const filialService = new FilialService(filialRepository);

export default function FilialGestionPage() {
    const [searchTerm, setSearchTerm] = useState("");
    const navigate = useNavigate();

    // 🔹 API de datos para la tabla
    const fetchFiliales = async (page: number, perPage: number, term?: string) => {
        if (term && term.trim()) {
            return await filialService.searchFilialPaginated(term, perPage);
        }
        return await filialService.getFilialesPaginated(page, perPage);
    };

    // 🔹 Confirmación y eliminación con SweetAlert2
    const handleDelete = async (filial: Filial) => {
        const result = await Swal.fire({
            title: "¿Eliminar Filial?",
            text: `Se eliminará la filial "${filial.nombre}"`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        });

        if (result.isConfirmed) {
            try {
                await filialService.deleteFilial(filial.id);
                Swal.fire("Eliminado", "La filial ha sido eliminada.", "success");
                window.location.reload(); // 👉 recarga lista
            } catch (error) {
                Swal.fire("Error", "No se pudo eliminar la filial.", "error");
            }
        }
    };

    // 🔹 Configuración de columnas
    const columns = [
        { key: "id", label: "ID" },
        { key: "nombre", label: "Nombre" },
        { key: "direccion", label: "Direccion" },
        { key: "telefono", label: "Telefono" },
        { key: "email", label: "Email" },
        {
            key: "foto",
            label: "Foto",
            render: (filial: Filial) =>
                filial.foto ? (
                    <img
                        src={filial.foto}
                        alt={filial.nombre}
                        className="w-10 h-10 rounded-full object-cover"
                    />
                ) : (
                    <span className="italic text-gray-400">Sin foto</span>
                ),
        },
    ];

    return (
        <div className="p-6 space-y-6">
            {/* 🔹 Breadcrumb arriba */}
            <PageBreadcrumb
                pageTitle="Gestión de Filiales"
                pageBack="Inicio"
                routeBack="dashboard-super-admin"
            />

            {/* 🔹 Tabla de roles */}
            <ComponentCard
                title="Tabla de Filiales"
                onSearch={(term) => setSearchTerm(term)}
                onAdd={() => navigate("/filiales/new")}
            >
                <BasicTableOne<Filial>
                    columns={columns}
                    fetchData={fetchFiliales}
                    searchTerm={searchTerm}
                    onEdit={(filial) => navigate(`/filiales/edit/${filial.id}`)}
                    onDelete={handleDelete}
                />
            </ComponentCard>
        </div>
    );
}