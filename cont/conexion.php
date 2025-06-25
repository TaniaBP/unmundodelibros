<?php
    
$local="localhost";
$user="root";
$pwd="";
$bd="library";
$con= mysqli_connect($local,$user,$pwd,$bd);
$id_usr=0;
if(!$con){
 echo"<h1>conexion exitosa</h1>";
 session_start(); // Iniciar la sesión
}
?>