## Descripción del Proyecto

Aplicacón web destinada a gestionar el ciclo de producción de máquinas industriales. En la cual cada máquina realiza tareas que generan tiempo de producción. Al completarse 5 tareas con una máquina, esta entra en un periodo de inactividad calculado en el lado del servidor y visualizado en esta aplicación.

### Lógica de Negocio y Ciclo de Producción

- **Gestión de Producción:** El sistema gestiona el ciclo de vida de las máquinas y sus tareas. Cada máquina registra el tiempo de producción generado por cada tarea.
    
- **Cálculo de Inactividad:** Cuando una máquina completa 5 tareas, un job en el lado del servidor se encarga de calcular el tiempo de inactividad que tendrá la máquina. Esta información se almacena y se expone al frontend para su visualización.

### Modelo de Datos

- **Base de Datos:** Utilizaremos MySQL para almacenar los datos de las máquinas, tareas y producciones.


---

### Endpoints de la API

- **Maquinas** (CRUD Completo)
    
    - `GET /api/maquinas`: Obtiene todas las máquinas.
        
    - `POST /api/maquinas`: Crea una nueva máquina.
        
    - `GET /api/maquinas/{id}`: Obtiene una máquina por su ID.
        
    - `PUT /api/maquinas/{id}`: Actualiza una máquina.
        
    - `DELETE /api/maquinas/{id}`: Elimina una máquina.
        
- **Tareas**
    
    - `GET /api/tareas`: Obtiene todas las tareas.
        
    - `PUT /api/tareas/{id}`: Actualiza una tarea.
        
- **Producciones**
    
    - `GET /api/producciones`: Obtiene todas las producciones.

---


### Instrucciones de Instalación y Ejecución

#### 1\. Clonar el repositorios

Bash

```
git clone git@github.com:jcardosorios/prueba_tecnica_gestion_maquinas_backend.git backend
git clone git@github.com:jcardosorios/prueba_tecnica_gestion_maquinas_frontend.git frontend
```


#### 2\. Instalar dependencias

Ve a los directorios del proyecto e instala las dependencias:

Bash

```
# Backend
cd backend
composer install

# Frontend
cd frontend
npm install

```

#### 3\. Configurar variables de entorno

En el directorio del backend, copia el archivo `.env.example` a un archivo `.env` y agrega las siguientes variables de entorno:

.env

```
DB_CONNECTION=Tipo de base de datos (en nuestro caso mysql)
DB_HOST=Host de la base de datos
DB_PORT=Puerto (en nuestro caso 3306)
DB_DATABASE=Nombre de la base de datos
DB_USERNAME=Usuario de la base de datos
DB_PASSWORD=Contraseña de la base de datos
SANCTUM_STATEFUL_DOMAINS=Host del frontend (Para nuestro entorno de desarrollo http://localhost:5173/)
```

En el directorio de frontend, copia el archivo `.env.example` a un archivo `.env.local` y agrega las siguientes variables de entorno:

.env.local

```
VITE_API_BASE_URL=Host del backend (Para nuestro entorno de desarrollo http://localhost:8000/api)
```

#### 4\. Limpiar cache de configuraciones

Modifica la zona horaria a utilizar en caso que se tenga una predefinida para el backend.

Bash

```
php artisan config:clear
```
#### 5\. Ejecuta migraciones y seeders

Genera tablas en la base de datos:

Bash

```
# En directorio del backend

php artisan migrate --seed
```

#### 6\. Levantar el servidor backend

Inicia el servidor local de Laravel:

Bash

```
cd backend
php artisan serve
```

El backend estará disponible en http://localhost:8000.

#### 7\. Iniciar worker para escucha Jobs

En una nueva terminal, en la carpeta de backend ejecuta:

Bash

```
cd backend
php artisan queue:listen
```

#### 8\. Levantar el servidor frontend

En una nueva terminal, ir a la carpeta de frontend y ejecutar servidor:

Bash

```
cd frontend
npm run dev
```

El frontend estará disponible en http://localhost:5173.

---

### Prompts de IA usados para dudas con Gemini

- A partir de ahora tomarás el papel de un desarrollador senior especializado en Laravel version 12.22 y me ayudarás explicando paso a paso a las preguntas que te haga en relación a la creación de un backend utilizando Laravel.
- Poseo conocimientos de PHP pero no de laravel, explica como crear un CRUD en laravel, dando enfasis a la estructura de la base de datos con mysql.
- Explica paso a paso como generar un seeder en laravel
- Explica las diferencias entre utilizar un comando artisan, job o funcion al momento de querer implementar una rutina automatizada, en este caso la tarea automatizada se debe ejecutar cuando se cumplen una condicion de conteo de elementos en una tabla en la base de datos
- Como creo relaciones entre dos modelos cuando hay una relacion uno es a uno?
- En que consiste el hasFactory, dame un ejemplo de uso de este metodo.
- Como aplicar reglas personalizadas para validacion? Dame un ejemplo explicado paso a paso
- Puede una regla devolver un valor calculado dentro de ella?
- Explicame paso a paso como generar un job que verifique de la tabla tareas cuantos elementos cuya columna estado sea igual a 'PENDIENTE', la columna "id_produccion" sea nula y que las columnas fecha_hora_inicio y fecha_hora_termino sean no nulos. Si son mas de 5 que cree un elemento en la tabla produccion y realice modificaciones en los elementos de la tabla tareas correspondientes.
- Como puedo obtener el siguiente dia habil de una fecha dada?