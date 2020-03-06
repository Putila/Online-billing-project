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


    <?php

    $kohde = $tyyppi = $tyomuoto = $erat = $kuvaus = $id= "";

    // Tulostaa ennaltamaaratyn kyselyn WWW-sivulle
    //echo "Tietokannassa olevat opiskelijat. <br /><br />";
    
    $y_tiedot = "host=dbstud2.sis.uta.fi port=5432 dbname=rp422600 user=rp422600 password=bigchungus";
    
    if (!$yhteys = pg_connect($y_tiedot))
       die("Tietokantayhteyden luominen epäonnistui.");
    
    pg_query("SET SEARCH_PATH TO SahkoFirma;");

    if (isset($_POST['tallenna']))
    {
        // suojataan merkkijonot ennen kyselyn suorittamista

        $kohde   = pg_escape_string($_POST['tyoKohde']);
        $tyyppi   = pg_escape_string($_POST['tyyppi']);
        $erat = pg_escape_string($_POST['erat']);
        $kuvaus = pg_escape_string($_POST['kuvaus']);
        $tyomuoto = pg_escape_string($_POST['tyomuoto']);

        $kohdeTemp = pg_query("SELECT kohteet.kohde_id FROM kohteet WHERE kohteet.nimi = '$kohde'");
        $kohdeId = pg_fetch_row($kohdeTemp);


        if($tyomuoto == "Urakka"){
            $urakkaLippu = True;
        }
        else if ($tyomuoto == "Tuntityö"){
            $urakkaLippu = False;
        }

        $viimeisin = pg_query("SELECT MAX(tyo_id) FROM TyoSuoritukset");
        $arvo = pg_fetch_row($viimeisin);
        $id = $arvo[0] + 1;

        if($urakkaLippu){
            $tiedot_ok =$id !=0  && $kohdeId[0] != 0 && $erat != 0 && trim($kuvaus) != '' && trim($tyomuoto) != '';
        }
        else if (!$urakkaLippu){
            $tiedot_ok =$id !=0  && $kohdeId[0] != 0 && $erat == '' && trim($kuvaus) != '' && trim($tyomuoto) != '';
        }

        if ($tiedot_ok) {
            if($urakkaLippu){
                $kysely = "INSERT INTO TyoSuoritukset (Tyo_id, Kuvaus, Erat, Tyomuoto) VALUES ($id, '$kuvaus', '$erat', '$tyomuoto')";
            }
            else{
                $kysely = "INSERT INTO TyoSuoritukset (Tyo_id, Kuvaus, Tyomuoto) VALUES ($id, '$kuvaus', '$tyomuoto')";
            }
            $paivitys = pg_query($kysely);
            if($paivitys && (pg_affected_rows($paivitys) > 0)){

                $kohdeJaTyo = "INSERT INTO Tehtiin (Kohde_id, Tyo_id) VALUES ($kohdeId[0], $id)";
                $kohdeTyoPaivitys = pg_query($kohdeJaTyo);

                if(!$urakkaLippu){
                    $tunnitTemp = "INSERT INTO tunnit (Tyo_id, suunnittelu_tunnit, tyo_tunnit, aputyo_tunnit) VALUES ($id, 0, 0, 0)";
                    $tunnit = pg_query($tunnitTemp);
                    pg_query("INSERT INTO maksaa (tyo_id, tyyppi, alennus) VALUES ($id, 'aputyo', 0)");
                    pg_query("INSERT INTO maksaa (tyo_id, tyyppi, alennus) VALUES ($id, 'tyo', 0)");
                    pg_query("INSERT INTO maksaa (tyo_id, tyyppi, alennus) VALUES ($id, 'suunnittelu', 0)");
                }

                // asetetaan viesti-muuttuja lisäämisen onnistumisen mukaan
                // lisätään virheilmoitukseen myös virheen syy (pg_last_error)

                if ($kohdeTyoPaivitys && (pg_affected_rows($kohdeTyoPaivitys) > 0))
                    $viesti = 'työ lisätty!';
                else
                    $viesti = 'työ ei lisätty: ' . pg_last_error($yhteys);
            }
        }
        else
            $viesti = 'Annetut tiedot puutteelliset - tarkista, ole hyvä!';
    }
    pg_close($yhteys);

    ?>
<!-- Lomake lähetetään samalle sivulle (vrt lomakkeen kutsuminen) -->
<form action="lisaa_tyo.php" method="post" id="tyolisays" style="width:500px; margin-left:10px;">

    <h2>Työn lisäys</h2>


    <?php if (isset($viesti)) echo '<p style="color:red">'.$viesti.'</p>'; ?>

    <!—PHP-ohjelmassa viitataan kenttien nimiin (name) -->
    <table class="table table-hover">

        <tr>
            <td>Työkohde</td>
            <td>
    <?php
    $y_tiedot = "host=dbstud2.sis.uta.fi port=5432 dbname=rp422600 user=rp422600 password=bigchungus";

    if (!$yhteys = pg_connect($y_tiedot))
        die("Tietokantayhteyden luominen epäonnistui.");

    pg_query("SET SEARCH_PATH TO SahkoFirma;");
    $omistajat = pg_query("SELECT kohteet.nimi FROM kohteet ORDER BY kohteet.kohde_id;");

    echo '<select name="tyoKohde">';

    while ($oRivi = pg_fetch_row($omistajat)){
        echo '<option value="' .$oRivi[0].'">' .$oRivi[0]. '</option>';
    }
    echo "</select>";

    pg_close($yhteys);
    ?>
            </td>
        </tr>
        <tr>
            <td>Työn muoto</td>
            <td><input type="radio" name="tyomuoto" value="Tuntityö">Tuntityö <input type="radio" name="tyomuoto" value="Urakka">Urakka</td>
        </tr>
        <tr>
            <td>Erien määrä</td>
            <td><input type="text" name="erat" value="" /> </td>
        </tr>
        <tr>
            <td>Työn kuvaus </td>
            <td><textarea name="kuvaus" form="tyolisays" rows="3" cols="22" maxlength="50"></textarea></td>
        </tr>
    </table>
    <br/>
    <br>
    <!-- hidden-kenttää käytetään varotoimena, esim. IE ei välttämättä
     lähetä submit-tyyppisen kentän arvoja jos lomake lähetetään
     enterin painalluksella. Tätä arvoa tarkkailemalla voidaan
     skriptissä helposti päätellä, saavutaanko lomakkeelta. -->

    <input type="hidden" name="tallenna" value="jep" />
    <button type="submit" value="Tallenna" id='tallennus' class="btn btn-primary" style="background-color:#008080;">Lisää työ</button>
</form>
<script>
    //js joka vaihtaa eräkentän disabled
    var form = document.forms['tyolisays'];
    form.tyomuoto[0].onfocus = function () {
        form.erat.value = "";
        form.erat.disabled = true;
    }
    form.tyomuoto[1].onfocus = function () {
        form.erat.disabled = false;
    }
</script>

</body>
</html>