// domain/entities/Role.ts

export class Role {
  readonly id: number;
  private _nombre: string;
  private _foto?: string; // puede ser opcional

  constructor(id: number, nombre: string, foto?: string) {
    if (!nombre || nombre.trim().length === 0) {
      throw new Error("El nombre del rol no puede estar vacío");
    }

    this.id = id;
    this._nombre = nombre;
    this._foto = foto;
  }

  // Getters
  get nombre(): string {
    return this._nombre;
  }

  get foto(): string | undefined {
    return this._foto;
  }

  // Métodos de negocio
  cambiarNombre(nuevoNombre: string): void {
    if (!nuevoNombre || nuevoNombre.trim().length === 0) {
      throw new Error("El nombre del rol no puede estar vacío");
    }
    this._nombre = nuevoNombre;
  }

  actualizarFoto(url: string): void {
    if (!url.startsWith("http")) {
      throw new Error("La URL de la foto no es válida");
    }
    this._foto = url;
  }
}