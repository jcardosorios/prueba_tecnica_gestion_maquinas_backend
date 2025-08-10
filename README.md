### Comandos en orden
php artisan migrate
php artisan db:seed

## Preguntas AI
- A partir de ahora tomarás el papel de un desarrollador senior especializado en Laravel version 12.22 y me ayudarás explicando paso a paso a las preguntas que te haga en relación a la creación de un backend utilizando Laravel.
- Poseo conocimientos de PHP pero no de laravel, explica como crear un CRUD en laravel, dando enfasis a la estructura de la base de datos con mysql.
- explica paso a paso como generar un seeder en laravel
- explica las diferencias entre utilizar un comando artisan, job o funcion al momento de querer implementar una rutina automatizada, en este caso la tarea automatizada se debe ejecutar cuando se cumplen una condicion de conteo de elementos en una tabla en la base de datos
- Como creo relaciones entre dos modelos cuando hay una relacion uno es a uno?
- En que consiste el hasFactory, dame un ejemplo de uso de este metodo.
- Como aplicar reglas personalizadas para validacion? Dame un ejemplo explicado paso a paso
- Puede una regla devolver un valor calculado dentro de ella?
- Explicame paso a paso como generar un job que verifique de la tabla tareas cuantos elementos cuya columna estado sea igual a 'PENDIENTE', la columna "id_produccion" sea nula y que las columnas fecha_hora_inicio y fecha_hora_termino sean no nulos. Si son mas de 5 que cree un elemento en la tabla produccion y realice modificaciones en los elementos de la tabla tareas correspondientes.