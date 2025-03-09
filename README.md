# Tienda Online

Este proyecto es una tienda en línea que permite la gestión de productos y categorías. Los usuarios pueden agregar, editar y eliminar productos, así como gestionar las categorías de los mismos.

## Requisitos

Para poder ejecutar este proyecto, necesitas tener instalados los siguientes componentes:

- **PHP 7.4 o superior**: El lenguaje de programación en el que está desarrollado el proyecto.
- **MySQL**: El sistema de gestión de bases de datos utilizado para almacenar la información de los productos y categorías.
- **XAMPP (opcional, pero recomendado)**: Un paquete de software que incluye Apache, MySQL y PHP, facilitando la configuración del entorno de desarrollo.

## Instalación

Sigue estos pasos para instalar y configurar el proyecto en tu entorno local:

1. **Clonar el repositorio**:
    Primero, clona el repositorio del proyecto desde GitHub a tu máquina local utilizando el siguiente comando:
    ```bash
    git clone https://github.com/tu_usuario/Tienda.git
    ```

2. **Navegar al directorio del proyecto**:
    Una vez clonado el repositorio, navega al directorio del proyecto con el siguiente comando:
    ```bash
    cd tienda-online
    ```

3. **Configurar la base de datos**:
    - **Crear una base de datos en MySQL**: Abre tu gestor de bases de datos (como phpMyAdmin) y crea una nueva base de datos para el proyecto.
    - **Importar el archivo `database.sql`**: En la carpeta `database` del proyecto, encontrarás un archivo llamado `database.sql`. Importa este archivo en la base de datos que acabas de crear. Este archivo contiene las tablas y datos iniciales necesarios para que el proyecto funcione correctamente.
    - **Ejecutar el script `insertuser`**: Si no hay usuarios en la base de datos, ejecuta el script `insertuser` para agregar usuarios iniciales. Puedes hacerlo desde la línea de comandos con el siguiente comando:
        ```bash
        php insertuser.php
        ```

4. **Configurar el archivo `config.php`**:
    - **Editar el archivo `config.php` con las credenciales de la base de datos**: Abre el archivo `config.php` en el directorio config del proyecto y edita las variables de entorno para que coincidan con las credenciales de tu base de datos MySQL. Por ejemplo:
        ```
        SetEnv DB_HOST localhost
        SetEnv DB_DATABASE nombre_de_tu_base_de_datos
        SetEnv DB_USERNAME tu_usuario
        SetEnv DB_PASSWORD tu_contraseña
        ```

5. **Iniciar el servidor**:
    - **Usando XAMPP**: Si estás utilizando XAMPP, coloca el proyecto en la carpeta `htdocs` de XAMPP. Luego, abre el panel de control de XAMPP y enciende Apache y MySQL.
    - **Sin usar XAMPP**: Si no estás utilizando XAMPP, puedes iniciar el servidor PHP desde la línea de comandos con el siguiente comando:
        ```bash
        php -S localhost:8000
        ```

## Uso

### Gestión de Productos

Desde el panel de administración, puedes realizar las siguientes acciones para gestionar los productos:

- **Agregar Producto**: Haz clic en el botón "Agregar Producto". Se abrirá un formulario donde deberás completar los campos necesarios (nombre, descripción, precio, estado, categoría e imagen). Una vez completado, haz clic en "Agregar Producto" para guardar el nuevo producto.
- **Editar Producto**: En la lista de productos, haz clic en el botón de editar (icono de lápiz) junto al producto que deseas editar. Se abrirá un formulario con los datos actuales del producto. Realiza los cambios necesarios y haz clic en "Actualizar" para guardar los cambios.
- **Eliminar Producto**: En la lista de productos, haz clic en el botón de eliminar (icono de basura) junto al producto que deseas eliminar. Se abrirá un modal de confirmación. Haz clic en "Eliminar" para confirmar la eliminación del producto.

### Gestión de Categorías

Desde el panel de administración, también puedes gestionar las categorías de los productos:

- **Agregar Categoría**: Navega a la sección de categorías y haz clic en "Agregar Categoría". Se abrirá un formulario donde deberás completar los campos necesarios (nombre y descripción). Una vez completado, haz clic en "Agregar Categoría" para guardar la nueva categoría.
- **Editar Categoría**: En la lista de categorías, haz clic en el botón de editar junto a la categoría que deseas editar. Se abrirá un formulario con los datos actuales de la categoría. Realiza los cambios necesarios y haz clic en "Actualizar" para guardar los cambios.
- **Eliminar Categoría**: En la lista de categorías, haz clic en el botón de eliminar junto a la categoría que deseas eliminar. Se abrirá un modal de confirmación. Haz clic en "Eliminar" para confirmar la eliminación de la categoría.

## Estructura del Proyecto

El proyecto está organizado en las siguientes carpetas y archivos:

- `app/controllers`: Contiene los controladores de la aplicación, que manejan la lógica de negocio y la interacción con los modelos.
- `app/models`: Contiene los modelos de la aplicación, que representan las entidades de la base de datos.
- `app/views`: Contiene las vistas de la aplicación, que son las plantillas HTML que se muestran al usuario.
- `public`: Contiene los archivos públicos como CSS, JS e imágenes.
- `database`: Contiene el archivo SQL para la base de datos.
- `imagenes`: Contiene las imágenes que se guardan al momento de subir la imagen del producto.
- `config`: Contiene las variables para la conexión a la base de datos.

## Contribuciones

Las contribuciones son bienvenidas. Si deseas contribuir al proyecto, por favor, abre un issue o envía un pull request. Asegúrate de seguir las mejores prácticas de codificación y de incluir pruebas para cualquier cambio que realices.

