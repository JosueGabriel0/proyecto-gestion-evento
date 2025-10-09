import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import Swal from "sweetalert2";

import PageBreadcrumb from "../../../../components/common/PageBreadCrumb";
import ComponentCard from "../../../../components/common/ComponentCard";

import { FacultadService } from "../../../../../application/services/FacultadService";
import { FacultadRepository } from "../../../../../infrastructure/repositories/FacultadRepository";

const facultadRepository = new FacultadRepository();
const facultadService = new FacultadService(facultadRepository);

export default function FacultadFormPage() {
    const navigate = useNavigate();
    const { id } = useParams();

    const [facultad, setFacultad] = useState({
        id: 0,
        nombre: "",
        codigo: "",
        foto: null as string | null,
        filialId: 0,
    });

    const [file, setFile] = useState<File | null>(null);

    useEffect(() => {
        if (id) {
            facultadService
                .getFacultadById(Number(id))
                .then((data) => {
                    if (data) {
                        setFacultad({
                            id: data.id,
                            nombre: data.nombre, // usa el getter si es instancia
                            codigo: data.codigo,
                            foto: data.foto ?? null,
                            filialId: data.filialId,
                        });
                    }
                })
                .catch(() => {
                    Swal.fire("Error", "No se pudo cargar la facultad", "error");
                });
        }
    }, [id]);

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value } = e.target;
        setFacultad((prev) => ({ ...prev, [name]: value }));
    };

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            setFile(e.target.files[0]);
        }
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        try {
            const facultadData = {
                ...facultad,
                filialId: Number(facultad.filialId),
            };

            if (id) {
                await facultadService.createFacultad(facultadData, file ?? undefined);
                Swal.fire("Actualizado", "Facultad actualizada correctamente", "success");
            } else {
                await facultadService.createFacultad(facultadData, file ?? undefined);
                Swal.fire("Creado", "Facultad creada correctamente", "success");
            }
            navigate("/superAdmin/cruds/facultad");
        } catch (error) {
            Swal.fire("Error", "No se pudo guardar la facultad", "error");
            console.error(error);
        }
    };

    return (
        <div className="p-6 space-y-6">
            <PageBreadcrumb
                pageTitle={id ? "Editar Facultad" : "Nueva Facultad"}
                pageBack="Volver"
                routeBack="/superAdmin/cruds/facultad"
            />

            <ComponentCard title="Formulario de Facultad">
                <form onSubmit={handleSubmit} className="space-y-4">
                    <input
                        type="text"
                        name="nombre"
                        placeholder="Nombre"
                        value={facultad.nombre}
                        onChange={handleChange}
                        className="border rounded p-2 w-full"
                        required
                    />
                    <input
                        type="text"
                        name="codigo"
                        placeholder="Código"
                        value={facultad.codigo}
                        onChange={handleChange}
                        className="border rounded p-2 w-full"
                        required
                    />
                    <input
                        type="number"
                        name="filialId"
                        placeholder="ID de Filial"
                        value={facultad.filialId}
                        onChange={handleChange}
                        className="border rounded p-2 w-full"
                    />
                    <input
                        type="file"
                        name="foto"
                        onChange={handleFileChange}
                        className="border rounded p-2 w-full"
                    />
                    <button
                        type="submit"
                        className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                    >
                        {id ? "Actualizar" : "Guardar"}
                    </button>
                </form>
            </ComponentCard>
        </div>
    );
}
