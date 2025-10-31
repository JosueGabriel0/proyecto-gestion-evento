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
  const [foto] = useState(initialEscuela?.foto || "");
  const [loading, setLoading] = useState(false);
  const [open, setOpen] = useState(false);

  // ⚠️ Estado de errores
  const [errors, setErrors] = useState({
    nombre: "",
    codigo: "",
    facultadId: "",
    foto: "",
  });

  // 🔹 Cargar facultades desde el backend
  useEffect(() => {
    facultadService
      .getFacultades()
      .then(setFacultades)
      .catch((err) => console.error("Error al cargar facultades:", err));
  }, []);

  // ✅ Validar campos
  const validate = () => {
    const newErrors = { nombre: "", codigo: "", facultadId: "", foto: "" };
    let isValid = true;

    if (!nombre.trim()) {
      newErrors.nombre = "El nombre de la escuela es obligatorio.";
      isValid = false;
    } else if (nombre.length < 3) {
      newErrors.nombre = "El nombre debe tener al menos 3 caracteres.";
      isValid = false;
    }

    const codigoRegex = /^[A-Za-z0-9-]+$/;
    if (!codigo.trim()) {
      newErrors.codigo = "El código es obligatorio.";
      isValid = false;
    } else if (!codigoRegex.test(codigo)) {
      newErrors.codigo = "El código solo puede contener letras, números o guiones.";
      isValid = false;
    }

    if (!facultadId) {
      newErrors.facultadId = "Debe seleccionar una facultad.";
      isValid = false;
    }

    if (!fotoFile && !foto) {
      newErrors.foto = "Debe seleccionar una imagen para la escuela.";
      isValid = false;
    }

    setErrors(newErrors);
    return isValid;
  };

  // 💾 Manejar el envío
  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!validate()) return;

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
      className="p-6 border rounded-lg bg-white shadow-md dark:bg-gray-800"
    >
      {/* 🧩 Inputs en dos columnas */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        {/* 🏷️ Nombre */}
        <div className="mt-5">
          <InputText
            value={nombre}
            onChange={(e) => {
              setNombre(e.target.value);
              if (errors.nombre) setErrors((prev) => ({ ...prev, nombre: "" }));
            }}
            label="Nombre de la escuela"
            placeholder="Escribe el nombre de la escuela"
          />
          {errors.nombre && <p className="text-red-500 text-sm mt-1">{errors.nombre}</p>}
        </div>

        {/* 🔢 Código */}
        <div className="mt-5">
          <InputText
            value={codigo}
            onChange={(e) => {
              setCodigo(e.target.value);
              if (errors.codigo) setErrors((prev) => ({ ...prev, codigo: "" }));
            }}
            label="Código"
            placeholder="Escribe el código"
          />
          {errors.codigo && <p className="text-red-500 text-sm mt-1">{errors.codigo}</p>}
        </div>
      </div>

      {/* 🎓 Selección de Facultad */}
      <div className="mt-5">
        <span className="inline-block bg-black text-white text-sm font-medium px-3 py-1 rounded-md border border-gray-400 text-center mb-1">
          Selecciona una Facultad
        </span>

        <div className="border rounded-lg overflow-hidden">
          <button
            type="button"
            onClick={() => setOpen(!open)}
            className="w-full flex justify-between items-center px-4 py-2 
              bg-black text-white 
              hover:bg-gray-900 
              dark:bg-black dark:text-white 
              dark:hover:bg-gray-950 
              font-medium rounded-md transition-colors duration-200"
          >
            {facultadId
              ? facultades.find((f) => f.id === facultadId)?.nombre || "Selecciona una facultad"
              : "Selecciona una facultad"}
            <span className="ml-2 text-gray-400">{open ? "▲" : "▼"}</span>
          </button>

          {open && (
            <div className="max-h-60 overflow-y-auto bg-white dark:bg-gray-800 border-t dark:border-gray-700">
              {facultades.length > 0 ? (
                facultades.map((facultad) => (
                  <div
                    key={facultad.id}
                    onClick={() => {
                      setFacultadId(facultad.id);
                      setOpen(false);
                      if (errors.facultadId)
                        setErrors((prev) => ({ ...prev, facultadId: "" }));
                    }}
                    className={`px-4 py-2 cursor-pointer 
                      text-gray-800 dark:text-gray-100   /* 👈 color adaptable claro/oscuro */
                      hover:bg-gray-100 dark:hover:bg-gray-700 
                      ${facultadId === facultad.id ? "bg-gray-200 dark:bg-gray-600 font-semibold" : ""}`}
                  >
                    {facultad.nombre}
                  </div>
                ))
              ) : (
                <div className="px-4 py-2 text-gray-400 dark:text-gray-600 text-sm">
                  No hay facultades disponibles
                </div>
              )}
            </div>
          )}
        </div>
        {errors.facultadId && (
          <p className="text-red-500 text-sm mt-1">{errors.facultadId}</p>
        )}
      </div>

      {/* 🖼️ Imagen */}
      <div className="mt-5">
        <InputFile
          file={fotoFile}
          onChange={(file) => {
            setFotoFile(file);
            if (errors.foto) setErrors((prev) => ({ ...prev, foto: "" }));
          }}
          initialUrl={foto}
          label="Foto de la escuela"
        />
        {errors.foto && <p className="text-red-500 text-sm mt-1">{errors.foto}</p>}
      </div>

      {/* 🔘 Botón */}
      <div className="mt-5">
        <AddEditButton
          name={loading ? "Guardando..." : initialEscuela ? "Actualizar" : "Crear"}
          disabled={loading}
        />
      </div>
    </form>
  );
}