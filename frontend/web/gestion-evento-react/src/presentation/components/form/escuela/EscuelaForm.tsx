import { useState } from "react";
import InputText from "../input/InputText";
import InputFile from "../input/InputFile";
import AddEditButton from "../../actions/AddEditButton";
import { Escuela } from "../../../../domain/entities/Escuela";
import { EscuelaRepository } from "../../../../infrastructure/repositories/EscuelaRepository";
import { EscuelaService } from "../../../../application/services/EscuelaService";

const escuelaRepository = new EscuelaRepository();
const escuelaService = new EscuelaService(escuelaRepository);

interface EscuelaFormProps {
  initialEscuela?: Escuela; // Si se pasa, es edición
  onSuccess: () => void; // Callback para redirigir o refrescar tabla
}

export default function EscuelaForm({ initialEscuela, onSuccess }: EscuelaFormProps) {
  const [nombre, setNombre] = useState(initialEscuela?.nombre || "");
  const [codigo, setCodigo] = useState(initialEscuela?.codigo || "");
  const [facultadId, setFacultadId] = useState(initialEscuela?.facultad_id || 1); // Valor fijo por ahora
  const [fotoFile, setFotoFile] = useState<File | null>(null);
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);

    try {
      const escuela = new Escuela(
        initialEscuela?.id ?? 0,
        nombre,
        codigo,
        facultadId,
        undefined
      );

      if (initialEscuela) {
        await escuelaService.updateEscuela(escuela, fotoFile || undefined);
      } else {
        await escuelaService.createEscuela(escuela, fotoFile || undefined);
      }

      onSuccess();
    } catch (error) {
      console.error("Error al guardar la escuela:", error);
      alert("Ocurrió un error al guardar la escuela.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <form
      onSubmit={handleSubmit}
      className="space-y-6 p-6 border rounded-lg bg-white shadow-md dark:bg-gray-800"
    >
      {/* Nombre */}
      <InputText
        value={nombre}
        onChange={(e) => setNombre(e.target.value)}
        label="Nombre de la escuela"
        placeholder="Escribe el nombre de la escuela"
      />

      {/* Código */}
      <InputText
        value={codigo}
        onChange={(e) => setCodigo(e.target.value)}
        label="Código"
        placeholder="Ejemplo: ESC-001"
      />

      {/* Facultad ID (temporal) */}
        <InputText
        value={facultadId?.toString() ?? ""}
        onChange={(e) => setFacultadId(Number(e.target.value))}
        label="Facultad ID (temporal)"
        placeholder="Ejemplo: 1"
        />

      {/* Foto */}
      <InputFile
        file={fotoFile}
        onChange={(file) => setFotoFile(file)}
        label="Foto de la escuela"
      />

      <AddEditButton
        name={loading ? "Guardando..." : initialEscuela ? "Actualizar" : "Crear"}
        onClick={() => {}}
        disabled={loading}
      />
    </form>
  );
}
