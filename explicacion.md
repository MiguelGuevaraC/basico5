# basico3 — Explicación (Laravel + Eloquent + Blade + AJAX)

basico3 es el equivalente de basico2 pero usando **Laravel** (Artisan + Composer) y sus mejores prácticas:

- Routing con `routes/web.php`
- Controladores en `app/Http/Controllers`
- Modelos **Eloquent** + relaciones
- Migraciones + seeders
- Validación con **FormRequest**
- Auth con middleware `auth` y CSRF automático
- Vistas **Blade**
- AJAX con JSON para datagrid/databox (como basico2)

---

## 1) Estructura principal (Laravel)

- `routes/web.php`: rutas web + rutas de API internas (prefijo `api`)
- `app/Http/Controllers`: controladores
- `app/Models`: modelos Eloquent
- `database/migrations`: estructura de tablas
- `database/seeders`: datos iniciales (usuario admin)
- `resources/views`: vistas Blade (layout + páginas)
- `public/assets`: css/js públicos (datagrid/databox/http + módulos)
- `.env`: configuración (BD, URL, etc.)

---

## 2) Base de datos (PostgreSQL) y migraciones

En `.env` se configuró PostgreSQL:
- [basico3/.env](file:///c:/xampp/htdocs/mvc-productos/basico3/.env)

Importante: la base `mvc_productos_b3` debe existir en PostgreSQL.

Migraciones:
- usuarios (se agregó `username`): [create_users_table.php](file:///c:/xampp/htdocs/mvc-productos/basico3/database/migrations/0001_01_01_000000_create_users_table.php)
- categorías: [create_categorias_table.php](file:///c:/xampp/htdocs/mvc-productos/basico3/database/migrations/2026_04_27_065327_create_categorias_table.php)
- marcas: [create_marcas_table.php](file:///c:/xampp/htdocs/mvc-productos/basico3/database/migrations/2026_04_27_065328_create_marcas_table.php)
- productos con foráneas: [create_productos_table.php](file:///c:/xampp/htdocs/mvc-productos/basico3/database/migrations/2026_04_27_065329_create_productos_table.php)

Modelo de datos:
- `categorias(id, nombre UNIQUE)`
- `marcas(id, nombre UNIQUE)`
- `productos(nombre, precio, categoria_id FK, marca_id FK)`

---

## 3) Modelos Eloquent y relaciones

Modelos:
- [Categoria.php](file:///c:/xampp/htdocs/mvc-productos/basico3/app/Models/Categoria.php)
- [Marca.php](file:///c:/xampp/htdocs/mvc-productos/basico3/app/Models/Marca.php)
- [Producto.php](file:///c:/xampp/htdocs/mvc-productos/basico3/app/Models/Producto.php)

Relaciones:
- Categoria `hasMany(Producto)`
- Marca `hasMany(Producto)`
- Producto `belongsTo(Categoria)` y `belongsTo(Marca)`

Esto reemplaza el SQL manual de basico2: aquí Laravel arma consultas con Eloquent y trae relaciones con `with()`.

---

## 4) Validación (FormRequest)

Requests:
- Categoría: [StoreCategoriaRequest](file:///c:/xampp/htdocs/mvc-productos/basico3/app/Http/Requests/StoreCategoriaRequest.php), [UpdateCategoriaRequest](file:///c:/xampp/htdocs/mvc-productos/basico3/app/Http/Requests/UpdateCategoriaRequest.php)
- Marca: [StoreMarcaRequest](file:///c:/xampp/htdocs/mvc-productos/basico3/app/Http/Requests/StoreMarcaRequest.php), [UpdateMarcaRequest](file:///c:/xampp/htdocs/mvc-productos/basico3/app/Http/Requests/UpdateMarcaRequest.php)
- Producto: [StoreProductoRequest](file:///c:/xampp/htdocs/mvc-productos/basico3/app/Http/Requests/StoreProductoRequest.php), [UpdateProductoRequest](file:///c:/xampp/htdocs/mvc-productos/basico3/app/Http/Requests/UpdateProductoRequest.php)

Ejemplo de regla (idea):
- `exists:categorias,id` asegura que el `categoria_id` exista antes de guardar.

---

## 5) Rutas (web + api)

Archivo:
- [routes/web.php](file:///c:/xampp/htdocs/mvc-productos/basico3/routes/web.php)

Se organizó así:
- `guest`: login
- `auth`: dashboard y módulos
- `auth + api/*`: endpoints JSON para AJAX

Endpoints (resumen):
- GET `/` dashboard
- GET `/categorias`, `/marcas`, `/productos`
- GET `/api/dashboard`
- GET `/api/categorias`, `/api/marcas`, `/api/productos`
- GET `/api/categorias/options`, `/api/marcas/options`
- POST `/api/categorias/crear`, `/api/marcas/crear`, `/api/productos/crear`
- POST `/{id}/editar` y `/{id}/eliminar` por cada módulo

---

## 6) Controladores (HTML + JSON)

### Auth (login/logout)
- [AuthController.php](file:///c:/xampp/htdocs/mvc-productos/basico3/app/Http/Controllers/AuthController.php)

Usa:
- `Auth::attempt(...)`
- regeneración de sesión
- CSRF automático en formularios Blade con `@csrf`

### Dashboard (databox)
- [DashboardController.php](file:///c:/xampp/htdocs/mvc-productos/basico3/app/Http/Controllers/DashboardController.php)

### CRUD módulos
- [CategoriaController.php](file:///c:/xampp/htdocs/mvc-productos/basico3/app/Http/Controllers/CategoriaController.php)
- [MarcaController.php](file:///c:/xampp/htdocs/mvc-productos/basico3/app/Http/Controllers/MarcaController.php)
- [ProductoController.php](file:///c:/xampp/htdocs/mvc-productos/basico3/app/Http/Controllers/ProductoController.php)

En el listado de productos:
- se usa `with(['categoria', 'marca'])` y luego se arma el JSON con `categoria` y `marca` en texto para el datagrid.

---

## 7) Vistas Blade + Layout

Layout principal:
- [layouts/app.blade.php](file:///c:/xampp/htdocs/mvc-productos/basico3/resources/views/layouts/app.blade.php)

Páginas:
- login: [auth/login.blade.php](file:///c:/xampp/htdocs/mvc-productos/basico3/resources/views/auth/login.blade.php)
- dashboard: [dashboard/index.blade.php](file:///c:/xampp/htdocs/mvc-productos/basico3/resources/views/dashboard/index.blade.php)
- categorías: [categorias/index.blade.php](file:///c:/xampp/htdocs/mvc-productos/basico3/resources/views/categorias/index.blade.php)
- marcas: [marcas/index.blade.php](file:///c:/xampp/htdocs/mvc-productos/basico3/resources/views/marcas/index.blade.php)
- productos: [productos/index.blade.php](file:///c:/xampp/htdocs/mvc-productos/basico3/resources/views/productos/index.blade.php)

El layout:
- carga Bootstrap/DataTables/Select2/SweetAlert2
- expone `APP_BASE_URL` para que el JS construya URLs
- carga JS común: `http.js`, `datagrid.js`, `databox.js`
- cada página agrega su JS con `@push('scripts')`

---

## 8) AJAX (frontend)

Helpers JS comunes:
- [public/assets/js/http.js](file:///c:/xampp/htdocs/mvc-productos/basico3/public/assets/js/http.js)
- [public/assets/js/datagrid.js](file:///c:/xampp/htdocs/mvc-productos/basico3/public/assets/js/datagrid.js)
- [public/assets/js/databox.js](file:///c:/xampp/htdocs/mvc-productos/basico3/public/assets/js/databox.js)

JS por módulo:
- [categorias.js](file:///c:/xampp/htdocs/mvc-productos/basico3/public/assets/js/categorias.js)
- [marcas.js](file:///c:/xampp/htdocs/mvc-productos/basico3/public/assets/js/marcas.js)
- [productos.js](file:///c:/xampp/htdocs/mvc-productos/basico3/public/assets/js/productos.js)
- [dashboard.js](file:///c:/xampp/htdocs/mvc-productos/basico3/public/assets/js/dashboard.js)

CSRF:
- Laravel lo expone en `<meta name="csrf-token">` y se manda en el header `X-CSRF-TOKEN`.

---

## 9) Seeder de usuario administrador

Seeder:
- [AdminUserSeeder.php](file:///c:/xampp/htdocs/mvc-productos/basico3/database/seeders/AdminUserSeeder.php)

Crea/actualiza:
- username: `administrador`
- password: `admin123`

---

## 10) Cómo dejarlo listo (BD)

1) Crear BD en PostgreSQL:

```sql
CREATE DATABASE mvc_productos_b3;
```

2) Ejecutar migraciones + seed:

```powershell
cd c:\xampp\htdocs\mvc-productos\basico3
php artisan migrate:fresh --seed
```

3) Abrir:
- `http://localhost/mvc-productos/basico3/public`

