<?php
session_start(); //Recordamos sesión
session_unset(); //Deshacemos sesión
session_destroy(); //Destruimos sesión
header("Location:index.php"); //Redireccionamos a index.php
?>