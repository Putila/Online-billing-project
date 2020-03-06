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
   <div class="content">
   <h2>Urakka laskut lähetetty seuraavilla tiedoilla:</h2>
   <br>
   <form action="Laheta_urakka_lasku.php" method="post" id="form1">
   <?php
      $erat=1;
      $asiakasId=1;
      $suunnittelu=0;
      $tyo=0;
      $aputyo=0;
      $yhteishinta =0;
      $tarvikkeetyht =0;
      $tyoId = pg_escape_string($_POST['tyot']);
      $asiakasTiedot =array();
      $pv=date("d.m.Y");
      $lpv=date("Y-m-d");
      $erapv= pg_escape_string($_POST['erapv1']);
      $erapv2= pg_escape_string($_POST['erapv2']);
      $erapv3= pg_escape_string($_POST['erapv3']);
      $kirja= pg_escape_string($_POST['kirja']);
      $realErapv=$erapv[8].$erapv[9].".".$erapv[5].$erapv[6].".".$erapv[0].$erapv[1].$erapv[2].$erapv[3];
      $realErapv2=$erapv2[8].$erapv2[9].".".$erapv2[5].$erapv2[6].".".$erapv2[0].$erapv2[1].$erapv2[2].$erapv2[3];
      $realErapv3=$erapv3[8].$erapv3[9].".".$erapv3[5].$erapv3[6].".".$erapv3[0].$erapv3[1].$erapv3[2].$erapv3[3];


      $saaja = pg_escape_string($_POST['saajaNimi']);
      $saajaTili = pg_escape_string($_POST['saajaTili']);
      $saajaOsoite = pg_escape_string($_POST['saajaosoite']);
      $saajaKunta = pg_escape_string($_POST['saajakunta']);

      $tila = pg_escape_string($_POST['laskuntila']);

      //yhteyden luomiseen tiedot
   $y_tiedot = "host=dbstud2.sis.uta.fi port=5432 dbname=rp422600 user=rp422600 password=bigchungus";
      //onnistuuko yhteyden luominen
      if (!$yhteys = pg_connect($y_tiedot))
         die("Tietokantayhteyden luominen epäonnistui.");
      //asetetaan search path oikeaksi
       pg_query("SET SEARCH_PATH TO SahkoFirma;");


       $viimeisin = pg_query("SELECT MAX(Lasku_id) FROM laskut");
       $arvo = pg_fetch_row($viimeisin);
       $laskuId = $arvo[0] + 1;
       $laskuId2 = $arvo[0] + 2;
       $laskuId3 = $arvo[0] + 3;
       
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
           exit;
         }
         
         while ($rivi = pg_fetch_row($tulos)) {
            $suunnitteluhinta = $suunnittelu * $rivi[0]* (1-($rivi[1]/100));

         }
         $tulos = pg_query("SELECT tyohinnasto.hinta, maksaa.alennus FROM tyohinnasto,maksaa, tyosuoritukset WHERE tyosuoritukset.tyo_id=maksaa.tyo_id AND tyohinnasto.tyyppi = maksaa.tyyppi AND tyohinnasto.tyyppi = 'tyo' AND tyosuoritukset.tyo_id = $tyoId;");
         if (!$tulos) {
           echo "Virhe kyselyssä.\n";
           exit;
         }
         
         while ($rivi = pg_fetch_row($tulos)) {
            $tyohinta = $tyo * $rivi[0]* (1-($rivi[1]/100));

         }
         $tulos = pg_query("SELECT tyohinnasto.hinta, maksaa.alennus FROM tyohinnasto,maksaa, tyosuoritukset WHERE tyosuoritukset.tyo_id=maksaa.tyo_id AND tyohinnasto.tyyppi = maksaa.tyyppi AND tyohinnasto.tyyppi = 'aputyo' AND tyosuoritukset.tyo_id = $tyoId;");
         if (!$tulos) {
           echo "Virhe kyselyssä.\n";
           exit;
         }
         
         while ($rivi = pg_fetch_row($tulos)) {
            $apuhinta = $aputyo * $rivi[0]* (1-($rivi[1]/100));

         }
         $yhteishinta= $suunnitteluhinta + $tyohinta + $apuhinta;

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

    ?>
    <?php
         echo'<div class="lasku" style= "border: solid;border-color:Gainsboro; width:900px;border-width: 1px;">';
            echo '<div style="margin-left:15px;padding-bottom:20px;">';
            echo '<h4 style="margin-top:5px;">Tmi Sähkötärsky </h4>';
                echo '<div style= "color:white;background-color:black;padding-top:4px;margin-top:30px;padding-left:10px;padding-bottom:3px; width:80%;"> Työt</div>';
                    echo '<div class="card" style="width:80%;">';
                        echo '<div class="card-body">';
                            echo "<p>Suunnittelu: $suunnittelu h</p>";
                            echo "<p>Työ: $tyo h </p>";
                            echo "<p>Apulaisen työ: $aputyo h </p>";
                            echo '<p style="margin-left:420px;">';
                            echo "Yhteishinta: $yhteishinta e  (sis.alv) </p>";
                        echo '</div>';
                    echo '</div>';


                echo '<div style= "color:white;background-color:black;padding-top:4px;margin-top:30px;padding-left:10px;padding-bottom:3px;width:80%;"> Tarvikkeet</div>';
                    echo '<div class="card" style="width:80%;">';
                        echo '<div class="card-body">';
                            $tulos = pg_query("SELECT tarvikkeet.nimi,kaytettiin.maara,tarvikkeet.yksikko, kaytettiin.alennus, tarvikkeet.myyntihinta FROM tarvikkeet, kaytettiin, tyosuoritukset WHERE tarvikkeet.tarvike_id = kaytettiin.tarvike_id AND kaytettiin.tyo_id = tyosuoritukset.tyo_id AND tyosuoritukset.tyo_id = $tyoId;");
                            if (!$tulos) {
                              echo "Virhe kyselyssä.\n";
                              exit;
                            }
                            
                            while ($rivi = pg_fetch_row($tulos)) {
                                $hinta = $rivi[4] *$rivi[1];
                                $kerroin =1-($rivi[3]/100);
                                $hinta =round($hinta * $kerroin,2);
                                $alvhinta=round($hinta * 1.24,2);
                                $tarvikkeetyhtalv =$tarvikkeetyhtalv +$alvhinta;
                                $tarvikkeetyht = $tarvikkeetyht +$hinta;
                                echo '<div style="display:flex">';
                                    echo '<p style="width:300px;">';
                                    echo "$rivi[0]</p>";
                                    echo '<p style="width:100px;padding-right:2px;">';
                                    echo "$rivi[1] $rivi[2] </p>";
                                    echo '<p style="width:150px;padding-right:2px;">';
                                    echo "Alennus: $rivi[3]% </p>";
                                echo '</div>';
                            }
                            echo '<p style="margin-left:420px;">';
                            echo "Yhteishinta ilman alv: $tarvikkeetyht e </p>";
                            echo '<p style="margin-left:420px;">';
                            echo "Yhteishinta sis alv: $tarvikkeetyhtalv e </p>";

                        echo '</div>';
                    echo '</div>';
                    echo' </div>';
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
                    echo' <div style="height:100px;border-bottom:solid;border-width: 2px;padding-left:20px;padding-top:20px;padding-left:40px;">';
                    echo"<p><b>Laskun numero</b>: $laskuId</p>";
                    echo"<p><b>Erä</b>: 1/$erat</p>";
                    echo '</div>';
                    echo' <div style="display:flex;border-bottom:solid;border-width: 2px;padding-left:20px;padding-top:20px;padding-left:40px;">';
                    echo"<p><b>Eräpäivä</b>:&nbsp;</p> <p>$realErapv</p>";
                    echo '</div>';
                    echo' <div style="display:flex;padding-left:20px;padding-top:20px;padding-left:40px;"><p><b>Summa</b>:&nbsp;</p>';
                    $loppuhinta= ($tarvikkeetyht +$tarvikkeetyhtalv)/$erat;
                    echo " <p>$loppuhinta e</p></div>";
                    echo' </div>';
                    echo' <br>';
                    echo' </div>';
                    echo' </div>';
                echo' </div>';
            echo' </div>';


            if($erat > 1){
                echo'<br>';
                echo'<br>';
                echo'<div class="lasku" style= "border: solid;border-color:Gainsboro; width:900px;border-width: 1px;">';
            echo '<div style="margin-left:15px;padding-bottom:20px;">';
            echo '<h4 style="margin-top:5px;">Tmi Sähkötärsky </h4>';

                echo '<div style= "color:white;background-color:black;padding-top:4px;margin-top:30px;padding-left:10px;padding-bottom:3px; width:80%;"> Työt</div>';
                    echo '<div class="card" style="width:80%;">';
                        echo '<div class="card-body">';
                            echo "<p>Suunnittelu: $suunnittelu h</p>";
                            echo "<p>Työ: $tyo h </p>";
                            echo "<p>Apulaisen työ: $aputyo h </p>";
                            echo '<p style="margin-left:420px;">';
                            echo "Yhteishinta: $yhteishinta e  (sis.alv) </p>";
                        echo '</div>';
                    echo '</div>';


                echo '<div style= "color:white;background-color:black;padding-top:4px;margin-top:30px;padding-left:10px;padding-bottom:3px;width:80%;"> Tarvikkeet</div>';
                    echo '<div class="card" style="width:80%;">';
                        echo '<div class="card-body">';
                            $tulos = pg_query("SELECT tarvikkeet.nimi,kaytettiin.maara,tarvikkeet.yksikko, kaytettiin.alennus, tarvikkeet.myyntihinta FROM tarvikkeet, kaytettiin, tyosuoritukset WHERE tarvikkeet.tarvike_id = kaytettiin.tarvike_id AND kaytettiin.tyo_id = tyosuoritukset.tyo_id AND tyosuoritukset.tyo_id = $tyoId;");
                            if (!$tulos) {
                              echo "Virhe kyselyssä.\n";
                              exit;
                            }
                            
                            while ($rivi = pg_fetch_row($tulos)) {
                                $hinta = $rivi[4] *$rivi[1];
                                $kerroin =1-($rivi[3]/100);
                                $hinta =round($hinta * $kerroin,2);
                                $alvhinta=round($hinta * 1.24,2);
                                $tarvikkeetyhtalv =$tarvikkeetyhtalv +$alvhinta;
                                $tarvikkeetyht = $tarvikkeetyht +$hinta;
                                echo '<div style="display:flex">';
                                    echo '<p style="width:300px;">';
                                    echo "$rivi[0]</p>";
                                    echo '<p style="width:100px;padding-right:2px;">';
                                    echo "$rivi[1] $rivi[2] </p>";
                                    echo '<p style="width:150px;padding-right:2px;">';
                                    echo "Alennus: $rivi[3]% </p>";
                                echo '</div>';
                            }
                            echo '<p style="margin-left:420px;">';
                            echo "Yhteishinta ilman alv: $tarvikkeetyht e </p>";
                            echo '<p style="margin-left:420px;">';
                            echo "Yhteishinta sis alv: $tarvikkeetyhtalv e </p>";

                        echo '</div>';
                    echo '</div>';
                    echo' </div>';
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
                    echo' <div style="height:100px;border-bottom:solid;border-width: 2px;padding-left:20px;padding-top:20px;padding-left:40px;">';
                    echo"<p><b>Laskun numero</b>: $laskuId2</p>";
                    echo"<p><b>Erä</b>: 2/$erat</p>";
                    echo '</div>';
                    echo' <div style="display:flex;border-bottom:solid;border-width: 2px;padding-left:20px;padding-top:20px;padding-left:40px;">';
                    echo"<p><b>Eräpäivä</b>:&nbsp;</p> <p>$realErapv2</p>";
                    echo '</div>';
                    echo' <div style="display:flex;padding-left:20px;padding-top:20px;padding-left:40px;"><p><b>Summa</b>:&nbsp;</p>';
                    $loppuhinta= ($tarvikkeetyht +$tarvikkeetyhtalv)/$erat;
                    echo " <p>$loppuhinta e</p></div>";
                    echo' </div>';
                    echo' <br>';
                    echo' </div>';
                    echo' </div>';
                echo' </div>';
            echo' </div>';
            }
            if($erat > 2){
                echo'<br>';
                echo'<br>';
                echo'<div class="lasku" style= "border: solid;border-color:Gainsboro; width:900px;border-width: 1px;">';
            echo '<div style="margin-left:15px;padding-bottom:20px;">';
            echo '<h4 style="margin-top:5px;">Tmi Sähkötärsky </h4>';

                echo '<div style= "color:white;background-color:black;padding-top:4px;margin-top:30px;padding-left:10px;padding-bottom:3px; width:80%;"> Työt</div>';
                    echo '<div class="card" style="width:80%;">';
                        echo '<div class="card-body">';
                            echo "<p>Suunnittelu: $suunnittelu h</p>";
                            echo "<p>Työ: $tyo h </p>";
                            echo "<p>Apulaisen työ: $aputyo h </p>";
                            echo '<p style="margin-left:420px;">';
                            echo "Yhteishinta: $yhteishinta e  (sis.alv) </p>";
                        echo '</div>';
                    echo '</div>';


                echo '<div style= "color:white;background-color:black;padding-top:4px;margin-top:30px;padding-left:10px;padding-bottom:3px;width:80%;"> Tarvikkeet</div>';
                    echo '<div class="card" style="width:80%;">';
                        echo '<div class="card-body">';
                            $tulos = pg_query("SELECT tarvikkeet.nimi,kaytettiin.maara,tarvikkeet.yksikko, kaytettiin.alennus, tarvikkeet.myyntihinta FROM tarvikkeet, kaytettiin, tyosuoritukset WHERE tarvikkeet.tarvike_id = kaytettiin.tarvike_id AND kaytettiin.tyo_id = tyosuoritukset.tyo_id AND tyosuoritukset.tyo_id = $tyoId;");
                            if (!$tulos) {
                              echo "Virhe kyselyssä.\n";
                              exit;
                            }
                            
                            while ($rivi = pg_fetch_row($tulos)) {
                                $hinta = $rivi[4] *$rivi[1];
                                $kerroin =1-($rivi[3]/100);
                                $hinta =round($hinta * $kerroin,2);
                                $alvhinta=round($hinta * 1.24,2);
                                $tarvikkeetyhtalv =$tarvikkeetyhtalv +$alvhinta;
                                $tarvikkeetyht = $tarvikkeetyht +$hinta;
                                echo '<div style="display:flex">';
                                    echo '<p style="width:300px;">';
                                    echo "$rivi[0]</p>";
                                    echo '<p style="width:100px;padding-right:2px;">';
                                    echo "$rivi[1] $rivi[2] </p>";
                                    echo '<p style="width:150px;padding-right:2px;">';
                                    echo "Alennus: $rivi[3]% </p>";
                                echo '</div>';
                            }
                            echo '<p style="margin-left:420px;">';
                            echo "Yhteishinta ilman alv: $tarvikkeetyht e </p>";
                            echo '<p style="margin-left:420px;">';
                            echo "Yhteishinta sis alv: $tarvikkeetyhtalv e </p>";

                        echo '</div>';
                    echo '</div>';
                    echo' </div>';
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
                    echo' <div style="height:100px;border-bottom:solid;border-width: 2px;padding-left:20px;padding-top:20px;padding-left:40px;">';
                    echo"<p><b>Laskun numero</b>: $laskuId3</p>";
                    echo"<p><b>Erä</b>: 3/$erat</p>";
                    echo '</div>';
                    echo' <div style="display:flex;border-bottom:solid;border-width: 2px;padding-left:20px;padding-top:20px;padding-left:40px;">';
                    echo"<p><b>Eräpäivä</b>:&nbsp;</p> <p>$realErapv3</p>";
                    echo '</div>';
                    echo' <div style="display:flex;padding-left:20px;padding-top:20px;padding-left:40px;"><p><b>Summa</b>:&nbsp;</p>';
                    $loppuhinta= ($tarvikkeetyht +$tarvikkeetyhtalv)/$erat;
                    echo " <p>$loppuhinta e</p></div>";
                    echo' </div>';
                    echo' <br>';
                    echo' </div>';
                    echo' </div>';
                echo' </div>';
            echo' </div>';
            }

            $kysely = "INSERT INTO Laskut (lasku_id,tyo_id,Tila,EraPv,lahetyspv,loppusumma,laskuNmr)
                 VALUES ($laskuId,$tyoId,true,'$erapv','$lpv',$loppuhinta,1);";
                 $paivitys = pg_query($kysely);
            if($erat >1){
                $kysely = "INSERT INTO Laskut (lasku_id,tyo_id,Tila,EraPv,lahetyspv,loppusumma,laskuNmr)
                 VALUES ($laskuId2,$tyoId,true,'$erapv2','$lpv',$loppuhinta,2);";
                 $paivitys = pg_query($kysely);
            }
            if($erat >2){
                $kysely = "INSERT INTO Laskut (lasku_id,tyo_id,Tila,EraPv,lahetyspv,loppusumma,laskuNmr)
                 VALUES ($laskuId3,$tyoId,true,'$erapv3','$lpv',$loppuhinta,3);";
                 $paivitys = pg_query($kysely);
            }

            pg_close($yhteys);
        
        ?>
         <br />
         <input type="hidden" name="tallenna" value="action" />
         <button type="submit" id='tallennus' class="btn btn-primary" style="background-color:#008080;">Ok</button>
         </form>
      </div>

</body>
</html>