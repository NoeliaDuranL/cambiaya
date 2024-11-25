# *Configuracion para el BACKEND:*

### Habilitar la extensión sodium:
Habilitar la extensión sodium en XAMPP
Abrir el archivo php.ini:

Navega al directorio de instalación de XAMPP, por ejemplo: C:\xampp\php.
Abre el archivo php.ini con un editor de texto (como Notepad o Visual Studio Code).

Busca la línea que dice:
```
;extension=sodium
```
Quita el ';' al inicio para habilitarla:

```
extension=sodium
```
Guarda los cambios en php.ini.
Reinicia Apache desde el Panel de Control de XAMPP.


En la línea de comandos, ejecuta:
```
php -m
```
para revisar si la extension esta habilitada

Una vez que la extensión esté habilitada, ejecuta el comando nuevamente:
```
composer install
```
O, si es necesario actualizar dependencias:
```
composer update
```




## Ejecuta tu backend de la siguiente manera:
```
php artisan serve --host=0.0.0.0 --port=8000
```
