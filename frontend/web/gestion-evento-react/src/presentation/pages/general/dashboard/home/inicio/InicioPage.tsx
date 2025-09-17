import InicioPageCSS from "./InicioPage.module.css"
import Spline from '@splinetool/react-spline';
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faBullseye } from "@fortawesome/free-solid-svg-icons";
import { faEye } from "@fortawesome/free-solid-svg-icons";

function InicioPage() {

    return (
        <div className={InicioPageCSS["container"]}>
            <Spline scene="https://prod.spline.design/zs09fv-21qL7okCU/scene.splinecode" className={InicioPageCSS["splineBackground"]} />

            <div className={InicioPageCSS["content"]}>
                <div className={InicioPageCSS["contenido-principal"]}>
                    <div className={InicioPageCSS["texto-principal"]}>
                        <h1>Bienvenido a al sistema. <br /> UPEU.</h1>
                    </div>
                    <div className={InicioPageCSS["mision"]}>
                        <div className={InicioPageCSS["mision-container"]}>
                            <div className={InicioPageCSS["mision-subtitulo"]}>
                                <h2>Misión</h2>
                                <div className={InicioPageCSS["primer-rectangulo"]}></div>
                            </div>
                            <div className={InicioPageCSS["mision-icono"]}>
                                <FontAwesomeIcon icon={faBullseye} style={{ color: "#4a8db7", }} size="2x" />
                            </div>
                        </div>
                        <p>Ser reconocidos por la Iglesia Adventista del Séptimo Día y la sociedad como líderes en el desarrollo de investigaciones científicas y tecnológicas
                            en todas las áreas de la ciencia sobre la base de valores cristianos, servicio y en armonía con el medio ambiente, para contribuir a la transformación
                            de una sociedad justa y equitativa.
                        </p>
                    </div>
                    <div className={InicioPageCSS["vision"]}>
                        <div className={InicioPageCSS["vision-container"]}>
                            <div className={InicioPageCSS["vision-subtitulo"]}>
                                <h2>Visión</h2>
                                <div className={InicioPageCSS["segundo-rectangulo"]}></div>
                            </div>
                            <div className={InicioPageCSS["vision-icono"]}>
                                <FontAwesomeIcon icon={faEye} style={{ color: "#4a8db7", }} size="2x" />
                            </div>
                        </div>
                        <p>Promover, gestionar y apollar el dessarrollo de investigadores capaces de generar conocimientos, en todas las áreas de las ciencias, desde una
                            consmovisión cristiana, preparando una comunidad de expertos y líderes comprometidos con la Iglesia Adventista del Séptimo Día y la sociedad.</p>
                    </div>
                </div>
                <div className={InicioPageCSS["contenido-secundario"]}>
                    <div className={InicioPageCSS["subtitulo-secundario"]}>
                        <div className={InicioPageCSS["cuadradito"]}></div>
                        <div className={InicioPageCSS["letras-secundarias"]}>
                            <h2 className={InicioPageCSS["h2-licenciamiento"]}>Licenciamiento y acreditación</h2>
                        </div>
                    </div>
                    <div className={InicioPageCSS["imagenes-secundarias"]}>
                        <div className={InicioPageCSS["contenedor-imagen-1"]}>
                            <img src="/images/top-10.png" alt="" />
                        </div>
                        <div className={InicioPageCSS["contenedor-imagen-2"]}>
                            <img src="/images/logo-sunedu.png" alt="" />
                        </div>
                        <div className={InicioPageCSS["contenedor-imagen-3"]}>
                            <img src="/images/logo-sineace.png" alt="" />
                        </div>
                        <div className={InicioPageCSS["contenedor-imagen-4"]}>
                            <img src="/images/AAA_logo_blanco.png" alt="" />
                        </div>
                        <div className={InicioPageCSS["contenedor-imagen-5"]}>
                            <img src="/images/logo2.png" alt="" />
                        </div>
                        <div className={InicioPageCSS["contenedor-imagen-6"]}>
                            <img src="/images/LOGO-EAD-02.png" alt="" />
                        </div>
                    </div>
                    <button className={InicioPageCSS["boton-libro-reclamaciones"]}>
                        <p>LIBRO DE RECLAMACIONES</p>
                    </button>
                </div>
            </div>
        </div>
    )
}

export default InicioPage;