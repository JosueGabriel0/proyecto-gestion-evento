import React, { useState, useEffect, ChangeEvent, FormEvent } from "react";
import { getUserRole, login } from "../../services/authServices/authService";
import { useNavigate, Link } from "react-router-dom";
import LoginPageCSS from "./LoginPage.module.css";

// Tipos de datos
interface Credentials {
  username: string;
  password: string;
}

type UserRole = "ADMINISTRADOR" | "ADMINISTRATIVO" | "DOCENTE" | "ESTUDIANTE" | null;

const LoginPage: React.FC = () => {
  const [credentials, setCredentials] = useState<Credentials>({ username: "", password: "" });
  const [error, setError] = useState<string | null>(null);
  const [userRole, setUserRole] = useState<UserRole>(null);
  const [showPassword, setShowPassword] = useState<boolean>(false);
  const [pupilPosition, setPupilPosition] = useState<{ x: string; y: string }>({ x: "50%", y: "50%" });
  const [isClosed, setIsClosed] = useState<boolean>(false);
  const [isBlinking, setIsBlinking] = useState<boolean>(false);

  const navigate = useNavigate();

  // 👁️ Animación de parpadeo
  useEffect(() => {
    const blinkInterval = setInterval(() => {
      if (!isClosed) {
        setIsBlinking(true);
        setTimeout(() => setIsBlinking(false), 200);
      }
    }, Math.random() * 4000 + 2000);

    return () => clearInterval(blinkInterval);
  }, [isClosed]);

  // 👁️ Seguimiento de la pupila con el mouse
  useEffect(() => {
    const handleMouseMove = (e: MouseEvent) => {
      const eyes = document.querySelectorAll<HTMLElement>(`.${LoginPageCSS.eye}`);

      eyes.forEach((eye) => {
        const pupil = eye.querySelector<HTMLElement>(`.${LoginPageCSS.pupil}`);
        if (!pupil) return;

        const rect = eye.getBoundingClientRect();
        const eyeRadius = rect.width / 2;
        const pupilRadius = pupil.offsetWidth / 2;

        const eyeCenterX = rect.left + eyeRadius;
        const eyeCenterY = rect.top + eyeRadius;

        const deltaX = e.clientX - eyeCenterX;
        const deltaY = e.clientY - eyeCenterY;
        const distance = Math.sqrt(deltaX ** 2 + deltaY ** 2);

        const maxMove = eyeRadius - pupilRadius;
        const limitedDistance = Math.min(distance, maxMove);
        const angle = Math.atan2(deltaY, deltaX);

        const moveX = Math.cos(angle) * limitedDistance;
        const moveY = Math.sin(angle) * limitedDistance;

        pupil.style.transform = `translate(${moveX}px, ${moveY}px)`;
      });
    };

    document.addEventListener("mousemove", handleMouseMove);
    return () => document.removeEventListener("mousemove", handleMouseMove);
  }, []);

  // 📝 Manejo de inputs
  const handleInputChange = (e: ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setCredentials({ ...credentials, [name]: value });
  };

  // 🔑 Manejo de login
  const handleLogin = async (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    try {
      const token = await login(credentials);
      const rolDelUsuario = await getUserRole();
      setUserRole(rolDelUsuario);

      switch (rolDelUsuario) {
        case "ADMINISTRADOR":
          navigate("/dashboard-administrador");
          break;
        case "ADMINISTRATIVO":
          navigate("/dashboard-administrativo");
          break;
        case "DOCENTE":
          navigate("/dashboard-docente");
          break;
        case "ESTUDIANTE":
          navigate("/dashboard-estudiante");
          break;
        default:
          setError("Rol desconocido. Comuníquese con soporte.");
      }
    } catch (err) {
      setError("El nombre de Usuario o la Contraseña son incorrectos");
    }
  };

  // 👁️ Alternar ojo (mostrar/ocultar password)
  const handleEyeClick = () => {
    setShowPassword(!showPassword);
    setIsClosed(!isClosed);
  };

  const classNameEye = `${LoginPageCSS.eye} ${isClosed ? LoginPageCSS.closed : ""} ${isBlinking ? LoginPageCSS.blink : ""}`.trim();

  return (
    <div className={LoginPageCSS["video-container"]}>
      <video className={LoginPageCSS["background-video"]} autoPlay loop muted playsInline>
        <source src="/videos/Fondo tecnológico en 4K.mp4" type="video/mp4" />
        Tu navegador no soporta videos HTML5.
      </video>

      <div className={LoginPageCSS["content"]}>
        <div className={LoginPageCSS["login-container"]}>
          <div className={LoginPageCSS["login-container-1"]}>
            <div className={LoginPageCSS["login-container-data"]}>
              <h2>Iniciar sesión aquí</h2>
              <form onSubmit={handleLogin}>
                {/* Usuario */}
                <div className={LoginPageCSS["user-container"]}>
                  <label>Usuario</label>
                  <input
                    type="text"
                    placeholder="Ingrese su usuario"
                    name="username"
                    value={credentials.username}
                    onChange={handleInputChange}
                    className={LoginPageCSS["input-field"]}
                  />
                </div>

                {/* Contraseña */}
                <div className={LoginPageCSS["password-container"]}>
                  <label>Password</label>
                  <div className={LoginPageCSS["input-wrapper"]}>
                    <button
                      type="button"
                      className={LoginPageCSS["toggle-password"]}
                      onClick={handleEyeClick}
                    >
                      <div className={classNameEye}>
                        {!isClosed && (
                          <div
                            className={LoginPageCSS["pupil"]}
                            style={{ left: pupilPosition.x, top: pupilPosition.y }}
                          >
                            <div className={LoginPageCSS["pupil-reflection"]}></div>
                          </div>
                        )}
                      </div>
                    </button>
                    <input
                      type={showPassword ? "text" : "password"}
                      placeholder="Ingrese su contraseña"
                      name="password"
                      value={credentials.password}
                      onChange={handleInputChange}
                      autoComplete="off"
                      minLength={8}
                      required
                      className={LoginPageCSS["input-field"]}
                    />
                  </div>
                </div>

                {/* Opciones */}
                <div className={LoginPageCSS["options"]}>
                  <div className={LoginPageCSS["checkbox-wrapper-46"]}>
                    <input type="checkbox" id="cbx-46" className={LoginPageCSS["inp-cbx"]} />
                    <label htmlFor="cbx-46" className={LoginPageCSS["cbx"]}>
                      <span>
                        <svg viewBox="0 0 12 10" height="10px" width="12px">
                          <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                        </svg>
                      </span>
                    </label>
                    <div className={LoginPageCSS["remember-me-label"]}>
                      <label>Recuérdame</label>
                    </div>
                  </div>
                  <Link to="/restablecimiento-contrasenia" className={LoginPageCSS["forgot-password"]}>
                    ¿Olvidaste la contraseña?
                  </Link>
                </div>

                {/* Error */}
                {error && <div className={LoginPageCSS["error"]}>{error}</div>}

                {/* Botón */}
                <button type="submit" className={LoginPageCSS["login-button"]}>
                  Iniciar Sesión
                </button>
              </form>
              <p className={LoginPageCSS["alternative-text"]}>O usa tu cuenta</p>
            </div>

            {/* Redes sociales */}
            <div className={LoginPageCSS["card"]}>
              {/* Aquí van los <a> con SVG que ya tenías */}
            </div>
          </div>

          <div className={LoginPageCSS["login-container-2"]}>
            <div className={LoginPageCSS["letras-logo"]}>
              <div className={LoginPageCSS["letras"]}>
                <h2>Educando para la vida y la eternidad</h2>
                <p>Ingresa al sistema universitario con tu cuenta institucional</p>
              </div>
              <div className={LoginPageCSS["logos"]}>
                <div className={LoginPageCSS["logo1"]}>
                  <img src="/images/logo2.png" alt="" />
                </div>
                <div className={LoginPageCSS["logo2"]}>
                  <img src="/images/imagen1.png" alt="" />
                </div>
              </div>
            </div>
            <video autoPlay loop muted playsInline>
              <source src="/videos/Fondo tecnológico en 4K.mp4" type="video/mp4" />
              Tu navegador no soporta videos HTML5.
            </video>
          </div>
        </div>
      </div>
    </div>
  );
};

export default LoginPage;