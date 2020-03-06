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
   <div style="margin-left:10px;">
   <h3>Uuden tarvikehinnaston syöttäminen</h3>
   <form action="paivita.php" method="post" enctype="multipart/form-data">
    Syötä tarvikehinnasto:
    <div style="display:flex;">
        <input type="file" name="fileToUpload" id="fileToUpload">
        <button type="submit" value="Lataa" id='tallennus' class="btn btn-primary"  name="submit" style="background-color:#008080;">Tallenna</button>
    </div>
   </form>
   </div>

<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;

if (file_exists($target_file)) {
    echo '<p style="margin-left:10px; color:orange;">Saman niminen hinnasto on jo olemassa.</p>';
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
   echo '<p style="margin-left:10px; color:orange;">Hinnaston koko liian suuri.</p>';
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
         $lines = array();
         $items = array();
         $filename = "$target_file";
         if (file_exists($filename) && is_readable ($filename)) {
            $myfile =  fopen($filename, "r");
            // Output one line until end-of-file
            while(!feof($myfile)) {
               array_push($lines,fgets($myfile));
            }
            fclose($myfile);

            $arrlength = count($lines);

            for($x = 0; $x < $arrlength; $x++) {
               $values = explode(";", $lines[$x]);
               array_push($items,$values);
            }



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
               $arrlength2 = count($items);
               for($x = 0; $x < $arrlength2; $x++) {
                  $current =$items[$x];
                  $nimi   = pg_escape_string(trim($current[0], "\'"));
                  $yksikko  = pg_escape_string(trim($current[1], "\'"));
                  $osto  = floatval($current[2]);
                  $myynti =$osto *1.20;
                  $maara   = floatval(0);

                  //selvitetään uudelle tarvikkelle id
                  $viimeisin = pg_query("SELECT MAX(Tarvike_id) FROM Tarvikkeet");
                  $arvo = pg_fetch_row($viimeisin);
                  $id = $arvo[0] + 1;   

                  //tarkistetaan että syötteet ok eikä tyhjiä
                  $tiedot_ok =$id !=0 && trim($nimi) != '' && trim($yksikko) != '';


                  if ($tiedot_ok){

                     $tarkistus =pg_query("SELECT tarvike_id,ostohinta,myyntihinta FROM Tarvikkeet WHERE nimi='$nimi' ");
                     $tarkistustulos = pg_fetch_row($tarkistus); 
                     $lisaysId = $tarkistustulos[0];
                     $lisaysOsto = $tarkistustulos[1];
                     $lisaysMyynti = $tarkistustulos[2];
                     $pv =date("Y-m-d");
                     if(pg_affected_rows($tarkistus)!=0){

                        $viimeisin = pg_query("SELECT MAX(Historia_id) FROM Historia");
                        $arvo = pg_fetch_row($viimeisin);
                        $newId = $arvo[0] + 1;   

                        $kysely = "INSERT INTO Historia (Historia_id,Tarvike_id,Nimi,Ostohinta,Myyntihinta,VanhenemisPv)
                        VALUES ('$newId','$lisaysId' ,'$nimi', '$lisaysOsto', '$lisaysMyynti','$pv')"; //MUUTA
                        $paivitys = pg_query($kysely);

                        $kysely = "UPDATE Tarvikkeet SET Ostohinta =$osto, Myyntihinta=$myynti WHERE tarvike_id = $lisaysId"; 
                        $paivitys = pg_query($kysely);
                     }
                     else{

                        $kysely = "INSERT INTO Tarvikkeet (Tarvike_id,Nimi,Ostohinta,Myyntihinta,Varastotilanne,Yksikko)
                           VALUES ($id, '$nimi', '$osto', '$myynti','$maara','$yksikko')"; 
                        
                        $paivitys = pg_query($kysely);
                        $viesti ="Hinnasto tallennettiin onnistuneesti!";
                     }

                  }

               }
         
            pg_close($yhteys);
            echo '<p style="margin-left:10px; color:green;">';
            echo "$viesti</p>";
         }
         else{
            echo '<p style="margin-left:10px; color:orange;">Tiedostoa ei löytynyt</p>';
         }
    } else {
      echo '<p style="margin-left:10px; color:orange;">Hinnaston latauksessa virhe.</p>';
    }
}

?>

</body>
</html>