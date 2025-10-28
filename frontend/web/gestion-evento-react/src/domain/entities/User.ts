// Definimos interfaces para las relaciones
export interface Persona {
  nombres: string;
  apellidos: string;
  tipoDocumento: string;
  numeroDocumento: string;
  telefono?: string;
  direccion?: string;
  pais?: string;
  religion?: string;
  correoElectronico: string;
  correoInstitucional?: string;
  fotoPerfil?: string;
  fechaNacimiento: Date;
}

export interface Role {
  id: number;
  nombre: string;
}

export interface Alumno {
  codigoUniversitario: string;
}

export interface Jurado {
  especialidad: string;
}

export interface Ponente {
  biografia: string;
}

// Clase User equivalente
export class User {
  readonly id?: number;
  private email: string;
  private password: string;
  private escuelaId: number;
  private roleId?: number;
  private emailVerifiedAt?: Date;
  private rememberToken?: string;

  private persona?: Persona;
  private role?: Role;
  private alumno?: Alumno;
  private jurado?: Jurado;
  private ponente?: Ponente;

  private createdAt?: Date;
  private updatedAt?: Date;

  constructor(params: {
    id?: number;
    email: string;
    password: string;
    escuelaId: number;
    roleId?: number;
    emailVerifiedAt?: Date;
    rememberToken?: string;
    persona?: Persona;
    role?: Role;
    alumno?: Alumno;
    jurado?: Jurado;
    ponente?: Ponente;
    createdAt?: Date;
    updatedAt?: Date;
  }) {
    this.id = params.id;
    this.email = params.email;
    this.password = params.password;
    this.escuelaId = params.escuelaId;
    this.roleId = params.roleId;
    this.emailVerifiedAt = params.emailVerifiedAt;
    this.rememberToken = params.rememberToken;
    this.persona = params.persona;
    this.role = params.role;
    this.alumno = params.alumno;
    this.jurado = params.jurado;
    this.ponente = params.ponente;
    this.createdAt = params.createdAt;
    this.updatedAt = params.updatedAt;
  }

  // ======================
  // Getters y setters
  // ======================

  getId(): number | undefined {
    return this.id;
  }

  getEmail(): string {
    return this.email;
  }

  getPassword(): string {
    return this.password;
  }

  getEscuelaId(): number {
    return this.escuelaId;
  }

  getRoleId(): number | undefined {
    return this.roleId;
  }

  getEmailVerifiedAt(): Date | undefined {
    return this.emailVerifiedAt;
  }

  isVerified(): boolean {
    return !!this.emailVerifiedAt;
  }

  getRememberToken(): string | undefined {
    return this.rememberToken;
  }

  getRole(): Role | undefined {
    return this.role;
  }

  getPersona(): Persona | undefined {
    return this.persona;
  }

  getAlumno(): Alumno | undefined {
    return this.alumno;
  }

  getJurado(): Jurado | undefined {
    return this.jurado;
  }

  getPonente(): Ponente | undefined {
    return this.ponente;
  }

  hasRole(roleName: string): boolean {
    return this.role?.nombre === roleName;
  }

  getCreatedAt(): Date | undefined {
    return this.createdAt;
  }

  getUpdatedAt(): Date | undefined {
    return this.updatedAt;
  }

  // ======================
  // Convertir a objeto plano (similar a toArray)
  // ======================
  toObject(): any {
    return {
      id: this.id,
      email: this.email,
      escuelaId: this.escuelaId,
      roleId: this.roleId,
      emailVerifiedAt: this.emailVerifiedAt?.toISOString(),
      persona: this.persona,
      role: this.role,
      alumno: this.alumno,
      jurado: this.jurado,
      ponente: this.ponente,
      createdAt: this.createdAt?.toISOString(),
      updatedAt: this.updatedAt?.toISOString(),
    };
  }
}