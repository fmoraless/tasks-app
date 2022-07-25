
## Tasks API

CRUD de tareas


Api desarrollada con Laravel9 y metodología TDD (desarrollo guiado por pruebas) con Docker a través de Laravel Sail.
Esta API Cumple con estándar JSON API

## Features

- Agregar, editar, eliminar tareas
- Listar tareas y consultar tarea individualmente


## Installación

#### Requisitos previos:
- Docker y Wsl2 instalados
- Postman
- PHP 8.0.2
- Node 12.22.12 o superior

#### Ejecución en local


* Clonar desde github (usar github desktop)
```bash
  gh repo clone fmoraless/tasks-app
```
* Vaya a la carpeta del proyecto (ideal en wsl)
```bash
  cd tasks-app
```

* Instalar dependencias con composer desde consola
```bash
  composer install
```

* Instalar dependencias node
```bash
  npm install
```

## Variables de entorno

Para ejecutar este proyecto, Necesitarás añadir las siguientes variables de entorno en **.env file**

`DB_DATABASE=your-database`

`DB_USERNAME=your-username`

`DB_PASSWORD=your-password`

`APP_KEY=base64:APP_KEY` (**)

** para generar el APP_KEY, se necesita ejecutar el siguiente comando:

```bash
  php artisan key:generate
```
## Ejecutar Localmente

Ejecutar Comando para datos de prueba

Este comando genera un usuario con su token para probar en Postman.
```bash
  sail artisan generate:test-data
```
ej:
User UUID:

37e8da0b-f02f-4d2d-84a4-293b041b0c62

Token:

8|GB2zysNaPdgy085RllGaKPqvPkDTrJpId0DQRQjc

Tarea ID:
64


Iniciar el contenedor Docker

* configurar alias de Laravel Sail
```bash
  alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```

```bash
  sail up -d
```


#
Ir a Postman

http://localhost/api/v1/register (tu localhost)

utilizar usuario para prueba:
```bash
 {
    "name": "Example user",
    "email": "example@admin.com",
    "password": "password",
    "device_name": "My Example device"
 }
```
La api responderá co el token para acceder a las funciones CRUD.

importante señalar que JSON API requiere los siguientes headers:
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json
## Ejecutar tests

Para ejecutar los test, use el siguiente comando:

```bash
  php artisan test
```


## Screenshots

#### Acceso - Registro usuario

![Registro de usuario](https://drive.google.com/uc?export=view&id=1_uMSo3fa6CqLvJs86VHc7Lr4bGNHWA0e)

#### Crear una Tarea
![Add task](https://drive.google.com/uc?export=view&id=1fNC3SR2Dtqactr6D3jCrNz_Wdd5klF4y)

#### Actualizar una tarea
![update task](https://drive.google.com/uc?export=view&id=1fEimhDdjCl6sGje8K_96kpnPc5HCOoFH)

## Feedback

Si tienes algun Feedback, por favor hazme saber fcomorales.sanchez@gmail.com

