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
<h1>Tiko 2019 Ryhmä 9</h1>
<h2>Tarvikelista</h2>
<table  class="table table-striped">
    <thead>
    <tr>
        <td><b>Id</b></td>
        <td><b>Nimi</b></td>
        <td><b>Ostohinta</b></td>
        <td><b>Myyntihinta</b></td>
        <td><b>Varastotilanne</b></td>
        <td><b>Yksikkö</b></td>
    </tr>
    </thead>
    <tbody>
    <?php

    //yhteyden luomiseen tiedot
    $y_tiedot = "host=dbstud2.sis.uta.fi port=5432 dbname=rp422600 user=rp422600 password=bigchungus";
    //onnistuuko yhteyden luominen
    if (!$yhteys = pg_connect($y_tiedot))
       die("Tietokantayhteyden luominen epäonnistui.");
     //asetetaan search path oikeaksi
    pg_query("SET SEARCH_PATH TO SahkoFirma;");

    $tulos = pg_query("SELECT * FROM Tarvikkeet ORDER BY tarvike_id");
    if (!$tulos) {
      echo "Virhe kyselyssä.\n";
      exit;
    }
    
    while ($rivi = pg_fetch_row($tulos)) {
        echo '<tr>';
        echo '<td>'.$rivi[0].'</td>';
        echo '<td>'.$rivi[1].'</td>';
        echo '<td>'.$rivi[2].'</td>';
        echo '<td>'.$rivi[3].'</td>';
        echo '<td>'.$rivi[4].'</td>';
        echo '<td>'.$rivi[5].'</td>';
        echo '</tr>';
    }     
   

    pg_close($yhteys);
    
    ?>
    </tbody>
</table>


</body>
</html>