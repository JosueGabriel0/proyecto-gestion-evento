import { useState, useEffect } from "react";
import InputText from "../input/InputText";
import InputFile from "../input/InputFile";
import AddEditButton from "../../actions/AddEditButton";
import { User } from "../../../../domain/entities/User";
import { UserRepository } from "../../../../infrastructure/repositories/UserRepository";
import { UserService } from "../../../../application/services/UserService";
import type { Role } from "../../../../domain/entities/Role";
import { Escuela } from "../../../../domain/entities/Escuela";
import { EscuelaRepository } from "../../../../infrastructure/repositories/EscuelaRepository";
import { EscuelaService } from "../../../../application/services/EscuelaService";
import { RoleRepository } from "../../../../infrastructure/repositories/RoleRepository";
import { RoleService } from "../../../../application/services/RoleService";
import InputDate from "../input/InputDate";
import { FilialRepository } from "../../../../infrastructure/repositories/FilialRepository";
import { FilialService } from "../../../../application/services/FilialService";
import { FacultadRepository } from "../../../../infrastructure/repositories/FacultadRepository";
import { FacultadService } from "../../../../application/services/FacultadService";
import type { Filial } from "../../../../domain/entities/Filial";
import type { Facultad } from "../../../../domain/entities/Facultad";
import Accordeon from "../../actions/Accordeon";

const userRepository = new UserRepository();
const userService = new UserService(userRepository);

const filialRepository = new FilialRepository();
const filialService = new FilialService(filialRepository);

const facultadRepository = new FacultadRepository();
const facultadService = new FacultadService(facultadRepository);

const escuelaRepository = new EscuelaRepository();
const escuelaService = new EscuelaService(escuelaRepository);

const roleRepository = new RoleRepository();
const roleService = new RoleService(roleRepository);

interface UserFormProps {
  initialUser?: User;
  onSuccess: () => void;
}

