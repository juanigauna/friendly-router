# Friendly Router (Actualización Julio 2021).
He estado trabajando muchísimo para traer nuevas caracteristicas a mi proyecto.

Por parte de las rutas he mejorado los módulos **Request** y **Response**.

Y también le he dado un buen refactor a la clase Router por lo que vamos a poder encontrar cosas más coherentes a la hora de usarlo.
```php
$router->get('/', function(Request $req, Response $res) {
    return 'Hello :)';
});
```
### Ahora paso a explicar cada parte del proyecto, módulos y métodos.

*Aclaro que algunas funciones se usan en el proyecto que estes desarrollando y otras simplemente son para el uso entre los módulos del sistema, pero yo igual los pongo para que sepan cómo funciona todo y puedan seguir mejorando el proyecto.*

# Módulo **Request** 

En éste modulo vamos a encontrar datos del servidor y del cliente, cada solicitud que se genera por parte del cliente al servidor contiene información, tales como: header, archivos, datos de formularios, ip y entre otras muchas cosas.

## Métodos

## getUri() : string
```php
$req->getUri();
```
Nos devuelve la uri solicitada en la solicitud entrante.

## clearUri() : string
```php
$req->clearUri($uri);
```
Se encarga de limpiar de caracteres extraños en nuestra url, por ejemplo, tenemos una url que ingresa así al sistema:

> /text/name%20of%20my%20cat

Usando la función clearUri esa cadena de texto quedaría algo así:

> /text/name-of-my-cat

El objetivo principal es tener unas url limpias y amigables para el usuario, algo entendible a simple vista en la url.

## getMethod() : string
```php
$req->getMethod();
```
Este método nos devuelve el verbo http de la solicitud entrante, tales pueden ser: GET, POST, PUT, DELETE. Entre otros.

## setUrlParams(array $params)
```php
$req->setUrlParams($params);
```

## param(string $param) : mixed
```php
$req->param('id');
```
Nos devuelve el parámetro de nuestra url que estemos indicando.
> De no existir lo que estamos pidiendo por parámetro, nos lanzará un error.

## server(string $param) : mixed
```php
$req->server('REQUEST_URI');
```
Nos va a devolver información tal como cabeceras, rutas y ubicaciones de scripts.
> De no existir lo que estamos pidiendo por parámetro, nos lanzará un error.

## files(string $param) : mixed
```php
$req->files('photo');
```
En caso de una petición contenga archivos, podremos tratarlos con este método.
> De no existir lo que estamos pidiendo por parámetro, nos lanzará un error.
## session(string $param) : mixed
```php
$req->session('loggedIn');
```
Nos devuelve valores que establecimos si estábamos usando sesiones.
> De no existir lo que estamos pidiendo por parámetro, nos lanzará un error.

## cookie(string $param) : mixed
```php
$req->cookie('acceptCookies');
```
Nos devuelve valores que establecimos si estábamos usando cookies.
> De no existir lo que estamos pidiendo por parámetro, nos lanzará un error.

# Módulo **Response**

Acá nos centramos en la funcionalida que tenga que ver con dar una respuesta al cliente y a que tipo de contenido vamos a devolver.

## Métodos

## setHeader(string $header) : bool -> true
```php 
$res->setHeader('Access-Control-Allow-Origin: *');
```
Lo vamos a utilizar para establecer cabeceras a nuestro gusto.
Todos las cabeceras se van a almacenar en la variable *array $headers* de la clase *System\Http\Response*.

## getHeaders() : array
```php 
$res->getHeaders();
```
Mediante este método podemos acceder al array de headers.

## setContentType(string $type) : bool -> true
```php 
$res->setContentType('application/json');
```
Con este establecemos el tipo de contenido para la respuesta.

## json(array $content) : string
```php 
$data = [
    'id' => 55,
    'username' => 'pro_coder',
    'password' => 'mybirthday_123'
];
$res->json($data);
```
La utilizamos para devolver una respuesta tipo json, usando esta función nos evitamos especificar el tipo de contenido.