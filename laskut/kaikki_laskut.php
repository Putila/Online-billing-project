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
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Kohteet
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

   <h3 style="margin-left:15px;">Tarkastele lähetettyjä laskuja:</h3>
    
   <div class="card" style="width: 900px;margin-left:20px;">
   <ul class="list-group list-group-flush">
      <div class="titles" style="color:white;background-color:#008080;display:flex;padding-top:5px;padding-left:5px;">
         <p style="width:80px;margin-left:5px;"><b>Lasku id</b> </p>
         <p style="width:190px;padding-left:20px;"><b>Kohde</b> </p>
         <p style="width:120px;"> <b>Työmuoto</b></p>
         <p style="width:140px;"><b>Loppusumma</b> </p>
         <p style="width:120px;padding-left:8px;"> <b>Eräpäivä</b></p>
         <p style="width:120px;"> <b>Maksupäivä</b></p>
         <p style="width:120px;"> <b>Maksettu</b></p></div>
    <?php

    //yhteyden luomiseen tiedot
    $y_tiedot = "host=dbstud2.sis.uta.fi port=5432 dbname=rp422600 user=rp422600 password=bigchungus";
    //onnistuuko yhteyden luominen
    if (!$yhteys = pg_connect($y_tiedot))
       die("Tietokantayhteyden luominen epäonnistui.");
     //asetetaan search path oikeaksi
    pg_query("SET SEARCH_PATH TO SahkoFirma;");

    $tulos = pg_query("SELECT lasku_id,kohteet.nimi, loppusumma,erapv,maksupv,tyosuoritukset.tyomuoto
    FROM tehtiin,kohteet,tyosuoritukset,laskut
    WHERE tila = true AND tyosuoritukset.tyo_id = laskut.tyo_id AND tyosuoritukset.tyo_id= tehtiin.tyo_id AND kohteet.kohde_id = tehtiin.kohde_id ORDER BY lasku_id;");

    if (!$tulos) {
      echo "Virhe kyselyssä.\n";
      exit;
    }
   
    while ($rivi = pg_fetch_row($tulos)) {
      $maksettu ="";
      if ($rivi[4] !=null){
         $maksettu= "Kyllä";
      }
      echo '<li class="list-group-item">';
         echo '<div style="display:flex;">';
            echo '<p style="width:90px;margin-left:5px;">';
               echo "$rivi[0]</p>";
            echo '<p style="width:190px;">';
               echo "$rivi[1]</p>";
            echo '<p style="width:120px;">';
               echo "$rivi[5]</p>";
            echo '<p style="width:120px;padding-left:5px">';
               echo "$rivi[2]e</p>";
            echo '<p style="width:120px;padding-left:20px">';
               echo "$rivi[3]</p>";
            echo '<p style="width:120px;padding-left:20px">';
               echo "$rivi[4]</p>";
            echo '<p style="width:120px;padding-left:20px">';
               echo "$maksettu</p>";
         echo '</div>';
      echo '</li>';
    }     
    pg_close($yhteys);
    
    ?>
    </ul>
   </div>
    


</body>
</html>