# 📌 Proyecto: API de Agendamiento de Citas Médicas

API REST desarrollada en **Laravel** para gestionar un sistema de citas médicas.  
Incluye autenticación con **Sanctum**, gestión de pacientes, médicos, consultorios, especialidades y consultas adicionales.  

---

## 🚀 Tecnologías usadas
- **Laravel 10+**
- **Sanctum** (autenticación por tokens)
- **MySQL** (base de datos relacional)

---

## 📂 Estructura de la base de datos (tablas principales)

- **pacientes** → información de los pacientes (nombre, apellido, documento, teléfono, email, fecha de nacimiento, dirección).
- **medicos** → datos de los médicos y su especialidad.
- **especialidades** → lista de especialidades médicas.
- **consultorios** → número y ubicación del consultorio.
- **citas** → agenda de citas médicas, relacionada con pacientes, médicos y consultorios.
- **users** → tabla de usuarios para login y autenticación con Sanctum.

---

## 🔑 Autenticación
Todos los endpoints protegidos usan **Bearer Token** (Laravel Sanctum).

**Ejemplo de header:**

- http
Authorization: Bearer <token>
Content-Type: application/json


## 📌 Endpoints de Autenticación (AuthController)

| Método | Ruta          | Descripción                              |
| ------ | ------------- | ---------------------------------------- |
| POST   | /api/register | Registrar un nuevo usuario (con rol)     |
| POST   | /api/login    | Iniciar sesión y obtener token JWT       |
| POST   | /api/logout   | Cerrar sesión e invalidar token          |
| GET    | /api/me       | Obtener información del usuario logueado |



## 🌐 Endpoints principales
### 🔹Citas

| Método | Ruta                      | Descripción             |
| ------ | ------------------------- | ----------------------- |
| GET    | /api/listarCitas          | Lista todas las citas   |
| POST   | /api/crearCitas           | Crear una nueva cita    |
| GET    | /api/citas/{id}           | Ver detalle de una cita |
| PUT    | /api/actualizarCitas/{id} | Actualizar una cita     |
| DELETE | /api/eliminarCitas/{id}   | Eliminar una cita       |


### 🔹Ejemplo - Crear cita

- POST /api/crearCitas

### 🔹Body

{ <br>
  "id_pacientes": 1, <br>
  "id_medicos": 2, <br>
  "id_consultorios": 1,<br>
  "fecha": "2025-09-10", <br>
  "hora": "10:30", <br>
  "estado": "Confirmada", <br>
  "motivo": "Chequeo general"<br>
}

### 🔹Respuesta 201

{ <br>
  "id": 12, <br>
  "id_pacientes": 1, <br>
  "id_medicos": 2, <br>
  "id_consultorios": 1, <br>
  "fecha": "2025-09-10", <br>
  "hora": "10:30", <br>
  "estado": "Confirmada", <br>
  "motivo": "Chequeo general", <br>
  "created_at": "2025-09-04T17:00:00Z", <br>
  "updated_at": "2025-09-04T17:00:00Z" <br>
}

### 🔹Consultorios
| Método | Ruta                             | Descripción                  |
| ------ | -------------------------------- | ---------------------------- |
| GET    | /api/listarConsultorios          | Lista todos los consultorios |
| POST   | /api/crearConsultorios           | Crear consultorio            |
| GET    | /api/consultorios/{id}           | Ver consultorio por id       |
| PUT    | /api/actualizarConsultorios/{id} | Actualizar consultorio       |
| DELETE | /api/eliminarConsultorios/{id}   | Eliminar consultorio         |

### 🔹Especialidades

| Método | Ruta                               | Descripción                    |
| ------ | ---------------------------------- | ------------------------------ |
| GET    | /api/listarEspecialidades          | Lista todas las especialidades |
| POST   | /api/crearEspecialidades           | Crear especialidad             |
| GET    | /api/especialidades/{id}           | Ver especialidad por id        |
| PUT    | /api/actualizarEspecialidades/{id} | Actualizar especialidad        |
| DELETE | /api/eliminarEspecialidades/{id}   | Eliminar especialidad          |


### 🔹Médicos

| Método | Ruta                        | Descripción             |
| ------ | --------------------------- | ----------------------- |
| GET    | /api/listarMedicos          | Lista todos los médicos |
| POST   | /api/crearMedicos           | Crear médico            |
| GET    | /api/medicos/{id}           | Ver médico por id       |
| PUT    | /api/actualizarMedicos/{id} | Actualizar médico       |
| DELETE | /api/eliminarMedicos/{id}   | Eliminar médico         |


### 🔹Pacientes
| Método | Ruta                          | Descripción               |
| ------ | ----------------------------- | ------------------------- |
| GET    | /api/listarPacientes          | Lista todos los pacientes |
| POST   | /api/crearPacientes           | Crear paciente            |
| GET    | /api/pacientes/{id}           | Ver paciente por id       |
| PUT    | /api/actualizarPacientes/{id} | Actualizar paciente       |
| DELETE | /api/eliminarPacientes/{id}   | Eliminar paciente         |

------------

## 📊 Consultas adicionales

- `GET /api/listarHotmail →` Pacientes con correo @hotmail

- `GET /api/listar20Anios →` Médicos menores de 20 años

- `GET /api/listarConsultoriosSegundoP →` Consultorios en segundo piso

- `GET /api/listarMenores →` Pacientes menores de edad

- `GET /api/listarCitasActivas →` Citas con estado Confirmada

- `GET /api/listarApellidosM →` Pacientes cuyo apellido empieza con "M"

- `GET /api/listarCitasGripa →` Citas con motivo que contenga "Gripa"

- `GET /api/listarMedicosCardiologia →` Médicos de especialidad "Cardiología"

- `GET /api/listarPacientesBogota →` Pacientes que viven en Bogotá

- `GET /api/listarCitasPacientes30 →` Citas de pacientes mayores de 30 años


------
## ⚙️ Instalación y ejecución

### Clonar el repositorio:

- git clone https://github.com/usuario/proyecto-citas.git


### Instalar dependencias:

- composer install

- Configurar variables de entorno (.env):

- DB_DATABASE=citas <br>
DB_USERNAME=root <br>
DB_PASSWORD= <br>


### Ejecutar migraciones:

- php artisan migrate


### Iniciar servidor:

- php artisan serve

----
## ✅ Notas

- Usar Bearer Token en los endpoints protegidos, esta verificación se realizó por medio de postman.

- Los controladores implementan validación con Validator para evitar datos inválidos.

- La API devuelve respuestas en formato JSON con mensajes de error claros.