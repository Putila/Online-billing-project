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




    <form action="paivita_tyo.php" method="post" id="tyot" style="width:700px; margin-left:10px;">
        <table class="table">

            <h2>Työt</h2>
            <tr>
            <td>Valitse työ tarkastelua varten: </td>

    <?php

    // Tulostaa ennaltamaaratyn kyselyn WWW-sivulle
    //echo "Tietokannassa olevat opiskelijat. <br /><br />";

    $y_tiedot = "host=dbstud2.sis.uta.fi port=5432 dbname=rp422600 user=rp422600 password=bigchungus";

    if (!$yhteys = pg_connect($y_tiedot))
        die("Tietokantayhteyden luominen epäonnistui.");

    pg_query("SET SEARCH_PATH TO SahkoFirma;");
    $tyot = pg_query("SELECT kohteet.nimi, tyosuoritukset.tyomuoto, tyosuoritukset.tyo_id FROM kohteet, tyosuoritukset, tehtiin
                WHERE tyosuoritukset.tyo_id = tehtiin.tyo_id AND tehtiin.kohde_id = kohteet.kohde_id;");

    echo '<td><select name="tyot">';
    while ($tRivi =  pg_fetch_row($tyot)){
        echo '<option value="' .$tRivi[2].'">Työn kohteen nimi: '.$tRivi[0].' | Työn tyyppi: '.$tRivi[1].'</option>';
    }
    echo '</select></td>';
pg_close($yhteys);
    ?></tr>



<br>
<br>
    <input type="hidden" name="tallenna" value="jep" />
    <tr>
        <td><button type="submit" value="Tallenna" id='tallennus' class="btn btn-primary" style="background-color:#008080;">Valitse</button></td>
    </tr>
</table>
</form>


<script src="js/scripts.js"></script>

</body>
</html>