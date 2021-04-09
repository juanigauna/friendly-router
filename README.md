# Bienvenido a friendly-router

## ¿Qué es Friendly Router?
<span style="color: #27ae60; font-weight: bold;">Friendly Router</span> es un enrutador para php inspirado en Frameworks como Express y Laravel. Pero con este tenes más libertad para estructurar tu proyecto de manera más personalizada y que puedas elegir el camino más conveniente para él.

El Framework trata de ser amigable y facil de entender, también cuenta con su propio motor de vistas, vos solamente debes especificar la ruta donde estarán los archivos de diseño y listo.

## Comienzo rápido.
Para comenzar debes importar el router e instanciar un nuevo objeto <span style="color: #27ae60; font-weight: bold;">Router</span>.

Si tu proyecto no está en la directorio raíz de tu servidor, vas a tener que especificar el <span style="color: #27ae60; font-weight: bold;">base dir</span>. 

Cuando ya hayas hecho eso, podrás crear rutas y cuando tengas todas las rutas creadas, solamente tendrás que decirle al router que espere las solicitudes entrantes para las rutas, llamando al método <span style="color: #27ae60; font-weight: bold;">wait</span> del objeto Router.

Veamos un ejemplo:
```php
// importamos el objeto Router.
use \FriendlyRouter\Router;

// Instanciamos el objeto.
$router = new Router();

// Esto solo si nuestro proyecto no se encuentra en el directorio raíz de nuestro servidor.
$router->setBaseDir('your_current_project_directory');

// Creamos las rutas
$router->get('/', function($req, $res) {
    $res->content("
        Bienvenido a la página principal <br>
        Tu dirección ip es: {$req['REMOTE_ADDR']}
    ");
    $res->send();
});

// Le decimos al router que espere las solicitudes entrantes.
$router->wait();
```

Y luego deberíamos definir unas reglas de .htaccess. 

Creamos un archivo .htacces en el directorio base de nuestro proyecto y le pegamos el siguiente contenido:

```
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*) index.php [NC,QSA]
RewriteRule ^ index.php [NC,QSA]
```
El archivo "index.php" puede ser reemplzado por el archivo el cual destinen para ser su archivo principal en su proyecto.

El resultado sería el siguiente:

![](/doc/img/1.png)

## AHORA QUIERO USAR MI PROPIO MODELO DE PLANTILLAS >:(
Okey, bro, calmate. Pensando en eso es que implemente un view engine.

Para usar el view engine deberás especificar el directorio donde están todos los archivos de tu plantilla, en la ruta que vayas a usar una vista usaremos el tercer parametro de la función callback que correponde al view engine object.

Deberías hacerlo de la siguiente manera:
```php
use \FriendlyRouter\Router;

$router = new Router();
$router->setBaseDir('your_current_project_directory');

// Aquí especificamos el directorio donde estáran los archivos.
$router->setTemplatePath('views'); // En mi caso cree un directorio llamado "views".


$router->get('/', function($req, $res, $layout) {
    // Ahora usamos el método "render" y le pasaremos un objeto con instrucciones.
    $layout->render([
        "title" => "Página principal",
        // Esta propiedad es importante, acá ira la ruta de la vista de la siguiente forma: directorio/contenido
        // Ahora usamos el método "viewPath" para obtener la ruta de la vista y listo.
        "content" => $layout->viewPath('home/content'),
        // Acá abajo podemos seguir añadiendo información para luego usarla en la vista.
        "ip_address" => $req['REMOTE_ADDR'],
        "country" => "Argentina"
    ]);
});

$router->wait();
```
## ¿Cómo debe estar estructurado nuestro directorio de plantillas?
Tu directorio independiente del nombre que tenga, debe contener un archivo llamado "container.php" el cual debe
lucir algo similar a esto:
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($view['title']) ? $view['title'] : 'Engine view' ?></title>
</head>
<body>
    <div id="root">
        <?php include_once $view['content'] ?>
    </div>
</body>
</html>
```
Bastante simple lo sé, pero te ahorra bastante tiempo.

Ahora bien, cada sección de tu página tendrá vistas. No es necesario, pero te recomiento que cada vista tenga un directorio con el nombre de la sección, por ejemplo.

Si el directorio donde tengo todas mis plantillas se llama "views", entonces debería lucir así:

>     views/
>           home/
>               content.php
>           login/
>               content.php
>           register/
>               content.php
>           container.php

Luego pasando al contenido da las vistas debemos tener en cuenta que cada información adicional que pasemos en el render, esa información la podemos recuperar mediante la variable $view.

Por ejemplo:

```html
<h3>Bienvendio a la página principal</h3>
<p>Tu dirección ip es: <?php echo $view['ip_address'] ?></p>
<p>Y tu país es: <?php echo $view['country'] ?></p>
```

El resultado de todo esto debería ser este:

![](/doc/img/2.png)

## Parámentros del callback en las rutas.
### $request o $req
Contiene toda la información que pide el cliente a nuestro servidor.

Algo que usamos mucho de este parámetro son los códigos de estados, la información que se nos pasa mediante formularios, entre otras cosas. Más adelante subiré un archivo con todas propiedades de este parametro, por mientras les dejo las más útiles:

> - $request['body'] >> Nos brinda información de los formularios enviados.
> - $request['params'] >> Devuelve la lista completa de los parámetros dinámico puestos en nuestras url, por ejemplo.
> Si creamos una ruta del la siguiente manera:
>> ```php
>> $router->get('/user/:id', function($req, $res) {
>>   /* 
>>       Acá el parámetro dinámico de la url sería << :id >> este puede variar de url en url, debido a que los
>>       usuario se representan con un id único.
>>   */
>>   // para recuperar esa información (que vendría a ser el id), usamos $req['params']['nombre_del_parametro']
>>   $res->content("Id del usuario: {$req['params']['id']}");
>>   $res->send();
>> });
>> ```
>>       El resultado sería algo así:
>>
>>    ![](/doc/img/3.png)

### $response o $res
Nos permite elaborar una respuesta para el cliente, en si este parametro es un objeto el cual contiene los siguiente métodos:

> - $res-><span style="color: #27ae60; font-weight: bold;">status()</span> con este método indicamos el código de estado de la respuesta
> - $res-><span style="color: #27ae60; font-weight: bold;">content()</span> nos permite definir el contenido de la respuesta.
> - $res-><span style="color: #27ae60; font-weight: bold;">json()</span> con este método le indicamos que lo que vamos a devolver es un json, como parámetro le podemos pasar el objeto y nos ahorramos usar el método $res-><span style="color: #27ae60; font-weight: bold;">content()</span>.
> - $res-><span style="color: #27ae60; font-weight: bold;">send()</span> y por último este método nos permite enviar la respuesta. 