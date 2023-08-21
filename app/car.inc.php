<?php

 class Car{    

   protected $id;
   protected $matricula;
   protected $modelo;
   protected $ciudad;
   protected $uso;
   protected $idusuario;
   protected $iniciotrayecto;
   protected $observaciones;

   //Método para establecer las observaciones
   public function setObservaciones($observacionesNuevas){
      $this->observaciones=$observacionesNuevas;
   }

   //Método para obtener las observaciones
   public function getObservaciones(){
      return $this->observaciones;
   }

   //Método que al ser llamado, ejecutará una consulta en la bbdd
   function run($idusuario) {
      $conn = openConn('m09');
      /*La consulta actualizará el coche con id igual al seleccionado en cardetails.php, cambiará el idusuario al introducido como parámetro,
      el iniciotrayecto será igual a la cantidad de segundos transcurridos desde la medianoche UTC del 1 de enero de 1970 desde la hora actual*/
      $sql="UPDATE flota SET idusuario=$idusuario, iniciotrayecto=(SELECT UNIX_TIMESTAMP(now()))
            WHERE id = '" . $_GET['id']. "'";                
      $result = $conn->query($sql);   
      closeConn($conn);
      return $result;
   }

   //Método que al ser llamado, ejecutará una consulta en la bbdd
   function stop($observaciones) {
      $conn = openConn('m09');  
      /*La consulta actualizará las tablas "flota" y "usuarios", el uso en ambas será igual a la suma del uso actual con la diferencia 
      de segundos que hay entre la función UNIX_TIMESTAMP(now()) (explicada en comentario del método 'run') e iniciotrayecto de flota
      (como la funión devuelve el valor en segundos, lo divido entre 60 y redondeo para llevarlo a minutos) 
      además, en flota, tanto el idusuario como iniciotrayecto serán NULL nuevamente y las observaciones serán: fecha actual, seguido de
      lo que hayamos añadido como observaciones*/

      //Si no se añaden observaciones, este campo no se actualiza y mantiene las observaciones que tenía anteriormente
      if(empty($observaciones)){
         $sql="UPDATE flota INNER JOIN usuarios ON usuarios.id = flota.idusuario SET
            flota.uso = flota.uso + (SELECT ROUND((UNIX_TIMESTAMP(now())-flota.iniciotrayecto) / 60, 0)),
            usuarios.uso = usuarios.uso + (SELECT ROUND((UNIX_TIMESTAMP(now())-flota.iniciotrayecto) / 60, 0)),    
            flota.idusuario=NULL, 
            flota.iniciotrayecto=NULL
            WHERE flota.id = '" .$_GET['id']. "'";  
      }else{ //Pero si se añaden observaciones, se actualiza el campo también
         $sql="UPDATE flota INNER JOIN usuarios ON usuarios.id = flota.idusuario SET
            flota.uso = flota.uso + (SELECT ROUND((UNIX_TIMESTAMP(now())-flota.iniciotrayecto) / 60, 0)),
            usuarios.uso = usuarios.uso + (SELECT ROUND((UNIX_TIMESTAMP(now())-flota.iniciotrayecto) / 60, 0)),    
            flota.idusuario=NULL, 
            flota.iniciotrayecto=NULL,  
            flota.observaciones=concat(CURRENT_DATE, ' - ', '" .$observaciones. "')
            WHERE flota.id = '" .$_GET['id']. "'"; 
      }
      
      if ($conn->query($sql) === TRUE) {
         echo '<div class="success">El coche ha sido dejado de utilizar correctamente.</div>';
     } else {
         echo '<div class="error">Error al dejar de utilizar el coche: ' . $conn->error . '</div>';
     }
     
      $result = $conn->query($sql);   
      closeConn($conn);
      return $result;
   }
 }
?>




