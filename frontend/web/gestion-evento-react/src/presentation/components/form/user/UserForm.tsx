import { useState, useEffect } from "react";
import InputText from "../input/InputText";
import InputFile from "../input/InputFile";
import AddEditButton from "../../actions/AddEditButton";
import { User } from "../../../../domain/entities/User";
import { UserRepository } from "../../../../infrastructure/repositories/UserRepository";
import { UserService } from "../../../../application/services/UserService";
import type { Role } from "../../../../domain/entities/User";
import { Escuela } from "../../../../domain/entities/Escuela";
import { EscuelaRepository } from "../../../../infrastructure/repositories/EscuelaRepository";
import { EscuelaService } from "../../../../application/services/EscuelaService";
import { RoleRepository } from "../../../../infrastructure/repositories/RoleRepository";
import { RoleService } from "../../../../application/services/RoleService";

const userRepository = new UserRepository();
const userService = new UserService(userRepository);

const escuelaRepository = new EscuelaRepository();
const escuelaService = new EscuelaService(escuelaRepository);

const roleRepository = new RoleRepository();
const roleService = new RoleService(roleRepository);

interface UserFormProps {
  initialUser?: User;
  onSuccess: () => void;
}

export default function UserForm({ initialUser, onSuccess }: UserFormProps) {
  // ========== Datos básicos ==========
  const [email, setEmail] = useState(initialUser?.getEmail() || "");
  const [password, setPassword] = useState(initialUser?.getPassword() || "");
  const [roleName, setRoleName] = useState(initialUser?.getRole()?.nombre || "");
  const [roleId, setRoleId] = useState<number>(initialUser?.getRoleId() || 0);
  const [escuelaId, setEscuelaId] = useState<number>(initialUser?.getEscuelaId() || 0);

  // ========== Datos personales ==========
  const [nombres, setNombres] = useState(initialUser?.getPersona()?.nombres || "");
  const [apellidos, setApellidos] = useState(initialUser?.getPersona()?.apellidos || "");
  const [tipoDocumento, setTipoDocumento] = useState(initialUser?.getPersona()?.tipoDocumento || "");
  const [numeroDocumento, setNumeroDocumento] = useState(initialUser?.getPersona()?.numeroDocumento || "");
  const [telefono, setTelefono] = useState(initialUser?.getPersona()?.telefono || "");
  const [direccion, setDireccion] = useState(initialUser?.getPersona()?.direccion || "");
  const [pais, setPais] = useState(initialUser?.getPersona()?.pais || "");
  const [religion, setReligion] = useState(initialUser?.getPersona()?.religion || "");
  const [correoElectronico, setCorreoElectronico] = useState(initialUser?.getPersona()?.correoElectronico || "");
  const [correoInstitucional, setCorreoInstitucional] = useState(initialUser?.getPersona()?.correoInstitucional || "");
  const [fechaNacimiento, setFechaNacimiento] = useState(
    initialUser?.getPersona()?.fechaNacimiento
      ? new Date(initialUser.getPersona()!.fechaNacimiento).toISOString().split("T")[0]
      : ""
  );

  // ========== Archivo y estados de UI ==========
  const [fotoFile, setFotoFile] = useState<File | null>(null);
  const [loading, setLoading] = useState(false);
  const [openRoles, setOpenRoles] = useState(false);
  const [openEscuelas, setOpenEscuelas] = useState(false);

  // ========== Datos externos ==========
  const [roles, setRoles] = useState<Role[]>([]);
  const [escuelas, setEscuelas] = useState<Escuela[]>([]);

  useEffect(() => {
    // 🔹 Cargar roles
    roleService.getRoles().then(setRoles).catch(console.error);

    // 🔹 Cargar escuelas
    escuelaService.getEscuelas().then(setEscuelas).catch(console.error);
  }, []);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);

    try {
      const role = {
        id: roleId,
        nombre: roleName,
      };

      const persona = {
        nombres,
        apellidos,
        tipoDocumento,
        numeroDocumento,
        telefono,
        direccion,
        pais,
        religion,
        correoElectronico,
        correoInstitucional,
        fechaNacimiento: new Date(fechaNacimiento),
      };

      const user = new User({
        email,
        password,
        escuelaId,
        persona,
        role
      });

      if (initialUser) {
        await userService.updateUser(user, fotoFile || undefined);
        console.log("✅ Usuario actualizado");
      } else {
        await userService.createUser(user, fotoFile || undefined);
        console.log("✅ Usuario creado");
      }

      onSuccess();
      console.log("✅ onSuccess() ejecutado correctamente");
    } catch (error) {
      console.error("Error al guardar el usuario:", error);
    } finally {
      setLoading(false);
    }
  };

  // ========== Render ==========
  return (
    <form onSubmit={handleSubmit} className="space-y-4 p-6 border rounded-lg bg-white shadow-md dark:bg-gray-800">

      <InputText value={email} onChange={(e) => setEmail(e.target.value)} label="Correo" placeholder="usuario@correo.com" />
      <InputText value={password} onChange={(e) => setPassword(e.target.value)} label="Contraseña" type="password" />

      <InputText value={nombres} onChange={(e) => setNombres(e.target.value)} label="Nombres" />
      <InputText value={apellidos} onChange={(e) => setApellidos(e.target.value)} label="Apellidos" />

      <div className="grid grid-cols-2 gap-3">
        <InputText value={tipoDocumento} onChange={(e) => setTipoDocumento(e.target.value)} label="Tipo de documento" />
        <InputText value={numeroDocumento} onChange={(e) => setNumeroDocumento(e.target.value)} label="Número de documento" />
      </div>

      <InputText value={telefono} onChange={(e) => setTelefono(e.target.value)} label="Teléfono" />
      <InputText value={direccion} onChange={(e) => setDireccion(e.target.value)} label="Dirección" />
      <InputText value={pais} onChange={(e) => setPais(e.target.value)} label="País" />
      <InputText value={religion} onChange={(e) => setReligion(e.target.value)} label="Religión" />

      <div className="grid grid-cols-2 gap-3">
        <InputText
          value={correoElectronico}
          onChange={(e) => setCorreoElectronico(e.target.value)}
          label="Correo personal"
        />
        <InputText
          value={correoInstitucional}
          onChange={(e) => setCorreoInstitucional(e.target.value)}
          label="Correo institucional"
        />
      </div>

      <InputText
        type="date"
        value={fechaNacimiento}
        onChange={(e) => setFechaNacimiento(e.target.value)}
        label="Fecha de nacimiento"
      />

      {/* Selección de Escuela */}
      <div className="mt-5">
        <span className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Selecciona una Escuela
        </span>
        <div className="border rounded-lg overflow-hidden">
          <button
            type="button"
            onClick={() => setOpenEscuelas(!openEscuelas)}
            className="w-full flex justify-between items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600"
          >
            {escuelaId
              ? escuelas.find((e) => e.id === escuelaId)?.nombre || "Selecciona una escuela"
              : "Selecciona una escuela"}
            <span className="ml-2 text-gray-400">{openEscuelas ? "▲" : "▼"}</span>
          </button>
          {openEscuelas && (
            <div className="max-h-60 overflow-y-auto bg-white dark:bg-gray-800 border-t">
              {escuelas.map((escuela) => (
                <div
                  key={escuela.id}
                  onClick={() => {
                    setEscuelaId(escuela.id);
                    setOpenEscuelas(false);
                  }}
                  className={`px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 ${escuelaId === escuela.id ? "bg-gray-200 dark:bg-gray-600 font-semibold" : ""
                    }`}
                >
                  {escuela.nombre}
                </div>
              ))}
            </div>
          )}
        </div>
      </div>

      {/* Selección de Rol */}
      <div className="mt-5">
        <span className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Selecciona un Rol
        </span>
        <div className="border rounded-lg overflow-hidden">
          <button
            type="button"
            onClick={() => setOpenRoles(!openRoles)}
            className="w-full flex justify-between items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600"
          >
            {roleId
              ? roles.find((r) => r.id === roleId)?.nombre || "Selecciona un rol"
              : "Selecciona un rol"}
            <span className="ml-2 text-gray-400">{openRoles ? "▲" : "▼"}</span>
          </button>
          {openRoles && (
            <div className="max-h-60 overflow-y-auto bg-white dark:bg-gray-800 border-t">
              {roles.map((role) => (
                <div
                  key={role.id}
                  onClick={() => {
                    setRoleId(role.id);
                    setRoleName(role.nombre); // ✅ Guarda también el nombre del rol
                    setOpenRoles(false);
                  }}
                  className={`px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 ${roleId === role.id ? "bg-gray-200 dark:bg-gray-600 font-semibold" : ""
                    }`}
                >
                  {role.nombre}
                </div>
              ))}
            </div>
          )}
        </div>
      </div>

      {/* Foto */}
      <InputFile file={fotoFile} onChange={(file) => setFotoFile(file)} label="Foto de perfil" />

      <AddEditButton
        name={loading ? "Guardando..." : initialUser ? "Actualizar" : "Crear"}
        disabled={loading}
      />
    </form>
  );
}