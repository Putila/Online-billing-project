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
    <h2>Kohteen lisäys</h2>

    <?php


    $id=$nimi=$kunta=$osoite=$omistaja=$omistajaId= "";
    
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


    $nimi   = pg_escape_string($_POST['kNimi']);
    $osoite   = pg_escape_string($_POST['osoite']);
    $omistaja = pg_escape_string($_POST['kOmistaja']);

    $omistajaTemp = pg_query("SELECT asiakkaat.asiakas_id FROM asiakkaat WHERE asiakkaat.nimi = '$omistaja'");
    $omistajaId = pg_fetch_row($omistajaTemp);



    // jos kenttiin on syötetty jotain, lisätään tiedot kantaan


      $viimeisin = pg_query("SELECT MAX(kohde_id) FROM kohteet");
      $arvo = pg_fetch_row($viimeisin);
      $id = $arvo[0] + 1;


    $tiedot_ok =$id !=0 && trim($nimi) != '' && trim($omistajaId[0]) != '' && trim($osoite) != '' ;

    

    if ($tiedot_ok) {

        $kysely = "INSERT INTO kohteet (kohde_id, osoite, nimi, omistaja)
		  VALUES ($id, '$osoite', '$nimi', '$omistajaId[0]')";
        $paivitys = pg_query($kysely);

        // asetetaan viesti-muuttuja lisäämisen onnistumisen mukaan
	     // lisätään virheilmoitukseen myös virheen syy (pg_last_error)

        if ($paivitys && (pg_affected_rows($paivitys) > 0))
            $viesti = 'kohde lisätty!';
        else
            $viesti = 'kohde ei lisätty: ' . pg_last_error($yhteys);
        }
      else
        $viesti = 'Annetut tiedot puutteelliset - tarkista, ole hyvä!';
   }
    pg_close($yhteys);
    
    ?>

    
    <!-- Lomake lähetetään samalle sivulle (vrt lomakkeen kutsuminen) -->
    <form action="lisaa_kohde.php" method="post" style="width:500px; margin-left:10px;">


    <?php if (isset($viesti)) echo '<p style="color:red">'.$viesti.'</p>'; ?>

	<!—PHP-ohjelmassa viitataan kenttien nimiin (name) -->
	<table class="table table-hover">
	   <tr>
    	   <td>Kohteen nimi</td>
    	   <td><input type="text" name="kNimi" value="" /></td>
	   </tr>
	   <tr>
    	   <td>Kohteen osoite</td>
    	   <td><input type="text" name="osoite" value="" /></td>
       </tr>
        <tr>
            <td>Omistajan nimi</td>


   <?php
      $y_tiedot = "host=dbstud2.sis.uta.fi port=5432 dbname=rp422600 user=rp422600 password=bigchungus";

      if (!$yhteys = pg_connect($y_tiedot))
      die("Tietokantayhteyden luominen epäonnistui.");

      pg_query("SET SEARCH_PATH TO SahkoFirma;");
      $omistajat = pg_query("SELECT asiakkaat.nimi FROM asiakkaat ORDER BY asiakkaat.nimi;");

      echo '<td><select name="kOmistaja">';

      while ($oRivi = pg_fetch_row($omistajat)){
         echo '<option value="' .$oRivi[0].'">' .$oRivi[0]. '</option>';
      }


      pg_close($yhteys);
   ?>
        </tr>
    </table>

	<br />

	<!-- hidden-kenttää käytetään varotoimena, esim. IE ei välttämättä
	 lähetä submit-tyyppisen kentän arvoja jos lomake lähetetään
	 enterin painalluksella. Tätä arvoa tarkkailemalla voidaan
	 skriptissä helposti päätellä, saavutaanko lomakkeelta. -->

        <input type="hidden" name="tallenna" id='action'/>

        <button type="submit" value="Tallenna" id='tallennus' class="btn btn-primary" style="background-color:#008080;">Lisää</button>

    </form>

    
  <script src="js/scripts.js"></script>

</body>
</html>