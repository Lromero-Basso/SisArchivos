
**Dato importante**: 
-El sistema antes de empezar a desarrollarlo se deberá poner en TRUE, en el archivo app.php, esto es para que uno como desarrollador vea las excepciones de Symfony, pero una vez finalizado y subido a producción, se tiene que volver a su estado FALSE, para que el usuario final NO vea las exepciones en rojo.
Para realizar cambios en producción, se deberán realizar primero localmente (locamente puede seguir en TRUE el valor del archivo .php) y posterior a eso una vez copiados y pegados los cambios mediante WinSCP, se abre la consola de este y se ejecuta el siguiente comando:
- php/bin console cache:clear --env=prod --no-debug

** Dato MUY importante **
-En el archivo .htaccess dentro de web, debemos descomentar la linea 12 (Options FollowSymlinks). Esto evita errores de http.
-En app_dev.php dentro de web, debemos comentar desde la linea 13 hasta la 19 (es un if). Esto nos permite poder poner en prod = true, el archivo app.php.
