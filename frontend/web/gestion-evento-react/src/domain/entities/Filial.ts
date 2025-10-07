export class Filial {
  readonly id: number;
  private _nombre: string;
  private _direccion?: string;
  private _telefono?: string;
  private _email?: string;
  private _foto?: string;

  constructor(
    id: number,
    nombre: string,
    direccion?: string,
    telefono?: string,
    email?: string,
    foto?: string
  ) {
    if (!nombre || nombre.trim().length === 0) {
      throw new Error("El nombre de la filial no puede estar vacío");
    }

    this.id = id;
    this._nombre = nombre;
    this._direccion = direccion;
    this._telefono = telefono;
    this._email = email;
    this._foto = foto;
  }

  get nombre(): string {
    return this._nombre;
  }

  get direccion(): string | undefined {
    return this._direccion;
  }

  get telefono(): string | undefined {
    return this._telefono;
  }

  get email(): string | undefined {
    return this._email;
  }

  get foto(): string | undefined {
    return this._foto;
  }
}