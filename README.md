# Tomin

**Tomin** es una plataforma web para control financiero personal y compartido.

Permite administrar billeteras, categorías, transacciones, comercios, compras a MSI, automatizaciones recurrentes e invitaciones para colaborar en billeteras compartidas.

## Características principales

- Gestión de **billeteras personales y compartidas**.
- Registro y consulta de **ingresos/gastos** con categorías y comercios.
- Soporte de **MSI (Meses Sin Intereses)** y procesamiento mensual automático.
- **Transacciones recurrentes** con ejecución programada.
- Flujo de **invitaciones por token** para unirse a billeteras compartidas.
- Panel administrativo con **Filament v5** y widgets financieros.

## Stack técnico

- **Backend:** Laravel 12, PHP 8.2+
- **Panel admin:** Filament v5 + Livewire
- **Base de datos:** MariaDB (Docker) / MySQL compatible
- **Cache y colas:** Redis
- **Frontend build:** Vite + Tailwind CSS 4
- **Testing:** Pest / PHPUnit

## Módulos del panel

Recursos disponibles en el panel administrativo:

- Cuentas
- Bitácora de actividad
- Categorías
- Compras a MSI
- Invitaciones
- Comercios
- Transacciones recurrentes
- Transacciones
- Billeteras

## Requisitos

- PHP 8.2 o superior
- Composer
- Node.js + npm
- MariaDB/MySQL y Redis

> Alternativamente puedes usar Docker con el `docker-compose.yml` incluido.

## Instalación rápida (sin Docker)

1. Instalar dependencias:

```bash
composer install
npm install
```

2. Configurar entorno:

```bash
cp .env.example .env
php artisan key:generate
```

3. Configurar base de datos y redis en `.env`.

4. Ejecutar migraciones:

```bash
php artisan migrate
```

5. Compilar assets:

```bash
npm run build
```

6. Levantar entorno de desarrollo:

```bash
composer run dev
```

Esto inicia servidor HTTP, cola, logs y Vite en paralelo.

## Instalación con Docker

El proyecto incluye servicios `nginx`, `app`, `db` (MariaDB) y `redis`.

```bash
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app npm install
docker compose exec app npm run build
```

URL por defecto:

- Landing: `http://localhost:8080/`
- Admin: `http://localhost:8080/admin`

## Tareas programadas

El proyecto agenda automáticamente:

- Procesamiento de transacciones recurrentes
- Procesamiento mensual de MSI
- Recordatorios de transacciones recurrentes
- Recordatorios de pago de tarjetas
- Snapshot diario de balances/patrimonio

Para ejecución continua del scheduler:

```bash
php artisan schedule:work
```

o configurar cron con:

```bash
* * * * * php /ruta/proyecto/artisan schedule:run >> /dev/null 2>&1
```

## Scripts útiles

- Desarrollo completo: `composer run dev`
- Tests: `composer run test`
- Formateo (Pint): `./vendor/bin/pint`

## Rutas relevantes

- `/` landing pública
- `/admin` panel administrativo
- `/invitation/{token}` aceptación de invitaciones

## Seguridad

- Autenticación de usuarios en panel administrativo
- Soporte de doble factor vía `laragear/two-factor`
- Manejo de invitaciones con token y expiración

## Licencia

Este proyecto está licenciado bajo **Polyform Noncommercial License 1.0.0**.

Consulta el archivo [LICENSE](LICENSE) para el texto completo y condiciones de uso.
