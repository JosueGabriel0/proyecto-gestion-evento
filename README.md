# 🎓 Proyecto Jornada Científica
Sistema de gestión para jornadas científicas que optimiza el registro de alumnos mediante QR y la evaluación de ponentes por parte de jurados, generando cálculos automáticos y reportes en tiempo real.

---

## 📌 Tecnologías
- **Backend:** Laravel
- **Frontend Web:** React (Panel de administración)
- **App Móvil:** Flutter (Registro y escaneo QR)
- **Base de Datos:** MySQL
- **Control de Versiones:** Git & GitHub

---

## 🚀 Características
- 📲 Registro de alumnos mediante QR
- 🏅 Evaluación digital de ponentes por jurados
- 🧮 Cálculo automático de promedios y puntuaciones
- 📊 Reportes en PDF/Excel para resultados y estadísticas
- 🔔 Notificaciones de resultados y horarios
- 🔐 Seguridad y autenticación de usuarios

---

## 🏗️ Arquitectura
El sistema sigue una arquitectura distribuida y multiplataforma:

```
[ React Web ]     [ Flutter App ]
        \           /
         \         /
         [ Laravel API ]
                |
         [ Base de Datos ]
```

- **Frontend Web:** Panel para administradores y jurados
- **App Móvil:** Registro de alumnos y escaneo de QR
- **Backend:** API REST con Laravel
- **Base de Datos:** MySQL para gestión centralizada

---

## 📦 Instalación

### 1️⃣ Clonar repositorio
```bash
git clone https://github.com/usuario/proyecto-jornada-cientifica.git
```

### 2️⃣ Configurar backend (Laravel)
- Instalar **PHP 8+**, **Composer** y **Laravel**
- Configurar `.env` con las credenciales de la base de datos
- Ejecutar migraciones y servidor:
```bash
cd backend/jornada-cientifica-laravel
composer install
php artisan migrate
php artisan serve
```

### 3️⃣ Configurar frontend web
```bash
cd frontend/jornada-cientifica-react
npm install
npm run dev
```

### 4️⃣ Configurar app móvil
```bash
cd mobile/jornada-cientifica-flutter
flutter pub get
flutter run
```

---

## 🧪 Requerimientos
- Node.js 18+
- Flutter 3.0+
- PHP 8+
- Composer
- Laravel 10+
- MySQL

---

## 📜 Licencia
Este proyecto está bajo la licencia **MIT**.
Consulta el archivo `LICENSE` para más información.

---

💡 Desarrollado para optimizar la gestión, evaluación y experiencia en jornadas científicas.