export default function UserForm({ initialUser, onSuccess }: UserFormProps) {
  const [email, setEmail] = useState(initialUser?.getEmail() || "");
  const [password, setPassword] = useState(initialUser?.getPassword() || "");
  const [roleName, setRoleName] = useState(initialUser?.getRole()?.nombre || "");
  const [roleId, setRoleId] = useState<number>(initialUser?.getRoleId() || 0);
  const [escuelaId, setEscuelaId] = useState<number>(initialUser?.getEscuelaId() || 0);
  const [filialId, setFilialId] = useState<number | null>(null);
  const [facultadId, setFacultadId] = useState<number | null>(null);

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

  const [codigoUniversitario, setCodigoUniversitario] = useState(initialUser?.getAlumno()?.codigoUniversitario || "");
  const [especialidad, setEspecialidad] = useState(initialUser?.getJurado()?.especialidad || "");
  const [biografia, setBiografia] = useState(initialUser?.getPonente()?.biografia || "");

  const [fotoFile, setFotoFile] = useState<File | null>(null);
  const [foto] = useState(initialUser?.getPersona()?.fotoPerfil || "");
  const [loading, setLoading] = useState(false);
  const [openRoles, setOpenRoles] = useState(false);
  const [openEscuelas, setOpenEscuelas] = useState(false);
  const [openFiliales, setOpenFiliales] = useState(false);
  const [openFacultades, setOpenFacultades] = useState(false);

  const [filiales, setFiliales] = useState<Filial[]>([]);
  const [facultades, setFacultades] = useState<Facultad[]>([]);
  const [roles, setRoles] = useState<Role[]>([]);
  const [escuelas, setEscuelas] = useState<Escuela[]>([]);
  const [errors, setErrors] = useState<Record<string, string>>({});

  const tiposDocumento = [
    "DNI",
    "Pasaporte",
    "Carnet de Extranjería",
    "Partida de Nacimiento",
    "Otro"
  ];

  const paises = [
    "Perú",
    "Argentina",
    "Bolivia",
    "Brasil",
    "Chile",
    "Colombia",
    "Ecuador",
    "Paraguay",
    "Uruguay",
    "Venezuela",
    "México",
    "Guatemala",
    "Honduras",
    "El Salvador",
    "Nicaragua",
    "Costa Rica",
    "Panamá",
    "Cuba",
    "República Dominicana",
    "Puerto Rico",
    "España",
    "Portugal",
    "Estados Unidos",
    "Canadá",
    "Italia",
    "Francia",
    "Alemania",
    "Reino Unido",
    "Suiza",
    "Suecia",
    "Noruega",
    "Dinamarca",
    "Países Bajos",
    "Bélgica",
    "Austria",
    "Finlandia",
    "Polonia",
    "Hungría",
    "Rusia",
    "China",
    "Japón",
    "Corea del Sur",
    "India",
    "Filipinas",
    "Tailandia",
    "Vietnam",
    "Indonesia",
    "Australia",
    "Nueva Zelanda",
    "Sudáfrica",
    "Egipto",
    "Marruecos",
    "Nigeria",
    "Kenia",
    "Arabia Saudita",
    "Emiratos Árabes Unidos",
    "Turquía",
    "Israel"
  ];

  useEffect(() => {
    filialService.getFiliales().then(setFiliales).catch(console.error);
    roleService.getRoles().then(setRoles).catch(console.error);
  }, []);

  // Cuando cambia la filial seleccionada, cargar sus facultades
  useEffect(() => {
    if (filialId) {
      facultadService
        .getFacultadesByFilial(filialId)
        .then((data) => setFacultades(data))
        .catch((error) => console.error("Error al cargar facultades:", error));
    } else {
      setFacultades([]); // Limpia si no hay filial seleccionada
    }
  }, [filialId]);

  useEffect(() => {
    if (facultadId) {
      escuelaService
        .getEscuelasByFacultad(facultadId)
        .then((data) => setEscuelas(data))
        .catch((error) => console.error("Error al cargar escuelas:", error));
    } else {
      setEscuelas([]); // Limpia si no hay filial seleccionada
    }
  }, [facultadId]);

  const validate = () => {
    const newErrors: Record<string, string> = {};
    let valid = true;

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const docRegex = /^[0-9A-Za-z-]+$/;
    const phoneRegex = /^[0-9+\s-]{6,}$/;

    if (!email || !emailRegex.test(email)) {
      newErrors.email = "Ingrese un correo electrónico válido.";
      valid = false;
    }

    if (!initialUser && (!password || password.length < 6)) {
      newErrors.password = "La contraseña debe tener al menos 6 caracteres.";
      valid = false;
    }

    if (!nombres.trim()) {
      newErrors.nombres = "Los nombres son obligatorios.";
      valid = false;
    }

    if (!apellidos.trim()) {
      newErrors.apellidos = "Los apellidos son obligatorios.";
      valid = false;
    }

    if (!tipoDocumento.trim()) {
      newErrors.tipoDocumento = "El tipo de documento es obligatorio.";
      valid = false;
    }

    if (!numeroDocumento.trim() || !docRegex.test(numeroDocumento)) {
      newErrors.numeroDocumento = "Ingrese un número de documento válido.";
      valid = false;
    }

    if (telefono && !phoneRegex.test(telefono)) {
      newErrors.telefono = "Ingrese un número de teléfono válido.";
      valid = false;
    }

    if (!escuelaId) {
      newErrors.escuelaId = "Debe seleccionar una escuela.";
      valid = false;
    }

    if (!roleId) {
      newErrors.roleId = "Debe seleccionar un rol.";
      valid = false;
    }

    if (roleName === "ROLE_ALUMNO" && !codigoUniversitario.trim()) {
      newErrors.codigoUniversitario = "El código universitario es obligatorio.";
      valid = false;
    }

    if (roleName === "ROLE_JURADO" && !especialidad.trim()) {
      newErrors.especialidad = "La especialidad es obligatoria.";
      valid = false;
    }

    if (roleName === "ROLE_PONENTE" && !biografia.trim()) {
      newErrors.biografia = "La biografía es obligatoria.";
      valid = false;
    }

    if (!fotoFile && !foto) {
      newErrors.foto = "Debe seleccionar una foto de perfil.";
      valid = false;
    }

    setErrors(newErrors);
    return valid;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    if (!validate()) return;

    setLoading(true);
    try {
      const role = { id: roleId, nombre: roleName };
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

      // 🔹 Estructura base del usuario
      const userData: any = {
        id: initialUser?.getId(),
        email,
        password,
        escuelaId,
        persona,
        role,
      };

      // 🔹 Campos dinámicos según rol
      if (roleName === "ROLE_ALUMNO") {
        userData.alumno = { codigoUniversitario };
      } else if (roleName === "ROLE_JURADO") {
        userData.jurado = { especialidad };
      } else if (roleName === "ROLE_PONENTE") {
        userData.ponente = { biografia };
      }

      const user = new User(userData);

      // ✅ Guardar o actualizar
      if (initialUser) {
        await userService.updateUser(user, fotoFile || undefined);
      } else {
        await userService.createUser(user, fotoFile || undefined);
      }

      onSuccess(); // refresca tabla o redirige
    } catch (error) {
      console.error("Error al guardar el usuario:", error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="p-6 border rounded-lg bg-white shadow-md dark:bg-gray-800">
      {/* ========== Datos básicos ========== */}
      <div className="grid grid-cols-2 gap-4 mt-5">
        <InputText
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          label="Correo"
          placeholder="usuario@correo.com"
        />
        <InputText
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          label="Contraseña"
          type="password"
        />
      </div>
      {(errors.email || errors.password) && (
        <div className="grid grid-cols-2 gap-4 text-red-500 text-sm">
          <p>{errors.email}</p>
          <p>{errors.password}</p>
        </div>
      )}

      {/* ========== Datos personales ========== */}
      <div className="grid grid-cols-2 gap-4 mt-10">
        <InputText value={nombres} onChange={(e) => setNombres(e.target.value)} label="Nombres" />
        <InputText value={apellidos} onChange={(e) => setApellidos(e.target.value)} label="Apellidos" />
      </div>

      <div className="grid grid-cols-2 gap-4 mt-10">
        <Accordeon
          label="Tipo de documento"
          options={tiposDocumento}
          value={tipoDocumento}
          onChange={setTipoDocumento}
        />
        <InputText value={numeroDocumento} onChange={(e) => setNumeroDocumento(e.target.value)} label="Número de documento" />
      </div>

      <div className="grid grid-cols-2 gap-4 mt-10">
        <InputText value={telefono} onChange={(e) => setTelefono(e.target.value)} label="Teléfono" />
        <InputText value={direccion} onChange={(e) => setDireccion(e.target.value)} label="Dirección" />
      </div>

      <div className="grid grid-cols-2 gap-4 mt-10">
        <Accordeon
          label="País"
          options={paises}
          value={pais}
          onChange={setPais}
        />
        <InputText value={religion} onChange={(e) => setReligion(e.target.value)} label="Religión" />
      </div>

      <div className="grid grid-cols-2 gap-4 mt-10">
        <InputText value={correoElectronico} onChange={(e) => setCorreoElectronico(e.target.value)} label="Correo personal" />
        <InputText value={correoInstitucional} onChange={(e) => setCorreoInstitucional(e.target.value)} label="Correo institucional" />
      </div>

      <div className="grid grid-cols-2 gap-4 mt-10">
        <InputDate
          value={fechaNacimiento}
          onChange={(e) => setFechaNacimiento(e.target.value)}
          label="Fecha de nacimiento"
        />
      </div>

      <div className="mt-5">
        <span className="inline-block bg-black text-white text-sm font-medium px-3 py-1 rounded-md border border-gray-400 text-center mb-1">
          Selecciona una Filial
        </span>
        <div className="border rounded-lg overflow-hidden">
          <button
            type="button"
            onClick={() => setOpenFiliales(!openFiliales)}
            className="w-full flex justify-between items-center px-4 py-2 
              bg-black text-white 
              hover:bg-gray-900 
              dark:bg-black dark:text-white 
              dark:hover:bg-gray-950 
              font-medium rounded-md transition-colors duration-200"
          >
            {filialId
              ? filiales.find((e) => e.id === filialId)?.nombre || "Selecciona una filial"
              : "Selecciona una filial"}
            <span className="ml-2 text-gray-400">{openFiliales ? "▲" : "▼"}</span>
          </button>
          {openFiliales && (
            <div className="max-h-60 overflow-y-auto bg-white dark:bg-gray-800 border-t dark:border-gray-700">
              {filiales.map((filial) => (
                <div
                  key={filial.id}
                  onClick={() => {
                    setFilialId(filial.id);
                    setOpenFiliales(false);
                  }}
                  className={`px-4 py-2 cursor-pointer 
                      text-gray-800 dark:text-gray-100   /* 👈 color adaptable claro/oscuro */
                      hover:bg-gray-100 dark:hover:bg-gray-700 
                      ${filialId === filial.id ? "bg-gray-200 dark:bg-gray-600 font-semibold" : ""}`}
                >
                  {filial.nombre}
                </div>
              ))}
            </div>
          )}
        </div>
        {errors.filialId && <p className="text-red-500 text-sm mt-1">{errors.filialId}</p>}
      </div>

      <div className="mt-5">
        <span className="inline-block bg-black text-white text-sm font-medium px-3 py-1 rounded-md border border-gray-400 text-center mb-1">
          Selecciona una Facultad
        </span>
        <div className="border rounded-lg overflow-hidden">
          <button
            type="button"
            disabled={!filialId} // 🔒 Deshabilita si no hay filial seleccionada
            onClick={() => filialId && setOpenFacultades(!openFacultades)} // 👈 evita abrir si no hay filial
            className={`w-full flex justify-between items-center px-4 py-2 
        ${!filialId
                ? "bg-gray-300 text-gray-500 cursor-not-allowed dark:bg-gray-700 dark:text-gray-400" // estilo bloqueado
                : "bg-black text-white hover:bg-gray-900 dark:bg-black dark:text-white dark:hover:bg-gray-950"} 
        font-medium rounded-md transition-colors duration-200`}
          >
            {filialId ?? 0 > 0
              ? facultadId ?? 0 > 0
                ? facultades.find((e) => e.id === facultadId)?.nombre || "Selecciona una facultad"
                : "Selecciona una facultad"
              : "Selecciona primero una filial"} {/* 👈 Mensaje guía */}
            <span className="ml-2 text-gray-400">{openFacultades ? "▲" : "▼"}</span>
          </button>

          {/* 🔽 Solo muestra las facultades si hay filial seleccionada */}
          {filialId && openFacultades && (
            <div className="max-h-60 overflow-y-auto bg-white dark:bg-gray-800 border-t dark:border-gray-700">
              {facultades.map((facultad) => (
                <div
                  key={facultad.id}
                  onClick={() => {
                    setFacultadId(facultad.id);
                    setOpenFacultades(false);
                  }}
                  className={`px-4 py-2 cursor-pointer 
                text-gray-800 dark:text-gray-100
                hover:bg-gray-100 dark:hover:bg-gray-700 
                ${facultadId === facultad.id ? "bg-gray-200 dark:bg-gray-600 font-semibold" : ""}`}
                >
                  {facultad.nombre}
                </div>
              ))}
            </div>
          )}
        </div>

        {errors.facultadId && <p className="text-red-500 text-sm mt-1">{errors.facultadId}</p>}
      </div>

      <div className="mt-5">
        <span className="inline-block bg-black text-white text-sm font-medium px-3 py-1 rounded-md border border-gray-400 text-center mb-1">
          Selecciona una Escuela
        </span>
        <div className="border rounded-lg overflow-hidden">
          <button
            type="button"
            disabled={!facultadId} // 🔒 Deshabilita si no hay filial seleccionada
            onClick={() => facultadId && setOpenEscuelas(!openEscuelas)} // 👈 evita abrir si no hay filial
            className={`w-full flex justify-between items-center px-4 py-2 
        ${!facultadId
                ? "bg-gray-300 text-gray-500 cursor-not-allowed dark:bg-gray-700 dark:text-gray-400" // estilo bloqueado
                : "bg-black text-white hover:bg-gray-900 dark:bg-black dark:text-white dark:hover:bg-gray-950"} 
        font-medium rounded-md transition-colors duration-200`}
          >
            {facultadId ?? 0 > 0
              ? escuelaId ?? 0 > 0
                ? escuelas.find((e) => e.id === escuelaId)?.nombre || "Selecciona una escuela"
                : "Selecciona una escuela"
              : "Selecciona primero una facultad"} {/* 👈 Mensaje guía */}
            <span className="ml-2 text-gray-400">{openEscuelas ? "▲" : "▼"}</span>
          </button>

          {/* 🔽 Solo muestra las facultades si hay filial seleccionada */}
          {facultadId && openEscuelas && (
            <div className="max-h-60 overflow-y-auto bg-white dark:bg-gray-800 border-t dark:border-gray-700">
              {escuelas.map((escuela) => (
                <div
                  key={escuela.id}
                  onClick={() => {
                    setEscuelaId(escuela.id);
                    setOpenEscuelas(false);
                  }}
                  className={`px-4 py-2 cursor-pointer 
                text-gray-800 dark:text-gray-100
                hover:bg-gray-100 dark:hover:bg-gray-700 
                ${escuelaId === escuela.id ? "bg-gray-200 dark:bg-gray-600 font-semibold" : ""}`}
                >
                  {escuela.nombre}
                </div>
              ))}
            </div>
          )}
        </div>

        {errors.escuelaId && <p className="text-red-500 text-sm mt-1">{errors.escuelaId}</p>}
      </div>

      {/* ========== Selección de Rol ========== */}
      <div className="mt-5">
        <span className="inline-block bg-black text-white text-sm font-medium px-3 py-1 rounded-md border border-gray-400 text-center mb-1">
          Selecciona un Rol
        </span>
        <div className="border rounded-lg overflow-hidden">
          <button
            type="button"
            onClick={() => setOpenRoles(!openRoles)}
            className="w-full flex justify-between items-center px-4 py-2 
              bg-black text-white 
              hover:bg-gray-900 
              dark:bg-black dark:text-white 
              dark:hover:bg-gray-950 
              font-medium rounded-md transition-colors duration-200"
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
                    setRoleName(role.nombre);
                    setOpenRoles(false);
                  }}
                  className={`px-4 py-2 cursor-pointer 
                      text-gray-800 dark:text-gray-100   /* 👈 color adaptable claro/oscuro */
                      hover:bg-gray-100 dark:hover:bg-gray-700 
                      ${roleId === role.id ? "bg-gray-200 dark:bg-gray-600 font-semibold" : ""}`}
                >
                  {role.nombre}
                </div>
              ))}
            </div>
          )}
        </div>
        {errors.roleId && <p className="text-red-500 text-sm mt-1">{errors.roleId}</p>}
      </div>

      {/* === Inputs dinámicos según el rol seleccionado === */}
      {roleName === "ROLE_ALUMNO" && (
        <div className="mt-10">
          <InputText
            value={codigoUniversitario}
            onChange={(e) => setCodigoUniversitario(e.target.value)}
            label="Código Universitario"
            placeholder="Ej. 202312345"
          />
        </div>
      )}

      {roleName === "ROLE_JURADO" && (
        <div className="mt-10">
          <InputText
            value={especialidad}
            onChange={(e) => setEspecialidad(e.target.value)}
            label="Especialidad"
            placeholder="Ej. Inteligencia Artificial"
          />
        </div>
      )}

      {roleName === "ROLE_PONENTE" && (
        <div className="mt-10">
          <InputText
            value={biografia}
            onChange={(e) => setBiografia(e.target.value)}
            label="Biografía"
            placeholder="Describe tu experiencia profesional..."
          />
        </div>
      )}

      {/* ========== Foto ========== */}
      <div className="mt-5 mb-5">
        <InputFile file={fotoFile} onChange={(file) => setFotoFile(file)} label="Foto de perfil" initialUrl={foto} />
        {errors.foto && <p className="text-red-500 text-sm mt-1">{errors.foto}</p>}
      </div>

      <AddEditButton
        name={loading ? "Guardando..." : initialUser ? "Actualizar" : "Crear"}
        disabled={loading}
      />
    </form>
  );
}