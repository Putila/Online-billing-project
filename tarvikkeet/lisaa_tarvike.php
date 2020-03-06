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

   <?php
    $nimi=$maara=$osto=$yksikko=$myynti=" ";
    $nimiErr=$maaraErr=$ostoErr=$yksikkoErr=" ";
        
    //yhteyden luomiseen tiedot
   $y_tiedot = "host=dbstud2.sis.uta.fi port=5432 dbname=rp422600 user=rp422600 password=bigchungus";
    //onnistuiko yhteyden luominen
    if (!$yhteys = pg_connect($y_tiedot)){
       die("Tietokantayhteyden luominen epäonnistui.");
    }
     //asetetaan search path oikeaksi
    pg_query("SET SEARCH_PATH TO SahkoFirma;");

    // isset funktiolla jäädään odottamaan syötettä.
    // POST on tapa tuoda tietoa lomaketta (tavallaan kutsutaan lomaketta).
    // Argumentti tallenna saadaan lomakkeen napin nimestä.

    if (isset($_POST['tallenna'])){
        
        $nimi   = pg_escape_string($_POST['nimi']);
        $maara   = floatval($_POST['maara']);
        $osto  = floatval($_POST['ostoHinta']);
        $yksikko  = pg_escape_string($_POST['yksikko']);
        $myynti =$osto *1.20; //MUUUTA

        if (empty($_POST["nimi"])) {
            $nimiErr = "*";
        } 
        if (empty($_POST["maara"])) {
            $maaraErr = "*";
        } 
        if (empty($_POST["ostoHinta"])) {
            $ostoErr = "*";
        } 
        if (empty($_POST["yksikko"])) {
            $yksikkoErr = "*";
        } 


        //selvitetään uudelle tarvikkelle id
        $viimeisin = pg_query("SELECT MAX(Tarvike_id) FROM Tarvikkeet");
        $arvo = pg_fetch_row($viimeisin);
        $id = $arvo[0] + 1;   

        //tarkistetaan että syötteet ok eikä tyhjiä
        $tiedot_ok =$id !=0 && trim($nimi) != '' && trim($maara) != '' && trim($osto) != '' && trim($yksikko) != '';

        if ($tiedot_ok){
            //katsotaan onko tarvike jo tiedoissa -> mikäli on niin tehdään ainoastaan varasto määrän lisäys
            $tarkistus =pg_query("SELECT tarvike_id,varastotilanne FROM Tarvikkeet WHERE nimi='$nimi' AND Ostohinta = $osto AND Yksikko='$yksikko' ");
            $tarkistustulos = pg_fetch_row($tarkistus); 
            $lisaysId = $tarkistustulos[0];
            $varastotilanne=$tarkistustulos[1];
            if(pg_affected_rows($tarkistus)==0){
                $kysely = "INSERT INTO Tarvikkeet (Tarvike_id,Nimi,Ostohinta,Myyntihinta,Varastotilanne,Yksikko)
                 VALUES ($id, '$nimi', '$osto', '$myynti','$maara','$yksikko')"; //MUUTA
            }
            else{
                $uusiVarastoTilanne=$varastotilanne+$maara;
                $kysely = "UPDATE Tarvikkeet SET Varastotilanne =$uusiVarastoTilanne WHERE tarvike_id = $lisaysId"; 
            }
            $paivitys = pg_query($kysely);

            // asetetaan viesti-muuttuja lisäämisen onnistumisen mukaan
             // lisätään virheilmoitukseen myös virheen syy (pg_last_error)

            if ($paivitys && (pg_affected_rows($paivitys) > 0)){
                $viesti = 'Tarviketiedot tallennettiin onnistuneesti!';
                $nimi=$osto=$maara=$yksikko='';
            }else{
                $viesti =' Tarviketietoja ei voi tallentaa: ' . pg_last_error($yhteys);

            }

        }
          else
            $viesti = 'Annetut tiedot puutteelliset - tarkista, ole hyvä!';
   

    }
    else if (isset($_POST['tyj'])){
        echo(1);
    }
   
    pg_close($yhteys);
    
    ?>
    <form action="lisaa_tarvike.php" method="post" style="width:500px; margin-left:10px;">

    <h3>Lisää tarvike tarvikelistaan</h3>

    <?php if (isset($viesti)) echo '<p style="color:green">'.$viesti.'</p>'; ?>

	<!—PHP-ohjelmassa viitataan kenttien nimiin (name) -->
    <div class="form-row">
    <div class="col">
            <tr>
                <td>Tarvikkeen nimi:</td>
                <td><input type="text" class="form-control" name="nimi" value="<?php echo $nimi;?>" /> <span class="error"> <?php echo $nimiErr;?></span></td>
            </tr>
            <tr>
                <td>Ostohinta:</td>
                <td><input type="text" class="form-control" name="ostoHinta" value="<?php echo $osto;?>" /><span class="error"> <?php echo $ostoErr;?></span></td>
            </tr>
            <tr>
                <td>Yksikkö:</td>
                <td><input type="text" class="form-control" name="yksikko" value="<?php echo $yksikko;?>" /><span class="error"> <?php echo $yksikkoErr;?></span></td>
	        </tr>  
        </div>
        <div class="col">
            <tr>
                <td>Määrä:</td>
                <td><input type="text"  class="form-control" name="maara" value="<?php echo $maara;?>" /><span class="error"> <?php echo $maaraErr;?></span></td>
            </tr>  
        </div>
        </div>
    </div>


	<br />

	<!-- hidden-kenttää käytetään varotoimena, esim. IE ei välttämättä
	 lähetä submit-tyyppisen kentän arvoja jos lomake lähetetään
	 enterin painalluksella. Tätä arvoa tarkkailemalla voidaan
	 skriptissä helposti päätellä, saavutaanko lomakkeelta. -->
    <input type="hidden" name="tallenna" id='action'/>
    <button type="submit" value="Tallenna" id='tallennus' class="btn btn-primary" style="background-color:#008080;">Tallenna</button>
	</form>
    
    

</body>
</html>