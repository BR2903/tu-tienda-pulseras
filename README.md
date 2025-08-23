# Tienda de Pulseras

Este es un proyecto web de una tienda de pulseras desarrollada en PHP. Permite a los usuarios ver un catálogo de productos, y cuenta con un panel de administración para gestionar las pulseras y los pedidos.

## Características

- **Catálogo público:** Los visitantes pueden ver todas las pulseras disponibles.
- **Panel de administración:** Gestión de productos y pedidos (protegido por login).
- **Conexión a base de datos MySQL:** Utiliza XAMPP para desarrollo local.
- **Diseño responsive:** Compatible con dispositivos móviles y computadoras.

## Estructura del proyecto

```
tu-tienda-pulseras/
│
├── admin/              # Panel de administración
├── conection/          # Archivos de conexión a la base de datos
├── img/                # Imágenes de las pulseras
├── index.php           # Página de inicio y catálogo
├── .gitignore          # Archivos que no se suben al control de versiones
├── README.md           # Este archivo
└── ...otros archivos
```

## Requisitos

- PHP 7.x o superior
- MySQL/MariaDB
- XAMPP o similar (para desarrollo local)
- Composer (opcional, si usas dependencias PHP externas)

## Instalación

1. Clona el repositorio:
   ```sh
   git clone https://github.com/BR2903/tu-tienda-pulseras.git
   ```

2. Copia el proyecto a la carpeta `htdocs` de XAMPP.

3. Configura la base de datos:
   - Crea una base de datos en phpMyAdmin.
   - Importa el archivo `.sql` si existe (o crea las tablas necesarias).

4. Configura la conexión a la base de datos en `conection/conection.php`:
   ```php
   $host = "localhost";
   $user = "usuario";
   $pass = "contraseña";
   $db   = "nombre_base_de_datos";
   ```

5. Abre tu navegador y accede a:  
   ```
   http://localhost/tu-tienda-pulseras/
   ```

## Uso

- El catálogo es público.
- Para acceder al panel de administración, inicia sesión desde `/admin/`.
- Desde el panel puedes agregar, editar y eliminar pulseras, y gestionar pedidos.

## Personalización

- Puedes cambiar imágenes y descripciones en la carpeta `img/` y en la base de datos.
- Si usas Composer, instala las dependencias con:
  ```sh
  composer install
  ```

## Contribuir

1. Haz un fork del repositorio
2. Crea una rama (`git checkout -b feature-nueva`)
3. Realiza tus cambios y haz commit (`git commit -m "Descripción"`)
4. Haz push a tu rama (`git push origin feature-nueva`)
5. Abre un Pull Request

---

¡Gracias por tu interés en este proyecto!  
Si tienes dudas, sugerencias o encuentras errores, abre un issue o contacta al autor.
