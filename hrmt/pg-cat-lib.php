<?php
session_start();
include('user.php');

    $idConectado=3;
    $perfil="";
    $nom_usr="Abi";
    $email_usr="abirojas@gmail.com";

    //echo $idConectado.", ". $perfil.", ". $nom_usr.", ". $email_usr;

 $QueryUsers = ("SELECT * FROM book  ORDER BY Nombre ASC ");
  $resultadoQuery = mysqli_query($con, $QueryUsers);

?>
<div class="status-bar"></div>
  <div class="row heading">
    <div class="col-sm-8 col-xs-8 heading-avatar">
      <div class="heading-avatar-icon">
        <img src="<?php echo 'imagenesperfil/' . $imgPerfil; ?>">
        <strong style="padding: 0px 0px 0px 20px;">
          <?php echo $nom_usr; ?>
        </strong>
      </div>
    </div>

    <div class="col-sm-1 col-xs-1 heading-compose  pull-right">
      <a href="acciones/salir.php?id=<?php echo $idConectado; ?>">
        <i class="zmdi zmdi-power" style="font-size: 25px;"></i>
      </a>
    </div>
    <div class="col-sm-1 col-xs-1  pull-right icohome">
      <a href="home.php">
        <i class="zmdi zmdi-refresh zmdi-hc-2x"></i>
      </a>
    </div>
  </div>

  <div class="row searchBox">
    <div class="col-sm-12 searchBox-inner">
      <div class="form-group has-feedback">
        <input id="searchText" type="search" class="form-control" name="searchText" placeholder="Buscar">
        <span class="glyphicon glyphicon-search form-control-feedback"></span>
      </div>
    </div>
  </div>

  <div class="row sideBar">
       <?php
    include "conexion.php"; // Conecta una sola vez antes del while

while ($FilaUsers = mysqli_fetch_array($resultadoQuery)) {
    $isbn = $FilaUsers['ISBN'];
      $libro = $FilaUsers['Nombre'];

    // IMPORTANTE: Asegúrate de que $idConectado tenga un valor válido aquí
    $consulta = "SELECT * FROM msjs WHERE id_usr='$idConectado' AND ISBN='$isbn' AND leido='NO'";
    
    $re = mysqli_query($con, $consulta);

    if ($re) {
       
        $numero_filas = mysqli_num_rows($re);
        // Puedes usar $numero_filas aquí
    


      //Buscando los msjs que estan sin leer por dicho usuario en sesion.
      $no_leidos = '';
      if ($numero_filas > 0) {
        $res = ("SELECT * FROM msjs WHERE user_id='$id_persona' AND leido='NO' ");
        $ree  = mysqli_query($con, $res);
        if ($cantMsjs = mysqli_num_rows($ree) > 0) { ?>
          <div style="display:none;">
            <audio controls autoplay>
              <source src="effect.mp3" type="audio/mp3">
            </audio>
          </div>
      <?php
        }
        }
      }
     $no_leidos = $numero_filas;
      ?>

      <div class="row sideBar-body" id="<?php echo $FilaUsers['Nombre']; ?>">
        <div class="col-sm-3 col-xs-3 sideBar-avatar">
          <?php
          if ($FilaUsers['ISBN'] != '') { ?>
            <div class="avatar-icon">
              <img src="<?php echo "hola imagen"; ?>" class="notification-container" style="border:3px solid #28a745 !important;">
            </div>
          <?php } else { ?>
            <div class="avatar-icon">
              <img src="<?php echo  "hola imagen "; ?>" class="notification-container" style="border:3px solid #696969 !important;">
            </div>
          <?php } ?>
        </div>

        <div class="col-sm-9 col-xs-9 sideBar-main">
          <div class="row">
            <div class="col-sm-8 col-xs-8 sideBar-name">
              <span class="name-meta">
                <?php
                echo $FilaUsers['Autor'];
                ?>
              </span>
            </div>
            <div class="col-sm-4 col-xs-4 pull-right sideBar-time" style="color:#93918f;">
              <span class="notification-counter">
                <?php echo $no_leidos; ?>
              </span>
            </div>
          </div>
        </div>
      </div>
    

<script src="../../js/jquery-3.1.1.min.js"></script>
<script src="../../js/pg-foro-ca.php"></script>
<?php 
  } ?>