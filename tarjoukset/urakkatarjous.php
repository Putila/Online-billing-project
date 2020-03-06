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
         $kohde   = pg_escape_string($_POST['kohde']);
         $suunnittelu   = floatval($_POST['suunnittelu']);
         $tyotunnit   = floatval($_POST['tyotunnit']);
         $aputyotunnit   = floatval($_POST['aputyotunnit']);
         $tyotYht = $suunnittelu * 55 + $tyotunnit *45 + $aputyotunnit * 35;
         $viesti="Urakkatarjous";
      ?>
   <div style="display:flex;">   
   <div style="margin-left:10px;">    
      <h2>Urakkatarjouksen luonti</h2>
      <form action="" method="post" style="width:500px;">
      <table class="table table-hover">
         <tr>
         <td>Kohde:</td>
         <td>
         <?php
         $y_tiedot = "host=dbstud2.sis.uta.fi port=5432 dbname=rp422600 user=rp422600 password=bigchungus";
               if (!$yhteys = pg_connect($y_tiedot))
                  die("Tietokantayhteyden luominen epäonnistui.");

               pg_query("SET SEARCH_PATH TO SahkoFirma;");
               session_start();
               if(!isset($_SESSION['tarvikeLista'])){
                  // create an array
                  $my_array=array();
                  
                  // put the array in a session variable
                  $_SESSION['tarvikeLista']=$my_array;
                  $_SESSION["idt"] = 1;
                  $_SESSION["name"]= "Valitse kohde";
               }

                $kohteet = pg_query("SELECT Nimi,Kohde_id  FROM Kohteet ORDER BY kohteet.kohde_id");
                echo '<select name="kohde" class="form-control">';
                     echo '<option value="' .$_SESSION["idt"].'">Kohde: '.$_SESSION["name"].'</option>';
                while ($tRivi =  pg_fetch_row($kohteet)){
                    echo '<option value="' .$tRivi[1].'">Kohde: '.$tRivi[0].'</option>';
                }
                pg_close($yhteys);
                
        ?>
        </td>
         </tr>
         <tr>
            <td>Suunnittelutunnit:</td>
            <td><input type="text"  class="form-control" name="suunnittelu" value="<?php echo $suunnittelu;?>"/></td>
         </tr>
         <tr>
            <td>Työtunnit:</td>
            <td><input type="text"  class="form-control" name="tyotunnit" value="<?php echo $tyotunnit;?>"/></td>
         </tr>
         <tr>
            <td>Avustajan tunnit:</td>
            <td><input type="text" class="form-control" name="aputyotunnit" value="<?php echo $aputyotunnit;?>"/></td>
         </tr>
         </table>
            <div style="display:flex;">
            <?php
            $y_tiedot = "host=dbstud2.sis.uta.fi port=5432 dbname=rp422600 user=rp422600 password=bigchungus";
               if (!$yhteys = pg_connect($y_tiedot))
                  die("Tietokantayhteyden luominen epäonnistui.");

               pg_query("SET SEARCH_PATH TO SahkoFirma;");
               session_start();
               if(!isset($_SESSION['tarvikeLista'])){
                  // create an array
                  $my_array=array();
                  
                  // put the array in a session variable
                  $_SESSION['tarvikeLista']=$my_array;
               }



               if($_REQUEST['btn_submit']=="Anna arvio"){
                  $viesti="Lopullinen urakkatarjous";
               }
               else if($_REQUEST['btn_submit']=="Lisää tarvike"){
                  $maaraArvo   = floatval($_POST['maara']);
                  if (isset($_POST["tarvike"]) and is_float($maaraArvo) and $maaraArvo != 0) {
                     $tarvikeId= $_POST["tarvike"];
                     $tulos = pg_query("SELECT nimi,myyntihinta,yksikko FROM Tarvikkeet WHERE tarvike_id=$tarvikeId");
                     if (!$tulos) {
                        echo "Virhe kyselyssä.\n";
                        exit;
                     }
                     while ($rivi = pg_fetch_row($tulos)) {
                        $tarvikeNimi=$rivi[0];
                        $tarvikeHinta=$rivi[1];
                        $tarvikeYksikko=$rivi[2];
                        $maara=$_POST["maara"];
                        $newVal=array($tarvikeNimi,$tarvikeHinta,$tarvikeYksikko,$maara);
                     }
                     $my_array=$_SESSION['tarvikeLista'];
                     array_push($my_array,$newVal);
                     $_SESSION['tarvikeLista']= $my_array;
                  }
                  $kohdetiedot= pg_query("SELECT nimi FROM Kohteet WHERE kohde_id=$kohde");
                     while ($rivi = pg_fetch_row($kohdetiedot)) {
                        $kohdeNimi=$rivi[0];
                     }
                  $_SESSION["idt"]= $kohde;
                  $_SESSION["name"]= $kohdeNimi;

               }
               else if($_REQUEST['btn_submit']=="Hyväksy tarjous"){
                  $viimeisin = pg_query("SELECT MAX(tyo_id) FROM TyoSuoritukset");
                  $arvo = pg_fetch_row($viimeisin);
                  $id = $arvo[0] + 1;
                  $kuvaus="Urakkatyö firmalta Tmi Sähkötärsky";
                  $erat=1;
                  $tyomuoto="Urakka";

                  $kysely = "INSERT INTO TyoSuoritukset (Tyo_id, Kuvaus, Erat, Tyomuoto) VALUES ($id, '$kuvaus', '$erat', '$tyomuoto')";
                  $paivitys = pg_query($kysely);
                  if($paivitys && (pg_affected_rows($paivitys) > 0)){

                     $kohdeJaTyo = "INSERT INTO Tehtiin (Kohde_id, Tyo_id) VALUES ($kohde, $id)";
                     $kohdeTyoPaivitys = pg_query($kohdeJaTyo);
     
     
                     if(!$urakkaLippu){
                         $tunnitTemp = "INSERT INTO tunnit (Tyo_id,Suunnittelu_tunnit,Tyo_tunnit,aputyo_tunnit) VALUES ($id,$suunnittelu,$tyotunnit,$aputyotunnit)";
                         $tunnit = pg_query($tunnitTemp);
                     }
     
                     // asetetaan viesti-muuttuja lisäämisen onnistumisen mukaan
                     // lisätään virheilmoitukseen myös virheen syy (pg_last_error)
     
                     if ($kohdeTyoPaivitys && (pg_affected_rows($kohdeTyoPaivitys) > 0))
                         $viesti = 'Tarjous hyväksytty!';
                     else
                         $viesti = 'Tarjouksen lisäyksessä ongelma';
                 }
                 $kohdetiedot= pg_query("SELECT nimi FROM Kohteet WHERE kohde_id=$kohde");
                     while ($rivi = pg_fetch_row($kohdetiedot)) {
                        $kohdeNimi=$rivi[0];
                     }
                  $_SESSION["idt"]= $kohde;
                  $_SESSION["name"]= $kohdeNimi;

               }
               
                $tyot = pg_query("SELECT Nimi,Tarvike_id  FROM Tarvikkeet ORDER BY tarvikkeet.tarvike_id");

                echo '<select name="tarvike" class="form-control">';
                while ($tRivi =  pg_fetch_row($tyot)){
                    echo '<option value="' .$tRivi[1].'">Tuote: '.$tRivi[0].'</option>';
                }

                $kohdetiedot= pg_query("SELECT Osoite,nimi,omistaja  FROM Kohteet WHERE kohde_id=$kohde");
                while ($rivi = pg_fetch_row($kohdetiedot)) {
                  $kohdeOsoite=$rivi[0];
                  $kohdeNimi=$rivi[1];
                  $kohdeOmistaja=$rivi[2];
               }
               $asiakastiedot= pg_query("SELECT asiakkaat.kotikunta,asiakkaat.nimi,asiakkaat.osoite FROM asiakkaat, kohteet WHERE kohteet.omistaja=asiakkaat.asiakas_id AND kohde_id=$kohde");
               while ($rivi = pg_fetch_row($asiakastiedot)) {
                  $aKotikunta=$rivi[0];
                  $aNimi=$rivi[1];
                  $aOsoite=$rivi[2];
               }
                     




                pg_close($yhteys);
                
            ?>



            <input id="maara" class="form-control" type="text" name="maara" value="1" style="margin-left:5px;" />
            </div>
            <button type="submit" value="Lisää tarvike" id='tallennus' name="btn_submit" class="btn btn-primary" style="background-color:grey;margin-left:350px; margin-top:10px;">Lisää Tarvike</button>
            <br>
            <button type="submit" value="Anna arvio" id='tallennus' name="btn_submit" class="btn btn-primary" style="background-color:#008080;margin-left:355px; margin-top:50px;">Anna arvio</button>
            <br>
         <br>
      
      </div>

      <div style="width:1050px;margin-left:30px;">
         <h3 style="padding-left:70px;"><?php echo "$viesti";?></h3>
         <div class="card">
            <div class="card-body">
                <div>
                <?php echo "<p>Asiakas: $aNimi , ($aOsoite,$aKotikunta)</p>"; ?>
                <?php echo "<p>Kohde: $kohdeNimi, ($kohdeOsoite)</p>"; ?>
                </div>


                <div style="display:flex;">
                  <div style="width:470px;margin-right:10px;"> 
                     <div style="display:flex;color:white;background-color:#008080;padding-top:4px;width:470px;">
                        <p style="width:155px;margin-left:5px;padding-left:20px;">Työ</p>
                        <p style="width:100px;">Määrä</p>
                        <p style="width:100px;">Hinta</p>
                        <p style="width:100px;">Summa</p>
                     </div>
                     <div style="width:470px;">
                        <div class="card">
                           <div class="card-body">
                              <div style="display:flex;">
                                 <p style="width:140px;">
                                 <?php
                                    echo "Suunnittelu:</p>";?>
                                 <p style="width:100px;">
                                 <?php
                                    echo "$suunnittelu h</p>";?>
                                 <p style="width:110px;">55e/h</p>
                                 <?php
                                    $shinta=$suunnittelu* 55;
                                    echo "<p>$shinta e</p>";?>
                              </div>
                              <div style="display:flex;">
                                 <p style="width:140px;">
                                 <?php
                                    echo "Työtunnit:</p>"; ?>
                                 <p style="width:100px;">
                                 <?php
                                    echo "$tyotunnit h</p>";?>
                                 <p style="width:110px;">45e/h</p>
                                 <?php
                                    $thinta=$tyotunnit* 45;
                                    echo "<p>$thinta e</p>";?>
                              </div>
                              <div style="display:flex;">
                                 <p style="width:140px;">
                                 <?php
                                    echo "Apulaisen tunnit:</p>"; ?>
                                 <p style="width:100px;">
                                 <?php
                                    echo "$aputyotunnit h</p>"; ?>
                                 <p style="width:110px;">35e/h</p>
                                 <?php
                                    $tahinta=$aputyotunnit* 35;
                                    echo "<p>$tahinta e</p>";?>
                              </div>
                              <br><p>
                              <?php
                                 $tyotYht= round($tyotYht,2);
                                 echo "Yhteensä: $tyotYht e </p>"; ?>
                           </div>
                        </div>
                     </div>
                  </div>


                  <div>
                     <div style="display:flex;color:white;background-color:#008080;width:500px;padding-top:5px;">
                        <p style="width:205px;margin-left:5px;padding-left:20px;">Tarvike</p>
                        <p style="width:100px;">Määrä</p>
                        <p style="width:100px;">Hinta</p>
                        <p style="width:100px;">Summa</p>
                     </div>
                     <div style="width:500px;">
                        <div class="card">
                           <div class="card-body">
                              <?php
                                 session_start();
                                 $tarvikkeetYht=0;
                                 foreach($_SESSION['tarvikeLista'] as $key=>$value){
                                    $summa = $value[1]*$value[3];
                                    // and print out the values
                                    echo '<div style="display:flex;">';
                                       echo '<p style="width:205px;margin-left:5px;">';
                                       echo "$value[0]</p>";
                                       echo '<p style="width:100px;">';
                                       echo "$value[3]</p>";
                                       echo '<p style="width:100px;">';
                                       echo "$value[1]e /$value[2]<p>";
                                       echo '<p style="width:90px;padding-left:5px;">';
                                       echo "$summa e</p>";
                                    echo "</div>";
                                    $tarvikkeetYht =$tarvikkeetYht + $summa;
                                 } 
                                 echo '<br><p>';
                                    $tarvikkeetYht= round($tarvikkeetYht,2);
                                    echo "Yhteensä: $tarvikkeetYht e </p>";
                              ?>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

               <div style="width:600px;margin-bottom:10px;margin-left:10px;">
                  <div style= "color:white;background-color:#008080;padding-top:4px;margin-top:30px;padding-left:5px;"> Loppusumma</div>
                     <div class="card">
                        <div class="card-body">
                           <?php
                              $loppusumma = round($tarvikkeetYht +$tyotYht,2);
                              echo '<p>';
                              echo "Lopullinen urakkatarjous: $loppusumma e</p>";
                           ?>
                        </div>
                     </div>
                  </div>
               </div>
               <button type="submit" value="Hyväksy tarjous" id='tallennus' name="btn_submit" class="btn btn-primary" style="background-color:DarkOrange;margin-left:900px; margin-top:10px;">Hyväksy tarjous</button>
            </div>
         </div>
      </div>
      </form> 

</body>
</html>