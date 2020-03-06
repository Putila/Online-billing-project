<html>
<head>
    <meta charset="utf-8">
    <title>Tiko HT</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body id="sivu">
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="../index.php">Etusivu</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Asiakkaat
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="../asiakkaat/nyk_asiakkaat.php">Nykyiset asiakkaat</a></li>
                    <li><a href="../asiakkaat/lisaa_asiakas.php">Lisää asiakas</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle" dauta-toggle="dropdown" href="#">Kohteet
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="../kohteet/nykyiset_kohteet.php">Nykyiset kohteet</a></li>
                    <li><a href="../kohteet/lisaa_kohde.php">Lisää kohde</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Työt
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="../tyot/nykyiset_tyot.php">Nykyiset työt</a></li>
                    <li><a href="../tyot/lisaa_tyo.php">Lisää työ</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Tarvikkeet
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="../tarvikkeet/tarvikelista.php">Lista tarvikkeista</a></li>
                    <li><a href="../tarvikkeet/lisaa_tarvike.php">Lisää tarvike</a></li>
                    <li><a href="../tarvikkeet/historia.php">Tarvikehistoria</a></li>
                    <li><a href="../tarvikkeet/paivita.php">Päivitä tarvikelista</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Laskut
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="../laskut/laheta_lasku.php">Lähetä lasku</a></li>
                    <li><a href="../laskut/Laheta_urakka_lasku.php">Lähetä urakkalasku</a></li>
                    <li><a href="../laskut/kaikki_laskut.php">Kaikki laskut</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Tarjoukset
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="../tarjoukset/hinta_arvio.php">Hinta-arvio</a></li>
                    <li><a href="../tarjoukset/urakkatarjous.php">Urakkatarjous</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
   <form action="historia.php" method="post" id="tarvike" style="margin-left:10px;width:700px;" >
      <p>Näytä tietyn tarvikkeen historia: </p>

      <?php

      // Tulostaa ennaltamaaratyn kyselyn WWW-sivulle
      //echo "Tietokannassa olevat opiskelijat. <br /><br />";

      $y_tiedot = "host=dbstud2.sis.uta.fi port=5432 dbname=mr428083 user=mr428083 password=salasana";

      if (!$yhteys = pg_connect($y_tiedot))
         die("Tietokantayhteyden luominen epäonnistui.");

      pg_query("SET SEARCH_PATH TO SahkoFirma;");
      $tyot = pg_query("SELECT Nimi,Tarvike_id  FROM Tarvikkeet");

      echo '<select name="tarvike" class="form-control">';
      while ($tRivi =  pg_fetch_row($tyot)){
         echo '<option value="' .$tRivi[1].'">Tuote: '.$tRivi[0].'</option>';
      }
      pg_close($yhteys);
      ?>
    </form>
    <input type="hidden" name="tallenna" value="jep" />
    <input type="submit"  class="btn btn-primary" value="Valitse"  style="background-color:#008080; width:80px;height:40px;margin-left:620px;margin-top:5px;" />

    <div class="card" style="width: 880px;margin-top:10px;">
    <ul class="list-group list-group-flush">
      <div class="titles" style="color:white;background-color:#008080;display:flex;padding-top:5px;padding-left:5px;"><p style="width:90px;padding-left:10px;"><b>Id</b> </p>
      <p style="width:200px;padding-left:10px;"><b>Nimi</b> </p> <p style="width:130px;"><b>Tarvike id</b> </p><p style="width:120px;"><b>Ostohinta</b> </p><p style="width:120px;"> 
      <b>Myyntihinta</b></p><p style="width:140px;"> <b>Vanhenemispäivä</b></p></div>
    <?php

    //yhteyden luomiseen tiedot
    $y_tiedot = "host=dbstud2.sis.uta.fi port=5432 dbname=mr428083 user=mr428083 password=salasana";
    //onnistuuko yhteyden luominen
    if (!$yhteys = pg_connect($y_tiedot))
       die("Tietokantayhteyden luominen epäonnistui.");
     //asetetaan search path oikeaksi
    pg_query("SET SEARCH_PATH TO SahkoFirma;");
    $tarvikeId = pg_escape_string($_POST['tarvike']);
    $tulos = pg_query("SELECT * FROM Historia WHERE Tarvike_id=$tarvikeId  ORDER BY VanhenemisPv;");

    while ($rivi = pg_fetch_row($tulos)) {
      echo '<li class="list-group-item">';
      echo '<div style="display:flex;">';
      echo '<p style="width:70px;margin-left:5px;">';
      echo "$rivi[0]</p>";
      echo '<p style="width:200px;">';
      echo "$rivi[2]</p>";
      echo '<p style="width:120px;margin-left:20px">';
      echo "$rivi[1]</p>";
      echo '<p style="width:120px;padding-left:5px">';
      echo "$rivi[3]</p>";
      echo '<p style="width:120px;padding-left:20px">';
      echo "$rivi[4]</p>";
      echo '<p style="width:140px;padding-left:20px">';
      echo "$rivi[5]</p>";
      echo '</div>';
      echo '</li>';
    } 
   

    pg_close($yhteys);
    
    ?>
    </ul>
    </div>


</body>
</html>