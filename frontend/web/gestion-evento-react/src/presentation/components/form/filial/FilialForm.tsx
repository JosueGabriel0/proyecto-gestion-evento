import { useState } from "react";
import InputText from "../input/InputText";
import InputFile from "../input/InputFile";
import AddEditButton from "../../actions/AddEditButton";
import { FilialRepository } from "../../../../infrastructure/repositories/FilialRepository";
import { FilialService } from "../../../../application/services/FilialService";
import { Filial } from "../../../../domain/entities/Filial";

const filialRepository = new FilialRepository();
const filialService = new FilialService(filialRepository);

interface FilialFormProps {
  initialFilial?: Filial;
  onSuccess: () => void;
}

export default function FilialForm({ initialFilial, onSuccess }: FilialFormProps) {
  const [nombre, setNombre] = useState(initialFilial?.nombre || "");
  const [direccion, setDireccion] = useState(initialFilial?.direccion || "");
  const [telefono, setTelefono] = useState(initialFilial?.telefono || "");
  const [email, setEmail] = useState(initialFilial?.email || "");
  const [foto] = useState(initialFilial?.foto || "");
  const [fotoFile, setFotoFile] = useState<File | null>(null);
  const [loading, setLoading] = useState(false);

  // ✅ Estado de errores
  const [errors, setErrors] = useState({
    nombre: "",
    direccion: "",
    telefono: "",
    email: "",
    foto: "",
  });

  // 📋 Validar campos
  const validate = () => {
    const newErrors = {
      nombre: "",
      direccion: "",
      telefono: "",
      email: "",
      foto: "",
    };
    let isValid = true;

    if (!nombre.trim()) {
      newErrors.nombre = "El nombre es obligatorio.";
      isValid = false;
    } else if (nombre.length < 3) {
      newErrors.nombre = "El nombre debe tener al menos 3 caracteres.";
      isValid = false;
    }

    if (!direccion.trim()) {
      newErrors.direccion = "La dirección es obligatoria.";
      isValid = false;
    }

    const telefonoRegex = /^[0-9]{6,15}$/;
    if (!telefono.trim()) {
      newErrors.telefono = "El teléfono es obligatorio.";
      isValid = false;
    } else if (!telefonoRegex.test(telefono)) {
      newErrors.telefono = "El teléfono solo debe contener números (6-15 dígitos).";
      isValid = false;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.trim()) {
      newErrors.email = "El email es obligatorio.";
      isValid = false;
    } else if (!emailRegex.test(email)) {
      newErrors.email = "El formato del email no es válido.";
      isValid = false;
    }

    if (!fotoFile && !foto) {
      newErrors.foto = "Debe seleccionar una imagen para la filial.";
      isValid = false;
    }

    setErrors(newErrors);
    return isValid;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!validate()) return;
    setLoading(true);

    try {
      if (initialFilial) {
        const filial = new Filial(initialFilial.id, nombre, direccion, telefono, email, "");
        await filialService.updateFilial(filial, fotoFile || undefined);
      } else {
        const filial = new Filial(0, nombre, direccion, telefono, email, "");
        await filialService.createFilial(filial, fotoFile || undefined);
      }
      onSuccess();
    } catch (error) {
      console.error("Error al guardar la filial:", error);
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
            onChange={(e) => setNombre(e.target.value)}
            label="Nombre de la filial"
            placeholder="Escribe el nombre de la filial"
          />
          {errors.nombre && <p className="text-red-500 text-sm mt-1">{errors.nombre}</p>}
        </div>

        {/* 📍 Dirección */}
        <div className="mt-5">
          <InputText
            value={direccion}
            onChange={(e) => setDireccion(e.target.value)}
            label="Dirección"
            placeholder="Escriba la dirección"
          />
          {errors.direccion && <p className="text-red-500 text-sm mt-1">{errors.direccion}</p>}
        </div>

        {/* ☎️ Teléfono */}
        <div className="mt-10">
          <InputText
            value={telefono}
            onChange={(e) => setTelefono(e.target.value)}
            label="Teléfono"
            placeholder="Escribe el teléfono"
          />
          {errors.telefono && <p className="text-red-500 text-sm mt-1">{errors.telefono}</p>}
        </div>

        {/* 📧 Email */}
        <div className="mt-10">
          <InputText
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            label="Email"
            placeholder="Escribe el email"
          />
          {errors.email && <p className="text-red-500 text-sm mt-1">{errors.email}</p>}
        </div>
      </div>

      {/* 🖼️ Foto */}
      <div className="mt-5">
        <InputFile
          file={fotoFile}
          onChange={(file) => {
            setFotoFile(file);
            setErrors((prev) => ({ ...prev, foto: "" }));
          }}
          initialUrl={foto}
          label="Foto de la filial"
        />
        {errors.foto && <p className="text-red-500 text-sm mt-1">{errors.foto}</p>}
      </div>

      {/* 🔘 Botón */}
      <div className="mt-5">
        <AddEditButton
          name={loading ? "Guardando..." : initialFilial ? "Actualizar" : "Crear"}
          disabled={loading}
        />
      </div>
    </form>
  );
}