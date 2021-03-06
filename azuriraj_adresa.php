<?php
//Skripta za azuriranje adresa.

include_once ("okviri/baza_class.php");
include_once ("okviri/postavi_vrijeme.php");
include_once ("okviri/izbornik.php");

session_start();

$greska = "";
$ustanova = "";
$baza = new Baza();
$vrijeme = azuriraj();

if (!isset($_SESSION['tip_korisnik']) || $_SESSION['tip_korisnik'] != 3) {
    header("Location:zabrana.php");
}

$tip = $_SESSION['tip_korisnik'];
$upit = "SELECT * FROM Ustanova;";
$rezultat = $baza->selectDB($upit);
$ID_adresa = $_GET['adresa'];
$dnevnikID = $_SESSION['ID_korisnik'];

while ($rez = $rezultat->fetch_array()) {
    $naziv = $rez['Naziv'];
    $id = $rez['ID_ustanova'];
    $ustanova.= "<option value='$id'>$naziv</option>";
    $ustanova .= "<br>";
}

if (isset($_POST['dodaj'])) {
    $ID_adresa = $_GET['adresa'];
    $ulica = $_POST['ulica'];
    $broj = $_POST['broj'];
    $grad = $_POST['grad'];
    $drzava = $_POST['drzava'];
    $ustanova_id = $_POST['ustanova'];

    $dnevnikInsert = "insert into Dnevnik values(default,'azuriraj_adresa.php','UPDATE Poslovnica;','$vrijeme',$dnevnikID,3);";
    $baza->updateDB($dnevnikInsert);

    $update2 = "UPDATE Poslovnica SET Drzava='$drzava', Grad='$grad', Ulica='$ulica', Broj='$broj', FK_ustanova='$ustanova_id' WHERE ID_poslovnica='$ID_adresa';";
    $baza->updateDB($update2);
    header("Location:pregled_adresa.php");
}
?>
<!DOCTYPE html>
<!--
Skripta za unos novih adresa.
Datum:1.6.2016.
-->
<html>
    <head>
        <title>E-Režije</title>
        <meta name="author" content="Helena Kišiček">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/hkisicek1.css" />
        <script src="js/izbornik.js"></script>
        <script src="js/provjera.js"></script>

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
        </header>
        <div id="omotac">
            <ul class="topnav">
                <?php echo kreirajIzbornik($tip); ?>
                <li class="icon">
                    <a href="javascript:void(0);" onclick="pokaziIzbornik()">&#8801;</a>
                </li>
            </ul>



            <section id="sadrzaj">
                <div id="inputForma">
                    <article id="greska"><?php echo $greska; ?> </article>
                    <form id="forma" method="post" action="<?php echo "azuriraj_adresa.php?adresa=$ID_adresa"; ?>">

                        <label id="ulicag" for="ulica">Ulica: </label><br/>
                        <input type="text" name="ulica" id="ulica" placeholder="" required=""/><br/>

                        <label id="ulicag" for="broj">Broj: </label><br/>
                        <input type="text" name="broj" id="broj" placeholder="" required="" /><br/>

                        <label id="gradg" for="grad">Grad: </label><br/>
                        <input type="text" name="grad" id="grad" placeholder="" required=""/><br/>

                        <label id="gradg" for="drzava">Država: </label><br/>
                        <input type="text" name="drzava" id="grad" placeholder="" required="" /><br/>

                        <label for="ustanova">Ustanova: </label>
                        <select id="ustanova_select" name="ustanova" required=""><?php echo $ustanova; ?></select><br>

                        <input id="submit" name="dodaj" type="submit" value="Ažuriraj" class="gumb"><br/>

                    </form>
                </div>
                <br/><br/><br/>
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

