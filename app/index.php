<?php
session_start(); //Recordamos sesión
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MUÉVETE CON SALLEGO</title>
    <link rel="stylesheet" type="text/css" href="css/mystyle.css" />
</head>
<body>

<?php
//De cabecera mostraremos el nombre de la app y en dependencia de si hay sesión iniciada o no / si el rol es admin o user / mostraremos opciones
if(isset($_SESSION["name"])){ // Si hay sesión, mostraremos el nombre de la sesión, así como la opción para cerrar la sesión.
    echo '
    <header>
		<div class="main-header">
        <div class="logo"><a class="logo" href="index.php">MUÉVETE CON SALLEGO</a></div>			
			<nav>
				<a>Bienvenid@: '.$_SESSION['name'].'</a>';                
                if($_SESSION["rol"] == 'admin'){ //Si la sesión es de un admin, dispondrá también de un link que le llevará al panel de administración de usuarios
                    echo '<a href="/admin/admin.php">Administrar usuarios</a>';
                }
                echo '<a href="logout.php">Cerrar sesión</a>                		
			</nav>
		</div>
	</header>';
}else { //Si no hay sesión, mostraremos el link para iniciarla.
    echo ' 
    <header>
		<div class="main-header">
        <div class="logo"><a class="logo" href="index.php">MUÉVETE CON SALLEGO</a></div>			
			<nav>
				<a href="login.php">Iniciar sesión</a>				
			</nav>
		</div>
	</header>';
}
?>   <!-- Pequeño form en el que se podrá introducir el nombre de la ciudad o no y nos redigirá a carlist.php -->
    <div class="form-ciudad">
    <form method='get' action='carlist.php' class="form-search">
        <label class="introduce-ciudad"> Introduce ciudad: 
            <input type='text' name='search'>
        </label>
        <button type="submit" class="btn btn-primary btn-block btn-large" name="login">Buscar</button>
    </form>
    </div>

</body>
</html>



