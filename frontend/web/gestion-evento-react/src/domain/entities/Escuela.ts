// src/domain/entities/Escuela.ts
export class Escuela {
  readonly id: number;
  private _nombre: string;
  private _codigo: string;
  private _facultad_id: number; // ✅ FK
  private _foto?: string;

  constructor(
    id: number,
    nombre: string,
    codigo: string,
    facultad_id: number,
    foto?: string
  ) {
    if (!nombre.trim()) throw new Error("El nombre de la escuela no puede estar vacío");
    if (!codigo.trim()) throw new Error("El código de la escuela no puede estar vacío");

    this.id = id;
    this._nombre = nombre;
    this._codigo = codigo;
    this._facultad_id = facultad_id;
    this._foto = foto;
  }

  get nombre(): string {
    return this._nombre;
  }

  get codigo(): string {
    return this._codigo;
  }

  get facultad_id(): number {
    return this._facultad_id;
  }

  get foto(): string | undefined {
    return this._foto;
  }
}