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
  const [foto] = useState(initialFacultad?.foto || "");
  const [loading, setLoading] = useState(false);
  const [open, setOpen] = useState(false);

  // 🧾 Estado de errores
  const [errors, setErrors] = useState({
    nombre: "",
    codigo: "",
    filialId: "",
    foto: "",
  });

  // 🔹 Cargar filiales desde el backend
  useEffect(() => {
    filialService
      .getFiliales()
      .then(setFiliales)
      .catch((err) => console.error("Error al cargar filiales:", err));
  }, []);

  // ✅ Validaciones del formulario
  const validate = () => {
    const newErrors = { nombre: "", codigo: "", filialId: "", foto: "" };
    let isValid = true;

    if (!nombre.trim()) {
      newErrors.nombre = "El nombre de la facultad es obligatorio.";
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

    if (!filialId) {
      newErrors.filialId = "Debe seleccionar una filial.";
      isValid = false;
    }

    if (!fotoFile && !foto) {
      newErrors.foto = "Debe seleccionar una imagen para la facultad.";
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
            label="Nombre de la facultad"
            placeholder="Escribe el nombre de la facultad"
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

      {/* 🏫 Selección de Filial */}
      <div className="mt-5">
        <span className="inline-block bg-black text-white text-sm font-medium px-3 py-1 rounded-md border border-gray-400 text-center mb-1">
          Selecciona una Filial
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
            {filialId
              ? filiales.find((f) => f.id === filialId)?.nombre || "Selecciona una filial"
              : "Selecciona una filial"}
            <span className="ml-2 text-gray-400">{open ? "▲" : "▼"}</span>
          </button>

          {open && (
            <div className="max-h-60 overflow-y-auto bg-white dark:bg-gray-800 border-t dark:border-gray-700">
              {filiales.length > 0 ? (
                filiales.map((filial) => (
                  <div
                    key={filial.id}
                    onClick={() => {
                      setFilialId(filial.id);
                      setOpen(false);
                      if (errors.filialId) setErrors((prev) => ({ ...prev, filialId: "" }));
                    }}
                    className={`px-4 py-2 cursor-pointer 
                      text-gray-800 dark:text-gray-100   /* 👈 color adaptable claro/oscuro */
                      hover:bg-gray-100 dark:hover:bg-gray-700 
                      ${filialId === filial.id ? "bg-gray-200 dark:bg-gray-600 font-semibold" : ""}`}
                  >
                    {filial.nombre}
                  </div>
                ))
              ) : (
                <div className="px-4 py-2 text-gray-500 dark:text-gray-300 text-sm">
                  No hay filiales disponibles
                </div>
              )}
            </div>
          )}
        </div>
        {errors.filialId && <p className="text-red-500 text-sm mt-1">{errors.filialId}</p>}
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
          label="Foto de la facultad"
        />
        {errors.foto && <p className="text-red-500 text-sm mt-1">{errors.foto}</p>}
      </div>

      {/* 🔘 Botón */}
      <div className="mt-5">
        <AddEditButton
          name={loading ? "Guardando..." : initialFacultad ? "Actualizar" : "Crear"}
          disabled={loading}
        />
      </div>
    </form>
  );
}