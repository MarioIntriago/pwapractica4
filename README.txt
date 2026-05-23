PROYECTO: Sistema de calificaciones de un docente
Lenguajes: HTML, CSS, JavaScript, PHP y MySQL
Base de datos: 01_calif

USUARIOS DE PRUEBA
Docente:
email: docente@gmail.com
clave: 1234

Estudiante:
email: mario@gmail.com
clave: 1234

CÓMO USAR EN LARAGON O XAMPP

1. Copia la carpeta "pwapractica4" dentro de:
   C:\laragon\www\

2. Enciende Laragon:
   Start All

3. Entra a phpMyAdmin:
   http://localhost/phpmyadmin

4. Importa el archivo:
   sql/01_calif.sql

5. Abre el sistema:
   http://localhost/pwapractica4

6. Inicia sesión con los usuarios de prueba.

CÓMO SUBIR A GITHUB

1. Crea un repositorio llamado:
   pwapractica4

2. Sube todos los archivos de la carpeta.

3. Tu URL quedará similar a:
   https://github.com/tu_usuario/pwapractica4

CÓMO SUBIR A INFINITYFREE

1. Sube los archivos al directorio:
   htdocs

2. Crea una base de datos MySQL en InfinityFree.

3. En phpMyAdmin de InfinityFree importa:
   sql/01_calif.sql

4. Edita config/conexion.php con los datos reales de InfinityFree:
   $DB_HOST
   $DB_USER
   $DB_PASS
   $DB_NAME

NOTA IMPORTANTE
Este proyecto usa contraseñas simples en texto plano para que sea fácil de revisar en una tarea básica/intermedia.
En un sistema real se debería usar password_hash() y password_verify().
