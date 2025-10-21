export class Escuela {
    readonly id: number;
    private _nombre: string;
    private _codigo:string;
    private _foto: string;
    private _facultadId: number;

    constructor(id: number, nombre: string, codigo: string, foto: string, facultadId: number){
        this.id = id;
        this._nombre = nombre;
        this._codigo = codigo;
        this._foto = foto;
        this._facultadId = facultadId;
    }

    get nombre(): string {
        return(this._nombre);
    }

    get codigo(): string {
        return(this._codigo);
    }

    get foto(): string {
        return(this._foto);
    }

    get facultadId(): number {
        return(this._facultadId);
    }
}