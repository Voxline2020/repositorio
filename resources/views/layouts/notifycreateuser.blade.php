<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Notificación</title>
</head>
<body>
		<h2>Creación de usuario</h2>
		<br>
		<p>Hola {{$name}}, bienvenido al sistema VxCMS.</p>
		<br>
    <p>Tus datos de acceso al sistema son los siguientes:</p>
    <ul>
				<li>Usuario: {{$email}}</li>
        <li>Contraseña temporal: {{$password}} </li>
		</ul>
		<p>Para acceder al sistema debes ingresar al siguiente link: <a href="http://vxcms.voxline.cl/">http://vxcms.voxline.cl/</a></p>
</body>
</html>
