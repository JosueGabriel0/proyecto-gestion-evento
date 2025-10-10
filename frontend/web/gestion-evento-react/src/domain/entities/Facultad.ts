export class Facultad {
    readonly id: number;
    private _nombre: string;
    private _codigo:string;
    private _foto: string;
    private _filialId: number;

    constructor(id: number, nombre: string, codigo: string, foto: string, filialId: number){
        this.id = id;
        this._nombre = nombre;
        this._codigo = codigo;
        this._foto = foto;
        this._filialId = filialId;
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

    get filialId(): number {
        return(this._filialId);
    }
}