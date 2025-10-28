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
  initialFilial?: Filial; // si se pasa, es edición
  onSuccess: () => void; // callback para redirigir o refrescar tabla
}

export default function FilialForm({ initialFilial, onSuccess }: FilialFormProps) {
  const [nombre, setNombre] = useState(initialFilial?.nombre || "");
  const [direccion, setDireccion] = useState(initialFilial?.direccion || "");
  const [telefono, setTelefono] = useState(initialFilial?.telefono || "");
  const [email, setEmail] = useState(initialFilial?.email || "");
  const [foto, setFoto] = useState(initialFilial?.foto || "");
  const [loading, setLoading] = useState(false);
  const [fotoFile, setFotoFile] = useState<File | null>(null);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);

    try {
      if (initialFilial) {
        const filial = new Filial(initialFilial.id, nombre, direccion, telefono, email, "");
        await filialService.updateFilial(filial, fotoFile || undefined);
      } else {
        const filial = new Filial(0, nombre, direccion, telefono, email, "");
        await filialService.createFilial(filial, fotoFile || undefined);
      }
      onSuccess(); // notificar éxito
    } catch (error) {
      console.error("Error al guardar la filial:", error);
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
          label="Nombre de la filial"
          placeholder="Escribe el nombre de la filial"
        />
      </div>
      <div className="mt-10">  <InputText
        value={direccion}
        onChange={(e) => setDireccion(e.target.value)}
        label="Direccion"
        placeholder="Escriba la direccion"
      />
      </div>
      <div className="mt-10"><InputText
        value={telefono}
        onChange={(e) => setTelefono(e.target.value)}
        label="Telefono"
        placeholder="Escribe el telefono"
      />
      </div>
      <div className="mt-10"><InputText
        value={email}
        onChange={(e) => setEmail(e.target.value)}
        label="Email"
        placeholder="Escribe el email"
      /></div>

      <div>
        <InputFile
          file={fotoFile}
          onChange={(file) => setFotoFile(file)}
          label="Foto de la filial"
        />
      </div>
      <AddEditButton
        name={loading ? "Guardando..." : initialFilial ? "Actualizar" : "Crear"}
        disabled={loading}
      />
    </form>
  );
}