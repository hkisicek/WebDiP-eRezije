<?php
//Skripta za kreiranje kategorija.

include_once ("okviri/baza_class.php");
include_once ("okviri/postavi_vrijeme.php");
include_once ("okviri/izbornik.php");

session_start();
$greska = "";
$baza = new Baza();
$moderator = "";
$ustanova = "";
$vrijeme = azuriraj();

if (!isset($_SESSION['tip_korisnik']) || $_SESSION['tip_korisnik'] < 2) {
    header("Location:zabrana.php");
}
$tip = $_SESSION['tip_korisnik'];
$moderator = $_SESSION['ID_korisnik'];

$upit = "SELECT * FROM Moderator_ustanova WHERE FK_moderator='$moderator';";
$rezultat = $baza->selectDB($upit);
while ($rez = $rezultat->fetch_array()) {
    $ustanova_id = $rez['FK_ustanova'];
    $upit2 = "SELECT Naziv FROM Ustanova WHERE ID_ustanova='$ustanova_id';";
    $rezultat2 = $baza->selectDB($upit2);
    $rez2 = $rezultat2->fetch_array();
    $naziv = $rez2['Naziv'];
    $ustanova .= "<option value='$ustanova_id'>$naziv</option>";
    $ustanova .= "<br>";
}

if (isset($_POST['dodaj'])) {

    $kategorija = $_POST['kategorija'];
    $cijena = $_POST['cijena'];
    $jedinica = $_POST['jedinica'];
    $ustanova = $_POST['ustanova'];
    $datum = $_POST['datum'];
    $opis = $_POST['opis'];
    $idUst = $_POST['ustanova'];

    $dnevnikInsert = "insert into Dnevnik values(default,'nova_kategorija.php','INSERT INTO Kategorija_ocitavanja;','$vrijeme',$moderator,2);";
    $baza->updateDB($dnevnikInsert);

    $upit3 = "INSERT INTO Kategorija_ocitavanja VALUES (default,'$kategorija','$cijena','$jedinica','$datum','$opis','0','$moderator','$ustanova');";
    $baza->updateDB($upit3);

    header("Location:popis_kategorija.php?ustanova=$idUst");
}
?>
<!DOCTYPE html>
<!--
Skripta za unos novih kategorija.
Datum:1.6.2016.
-->
<html>
    <head>
        <title>E-Režije</title>
        <meta name="author" content="Helena Kišiček">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="./css/hkisicek1.css">
        <script src="js/izbornik.js"></script>
        <meta charset="UTF-8">
    </head>
    <body>
        <header id="header">
            <p>E-Režije</p>
            <a class="strana" href="odjava.php" style="">Odjava</a>
            <a class="strana" href='prijava.php' style="">Prijava</a>
            <p id="prijavljeno"> <?php
                if (isset($_SESSION['ID_korisnik'])) {
                    echo "Prijavljeni ste kao " . $_SESSION['korisnicko'];
                }
                ?>
            </p>
        </header>
        <div id="omotac">
            <ul class="topnav">
                <?php echo kreirajIzbornik($tip); ?>
                <li class="icon">
                    <a href="javascript:void(0);" onclick="pokaziIzbornik()">&#8801;</a>
                </li>
            </ul>


            <section id="sadrzaj">
                <div class="large-11 large-offset-1 columns" style="color: #ff2121; font-size: 14px;">
                    <?php echo $greska; ?>
                </div> 
                <div id="inputForma">
                    <form name="nova_kategorija" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

                        <label id="kategorija" for="jedinica">Kategorija: </label><br/>
                        <select id="kategorija" name="kategorija" required="">
                            <option value="dnevna" >dnevna</option>
                            <option value="noćna">noćna</option>
                            <option value="vikend">vikend</option>
                            <option value="blagdanska">blagdanska</option>
                            <option value="velika blagdanska">velika blagdanska</option>
                            <option value="ostalo">ostalo</option>
                        </select><br/><br/>

                        <label id="cijena" for="cijena">Cijena: </label><br/>
                        <input type="number" step="0.01" name="cijena" min="0" id="cijena" required="" /><br/>

                        <label id="jedinica" for="jedinica">Jedinica: </label><br/>
                        <select id="jedinica" name="jedinica" required="">
                            <option value="kn/kWh" >kn/kWh</option>
                            <option value="kn/m3">kn/m3</option>
                        </select>
                        <br/><br/>

                        <label for="datum">Datum: </label><br/>
                        <input type="date" name="datum" required=""/><br/>

                        <label id="opis" for="opis">Opis: </label><br/>
                        <input type="text" name="opis"/><br/>

                        <label for="ustanova">Ustanova: </label><br/>
                        <select id="ustanova_select" name="ustanova" required=""><?php echo $ustanova; ?></select><br>

                        <input id="submit" name="dodaj" type="submit" value="Dodaj kategoriju" class="gumb">


                    </form>
                </div>
            </section>
        </div>
        <footer id="footer">
            <address>
                Kontaktirajte me na: <br>
                <a class="strana" href="mailto:hkisicek@foi.hr">Helena Kišiček</a>
            </address>
            <p><small>&copy; Sva prava pridržana, Web dizajn i programiranje, 2016</small></p>

        </footer>
    </body>
</html>