<?php
    session_start();
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
    <!-- Mantenemos el form que filtra por ciudad -->
    <div class="form-ciudad">
     <form method='get' action='carlist.php' class="form-search">
        <label class="introduce-ciudad"> Introduce ciudad: 
            <input type='text' name='search'>
        </label>
            <button type="submit" class="btn btn-primary btn-block btn-large" name="login">Buscar</button>
     </form>
    </div>
    <!-- Crearemos una tabla en la que mostraremos los resultados de búsqueda -->
    <table>
        <tr> 
            <th scope="col">Coche</th>           
            <th scope="col">Matrícula</th>
            <th scope="col">Modelo</th>
            <th scope="col">Ciudad</th>
            <th scope="col">Uso</th>
            <th scope="col">Detalles</th>            
        </tr> 

    <?php
        require('dbConn.php'); //Incluimos el archivo donde tenemos la lógica para establecer la conexión con la bbdd

        /*Creamos una función, la cual establecerá una conexión con la bbdd "m09" y realizaremos una consulta tipo select, en la cual, en caso
        de que se haya establecido una ciudad mediante el form, filtrará los resultados para que solo aparezcan los de dicha ciudad, se 
        almacenará el resultado en la variable $result y nos devolverá dicha variable*/
        function search($ciudad=NULL){
            $conn = openConn('m09');
            $sql = "SELECT id, matrícula, modelo, ciudad, uso FROM flota";
                    
            if ($ciudad) {
                $sql .= " WHERE ciudad = '" . $ciudad . "'";
            }
                    
            $result = $conn->query($sql);
            closeConn($conn);
            return $result;
        }
        
        //Declaramos la variable que utilizaremos como parámetro para llamar la función search()
        $mySearch=NULL;

        //Si en el form hemos indicado alguna ciudad, la variable $mySearch obtendrá como valor lo que hayamos escrito
        if(isset($_GET['search'])){
        $mySearch=$_GET['search'];
        }
        //Finalmente almacenamos en $result lo que nos devuelva la función search() con la variable $mySearch introducida como parámetro
        $result = search($mySearch);

        //Hacemos un bucle que recorra cada uno de los resultados y los printe como parte de la tabla
        while($row = $result->fetch_assoc()){
            echo '
            <tr>    
                <td><img src="/img/'.$row['modelo'].'.jpg"></td>           
                <td>'.$row['matrícula'].'</td>
                <td>'.$row['modelo'].'</td>
                <td>'.$row['ciudad'].'</td>
                <td>'.$row['uso'].'</td>                
                <td><form action="cardetails.php?id='.$row['id'].'" method="post">               
                <button type="submit" class="btn btn-primary btn-block btn-large" value="Detalles" name="Detalles">Ver detalles</button> 
                </form></td> 
            </tr>'; // Mostraremos también en la última columna, un botón que nos lleve a los detalles del coche filtrando por su id
        }
    ?>     
    </table>
</body>
</html>

