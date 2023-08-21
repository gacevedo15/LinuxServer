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
    //De cabecera mostraremos el nombre de la app y en dependencia de si hay sesión iniciada o no, mostraremos opciones.
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
    ?>

    <a class="back" href="carlist.php">Volver atrás</a> <!-- Link para regresar a carlist.php -->
    <table>
        <?php
            //Incluimos el fichero con la lógica para establecer conexión a la bbdd y el fichero que almacena la clase Car
            require('car.inc.php');
            require('dbConn.php');

            $selected_car = new Car(); //Declaramos un objeto tipo Car        

            //Creamos una función que establezca conexión con el servidor y realice una query filtrando como condición el id
            function search($id=NULL){
             $conn = openConn('m09');
             $sql="SELECT * FROM flota WHERE id = '" . $_GET['id']. "'";              
             $result = $conn->query($sql);   
             closeConn($conn);
             return $result;
            }    

            //Si se envían datos con método POST mediante el form 'run' ejecutará lo siguiente:
            if(isset($_POST['run'])){
                if(isset($_SESSION["name"])){ 
                    $selected_car->run($_SESSION['id']);
                    
                    // Actualizar la base de datos con la información de utilización
                    $conn = openConn('m09');
                    $sql = "UPDATE flota SET idusuario = '" . $_SESSION['id'] . "', iniciotrayecto = NOW() WHERE id = '" . $_GET['id'] . "'";
                    $conn->query($sql);
                    closeConn($conn);
                } else {
                    echo '<div class="error_user_not_logged"><a class="error_user_not_logged">¡Debes iniciar sesión para utilizar el coche!</a></div>';
                }
            }

            //Si se envían datos con método POST mediante el form 'dejarDeUtilizar' ejecutará lo siguiente:
            if(isset($_POST['dejarDeUtilizar'])){
                $selected_car->setObservaciones($_POST['observaciones']);
                $selected_car->stop($selected_car->getObservaciones());
                
                // Actualizar la base de datos con la información de dejar de utilizar
                $conn = openConn('m09');
                $sql = "UPDATE flota SET idusuario = NULL, iniciotrayecto = NULL WHERE id = '" . $_GET['id'] . "'";
                $conn->query($sql);
                closeConn($conn);
            }

            //Declaramos la variable que utilizaremos como parámetro para llamar la función search()
            $mySearch=NULL;

            //Si en la url hay algún id, la variable $mySearch obtendrá como valor dicho id
            if(isset($_GET['id'])){
             $mySearch=$_GET['id'];
            }

            //Finalmente almacenamos en $result lo que nos devuelva la función search() con la variable $mySearch introducida como parámetro
            $result = search($mySearch);
    
            //Bucle que recorra en nuestra tabla, todos los campos de todos los resultados
            while($row = $result->fetch_assoc()){ 
                /*Si idusuario es NULL, significa que el coche está libre, por lo que mostrará un botón para poder utilizarlo, el cual al ser ejecutado
                enviará el form 'run' con método post*/
                if($row['idusuario']==NULL){ 
                    echo '
                        <tr> 
                            <th scope="col">Coche</th>           
                            <th scope="col">Matrícula</th>
                            <th scope="col">Modelo</th>
                            <th scope="col">Ciudad</th>
                            <th scope="col">Uso</th>   
                            <th scope="col">Observaciones</th>  
                            <th scope="col">El coche está libre</th>    
                        </tr>    
                    ';
                    echo '
                        <tr>    
                            <td><img src="/img/'.$row['modelo'].'.jpg"></td>           
                            <td>'.$row['matrícula'].'</td>
                            <td>'.$row['modelo'].'</td>
                            <td>'.$row['ciudad'].'</td>
                            <td>'.$row['uso'].'</td>
                            <td>'.$row['observaciones'].'</td>  
                            <td><form action="" method="post">               
                            <button type="submit" class="btn btn-primary btn-block btn-large" value="Utilizar" name="run">Utilizar</button> 
                            </form></td>                              
                        </tr>'; 
                /*Si idusuario coincide con el id de la sesión, significa que el coche está siendo utilizado por ese usuario, por lo que mostrará
                un botón para dejar de utilizarlo, el cual enviará el form 'dejarDeUtilizar' con método post*/        
                }elseif($row['idusuario']==$_SESSION['id']){
                    echo '
                        <tr> 
                            <th scope="col">Coche</th>           
                            <th scope="col">Matrícula</th>
                            <th scope="col">Modelo</th>
                            <th scope="col">Ciudad</th>
                            <th scope="col">Uso</th>   
                            <th scope="col">Observaciones</th> 
                            <th scope="col">Añadir observaciones</th> 
                            <th scope="col">El coche está siendo utilizado por usted</th>    
                        </tr>    
                    ';      
                    echo '
                        <tr>    
                            <td><img src="/img/'.$row['modelo'].'.jpg"></td>           
                            <td>'.$row['matrícula'].'</td>
                            <td>'.$row['modelo'].'</td>
                            <td>'.$row['ciudad'].'</td>
                            <td>'.$row['uso'].'</td>
                            <td>'.$row['observaciones'].'</td>                                
                            <td>
                                <form action="cardetails.php?id='.$row['id'].'" method="post"> 
                                    <textarea rows="4" cols="50" name="observaciones"></textarea> 
                                    <td>
                                    <button type="submit" class="btn btn-primary btn-block btn-large" value="Dejar de utilizar" name="dejarDeUtilizar">Dejar de utilizar</button>                 
                                    </td>
                                </form>          
                            </td>                              
                        </tr>';   
                /*Si no está libre, ni está siendo utilizado por el usuario con sesión iniciada, significa que el coche está siendo utilizado por otro
                usuario, si este es el caso, mostraremos un mensaje indicando esto y no habrá opción para utilizar*/                 
                }else{
                    echo '
                        <tr> 
                            <th scope="col">Coche</th>           
                            <th scope="col">Matrícula</th>
                            <th scope="col">Modelo</th>
                            <th scope="col">Ciudad</th>
                            <th scope="col">Uso</th>   
                            <th scope="col">Observaciones</th>                  
                            <th scope="col">El coche está siendo utilizado</th>    
                        </tr>    
                    ';      
                    echo '
                        <tr>    
                            <td><img src="/img/'.$row['modelo'].'.jpg"></td>           
                            <td>'.$row['matrícula'].'</td>
                            <td>'.$row['modelo'].'</td>
                            <td>'.$row['ciudad'].'</td>
                            <td>'.$row['uso'].'</td>
                            <td>'.$row['observaciones'].'</td>                    
                            <td>El coche está siendo utilizado por otra persona</td>                              
                        </tr>';   
                }
            }
        ?>
    </table>
</body>
</html>