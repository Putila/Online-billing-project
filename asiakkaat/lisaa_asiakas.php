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
   
   $id=$nimi=$kunta=$osoite=$kot="";
    
    $y_tiedot = "host=dbstud2.sis.uta.fi port=5432 dbname=rp422600 user=rp422600 password=bigchungus";
    
    if (!$yhteys = pg_connect($y_tiedot))
       die("Tietokantayhteyden luominen epäonnistui.");
    
    pg_query("SET SEARCH_PATH TO SahkoFirma;");

// isset funktiolla jäädään odottamaan syötettä.
// POST on tapa tuoda tietoa lomaketta (tavallaan kutsutaan lomaketta).
// Argumentti tallenna saadaan lomakkeen napin nimestä.

if (isset($_POST['tallenna']))
{
    // suojataan merkkijonot ennen kyselyn suorittamista


    $id  = intval($_POST['id']);
    $nimi   = pg_escape_string($_POST['nimi']);
    $kunta   = pg_escape_string($_POST['kunta']);
    $osoite   = pg_escape_string($_POST['osoite']);
    $kot = 0;

    // jos kenttiin on syötetty jotain, lisätään tiedot kantaan

    if($id == null){
      $viimeisin = pg_query("SELECT MAX(asiakas_id) FROM asiakkaat");
      $arvo = pg_fetch_row($viimeisin);
      $id = $arvo[0] + 1;
    }

    $tiedot_ok =$id !=0 && trim($nimi) != '' && trim($kunta) != '' && trim($osoite) != '' ;


    if ($tiedot_ok)
    {
        $kysely = "INSERT INTO asiakkaat (asiakas_id, kotikunta, nimi, osoite, kotitalousvah)
		 VALUES ($id, '$kunta', '$nimi', '$osoite', '$kot')";
        $paivitys = pg_query($kysely);

        // asetetaan viesti-muuttuja lisäämisen onnistumisen mukaan
	     // lisätään virheilmoitukseen myös virheen syy (pg_last_error)

        if ($paivitys && (pg_affected_rows($paivitys) > 0))
            $viesti = 'asiakas lisätty!';
        else
            $viesti = 'asiakasta ei lisätty: ' . pg_last_error($yhteys);
        }
      else
        $viesti = 'Annetut tiedot puutteelliset - tarkista, ole hyvä!';
   }
    pg_close($yhteys);
    
    ?>

    
    <!-- Lomake lähetetään samalle sivulle (vrt lomakkeen kutsuminen) -->
    <form action="lisaa_asiakas.php" method="post" style="width:500px; margin-left:10px;">

    <h2>Asiakkaan lisäys</h2>


    <?php if (isset($viesti)) echo '<p style="color:red">'.$viesti.'</p>'; ?>

	<!—PHP-ohjelmassa viitataan kenttien nimiin (name) -->
	<table class="table table-hover">

	    <tr>
    	    <td>Nimi</td>
    	    <td><input type="text" name="nimi" value="" /></td>
	    </tr>
	    <tr>
    	    <td>Kotikunta</td>
    	    <td><input type="text" name="kunta" value="" /></td>
       </tr>
       <tr>
    	    <td>Osoite</td>
    	    <td><input type="text" name="osoite" value="" /></td>
	    </tr>
	</table>

	<br />

	<!-- hidden-kenttää käytetään varotoimena, esim. IE ei välttämättä
	 lähetä submit-tyyppisen kentän arvoja jos lomake lähetetään
	 enterin painalluksella. Tätä arvoa tarkkailemalla voidaan
	 skriptissä helposti päätellä, saavutaanko lomakkeelta. -->

	<input type="hidden" name="tallenna" value="jep" />
    <button type="submit" value="tallenna" id='tallennus' class="btn btn-primary" style="background-color:#008080;">Lisää</button>
	</form>

    

  <script src="js/scripts.js"></script>

</body>
</html>