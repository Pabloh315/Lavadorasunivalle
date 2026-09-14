# QuikWash

QuikWash es una aplicacion web universitaria para administrar reservas de lavanderia para estudiantes.

**Eslogan:** Lava fácil. Reserva rápido.

## Tecnologias utilizadas

- Laravel 13
- PHP 8.5 en contenedor Docker
- SQLite para desarrollo
- Blade
- Tailwind CSS 4
- Vite
- Eloquent ORM
- PHPUnit
- Laravel Boost como dependencia de desarrollo

## Requisitos

No necesitas instalar PHP, Composer, MySQL, Node.js ni Laravel directamente en Windows. El proyecto esta preparado para ejecutarse con Docker Desktop.

## Instalacion

Desde la carpeta del proyecto:

```powershell
docker compose run --rm app composer install
docker compose run --rm app php artisan key:generate
```

## Configuracion .env

El archivo `.env` no debe subirse al repositorio. Usa `.env.example` como referencia segura.

Configuracion de desarrollo esperada:

```env
APP_NAME=QuikWash
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

## SQLite

La base de datos de desarrollo esta en:

```text
database/database.sqlite
```

Si no existe, se puede crear como archivo vacio antes de migrar.

## Migraciones y seeders

Recrear la base de datos de desarrollo con datos demo:

```powershell
docker compose run --rm app php artisan migrate:fresh --seed
```

No ejecutar `migrate:fresh` sobre una base de datos de produccion.

## Iniciar el servidor

```powershell
docker compose up -d app vite
```

Abrir la aplicacion en:

```text
http://localhost:8000
```

Vite queda disponible en:

```text
http://localhost:5173
```

## Compilar assets

```powershell
docker compose exec vite npm run build
```

## Ejecutar pruebas

```powershell
docker compose run --rm app php artisan test
```

## Usuarios de demostracion

Personal de lavanderia:

- Correo: `personal@quikwash.test`
- Contraseña: `Password123!`
- Rol: `laundry_staff`

Estudiante:

- Correo: `estudiante1@quikwash.test`
- Contraseña: `Password123!`
- Rol: `student`

Las credenciales son ficticias y solo sirven para demostracion academica.

## Roles del sistema

- `student`: estudiante universitario que registra y administra sus propias reservas.
- `laundry_staff`: personal de lavanderia que administra reservas, estudiantes y lavadoras.

## Principales reglas de negocio

- Un estudiante puede tener maximo 3 reservas activas.
- Reservas activas: `pending` e `in_progress`.
- Reservas `completed` y `cancelled` no cuentan como activas.
- Una lavadora no puede tener dos reservas activas en la misma fecha y hora.
- Una reserva cancelada libera el horario.
- No se permiten fechas pasadas ni horas pasadas para reservas de hoy.
- El peso debe ser mayor a 0 kg.
- Una reserva nueva comienza como `pending`.
- El estudiante solo puede cancelar reservas `pending` propias.
- El estudiante no puede ver ni modificar reservas de otros estudiantes.
- El personal puede cambiar `pending -> in_progress` e `in_progress -> completed`.
- El personal puede cancelar reservas pendientes o en proceso.
- Una reserva `completed` no puede modificarse.
- Una reserva `cancelled` no puede reactivarse.
- Lavadoras `maintenance` u `out_of_service` no pueden reservarse.

## Estados

Reservas:

- `pending`
- `in_progress`
- `completed`
- `cancelled`

Lavadoras:

- `available`
- `maintenance`
- `out_of_service`

## Estructura basica

```text
app/Http/Controllers/Auth
app/Http/Controllers/Student
app/Http/Controllers/Staff
app/Http/Requests
app/Http/Middleware
app/Models
app/Policies
app/Services
database/factories
database/migrations
database/seeders
resources/css
resources/js
resources/views
routes/web.php
tests/Feature
docker-compose.yml
```

## Seguridad implementada

- Autenticacion con sesiones Laravel.
- Proteccion CSRF en formularios.
- Passwords con hash automatico.
- Middleware por rol.
- Policies para reservas.
- Form Requests para validacion backend.
- Restriccion de acceso por ID para evitar IDOR.
- `role` no es asignable masivamente desde formularios.
- Indices y restricciones unique en base de datos.
- `.env`, `vendor/`, `node_modules/`, logs y archivos temporales ignorados por Git.
