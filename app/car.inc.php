<?php

class Car
{
    protected $id;
    protected $matricula;
    protected $modelo;
    protected $ciudad;
    protected $uso;
    protected $idusuario;
    protected $iniciotrayecto;
    protected $observaciones;

    public function setObservaciones($observacionesNuevas)
    {
        $this->observaciones = $observacionesNuevas;
    }

    public function getObservaciones()
    {
        return $this->observaciones;
    }

    public function run($idusuario)
    {
        $conn = openConn('m09');

        // Obtener la hora actual en formato UNIX_TIMESTAMP
        $currentTimestamp = time();

        $sql = "UPDATE flota SET idusuario = $idusuario, iniciotrayecto = $currentTimestamp WHERE id = '" . $_GET['id'] . "'";
        $result = $conn->query($sql);
        closeConn($conn);
        return $result;
    }

    function stop($observaciones) {
      $conn = openConn('m09');
  
      // Calcular el tiempo transcurrido en segundos
      $calculateTimeQuery = "SELECT TIMESTAMPDIFF(SECOND, flota.iniciotrayecto, NOW()) AS tiempo_pasado FROM flota WHERE id = '" . $_GET['id'] . "'";
      $result = $conn->query($calculateTimeQuery);
      $row = $result->fetch_assoc();
      $tiempo_pasado = $row['tiempo_pasado'];
  
      // Consulta de actualización
      if (empty($observaciones)) {
          $sql = "UPDATE flota
                  INNER JOIN usuarios ON usuarios.id = flota.idusuario
                  SET
                      flota.uso = flota.uso + $tiempo_pasado,
                      usuarios.uso = usuarios.uso + $tiempo_pasado,
                      flota.idusuario = NULL,
                      flota.iniciotrayecto = NULL
                  WHERE flota.id = '" . $_GET['id'] . "'";
      } else {
          $sql = "UPDATE flota
                  INNER JOIN usuarios ON usuarios.id = flota.idusuario
                  SET
                      flota.uso = flota.uso + $tiempo_pasado,
                      usuarios.uso = usuarios.uso + $tiempo_pasado,
                      flota.idusuario = NULL,
                      flota.iniciotrayecto = NULL,
                      flota.observaciones = CONCAT(CURRENT_DATE, ' - ', '" . $conn->real_escape_string($observaciones) . "')
                  WHERE flota.id = '" . $_GET['id'] . "'";
      }
  
      // Ejecutar consulta de actualización
      if ($conn->query($sql) === TRUE) {
          echo '<div class="success">El coche ha sido dejado de utilizar correctamente.</div>';
      } else {
          echo '<div class="error">Error al dejar de utilizar el coche: ' . $conn->error . '</div>';
      }
  
      closeConn($conn);
  }
  
}

?>
