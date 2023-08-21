<?php
    ob_start(); // Iniciar el buffer de salida
    session_start(); //Recordamos sesión

    /*Pasamos condición que evaluará el rol de la sesión. Si es diferente a admin, redireccionará directamente a index.php, 
    de esta manera no será posible acceder ni mediante la url*/
    if($_SESSION['rol'] != 'admin'){
        header("Location:../index.php");
        exit; // Agregamos un exit para detener la ejecución después de la redirección
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MUÉVETE CON SALLEGO</title>
    <link rel="stylesheet" type="text/css" href="../css/mystyle.css" />
</head>
<body>
    <header>
		<div class="main-header">
        <div class="logo"><a class="logo" href="../index.php">MUÉVETE CON SALLEGO</a></div>			
			<nav>
				<a><?php echo ''.$_SESSION['name'].''?></a> <!-- Mostramos nombre de la sesión en header -->
                <a href="../logout.php">Cerrar sesión</a>				
			</nav>
		</div>
	</header>
<a class="back" href="../admin/admin.php">Volver atrás</a>  
<div class="cpanel"><h1>Registrar nuevo usuario:</h1></div>
    <table>
        <tr>           
            <th scope="col">Rol</th>
            <th scope="col">Username</th>
            <th scope="col">Password</th>
            <th scope="col">Precio por minuto</th>  
            <th scope="col">Registrar</th>         
        </tr> 
                 
        <?php
            require('../dbConn.php'); //Incluimos fichero de conexión a bbdd          

            //Si se envían datos con método POST mediante el form 'insert' ejecutará lo siguiente:
            if(isset($_POST['insert'])){
                //Primero se establecerá la conexión con el servidor
                $conn = openConn('m09');
                if(empty($_POST['username']) || empty($_POST["password"]) || empty($_POST["precio"])){ //Si está vacío alguno de los campos, no se enviará el form y se mostrará mensaje de error indicando que debe rellenar los campos
                    echo '<h1 class="error_user_not_logged">Rellena todos los campos, por favor</h1>'; 
                }else{ //Si se rellenan todos los campos, se ejecutará una consulta tipo INSERT, la cual añadirá al nuevo usuario a la tabla usuarios             
                $sql="INSERT INTO usuarios (rol, username, password, precio)
                VALUES ('" . $_POST["rol"] . "', '" . $_POST["username"] . "', '". MD5($_POST["password"])."', '" . $_POST["precio"] . "')"; 
                $result = $conn->query($sql); 
                closeConn($conn);     
                header("Location:../admin/admin.php");    
               }
            }             
        ?>
           
        <tr>                  
            <form method="post" action="">
                <td>    
                    <select id="rol" name="rol" class="btn btn-primary btn-block btn-large">
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </td>    
    	        <td><input type="text" name="username" id="username"/></td>
                <td><input type="text" name="password" id="password"/></td>
                <td><input type="text" name="precio" id="precio"/></td>
                <td>                
                    <button type="submit" class="btn btn-primary btn-block btn-large" value="insert" name="insert" id="insert">Registrar usuario</button>
                </td>
            </form> 
        </tr>
    </table>
</body>
</html>

<?php
    ob_end_flush(); // Enviar el contenido almacenado en el buffer de salida al navegador
?>