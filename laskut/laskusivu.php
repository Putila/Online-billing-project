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
<div class="content" style="margin-left:10px;">
   <h2>Tallennetut laskutiedot</h2>
   <form action="laheta_lasku.php" method="post" id="form1">
      <?php
         //tarvikkeiden yhteishinta
         $tarvikkeetyht =0;
         
         //poimitaan asiakastiedot ja työid
         $tyoId = pg_escape_string($_POST['tyot']);
         $asiakasTiedot =array();

         //asetetaanko kesken vai valmiiksi olevaksi laskuksi
         $tila = pg_escape_string($_POST['laskuntila']);

         //alusteaan asiakas id ja erät
         $erat=1;
         $asiakasId=1;

         //töihin liittyvät alustettu
         $suunnittelu=0;
         $tyo=0;
         $aputyo=0;
         $yhteishinta =0;

         //päiviin liittyvät tiedot alustettu
         $pv=date("d.m.Y");
         $lpv=date("Y-m-d");
         $erapv= pg_escape_string($_POST['erapv']);
         $kirja= pg_escape_string($_POST['kirja']);
         $realErapv=$erapv[8].$erapv[9].".".$erapv[5].$erapv[6].".".$erapv[0].$erapv[1].$erapv[2].$erapv[3];

         //saajan tiedot alustettu
         $saaja = pg_escape_string($_POST['saajaNimi']);
         $saajaTili = pg_escape_string($_POST['saajaTili']);
         $saajaOsoite = pg_escape_string($_POST['saajaosoite']);
         $saajaKunta = pg_escape_string($_POST['saajakunta']);

         

         //yhteyden luomiseen tiedot
         $y_tiedot = "host=dbstud2.sis.uta.fi port=5432 dbname=rp422600 user=rp422600 password=bigchungus";
         //onnistuuko yhteyden luominen
         if (!$yhteys = pg_connect($y_tiedot))
            die("Tietokantayhteyden luominen epäonnistui.");
         //asetetaan search path oikeaksi
         pg_query("SET SEARCH_PATH TO SahkoFirma;");

         //valitaan viimeisin lasku id ja lisätään sitä yhdellä
         $viimeisin = pg_query("SELECT MAX(Lasku_id) FROM laskut");
         $arvo = pg_fetch_row($viimeisin);
         $laskuId = $arvo[0] + 1;
         
         //haetaan asiakastietoja työid:n avulla
         $tulos = pg_query("SELECT asiakkaat.nimi, asiakkaat.kotikunta, asiakkaat.osoite, asiakkaat.asiakas_id,kohteet.nimi,tyosuoritukset.kuvaus FROM asiakkaat, kohteet, tyosuoritukset, tehtiin WHERE tyosuoritukset.tyo_id=tehtiin.tyo_id AND tehtiin.kohde_id=kohteet.kohde_id AND kohteet.omistaja = asiakkaat.asiakas_id AND tyosuoritukset.tyo_id = $tyoId;");
         if (!$tulos) {
            echo "Virhe kyselyssä.\n";
            exit;
         }
      
         while ($rivi = pg_fetch_row($tulos)) {
            array_push($asiakasTiedot,$rivi[0],$rivi[1],$rivi[2]);
            $asiakasId=$rivi[3];
            $tyokohde=$rivi[4];
            $kuvaus=$rivi[5];
         }     

         //haetaan erien määrä työid:n avulla
         $tulos = pg_query("SELECT erat FROM tyosuoritukset WHERE tyosuoritukset.tyo_id = $tyoId;");
         if (!$tulos) {
            echo "Virhe kyselyssä.\n";
            exit;
         }
         while ($rivi = pg_fetch_row($tulos)) {
            $erat=$rivi[0];
         }    
         if ($erat == NULL){
            $erat=1;
         }

      echo'<div class="lasku" style= "border: solid;border-color:Gainsboro; width:900px;border-width: 1px;">';
         //laskun yläosa  -- asiakas ja laskutietoja
         echo'<div class= "top" style="display:flex; margin-bottom:40px;">';
            echo'<div class="kayttajaTiedot" style="margin-left: 100px;margin-top:80px;">';
               echo "<p>$asiakasTiedot[0]</p>";
               echo "<p>$asiakasTiedot[1]</p>";
               echo "<p>$asiakasTiedot[2]</p>";
            echo' </div>';

            echo'<div class="laskutustiedot" style="border: solid;border-width: 2px;width:300px;margin-left: 300px;margin-top:40px;padding-left:10px; border-radius: 2px;">';
               echo "<p><b>Päivämäärä</b>: $pv</p>";
               echo "<p><b>Eräpäivä</b>: $realErapv</p>";
               echo "<p><b>Laskun numero</b>: $laskuId</p>";
               echo "<p><b>Erä</b>: $erat / $erat</p>";
               echo "<p><b>Asiakas numero</b>: $asiakasId</p>";
               echo' <p><b>Viivästyskorko</b>: 16% </p>';
               echo' <p><b>Laskutuslisä</b>: 5e/lasku </p>';
            echo' </div>';
         echo' </div>';
         echo'<div>';
            echo '<p style="margin-left:10px">';
            echo "<b>Työkohde</b>: $tyokohde </p>";
            echo '<p style="margin-left:10px">';
            echo "<b>Työn kuvaus</b>: $kuvaus </p>";
         echo'</div>';

         //laskun erittely osa
         echo' <div class="erittely" style="padding-left:10px;">';
            echo '<br>';
            echo '<div style="display:flex; border-bottom:solid;width:850px;border-width: 2px">';
               echo '<p style="margin-right: 20px;"><b>Tuotekoodi</b></p> <p style="margin-right:95px;"><b>Nimike</b> </p> <p style="margin-right:20px;"><b>Veroton hinta</b></p> <p style="margin-right:25px;"><b>Määrä</b></p><p style="margin-right:25px;"><b>Yksikkö</b> </p><p style="margin-right:25px;"><b>Alennus</b></p> <p style="margin-right:25px;"><b>ALV-%</b> </p><p style="margin-right:25px;"><b>Verollinen hinta</b></p>';
            echo '</div>';

            //haetaan tarviketietoja
            $tulos = pg_query("SELECT tarvikkeet.tarvike_id, tarvikkeet.nimi, tarvikkeet.myyntihinta, tarvikkeet.yksikko, kaytettiin.maara, kaytettiin.alennus FROM tarvikkeet, kaytettiin, tyosuoritukset WHERE tarvikkeet.tarvike_id = kaytettiin.tarvike_id AND kaytettiin.tyo_id = tyosuoritukset.tyo_id AND tyosuoritukset.tyo_id = $tyoId;");
            if (!$tulos) {
            echo "Virhe kyselyssä.\n";
            exit;
            }
            
            //tarvikkeiden listaaminen
            while ($rivi = pg_fetch_row($tulos)) {
               $hinta = $rivi[2] *$rivi[4];
               $kerroin =1-($rivi[5]/100);
               $hinta =round($hinta * $kerroin,2);
               $alvhinta=round($hinta * 1.24,2);
               $tarvikkeetyht = $tarvikkeetyht +$alvhinta;
               echo '<div style="display:flex">';
                  echo '<p style="width:108px;">';
                  echo "$rivi[0]</p>";
                  echo '<p style="width:146px;padding-right:2px;">';
                  echo "$rivi[1] </p>";
                  echo '<p style="width:100px;">';
                  echo "$hinta e </p>";
                  echo '<p style="width:65px;">';
                  echo "$rivi[4]</p>";
                  echo '<p style="width:85px;">';
                  echo "$rivi[2] e / $rivi[3] </p>";
                  echo '<p style="width:80px;padding-left:10px;">';
                  echo "$rivi[5] %</p>";
                  echo '<p style="width:79px;padding-left:10px;">';
                  echo "24%</p>";
                  echo '<p style="width:80px;padding-left:10px;">';
                  echo "$alvhinta e</p>";
               echo '</div>';
            }
            //tehdään jos opaskirja on haluttu lisätä laskuun
            if($kirja =="valittu"){
               echo '<div style="display:flex">';
                  echo '<p style="width:108px;">';
                  echo "0001</p>";
                  echo '<p style="width:146px;padding-right:2px;">';
                  echo "Opaskirja </p>";
                  echo '<p style="width:125px;">';
                  echo "10e </p>";
                  echo '<p style="width:72px;">';
                  echo "1</p>";
                  echo '<p style="width:80px;">';
                  echo "10e/kpl </p>";
                  echo '<p style="width:80px;padding-left:10px;">';
                  echo "0 %</p>";
                  echo '<p style="width:79px;padding-left:10px;">';
                  echo "10%</p>";
                  $alvhinta= 10 *1.10;
                  echo '<p style="width:80px;padding-left:10px;">';
                  echo "$alvhinta e</p>";
               echo '</div>';
               $tarvikkeetyht = $tarvikkeetyht +$alvhinta;
            }    
            echo' <p style="text-align:right;padding-right:10px;">';
            echo "<b>Yhteensä</b>: $tarvikkeetyht e </p>";
            echo'<br>';

            //töiden listaaminen
            echo '<div style="display:flex;border-bottom:solid;width:850px;border-width: 2px">';
               echo  '<p style="width:110px;"><b>Työ</b></p>
                        <p style="width:95px;"><b>Aika</b></p>
                        <p style="width:95px;"><b>Hinta</b></p>
                        <p style="width:95px;"><b>Alennus</b></p> 
                        <p style="width:130px;"><b>Verollinen hinta</b></p>';
            echo '</div>';

            //haetaan työtiedot
            $tulos = pg_query("SELECT tunnit.suunnittelu_tunnit, tunnit.tyo_tunnit,tunnit.aputyo_tunnit FROM tunnit, tyosuoritukset WHERE tunnit.tyo_id = tyosuoritukset.tyo_id AND tyosuoritukset.tyo_id = $tyoId;");
            if (!$tulos) {
               echo "Virhe kyselyssä.\n";
               exit;
            }
            
            while ($rivi = pg_fetch_row($tulos)) {
               $suunnittelu= $rivi[0];
               $tyo= $rivi[1];
               $aputyo= $rivi[2];
            }    
            $tulos = pg_query("SELECT tyohinnasto.hinta, maksaa.alennus FROM tyohinnasto,maksaa, tyosuoritukset WHERE tyosuoritukset.tyo_id=maksaa.tyo_id AND tyohinnasto.tyyppi = maksaa.tyyppi AND tyohinnasto.tyyppi = 'suunnittelu' AND tyosuoritukset.tyo_id = $tyoId;");
            if (!$tulos) {
            echo "Virhe kyselyssä.\n";
               echo "bitch lasagne";
            exit;


            }
            
            //suunnittelu tiedot
            while ($rivi = pg_fetch_row($tulos)) {
               $hinta = $suunnittelu * $rivi[0]* (1-($rivi[1]/100));
               $yhteishinta = $yhteishinta +$hinta;
               echo '<div style="display:flex">';
                  echo '<p style="width:110px;">';
                  echo "Suunnittelu </p>";
                  echo '<p style="width:95px;">';
                  echo "$suunnittelu h </p>";
                  echo '<p style="width:95px;">';
                  echo "$rivi[0]e /h </p>";
                  echo '<p style="width:95px;">';
                  echo "$rivi[1]%</p>";
                  echo '<p style="width:95px;">';
                  echo "$hinta e </p>";
               echo '</div>';
            }

            $tulos = pg_query("SELECT tyohinnasto.hinta, maksaa.alennus FROM tyohinnasto,maksaa, tyosuoritukset WHERE tyosuoritukset.tyo_id=maksaa.tyo_id AND tyohinnasto.tyyppi = maksaa.tyyppi AND tyohinnasto.tyyppi = 'tyo' AND tyosuoritukset.tyo_id = $tyoId;");
            if (!$tulos) {
            echo "Virhe kyselyssä.\n";
               echo "bitch lasagne222323";
            exit;
            }
            
            //työ tiedot
            while ($rivi = pg_fetch_row($tulos)) {
               $hinta = $tyo * $rivi[0]* (1-($rivi[1]/100));
            echo '<div style="display:flex">';
               echo '<p style="width:110px;">';
               echo "Tyo </p>";
               echo '<p style="width:95px;">';
               echo "$tyo h </p>";
               echo '<p style="width:95px;">';
               echo "$rivi[0]e /h </p>";
               echo '<p style="width:95px;">';
               echo "$rivi[1]%</p>";
               echo '<p style="width:95px;">';
               echo "$hinta e </p>";
            echo '</div>';
            $yhteishinta = $yhteishinta +$hinta;
            }  

            $tulos = pg_query("SELECT tyohinnasto.hinta, maksaa.alennus FROM tyohinnasto,maksaa, tyosuoritukset WHERE tyosuoritukset.tyo_id=maksaa.tyo_id AND tyohinnasto.tyyppi = maksaa.tyyppi AND tyohinnasto.tyyppi = 'aputyo' AND tyosuoritukset.tyo_id = $tyoId;");
            if (!$tulos) {
            echo "Virhe kyselyssä.\n";
            exit;
            }
            
            //apulaisen työtiedot
            while ($rivi = pg_fetch_row($tulos)) {
            $hinta = $aputyo * $rivi[0]* (1-($rivi[1]/100));
            echo '<div style="display:flex">';
               echo '<p style="width:110px;">';
               echo "Apulaisen työ</p>";
               echo '<p style="width:95px;">';
               echo "$aputyo h </p>";
               echo '<p style="width:95px;">';
               echo "$rivi[0]e /h </p>";
               echo '<p style="width:95px;">';
               echo "$rivi[1]%</p>";
               echo '<p style="width:95px;">';
               echo "$hinta e </p>";
            echo '</div>';
            $yhteishinta = $yhteishinta +$hinta;
            }

            echo'  <p style="text-align:right;padding-right:10px;">';
            echo   "<b>Yhteensä</b>: $yhteishinta e</p>";

            //Kotalousvähennys tiedot
            $asiakas = pg_query("SELECT kotitalousvah FROM asiakkaat WHERE asiakas_id = $asiakasId");
            $tempRivi = pg_fetch_row($asiakas);
            $kotitalousvahennys = $tempRivi[0];
            $kotitalousvah = ($yhteishinta* 0.5)-100;
            if($kotitalousvah > 2400){
               $kotitalousvah=2400;
            }
            if($kotitalousvah < 0){
               $kotitalousvah = 0;
            }
            //tarkistetaan onko vuoden vähennykset käytetty
            if($kotitalousvahennys != 2400){
               //tarkistetaan onko uusi summa vähemmän kuin maksimi
               if($kotitalousvah + $kotitalousvahennys <= 2400){
                  $laskuKotitalousvah = $kotitalousvah;
                  $kaytettyKotitalousvah = $kotitalousvah + $kotitalousvahennys;
                  //päivitetään taulu
                  $lol = pg_query("UPDATE asiakkaat SET kotitalousvah = $kaytettyKotitalousvah WHERE asiakas_id =$asiakasId");
               }
               else{
                  //jos uuden ja käytetyn vähennyksen summa on isompi kuin maksimi, lasketaan nykyisen laskun vähennys uudestaan
                  $temp = $kotitalousvah + $kotitalousvahennys -2400;
                  $laskuKotitalousvah = $kotitalousvah - $temp;
                  $kaytettyKotitalousvah = $kotitalousvahennys + $laskuKotitalousvah;
                  //päivitetään taulu
                  $lol = pg_query("UPDATE asiakkaat SET kotitalousvah = $kaytettyKotitalousvah WHERE asiakas_id =$asiakasId");
               }
            }
            else{
               $laskuKotitalousvah = 0;
            }
            echo'  <p style="text-align:right;padding-right:10px;">';
            echo "<b>Kotitalousvähennys kelpoista</b>: $laskuKotitalousvah e </p>";

            echo'<br>';
         echo' </div>';

         //Alaosan tiedot
         echo' <div class="bottom" style="display:flex; border-top:dashed; border-width: 2px;">';
            echo' <div class="left"style="padding-top:20px;">';
               echo' <div style="border-bottom:solid; border-width: 2px;display:flex;padding-left:40px;padding-right:180px;">';
                  echo "<p> <b>Saajan tilinumero</b>:&nbsp;</p> <p>$saajaTili</p>";
               echo '</div>';
               echo' <div style="border-bottom:solid;border-width: 2px;display:flex;padding-left:40px;padding-right:180px;">';
                  echo "<p><b>Saaja</b>:&nbsp;</p> <p>$saaja  <br /> $saajaOsoite <br /> $saajaKunta  </p>";
               echo '</div>';
               echo' <div style="display:flex;padding-left:40px;padding-right:180px;">';
                  echo "<p><b>Maksaja</b>:&nbsp;</p> <p>$asiakasTiedot[0] <br /> $asiakasTiedot[1] <br />$asiakasTiedot[2]</p>";
               echo '</div>';
            echo'</div>';
            echo' <div class="right" style="border-left:solid; border-width: 2px;width:365px">';
               echo'<div style="height:100px;border-bottom:solid;border-width: 2px;padding-left:20px;padding-top:20px;padding-left:40px;">';
                  echo"<p><b>Laskun numero</b>: $laskuId</p>";
               echo '</div>';
               echo' <div style="display:flex;border-bottom:solid;border-width: 2px;padding-left:20px;padding-top:20px;padding-left:40px;">';
                  echo"<p><b>Eräpäivä</b>:&nbsp;</p> <p>$realErapv</p>";
               echo '</div>';
               echo' <div style="display:flex;padding-left:20px;padding-top:20px;padding-left:40px;"><p><b>Summa</b>:&nbsp;</p>';
                  $loppuhinta= $tarvikkeetyht +$yhteishinta;
                  echo " <p>$loppuhinta e</p></div>";
               echo' </div>';
               echo' <br>';
            echo' </div>';
         echo' </div>';

         //Lisätään tietokantaan laskutiedot
         if($tila == "valmis"){
            $kysely = "INSERT INTO Laskut (lasku_id,tyo_id,Tila,EraPv,lahetyspv,loppusumma,laskuNmr)
            VALUES ($laskuId,$tyoId,true,'$erapv','$lpv',$loppuhinta,1);";
            $paivitys = pg_query($kysely);
         }else if ($tila == "kesken"){
            $kysely = "INSERT INTO Laskut (lasku_id,tyo_id,Tila,EraPv,loppusumma,laskuNmr)
            VALUES ($laskuId,$tyoId,false,'$erapv',$loppuhinta,1);";
            $paivitys = pg_query($kysely);
         }

         //suljetaan yhteys
         pg_close($yhteys);
      ?>
      <br/>
      <input type="hidden" name="tallenna" value="action" />
      <button type="submit" id='tallennus' class="btn btn-primary" style="background-color:#008080;">Ok</button>
   </form>
</div>
</body>
</html>