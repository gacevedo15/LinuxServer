<?php
    session_start(); //Recordamos sesión

    /*Pasamos condición que evaluará el rol de la sesión. Si es diferente a admin, redireccionará directamente a index.php, 
    de esta manera no será posible acceder ni mediante la url*/
    if($_SESSION['rol'] != 'admin'){
        header("Location:../index.php");
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
    <?php
    //De cabecera mostraremos el nombre de la app y en dependencia de si hay sesión iniciada o no, mostraremos opciones.
        if(isset($_SESSION["name"])){ // Si hay sesión, mostraremos el nombre de la sesión, así como la opción para cerrar la sesión.
            echo '
                <header>
                    <div class="main-header">
                        <div class="logo"><a class="logo" href="../index.php">MUÉVETE CON SALLEGO</a></div>			
                        <nav>
                            <a>Bienvenid@: '.$_SESSION['name'].'</a>';                
                            echo '<a href="../logout.php">Cerrar sesión</a>                		
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
    ?>  
<div class="cpanel"><h1>Bienvenid@ al CPanel de usuarios</h1></div>
     
    <table>
        <tr> 
            <th scope="col">Id</th>           
            <th scope="col">Rol</th>
            <th scope="col">Username</th>
            <th scope="col">Password</th>
            <th scope="col">Uso</th>
            <th scope="col">Precio por segundo</th>  
            <th scope="col">Facturación</th> 
            <th scope="col">Modificar</th>           
        </tr> 
                 
        <?php        
            require('../dbConn.php'); //Incluimos fichero de conexión a bbdd

           //Abrimos conexión y establecemos consulta que seleccione todo de latabla usuarios 
           $conn = openConn('m09');
           $sql="SELECT * FROM usuarios";            
           $result = $conn->query($sql);   
           closeConn($conn);          
           
           /*Printamos en la tabla cada uno de los campos y una última columna para modificar datos del usuario, la cual redirigirá a 
           admin.update_user.php con el id de dicho usuario*/
           while($row = $result->fetch_assoc()){
             echo '
                <tr>    
                    <td>'.$row['id'].'</td>           
                    <td>'.$row['rol'].'</td>
                    <td>'.$row['username'].'</td>
                    <td>'.$row['password'].'</td>
                    <td>'.$row['uso'].'</td>
                    <td>'.$row['precio'].'</td>
                    <td>'.$row['facturacion'].'</td>
                    <td><form action="../admin/admin.update_user.php?id='.$row['id'].'" method="post">             
                    <button type="submit" class="btn btn-primary btn-block btn-large" value="Modificar" name="dejarDeUtilizar">Modificar</button>                 
                    </form></td>
                </tr>';
           }
        ?>       
    </table>
    <!-- Mostramos botón para insertar nuevo usuario que nos redirija a admin.new_user.php-->
    <table>
    <tr>
        <td>
            <form action="../admin/admin.new_user.php" method="post">                 
                <button type="submit" class="btn btn-primary btn-block btn-large" value="Insertar" name="Insertar">Registrar nuevo usuario</button>                 
            </form>
        </td>
    </tr>
</table>
</body>
</html>