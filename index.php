<!-- PHP osnove – parcijalni ispit
1 Treba kreirati aplikaciju (vidi sliku) koja će iz
datoteke words.json u desnu tablicu ispisati sve
riječi koje su analizirane.
2 S lijeve strane treba kreirati obrazac kroz koji će
se unositi nova riječ.
3 Unesenu riječ treba obraditi na sljedeći način:
    1 polje ne smije biti prazno
    2 izbrojiti broj slova u riječi
    3 izbrojiti suglasnike i samoglasnike u
    riječi (za ovu funkcionalnost kreirajte
    funkcije).
    4 Obrađenu riječ treba zapisati u words.json
    datoteku. -->

<?php

// Ako ne postoji file words.json, kreiraj ga (json_encode([], true) znaci prazan array)
if (!file_exists("words.json")) {
    file_put_contents("words.json", json_encode([]));
}

/**
 * Funcija za brojanje suglasnika
 *
 * @param string $rijec
 * @return integer
 */
function brojacSuglasnika(string $rijec): int
{
    $slova = str_split(strtolower($rijec));
    $samoglasnici = ["a", "e", "i", "o", "u"];
    $brojSamoglasnika = 0;

    foreach ($slova as $slovo) {
        if (in_array($slovo, $samoglasnici)) {
            $brojSamoglasnika++;
        }
    }
    return $brojSamoglasnika;
}
/**
 * Funkcija za brojanje slova.
 * Replace space " " s "" kako nebismo brojali prazne znakove.
 *
 * @param string $rijec
 * @return integer
 */
function brojacSlova(string $rijec): int
{
    return strlen(str_replace(" ", "", $rijec));
}

// Ako nismo usli sa post metodom nemoj pokusati kreirati novu stavku u words.json
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ako ne postoji $_POST["rijec"] baci gresku i zaustavi skriptu
    if (empty($_POST["rijec"])) {
        echo "Greška. Niste postavili riječ.";
        die();
    }

    $json = file_get_contents("words.json");
    // Ucitaj sve rijeci iz jsona kao lista
    $rijeci = json_decode($json, true);

    $rijec = $_POST["rijec"];
    $brojSlova = brojacSlova($rijec);
    $brojSamoglasnika = brojacSuglasnika($rijec);
    $brojSuglasnika = $brojSlova - $brojSamoglasnika;

    $novaRijec = [
        "rijec" => $rijec,
        "brojSlova" => $brojSlova,
        "brojSuglasnika" =>  $brojSuglasnika,
        "brojSamoglasnika" => $brojSamoglasnika
    ];

    //Dodaj novu rijec u array svih rijeci
    $rijeci[] = $novaRijec;

    // enkodiraj json i zapisi u words.sjon
    $json = json_encode($rijeci, JSON_PRETTY_PRINT);
    file_put_contents("words.json", $json);
    str_split(strtolower($rijec));
    // Ucitaj opet stranicu jer inace na refresh salje opet podatke u post.
    header('Location:' . $_SERVER['PHP_SELF']);
}

$json = file_get_contents("words.json");
$rijeci = json_decode($json, true);

?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Parcijalni ispit - OSNOVE PHP</title>
</head>

<body>

    <div class="container">

        <form action="" method="post">
            <h2>Dodajte riječ</h2>
            <label>
                Upišite riječ:
                <input required type="text" name="rijec" id="rijec">
            </label>
            <input type="submit" value="Pošalji">

        </form>
        <table>
            <tr>
                <th>
                    Rjieč
                </th>
                <th>
                    Broj slova
                </th>
                <th>
                    Broj suglasnika
                </th>
                <th>
                    Broj samoglasnika
                </th>
            </tr>

            <?php
            foreach ($rijeci as $rijec) {
                echo "<tr>" .
                    "<td>" . $rijec["rijec"] . "</td>" .
                    "<td>" . $rijec["brojSlova"] . "</td>" .
                    "<td>" . $rijec["brojSuglasnika"] . "</td>" .
                    "<td>" . $rijec["brojSamoglasnika"] . "</td>" .
                    "</tr>";
            }
            ?>

        </table>
    </div>
</body>

</html>