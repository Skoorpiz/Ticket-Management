<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>

<head>
  <meta charset="UTF-8">
  <title></title>
  <script src="/edsa-gestion_ticket/amcharts4/core.js" type="text/javascript"></script>
  <script src="/edsa-gestion_ticket/amcharts4/charts.js" type="text/javascript"></script>
  <script src="/edsa-gestion_ticket/amcharts4/themes/animated.js" type="text/javascript"></script>
  <link href="/edsa-gestion_ticket/bootstrap-5.0.0-beta2-dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/edsa-gestion_ticket/includes/styles.css" rel="stylesheet" type="text/css" />
  <link href="/edsa-gestion_ticket/includes/styles.scss" rel="stylesheet" type="text/scss" />
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
  <script type="text/javascript" src="//code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  </script>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
  
</head>
<style>
  .bold {
    font-weight: bold;
  }
  .black {
    color : black;
  }
</style>
<body>
  <nav role='navigation' class="navbar navbar-expand-lg navbar-light " style="background-color: #e3f2fd;">
    <div class="container-fluid">
      <a <?php if(isset($page) && $page == "home"){ ?> class="nav-link black bold" <?php }else {?>  class="nav-link active black"  <?php } ?>  class="navbar-brand" href="/edsa-gestion_ticket/index.php">Home</a>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">

          <a <?php if(isset($page) && $page == "détails"){ ?> class="nav-link active bold" <?php }else {?>  class="nav-link active"  <?php } ?> aria-current="page" href="/edsa-gestion_ticket/detail.php">Détails</a>
          <div class="dropdown">
            <button type="button" <?php if(isset($page) && $page == "doublon") { ?> class="btn dropdown-toggle bold" <?php } else { ?> class="btn  dropdown-toggle" <?php } ?> data-toggle="dropdown">
              Gestion doublons
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="/edsa-gestion_ticket/doublon.php">Ajout de regroupement</a>
              <a class="dropdown-item" href="/edsa-gestion_ticket/gestionDoublon.php">Edition de regroupement</a>
            </div>
          </div>
          <a <?php if(isset($page) && $page == "ticket"){ ?> class="nav-link active bold" <?php }else {?>  class="nav-link active"  <?php } ?> class="nav-link active" aria-current="page" href="/edsa-gestion_ticket/ticket.php">Importation des tickets</a>
        </div>
      </div>
    </div>
    </div>
  </nav>
