import { useState, useEffect } from "react";
import InputText from "../input/InputText";
import InputFile from "../input/InputFile";
import AddEditButton from "../../actions/AddEditButton";
import { FilialRepository } from "../../../../infrastructure/repositories/FilialRepository";
import { FilialService } from "../../../../application/services/FilialService";
import { Filial } from "../../../../domain/entities/Filial";
import { FacultadRepository } from "../../../../infrastructure/repositories/FacultadRepository";
import { FacultadService } from "../../../../application/services/FacultadService";
import { Facultad } from "../../../../domain/entities/Facultad";

const filialRepository = new FilialRepository();
const filialService = new FilialService(filialRepository);

const facultadRepository = new FacultadRepository();
const facultadService = new FacultadService(facultadRepository);

interface FacultadFormProps {
  initialFacultad?: Facultad;
  onSuccess: () => void;
}

export default function FacultadForm({ initialFacultad, onSuccess }: FacultadFormProps) {
  const [nombre, setNombre] = useState(initialFacultad?.nombre || "");
  const [codigo, setCodigo] = useState(initialFacultad?.codigo || "");
  const [filialId, setFilialId] = useState<number>(initialFacultad?.filialId || 0);
  const [filiales, setFiliales] = useState<Filial[]>([]);
  const [fotoFile, setFotoFile] = useState<File | null>(null);
  const [loading, setLoading] = useState(false);
  const [open, setOpen] = useState(false); // 👈 controla el acordeón

  // 🔹 Cargar filiales desde el backend
  useEffect(() => {
    filialService
      .getFiliales()
      .then(setFiliales)
      .catch((err) => console.error("Error al cargar filiales:", err));
  }, []);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);

    try {
      const facultad = new Facultad(initialFacultad?.id || 0, nombre, codigo, "", filialId);

      if (initialFacultad) {
        await facultadService.putFacultad(facultad, fotoFile || undefined);
      } else {
        await facultadService.createFacultad(facultad, fotoFile || undefined);
      }

      onSuccess();
    } catch (error) {
      console.error("Error al guardar la facultad:", error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <form
      onSubmit={handleSubmit}
      className="space-y-4 p-6 border rounded-lg bg-white shadow-md dark:bg-gray-800"
    >
      <div className="mt-5">
        <InputText
          value={nombre}
          onChange={(e) => setNombre(e.target.value)}
          label="Nombre de la facultad"
          placeholder="Escribe el nombre de la facultad"
        />
      </div>

      <div className="mt-10">
        <InputText
          value={codigo}
          onChange={(e) => setCodigo(e.target.value)}
          label="Código"
          placeholder="Escribe el código"
        />
      </div>
      {/* 🔹 Acordeón de Filiales */}
      <div className="mt-5">
        <span className="inline-block bg-black text-white text-sm font-medium px-3 py-1 rounded-md border border-gray-400 text-center mb-1">
          Selecciona una Filial
        </span>

        <div className="border rounded-lg overflow-hidden">
          {/* Botón del acordeón */}
          <button
            type="button"
            onClick={() => setOpen(!open)}
            className="w-full flex justify-between items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 font-medium"
          >
            {filialId
              ? filiales.find((f) => f.id === filialId)?.nombre || "Selecciona una filial"
              : "Selecciona una filial"}
            <span className="ml-2 text-gray-400">{open ? "▲" : "▼"}</span>
          </button>

          {/* Contenido del acordeón */}
          {open && (
            <div className="max-h-60 overflow-y-auto bg-white !dark:bg-gray-800 border-t !dark:border-gray-700">
              {filiales.length > 0 ? (
                filiales.map((filial) => (
                  <div
                    key={filial.id}
                    onClick={() => {
                      setFilialId(filial.id);
                      setOpen(false);
                    }}
                    className={`px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 ${filialId === filial.id ? "bg-gray-200 dark:bg-gray-600 font-semibold" : ""
                      }`}
                  >
                    {filial.nombre}
                  </div>
                ))
              ) : (
                <div className="px-4 py-2 text-gray-500 text-sm">No hay filiales disponibles</div>
              )}
            </div>
          )}
        </div>
      </div>

      {/* Imagen */}
      <InputFile
        file={fotoFile}
        onChange={(file) => setFotoFile(file)}
        label="Foto de la facultad"
      />

      <AddEditButton
        name={loading ? "Guardando..." : initialFacultad ? "Actualizar" : "Crear"}
        onClick={() => { }}
        disabled={loading}
      />
    </form>
  );
}