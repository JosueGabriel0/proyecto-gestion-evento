export class Facultad {
  readonly id: number;
  private _nombre: string;
  private _codigo: string;
  private _foto?: string | null;
  private _filialId: number;

  constructor(
    id: number,
    nombre: string,
    codigo: string,
    foto: string | null,
    filialId: number
  ) {
    if (!nombre || nombre.trim().length === 0) {
      throw new Error("El nombre de la facultad no puede estar vacío");
    }

    this.id = id;
    this._nombre = nombre;
    this._codigo = codigo;
    this._foto = foto;
    this._filialId = filialId;
  }

  get nombre(): string {
    return this._nombre;
  }

  get codigo(): string {
    return this._codigo;
  }

  get foto(): string | null | undefined {
    return this._foto;
  }

  get filialId(): number {
    return this._filialId;
  }
}
