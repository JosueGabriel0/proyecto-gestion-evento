import { useState, useEffect } from "react";
import InputText from "../input/InputText";
import InputFile from "../input/InputFile";
import AddEditButton from "../../actions/AddEditButton";
import { FacultadRepository } from "../../../../infrastructure/repositories/FacultadRepository";
import { FacultadService } from "../../../../application/services/FacultadService";
import { Facultad } from "../../../../domain/entities/Facultad";
import { Escuela } from "../../../../domain/entities/Escuela";
import { EscuelaRepository } from "../../../../infrastructure/repositories/EscuelaRepository";
import { EscuelaService } from "../../../../application/services/EscuelaService";

const facultadRepository = new FacultadRepository();
const facultadService = new FacultadService(facultadRepository);

const escuelaRepository = new EscuelaRepository();
const escuelaService = new EscuelaService(escuelaRepository);

interface EscuelaFormProps {
  initialEscuela?: Escuela;
  onSuccess: () => void;
}

export default function EscuelaForm({ initialEscuela, onSuccess }: EscuelaFormProps) {
  const [nombre, setNombre] = useState(initialEscuela?.nombre || "");
  const [codigo, setCodigo] = useState(initialEscuela?.codigo || "");
  const [facultadId, setFacultadId] = useState<number>(initialEscuela?.facultadId || 0);
  const [facultades, setFacultades] = useState<Facultad[]>([]);
  const [fotoFile, setFotoFile] = useState<File | null>(null);
  const [loading, setLoading] = useState(false);
  const [open, setOpen] = useState(false); // 👈 controla el acordeón

  // 🔹 Cargar filiales desde el backend
  useEffect(() => {
    facultadService
      .getFacultades()
      .then(setFacultades)
      .catch((err) => console.error("Error al cargar facultades:", err));
  }, []);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);

    try {
      const escuela = new Escuela(initialEscuela?.id || 0, nombre, codigo, "", facultadId);

      if (initialEscuela) {
        await escuelaService.updateEscuela(escuela, fotoFile || undefined);
      } else {
        await escuelaService.createEscuela(escuela, fotoFile || undefined);
      }

      onSuccess();
    } catch (error) {
      console.error("Error al guardar la escuela:", error);
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
          label="Nombre de la escuela"
          placeholder="Escribe el nombre de la escuela"
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
          Selecciona una Facultad
        </span>

        <div className="border rounded-lg overflow-hidden">
          {/* Botón del acordeón */}
          <button
            type="button"
            onClick={() => setOpen(!open)}
            className="w-full flex justify-between items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 font-medium"
          >
            {facultadId
              ? facultades.find((f) => f.id === facultadId)?.nombre || "Selecciona una facultad"
              : "Selecciona una facultad"}
            <span className="ml-2 text-gray-400">{open ? "▲" : "▼"}</span>
          </button>

          {/* Contenido del acordeón */}
          {open && (
            <div className="max-h-60 overflow-y-auto bg-white !dark:bg-gray-800 border-t !dark:border-gray-700">
              {facultades.length > 0 ? (
                facultades.map((facultad) => (
                  <div
                    key={facultad.id}
                    onClick={() => {
                      setFacultadId(facultad.id);
                      setOpen(false);
                    }}
                    className={`px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 ${facultadId === facultad.id ? "bg-gray-200 dark:bg-gray-600 font-semibold" : ""
                      }`}
                  >
                    {facultad.nombre}
                  </div>
                ))
              ) : (
                <div className="px-4 py-2 text-gray-500 text-sm">No hay facultades disponibles</div>
              )}
            </div>
          )}
        </div>
      </div>

      {/* Imagen */}
      <InputFile
        file={fotoFile}
        onChange={(file) => setFotoFile(file)}
        label="Foto de la escuela"
      />

      <AddEditButton
        name={loading ? "Guardando..." : initialEscuela ? "Actualizar" : "Crear"}
        onClick={() => { }}
        disabled={loading}
      />
    </form>
  );
}