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
    <h1>Tiko 2019 Ryhmä 9</h1>
    <h2>Työn päivitys</h2>

    <?php

    // Tulostaa ennaltamaaratyn kyselyn WWW-sivulle
    //echo "Tietokannassa olevat opiskelijat. <br /><br />";

    $y_tiedot = "host=dbstud2.sis.uta.fi port=5432 dbname=rp422600 user=rp422600 password=bigchungus";
    
    if (!$yhteys = pg_connect($y_tiedot))
       die("Tietokantayhteyden luominen epäonnistui.");
    
    pg_query("SET SEARCH_PATH TO SahkoFirma;");

    if (isset($_POST['tallenna'])){
        $tyoId = pg_escape_string($_POST['tyot']);
        $tiedot = pg_query("SELECT * FROM tyosuoritukset WHERE tyosuoritukset.tyo_id = '$tyoId'");
        $tyoRivi = pg_fetch_row($tiedot);

        $kohdeTiedot = pg_query("SELECT  kohteet.nimi, tyosuoritukset.tyomuoto, tyosuoritukset.tyo_id, kohteet.kohde_id
        FROM tyosuoritukset, kohteet INNER JOIN tehtiin ON kohteet.kohde_id = tehtiin.kohde_id
        WHERE tyosuoritukset.tyo_id = tehtiin.tyo_id AND tyosuoritukset.tyo_id = $tyoId;");


        $kohdeRivi= pg_fetch_row($kohdeTiedot);

        if($kohdeRivi[1] == "Tuntityö"){
            $tunnit = pg_query("SELECT * FROM tunnit WHERE tunnit.tyo_id = $tyoId");
            $tunnitRivi = pg_fetch_row($tunnit);
            $tuntiAlennukset = pg_query("SELECT alennus FROM maksaa WHERE tyo_id = $tyoId ORDER BY tyyppi");

        }

        $tarvikkeet = pg_query("SELECT * FROM Tarvikkeet ORDER BY tarvike_id");

        $tyoTarvikkeet = pg_query("SELECT tarvikkeet.nimi, kaytettiin.maara, kaytettiin.alennus
        FROM tarvikkeet NATURAL JOIN kaytettiin
        WHERE kaytettiin.tyo_id = $tyoId ORDER BY tarvikkeet.tarvike_id;");

        echo '<form action="paivita_tyo.php" method="post" id="paivitys">';

        echo '<h2>' .$kohdeRivi[0].  '</h2>';
        echo '<table border ="0" cellspasing="0" cellpadding="3">';
        echo '<tr>';
            echo '<td><b> Työn kuvaus:</b> '.$tyoRivi[1].'</td>';
        echo '</tr>';

        echo '</table>';
        if($kohdeRivi[1] == "Tuntityö"){

            echo '<table class="table table-hover">';


                echo '<h3>Tunnit</h3>';

            echo '<tr>';
                echo '<td>Aputyotunnit: </td>';
                echo '<td><input type="number" name="aputyoT" value="'.$tunnitRivi[3].'"></td>';
                echo '<td>Alennus: </td>';
                $tuntiAlennusRivi = pg_fetch_row($tuntiAlennukset);
                echo '<td><input type="number" name="aputyoAle" value="'.$tuntiAlennusRivi[0].'"></td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td>Suunnittelutunnit: </td>';
                echo '<td><input type="number" name="suunnitteluT" value="'.$tunnitRivi[1].'"></td>';
                echo '<td>Alennus: </td>';
                $tuntiAlennusRivi = pg_fetch_row($tuntiAlennukset);
                echo '<td><input type="number" name="suunnitteluAle" value="'.$tuntiAlennusRivi[0].'"></td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td>Työtunnit: </td>';
                echo '<td><input type="number" name="tyoT" value="'.$tunnitRivi[2].'"></td>';
                echo '<td>Alennus: </td>';
                $tuntiAlennusRivi = pg_fetch_row($tuntiAlennukset);
                echo '<td><input type="number" name="tyoAle" value="'.$tuntiAlennusRivi[0].'"></td>';
            echo '</tr>';


            echo '</table>';

        }
        echo '<table class="table table-striped">';

        echo '<h3>Työn Tarvikkeet</h3>';
        echo '<thead>';
        echo '<tr>';
            echo '<td><b>NIMI</b></td>';
            echo '<td><b>MÄÄRÄ</b></td>';
            echo '<td><b>ALENNUS</b></td>';
        echo '</tr>';
        echo '</thead>';

        echo '<tbody>';
        while ($tyoTarvikeRivi = pg_fetch_row($tyoTarvikkeet)) {
            echo '<tr>';
                echo '<td>'.$tyoTarvikeRivi[0].'</td>';
                echo '<td>'.$tyoTarvikeRivi[1].'</td>';
                echo '<td>'.$tyoTarvikeRivi[2].'</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '<table class="container">';
        echo '<thead>';
        echo '<tr>';
            echo '<td><h3>Lisää tarvike</h3></td>>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo '<tr>';
            echo '<td>Tarvike:</td>>';
            echo '<td><select name="tarvike">>';
            while($tarvikeRivi = pg_fetch_row($tarvikkeet)){
                echo '<option value="'.$tarvikeRivi[0].'">'.$tarvikeRivi[1].'</option>';
            }
            echo "</select></td>";
            echo '<td>Määrä:</td>>';
            echo '<td><input type="number" name="tarvikeMaara" value="0"></td>>';
            echo '<td>Alennus:</td>>';
            echo '<td><input type="text" name="tarvikeAlennus" value="0"></td>>';
        echo '</tr>';



        echo '<input type="hidden" name="tyoId" value="'.$tyoId.'" />';


        echo '<input type="hidden" name="paivitaTyo" value="jep" />';
        echo '<td><input type="submit" value="Päivitä" /></td>';

       // echo '<input type="hidden" name="tallenna" id='action'/>';
       // echo '<button type="submit" value="Tallenna" id='tallennus' class="btn btn-primary" style="background-color:#008080;">Tallenna</button>';


        echo '</form>';
        echo '</tbody>';
        echo '</table>';

    }


    if(isset($_POST['paivitaTyo'])){

        $uusiMaara=$tarvikkeita =$suunnittelutunnit=$tyotunnit=$aputyotunnit=$tarvike=$id =$tarvikeMaara=$tarvikeAlennus=$urakkalippu=$suunnitteluAle=$tyoAle =$aputyoAle = 0;
        $ei = true;


        $suunnittelutunnit   = pg_escape_string($_POST['suunnitteluT']);
        $tyotunnit   = pg_escape_string($_POST['tyoT']);
        $aputyotunnit = pg_escape_string($_POST['aputyoT']);
        $suunnitteluAle = pg_escape_string($_POST['suunnitteluAle']);
        $tyoAle = pg_escape_string($_POST['tyoAle']);
        $aputyoAle = pg_escape_string($_POST['aputyoAle']);

        $id = pg_escape_string($_POST['tyoId']);
        $tarvike = pg_escape_string($_POST['tarvike']);
        $tarvikeMaara = pg_escape_string($_POST['tarvikeMaara']);
        $tarvikeAlennus = pg_escape_string($_POST['tarvikeAlennus']);

        $kaytetytTarvikkeet = pg_query("SELECT * FROM kaytettiin WHERE kaytettiin.tyo_id = $id;");

        $tarvikeNykMaar = pg_query("SELECT maara FROM kaytettiin WHERE tarvike_id = $tarvike AND tyo_id = $id");
        $tarvikeNykMaarRivi = pg_fetch_row($tarvikeNykMaar);

        $tarvikeVarasto = pg_query("SELECT * FROM Tarvikkeet ORDER BY tarvike_id;");



        if($suunnittelutunnit == null && $tyotunnit == null && $aputyotunnit == null){
            $urakkalippu = true;
        }
        if($tarvikeMaara > 0 || $tarvikeMaara < 0){
            $tarvikkeita = true;
        }
        if($tarvikeNykMaarRivi[0] + $tarvikeMaara <0){
            $ei = false;
        }


        $tiedot_ok = $id != 0 && $tarvike != null && $tarvikeMaara != 0 && $tarvikeAlennus < 100 && $suunnittelutunnit >=0
        && $tyotunnit >= 0 && $aputyotunnit >0;
        if(tiedot_ok){

            if(!$urakkalippu){

                //tunnit
                $tuntiLisays = "UPDATE tunnit SET Suunnittelu_tunnit = $suunnittelutunnit, Tyo_tunnit = $tyotunnit,
                Aputyo_tunnit = $aputyotunnit WHERE tunnit.tyo_id = $id;";
                $tuntiPaivitys = pg_query($tuntiLisays);
                $tuntiPaivitysOnnistui = false;

                //TODO virheenkäsittely
                pg_query("UPDATE maksaa SET alennus = $aputyoAle WHERE tyo_id = $id AND tyyppi = 'aputyo';");
                pg_query("UPDATE maksaa SET alennus = $suunnitteluAle WHERE tyo_id = $id AND tyyppi = 'suunnittelu';");
                pg_query("UPDATE maksaa SET alennus = $tyoAle WHERE tyo_id = $id AND tyyppi = 'tyo';");
            }
            //jos tarvikkeita on lisätty
            if($tarvikkeita && $ei){

                //käydään läpi jo käytetyt tarvikkeet
                while($tarvRivi = pg_fetch_row($kaytetytTarvikkeet)){
                    if($tarvRivi[0] == $tarvike){

                        $uusiMaara = $tarvRivi[2] + $tarvikeMaara;

                        $tarvikeVarasto = pg_query("SELECT varastotilanne FROM tarvikkeet WHERE tarvike_id = $tarvike;");
                        $tarvikeVarastoRivi = pg_fetch_row($tarvikeVarasto);
                        if($tarvikeVarastoRivi[0] - $tarvikeMaara >=0){


                            $lisays = "UPDATE kaytettiin SET maara = $uusiMaara, alennus = $tarvikeAlennus
                            WHERE tyo_id = $id AND tarvike_id = $tarvike";
                            $lisaysPaivitys = pg_query($lisays);
                            $uusiVarastotilanne = $tarvikeVarastoRivi[0] - $tarvikeMaara;
                            $tarvikeVarastoPaivitys = pg_query("UPDATE tarvikkeet SET varastotilanne = $uusiVarastotilanne WHERE tarvike_id = $tarvike;");
                            $onnistunutViesti = 'Tarvike päivitetty ';
                        }
                    }
                }
                if(!$lisaysPaivitys ){
                    $tarvikeVarasto = pg_query("SELECT varastotilanne FROM tarvikkeet WHERE tarvike_id = $tarvike;");
                    $tarvikeVarastoRivi = pg_fetch_row($tarvikeVarasto);
                    if($tarvikeVarastoRivi[0] - $tarvikeMaara >= 0){
                        $tarvikeLisays = "INSERT INTO kaytettiin (tarvike_id, tyo_id, maara, alennus) 
                        VALUES ($tarvike, $id, $tarvikeMaara, $tarvikeAlennus);";
                        $tarvikePaivitys = pg_query($tarvikeLisays);

                        $uusiVarastotilanne = $tarvikeVarastoRivi[0] - $tarvikeMaara;
                        $tarvikeVarastoPaivitys = pg_query("UPDATE tarvikkeet SET varastotilanne = $uusiVarastotilanne WHERE tarvike_id = $tarvike;");
                        $onnistunutViesti = 'Tarvike päivitetty ';
                    }
                }
            }
            if($tuntiPaivitys && (pg_affected_rows($tuntiPaivitys) > 0)){
                $onnistunutTuntiViesti = 'Tunnit päivitetty';
                $tuntiPaivitysOnnistui = true;
            }
            if(!$urakkalippu && !$tuntiPaivitysOnnistui){
                $viesti = 'Ongelma tuntien päivityksessä';
            }
        }
    }
    pg_close($yhteys);
    
    ?>

<?php if (isset($viesti)) echo '<p style="color:red">'.$viesti.'</p>'; ?>
<?php if (isset($onnistunutViesti)) echo '<p style="color:green">' .$onnistunutViesti.'</p>'; ?>
<?php if (isset($onnistunutTuntiViesti)) echo '<p style="color:#298040">' .$onnistunutTuntiViesti.'</p>'; ?>

  <script src="js/scripts.js"></script>

</body>
</html>