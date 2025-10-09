import InicioPageCSS from "./InicioPage.module.css"
import Spline from '@splinetool/react-spline';
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faBullseye } from "@fortawesome/free-solid-svg-icons";
import { faEye } from "@fortawesome/free-solid-svg-icons";
import SplitText from "../../../../../components/text/SplitText";
import TextType from "../../../../../components/text/TextType";

function InicioPage() {

    const handleAnimationComplete = () => {
        console.log('All letters have animated!');
    };

    return (
        <div className={InicioPageCSS["container"]}>
            <Spline scene="https://prod.spline.design/zs09fv-21qL7okCU/scene.splinecode" className={InicioPageCSS["splineBackground"]} />

            <div className={InicioPageCSS["content"]}>
                <div className={InicioPageCSS["contenido-principal"]}>
                    <SplitText
                        text="Bienvenido al sistema de gestion de eventos UPeU"
                        className="text-5xl font-semibold text-blue-500 text-center mb-10"
                        delay={30}
                        duration={0.6}
                        ease="power3.out"
                        splitType="chars"
                        from={{ opacity: 0, y: 40 }}
                        to={{ opacity: 1, y: 0 }}
                        threshold={0.1}
                        rootMargin="-100px"
                        textAlign="start"
                        onLetterAnimationComplete={handleAnimationComplete}
                    />
                    <div className={InicioPageCSS["mision"]}>
                        <div className={InicioPageCSS["mision-container"]}>
                            <div className={InicioPageCSS["mision-subtitulo"]}>
                                <SplitText
                                    text="Misión"
                                    className="!text-3xl !text-blue-900 !font-semibold !text-start"
                                    delay={50}
                                    duration={0.6}
                                    ease="power3.out"
                                    splitType="chars"
                                    from={{ opacity: 0, y: 40 }}
                                    to={{ opacity: 1, y: 0 }}
                                    threshold={0.1}
                                    rootMargin="-100px"
                                    textAlign="start"
                                    onLetterAnimationComplete={handleAnimationComplete}
                                />
                                <div className={InicioPageCSS["primer-rectangulo"]}></div>
                            </div>
                            <div className={InicioPageCSS["mision-icono"]}>
                                <FontAwesomeIcon icon={faBullseye} style={{ color: "#4a8db7", }} size="2x" />
                            </div>
                        </div>
                        <TextType
                            className="!text-xl !text-justify bg-blue-400 text-white p-4 rounded-lg mt-2"
                            text={
                                "Ser reconocidos por la Iglesia Adventista del Séptimo Día y la sociedad como líderes en el desarrollo de investigaciones científicas y tecnológicas en todas las áreas de la ciencia sobre la base de valores cristianos, servicio y en armonía con el medio ambiente, para contribuir a la transformación de una sociedad justa y equitativa."
                            }
                            typingSpeed={1}
                            pauseDuration={1500}
                            showCursor={true}
                            cursorCharacter="|"
                            cursorClassName="text-blue-900"
                            textColors={["#f3f3f3ff"]}
                        />
                    </div>
                    <div className={InicioPageCSS["vision"]}>
                        <div className={InicioPageCSS["vision-container"]}>
                            <div className={InicioPageCSS["vision-subtitulo"]}>
                                <SplitText
                                    text="Visión"
                                    className="!text-3xl !text-blue-900 !font-semibold !text-start"
                                    delay={50}
                                    duration={0.6}
                                    ease="power3.out"
                                    splitType="chars"
                                    from={{ opacity: 0, y: 40 }}
                                    to={{ opacity: 1, y: 0 }}
                                    threshold={0.1}
                                    rootMargin="-100px"
                                    textAlign="start"
                                    onLetterAnimationComplete={handleAnimationComplete}
                                />
                                <div className={InicioPageCSS["segundo-rectangulo"]}></div>
                            </div>
                            <div className={InicioPageCSS["vision-icono"]}>
                                <FontAwesomeIcon icon={faEye} style={{ color: "#4a8db7", }} size="2x" />
                            </div>
                        </div>
                        <TextType
                            className="!text-xl !text-justify bg-blue-400 text-white p-4 rounded-lg mt-2"
                            text={
                                "Promover, gestionar y apollar el dessarrollo de investigadores capaces de generar conocimientos, en todas las áreas de las ciencias, desde una consmovisión cristiana, preparando una comunidad de expertos y líderes comprometidos con la Iglesia Adventista del Séptimo Día y la sociedad."
                            }
                            typingSpeed={1}
                            pauseDuration={1500}
                            showCursor={true}
                            cursorCharacter="|"
                            cursorClassName="text-blue-900"
                            textColors={["#f3f3f3ff"]}
                        />
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