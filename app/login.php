<?php
session_start(); // Iniciar sesión

// Incluir el archivo de conexión
require('dbConn.php');

$message = "";

// Verificar si se envió el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Abrir conexión a la base de datos
    $conn = openConn('m09');
    
    // Realizar consulta para verificar las credenciales
    $result = mysqli_query($conn,"SELECT * FROM usuarios WHERE username='" . $_POST["username"] . "' and password = '". MD5($_POST["password"])."'");
    $row  = mysqli_fetch_array($result);
    
    // Si el usuario y contraseña coinciden, iniciar sesión
    if(is_array($row)) {
        $_SESSION["id"] = $row['id'];
        $_SESSION["name"] = $row['username'];
        $_SESSION["rol"] = $row['rol'];
        header("Location:index.php");
        exit(); // Importante: salir del script después de redirigir
    } else {  
        $message = 'Error: usuario y/o contraseña incorrectos!!';
    }
}
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
    <header>
		<div class="main-header">
        <div class="logo"><a class="logo" href="index.php">MUÉVETE CON SALLEGO</a></div>		
			<nav>
				<a href="login.php">Iniciar sesión</a>				
			</nav>
		</div>
	</header>

    <!-- Form que guardará con POST los datos introducidos en campos nombre de usuario y contraseña -->
    <div class="login">
        <h1>Iniciar sesión</h1>
        <?php
        if (!empty($message)) {
            echo '<h1 class="error_user_not_logged">' . $message . '</h1>';
        }
        ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="username" placeholder="nombre de usuario" required="required" />
            <input type="password" name="password" placeholder="contraseña" required="required" />
            <button type="submit" class="btn btn-primary btn-block btn-large" name="login">Iniciar</button>
        </form>
    </div>

</body>
</html>
