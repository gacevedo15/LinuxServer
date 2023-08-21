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
    <div class="cpanel"><h1>Actualizar usuario:</h1></div>
    <table>
        <tr> 
            <th scope="col">Id</th>           
            <th scope="col">Rol</th>
            <th scope="col">Username</th>
            <th scope="col">Password</th>
            <th scope="col">Uso</th>
            <th scope="col">Precio por minuto</th>  
            <th scope="col">Facturación</th>  
            <th scope="col">Actualizar</th>         
        </tr> 
                 
        <?php        
            require('../dbConn.php');  //Incluimos fichero de conexión a bbdd       

            //Creamos una función que establezca conexión con el servidor y realice una query filtrando como condición el id
            function search($id=NULL){
                $conn = openConn('m09');
                $sql="SELECT * FROM usuarios WHERE id ='" .$_GET['id']. "'";              
                $result = $conn->query($sql);  
                closeConn($conn);
                return $result;
            }   

            //Declaramos la variable que utilizaremos como parámetro para llamar la función search()
            $mySearch=NULL;

            //Si en la url hay algún id, la variable $mySearch obtendrá como valor dicho id
            if(isset($_GET['id'])){
             $mySearch=$_GET['id'];
            }

            //Finalmente almacenamos en $result lo que nos devuelva la función search() con la variable $mySearch introducida como parámetro
            $result = search($mySearch);

            //Si se envían datos con método POST mediante el form 'update' ejecutará lo siguiente:
            if(isset($_POST['update'])){
                //Primero se establecerá la conexión con el servidor
                $conn2 = openConn('m09');                
                if(empty($_POST['password']) && empty($_POST["precio"])){ //Si están vacíos tanto el campo password como precio, mostrará mensaje de error por pantalla indicando que deben ser completados
                    echo '<h1 class="error_user_not_logged">Introduce valores en los campos</h1>'; 
                }elseif(empty($_POST['password']) && !empty($_POST["precio"])){ //Si está vacío campo password, pero no precio, actualizará solamente el precio
                    $sql2="UPDATE usuarios SET precio = '" . $_POST["precio"] . "'
                    WHERE id ='" .$_GET['id']. "'"; 
                    $result2 = $conn2->query($sql2); 
                    closeConn($conn2); 
                    header("Location:../admin/admin.php");
                }elseif(!empty($_POST['password']) && empty($_POST["precio"])){ //Si está vacío campo precio, pero no password, actualizará solamente la password
                    $sql2="UPDATE usuarios SET password = '". MD5($_POST["password"])."'
                    WHERE id ='" .$_GET['id']. "'"; 
                    $result2 = $conn2->query($sql2); 
                    closeConn($conn2); 
                    header("Location:../admin/admin.php");
                }else{ //Si no están vacíos ninguno de los dos, se actualizarán ambos
                $sql2="UPDATE usuarios SET password = '". MD5($_POST["password"])."', precio = '" . $_POST["precio"] . "'
                    WHERE id ='" .$_GET['id']. "'"; 
                    $result2 = $conn2->query($sql2); 
                    closeConn($conn2);     
                    header("Location:../admin/admin.php");         
                }
            }              
           
            //Mostraremos por pantalla los datos del usuario seleccionado y mediante el form podremos isertar nueva password o precio por minuto
            while($row = $result->fetch_assoc()){
                echo '
                    <tr>   
                        <td>'.$row['id'].'</td>           
                        <td>'.$row['rol'].'</td>
                        <td>'.$row['username'].'</td>

                        <form method="post" action="">
    	                <td><input type="text" name="password" id="password" placeholder="'.$row['password'].' "/></td>
                        <td>'.$row['uso'].'</td>
                        <td><input type="text" name="precio" placeholder="'.$row['precio'].'"/></td>
                        <td>'.$row['facturacion'].'</td>
                        <td>                
                        <button type="submit" class="btn btn-primary btn-block btn-large" value="update" name="update" id="update">Actualizar</button>
                        </td>
                        </form>
                    </tr>';
            }
        ?>
    </table>
</body>
</html>

<?php
    ob_end_flush(); // Enviar el contenido almacenado en el buffer de salida al navegador
?>