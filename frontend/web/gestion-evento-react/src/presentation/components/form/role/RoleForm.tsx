import { useState } from "react";
import { Role } from "../../../../domain/entities/Role";
import { RoleService } from "../../../../application/services/RoleService";
import { RoleRepository } from "../../../../infrastructure/repositories/RoleRepository";
import InputText from "../input/InputText";
import InputFile from "../input/InputFile";
import AddEditButton from "../../actions/AddEditButton";

const roleRepository = new RoleRepository();
const roleService = new RoleService(roleRepository);

interface RoleFormProps {
  initialRole?: Role; // si se pasa, es edición
  onSuccess: () => void; // callback para redirigir o refrescar tabla
}

export default function RoleForm({ initialRole, onSuccess }: RoleFormProps) {
  const [nombre, setNombre] = useState(initialRole?.nombre || "");
  const [foto] = useState(initialRole?.foto || "");
  const [fotoFile, setFotoFile] = useState<File | null>(null);
  const [loading, setLoading] = useState(false);

  // ✅ Estado de errores
  const [errors, setErrors] = useState({
    nombre: "",
    foto: "",
  });

  // 📋 Validar campos antes de enviar
  const validate = () => {
    const newErrors = { nombre: "", foto: "" };
    let isValid = true;

    if (!nombre.trim()) {
      newErrors.nombre = "El nombre del rol es obligatorio.";
      isValid = false;
    } else if (nombre.length < 3) {
      newErrors.nombre = "El nombre debe tener al menos 3 caracteres.";
      isValid = false;
    }

    if (!fotoFile && !foto) {
      newErrors.foto = "Debe seleccionar una imagen para el rol.";
      isValid = false;
    }

    setErrors(newErrors);
    return isValid;
  };

  // 💾 Envío del formulario
  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    if (!validate()) return; // ❌ No continúa si hay errores

    setLoading(true);
    try {
      if (initialRole) {
        const role = new Role(initialRole.id, nombre, "");
        await roleService.updateRole(role, fotoFile || undefined);
      } else {
        const role = new Role(0, nombre, "");
        await roleService.createRole(role, fotoFile || undefined);
      }

      onSuccess(); // notificar éxito
    } catch (error) {
      console.error("Error al guardar el rol:", error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <form
      onSubmit={handleSubmit}
      className="space-y-4 p-6 border rounded-lg bg-white shadow-md dark:bg-gray-800"
    >
      {/* 🏷️ Nombre del rol */}
      <div className="mt-5">
        <InputText
          value={nombre}
          onChange={(e) => {
            setNombre(e.target.value);
            if (errors.nombre) setErrors((prev) => ({ ...prev, nombre: "" }));
          }}
          label="Nombre del Rol"
          placeholder="Escribe el nombre del rol"
        />
        {errors.nombre && (
          <p className="text-red-500 text-sm mt-1">{errors.nombre}</p>
        )}
      </div>

      {/* 🖼️ Foto */}
      <div className="mt-5">
        <InputFile
          file={fotoFile}
          onChange={(file) => {
            setFotoFile(file);
            if (errors.foto) setErrors((prev) => ({ ...prev, foto: "" }));
          }}
          initialUrl={foto}
          label="Foto del Rol"
        />
        {errors.foto && (
          <p className="text-red-500 text-sm mt-1">{errors.foto}</p>
        )}
      </div>

      {/* 🔘 Botón */}
      <AddEditButton
        name={loading ? "Guardando..." : initialRole ? "Actualizar" : "Crear"}
        disabled={loading}
      />
    </form>
  );
}