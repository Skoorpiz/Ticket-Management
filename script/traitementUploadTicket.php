<?php
include_once '../includes/bdd.php';
include_once '../includes/functions.php';
$download = 'fichier';
$debug = false;
if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    $allowed = array("csv" => "text/csv");
    $filename = $_FILES["file"]["name"];
    $filetype = $_FILES["file"]["type"];
    $filesize = $_FILES["file"]["size"];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (!array_key_exists($ext, $allowed))
        die("Erreur : Veuillez sélectionner un format de fichier valide.");
    // if (in_array($filetype, $allowed)) {
    if (file_exists($_FILES["file"]["name"])) {
        echo $_FILES["file"]["name"] . " existe déjà.";
    } else {
        move_uploaded_file($_FILES["file"]["tmp_name"], "$download/$filename");
        if ($debug == true) {
            echo "Votre fichier a été téléchargé avec succès.";
        }
?>
        <?php
        if ($debug == true) {
            echo "<br>";
            echo "Nom du fichier : " . $filename;
            echo "<br>";
            echo "Taille du fichier : " . $filesize;
            echo "<br>";
            echo "Type du fichier : " . $filetype;
        }
    }
    // } else {
    //     echo "Error: Il y a eu un problème de téléchargement de votre fichier. Veuillez réessayer.";
    // }
} else {
    echo "Error: " . $_FILES["file"]["error"];
}
$file = __DIR__ . DIRECTORY_SEPARATOR .  "fichier" . DIRECTORY_SEPARATOR .   "$filename";
if (file_exists($file)) {

    $handle = fopen($file, 'r');
    $nb = 0;
    $errors = [];

    if ($handle) {

        while (!feof($handle)) {

            $line = fgetcsv($handle, 0, ';');
            if ($nb == 0) {

                $nb++;
            } else {
                if (is_array($line)) {
                    $priority = $line[2];
                    $operator = $line[7];
                    $customer =  $pdo->quote($line[9]);
                    $title = $pdo->quote($line[10]);
                    $tagDecoup = explode("(", $line[9]);
                    $tag = $pdo->quote($tagDecoup[0]);
                    $id = $pdo->quote($line[1]);
                    $created_at = $pdo->quote($line[5]);
                    $time_hour = $pdo->quote($line[11]);
                    $date = new DateTime();
                    if (preg_match('/(\d{1,})\/(\d{1,})\/(\d{4})/i', $line[5], $matches)) {
                        $date->modify(sprintf('%s-%s-%s 00:00:00', $matches[3], $matches[2], $matches[1]));
                    }
                    $date2 = clone $date;

                    if (preg_match('/(\d{1,})\sh\s(\d{1,})\sm\s(\d{1,})/i', $line[11], $matches)) {
                        $date2->modify(sprintf('+%shours +%sminutes +%sseconds', $matches[1], $matches[2], $matches[3]));
                    }
                    $diff = $date->diff($date2);
                    $time_minute = (int)number_format(round(($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i + ($diff->s / 60), PHP_ROUND_HALF_DOWN), 0, '', '');

                    $month = $date->format('m');
                    $year = $date->format('Y');
                    $created_at = $date->format('Y-m-d');
                    // $ids = array(
                    //     "idCustomer" => 0,
                    //     "idTag" => 0,
                    //     "idOperator" => 0,
                    //     "idPriority" => 0,
                    // );
                    try {
                        // $ids['idCustomer'] =
                        insertCustomer($pdo, $customer);
                        // $ids['idTag'] =
                        insertTag($pdo, $tag);
                        // $ids['idOperator'] =
                        insertOperator($pdo, $operator);
                        // $ids['idPriority'] =
                        insertPriority($pdo, $priority);
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
                    }
                    // print_r($ids);

                    $req = "SELECT id_customer FROM customer WHERE name = $customer;";
                    $res = $pdo->query($req);
                    $idCustomer = $res->fetchColumn();

                    $req = "SELECT id_operator FROM operator WHERE name = '$operator';";
                    $res = $pdo->query($req);
                    $idOperator = $res->fetchColumn();

                    $req = "SELECT id_priority FROM priority WHERE name = '$priority';";
                    $res = $pdo->query($req);
                    $idPriority = $res->fetchColumn();

                    $req = "SELECT id_tag FROM tag WHERE name = $tag;";
                    $res = $pdo->query($req);
                    $idTag = $res->fetchColumn();

                    switch ($time_minute) {

                        case ($time_minute == 1):
                            $req = "SELECT id_zone FROM zone WHERE id_zone = 1;";
                            $res = $pdo->query($req);
                            $idZone = $res->fetchColumn();
                            break;
                        case ($time_minute > 0 && $time_minute < 31):
                            $req = "SELECT id_zone FROM zone WHERE id_zone = 2;";
                            $res = $pdo->query($req);
                            $idZone = $res->fetchColumn();
                            break;
                        case ($time_minute > 31 && $time_minute < 181):
                            $req = "SELECT id_zone FROM zone WHERE id_zone = 3;";
                            $res = $pdo->query($req);
                            $idZone = $res->fetchColumn();
                            break;
                        case ($time_minute > 180):
                            $req = "SELECT id_zone FROM zone WHERE id_zone = 4;";
                            $res = $pdo->query($req);
                            $idZone = $res->fetchColumn();
                            break;
                    }
                    try {
                        insertTicket($pdo, $id, $title, $time_hour, $time_minute, $created_at, $month, $year, $idZone, $idCustomer, $idOperator, $idPriority);
                        updateCustomer($pdo, $customer, $idTag);
                        updateTicket($pdo, $idCustomer, $idTag);
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
                    }

                    $nb++;
                }
            }
        }
    }
    fclose($handle);
    if (count($errors) > 0) {
        print_r($errors);
    }
    header('Location: ../index.php');
} else {
    echo "erreur le fichier est introuvable";
}
