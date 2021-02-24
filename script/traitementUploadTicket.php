<?php
include_once '../includes/bdd.php';
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

                    // $a_time_hour = explode('h', $time_hour);
                    // $a_time_minute = explode('m', $a_time_hour[1]);
                    // $hour = preg_replace('/[^0-9]/', '', $a_time_hour[0]);
                    // $minute = preg_replace('/[^0-9]/', '', $a_time_minute[0]);
                    // $time_minute = $hour * 60 + $minute;

                    try {

                        $req = "INSERT INTO customer (name) VALUES ($customer);";
                        $statement = $pdo->prepare($req);
                        $statement->bindValue(':client', $customer, PDO::PARAM_STR);
                        $statement->execute();
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
                    }


                    try {

                        $req = "INSERT INTO operator (name) VALUES ('$operator');";
                        $res = $pdo->query($req);
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
                    }

                    try {

                        $req = "INSERT INTO priority (name) VALUES ('$priority');";
                        $res = $pdo->query($req);
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
                    }

                    $req = "SELECT id_customer FROM customer WHERE name = $customer;";
                    $res = $pdo->query($req);
                    $idCustomer = $res->fetchColumn();

                    $req = "SELECT id_operator FROM operator WHERE name = '$operator';";
                    $res = $pdo->query($req);
                    $idOperator = $res->fetchColumn();

                    $req = "SELECT id_priority FROM priority WHERE name = '$priority';";
                    $res = $pdo->query($req);
                    $idPriority = $res->fetchColumn();


                    switch ($time_minute) {

                        case ($time_minute <= 15):
                            $req = "SELECT id_zone FROM zone WHERE id_zone = 1;";
                            $res = $pdo->query($req);
                            $idZone = $res->fetchColumn();
                            break;
                        case ($time_minute > 15 && $time_minute <= 30):
                            $req = "SELECT id_zone FROM zone WHERE id_zone = 2;";
                            $res = $pdo->query($req);
                            $idZone = $res->fetchColumn();
                            break;
                        case ($time_minute > 30 && $time_minute <= 60):
                            $req = "SELECT id_zone FROM zone WHERE id_zone = 3;";
                            $res = $pdo->query($req);
                            $idZone = $res->fetchColumn();
                            break;
                        case ($time_minute > 60 && $time_minute <= 120):
                            $req = "SELECT id_zone FROM zone WHERE id_zone = 4;";
                            $res = $pdo->query($req);
                            $idZone = $res->fetchColumn();
                            break;
                        case ($time_minute > 120):
                            $req = "SELECT id_zone FROM zone WHERE id_zone = 5;";
                            $res = $pdo->query($req);
                            $idZone = $res->fetchColumn();
                            break;
                    }
                    try {
                        $req = "INSERT INTO ticket (id,title,time_hour,time_minute,created_at,month,year,id_zone,id_customer,id_operator,id_priority) VALUES ($id,$title,$time_hour,'$time_minute','$created_at','$month','$year','$idZone','$idCustomer','$idOperator','$idPriority');";

                        // echo $nb;
                        $res = $pdo->query($req);
                        // $req = "UPDATE customer set id_tag = $idTag WHERE name = $customer";
                        // $res = $pdo->query($req);
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
                    }

                    $nb++;
                }
            }
        }
        $req = "SELECT name
                    FROM customer
                    WHERE SUBSTRING(name, 1, 4) IN (
                    SELECT SUBSTRING(name, 1, 4)
                    FROM customer
                    GROUP BY SUBSTRING(name, 1, 4)
                    HAVING COUNT(SUBSTRING(name, 1, 4)) = 1 )";
        $res = $pdo->query($req);
        $a_tag = $res->fetchAll();
        try {
            for ($i = 0; $i < count($a_tag); $i++) {
                $tag =  $pdo->quote($a_tag[$i]['name']);
                $req = "INSERT INTO tag (name) VALUES ($tag);";
                $res = $pdo->query($req);
            }
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
        $req = "SELECT customer.name FROM customer,tag WHERE customer.name = tag.name";
        $res = $pdo->query($req);
        $a_nameCustomer = $res->fetchAll();
        for ($i = 0; $i < count($a_nameCustomer); $i++) {
            $nameCustomer = $pdo->quote($a_nameCustomer[$i]['name']);
            $req = "SELECT DISTINCT tag.id_tag FROM tag,customer WHERE tag.name = $nameCustomer;";
            $res = $pdo->query($req);
            $idTag = $res->fetchColumn();
            $req ="UPDATE customer set id_tag = $idTag WHERE name = $nameCustomer;";
            $res = $pdo->exec($req);
        }
        fclose($handle);
    }

    if (count($errors) > 0) {
        print_r($errors);
    }
    header('Location: ../index.php');
} else {
    echo "erreur le fichier est introuvable";
}
