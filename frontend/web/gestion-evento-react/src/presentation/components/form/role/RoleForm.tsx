import { useState } from "react";
import { Role } from "../../../../domain/entities/Role";
import { RoleService } from "../../../../application/services/RoleService";
import { RoleRepository } from "../../../../infrastructure/repositories/RoleRepository";
import InputText from "../input/InputText";
import InputFile from "../input/InputFile";
import ElectricBorder from "../../actions/ElectricBorder";
import AddEditButton from "../../actions/AddEditButton";

const roleRepository = new RoleRepository();
const roleService = new RoleService(roleRepository);

interface RoleFormProps {
  initialRole?: Role; // si se pasa, es edición
  onSuccess: () => void; // callback para redirigir o refrescar tabla
}

export default function RoleForm({ initialRole, onSuccess }: RoleFormProps) {
  const [nombre, setNombre] = useState(initialRole?.nombre || "");
  const [foto, setFoto] = useState(initialRole?.foto || "");
  const [loading, setLoading] = useState(false);
  const [fotoFile, setFotoFile] = useState<File | null>(null);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
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
      <div className="mt-5">
        <InputText
          value={nombre}
          onChange={(e) => setNombre(e.target.value)}
          label="Nombre del Rol"
          placeholder="Escribe el nombre del rol"
        />
      </div>

      <div>
        <InputFile
          file={fotoFile}
          onChange={(file) => setFotoFile(file)}
          label="Foto del Rol"
        />
      </div>
        <AddEditButton
          name={loading ? "Guardando..." : initialRole ? "Actualizar" : "Crear"}
          onClick={() => { }}
          disabled={loading}
        />
    </form>
  );
}