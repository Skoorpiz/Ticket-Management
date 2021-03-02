<?php
function moyMinuteCustomer($pdo, $customer, $year)
{
    for ($i = 1; $i <= 12; $i++) {
        $req = "SELECT SUM(time_minute) / COUNT(time_minute) FROM ticket WHERE id_tag = $customer AND year = $year AND month = $i;";
        $res = $pdo->query($req);
        $moyMinute[$i] = $res->fetchColumn();
        if (empty($moyMinute[$i])) {
            $moyMinute[$i] = 0;
        }
        $moyMinute[$i] = number_format($moyMinute[$i], 0, '.', ' ');
    }
    return $moyMinute;
}

function maxMinuteCustomer($pdo, $customer, $year)
{
    for ($i = 1; $i <= 12; $i++) {

        $req = "SELECT MAX(time_minute) FROM ticket WHERE id_tag = $customer AND year = $year AND month = $i;";
        $res = $pdo->query($req);
        $maxMinute[$i] = $res->fetchColumn();
        if (empty($maxMinute[$i])) {
            $maxMinute[$i] = 0;
        }
    }
    return $maxMinute;
}

function minMinuteCustomer($pdo, $customer, $year)
{
    for ($i = 1; $i <= 12; $i++) {

        $req = "SELECT MIN(time_minute) FROM ticket WHERE id_tag = $customer AND year = $year AND month = $i;";
        $res = $pdo->query($req);
        $minMinute[$i] = $res->fetchColumn();
        if (empty($minMinute[$i])) {
            $minMinute[$i] = 0;
        }
    }
    return $minMinute;
}

function moyMinuteOperator($pdo, $operator, $year)
{
    for ($i = 1; $i <= 12; $i++) {

        $req = "SELECT SUM(time_minute) / COUNT(time_minute) FROM ticket WHERE id_operator = $operator AND year = $year AND month = $i;";
        $res = $pdo->query($req);
        $moyMinute[] = $res->fetchColumn();
        if (empty($moyMinute[$i])) {
            $moyMinute[$i] = 0;
        }
        $moyMinute[$i] = number_format($moyMinute[$i], 0, '.', ' ');
    }
    return $moyMinute;
}

function maxMinuteOperator($pdo, $operator, $year)
{
    for ($i = 1; $i <= 12; $i++) {

        $req = "SELECT MAX(time_minute) FROM ticket WHERE id_operator = $operator AND year = $year AND month = $i;";
        $res = $pdo->query($req);
        $maxMinute[] = $res->fetchColumn();
        if (empty($maxMinute[$i])) {
            $maxMinute[$i] = 0;
        }
    }
    return $maxMinute;
}

function minMinuteOperator($pdo, $operator, $year)
{
    for ($i = 1; $i <= 12; $i++) {

        $req = "SELECT MIN(time_minute) FROM ticket WHERE id_operator = $operator AND year = $year AND month = $i;";
        $res = $pdo->query($req);
        $minMinute[] = $res->fetchColumn();
        if (empty($minMinute[$i])) {
            $minMinute[$i] = 0;
        }
    }
    return $minMinute;
}
function nbMinuteCustomer($pdo, $customer)
{
    $req = "SELECT DISTINCT year FROM ticket ORDER BY ticket.year ASC";
    $res = $pdo->query($req);
    $allYear = $res->fetchAll();
    for ($i = 0; $i < count($allYear); $i++) {
        $year = $allYear[$i][0];
        $req = "SELECT SUM(time_minute) FROM ticket WHERE id_tag = $customer AND year = $year;";
        $res = $pdo->query($req);
        $nbMinute[$i] = $res->fetchColumn();
        if (empty($nbMinute[$i])) {
            $nbMinute[$i] = 0;
        }
        $nbMinute[$i] = $nbMinute[$i] / 60;
        $nbMinute[$i] = number_format($nbMinute[$i], 0, '.', ' ');
    }
    return $nbMinute;
}
function nbMinuteOperator($pdo, $operator)
{
    $req = "SELECT DISTINCT year FROM ticket ORDER BY ticket.year ASC";
    $res = $pdo->query($req);
    $allYear = $res->fetchAll();
    for ($i = 0; $i  < count($allYear); $i++) {
        $year = $allYear[$i][0];
        $req = "SELECT SUM(time_minute) FROM ticket WHERE id_operator = $operator AND year = $year;";
        $res = $pdo->query($req);
        $nbMinute[] = $res->fetchColumn();
        if (empty($nbMinute[$i])) {
            $nbMinute[$i] = 0;
        }
        $nbMinute[$i] = $nbMinute[$i] / 60;
        $nbMinute[$i] = number_format($nbMinute[$i], 0, '.', ' ');
    }
    return $nbMinute;
}
function nbMinuteTotal($pdo)
{
    $req = "SELECT DISTINCT year FROM ticket ORDER BY `ticket`.`year` ASC";
    $res = $pdo->query($req);
    $allYear = $res->fetchAll();
    for ($i = 0; $i  < count($allYear); $i++) {
        $year = $allYear[$i][0];
        $req = "SELECT SUM(time_minute) FROM ticket WHERE year = $year;";
        $res = $pdo->query($req);
        $nbMinuteTotal[] = $res->fetchColumn();
        if (empty($nbMinuteTotal[$i])) {
            $nbMinute[$i] = 0;
        }
        $nbMinuteTotal[$i] = $nbMinuteTotal[$i] / 60;
        $nbMinuteTotal[$i] = number_format($nbMinuteTotal[$i], 0, '.', ' ');
    }
    return $nbMinuteTotal;
}

function nbInterventionTotal($pdo)
{
    $req = "SELECT DISTINCT year FROM ticket ORDER BY `ticket`.`year` ASC";
    $res = $pdo->query($req);
    $allYear = $res->fetchAll();
    for ($i = 0; $i  < count($allYear); $i++) {
        $year = $allYear[$i][0];
        $req = "SELECT COUNT(*) FROM ticket WHERE year = $year";
        $res = $pdo->query($req);
        $nbIntervention[] = $res->fetchColumn();
    }
    return $nbIntervention;
}
function insertCustomer($pdo, $customer)
{
    $req = "INSERT INTO customer (name) VALUES ($customer);";
    $pdo->query($req);
    // return $pdo->lastInsertId();
}
function insertTag($pdo, $tag)
{
    $req = "INSERT INTO tag (name) VALUES ($tag);";
    $pdo->query($req);

    // return $pdo->lastInsertId();
}
function insertOperator($pdo, $operator)
{
    $req = "INSERT INTO operator (name) VALUES ('$operator');";
    $pdo->query($req);
    // return $pdo->lastInsertId();
}
function insertPriority($pdo, $priority)
{
    $req = "INSERT INTO priority (name) VALUES ('$priority');";
    $pdo->query($req);
    // return $pdo->lastInsertId();
}
function insertTicket($pdo, $id, $title, $time_hour, $time_minute, $created_at, $month, $year, $idZone, $idCustomer, $idOperator, $idPriority)
{
    $req = "INSERT INTO ticket (id,title,time_hour,time_minute,created_at,month,year,id_zone,id_customer,id_operator,id_priority) VALUES ($id,$title,$time_hour,'$time_minute','$created_at','$month','$year','$idZone','$idCustomer','$idOperator','$idPriority');";
    $pdo->query($req);
    echo $req;
    echo "<br>";
}
function updateCustomer($pdo, $customer, $idTag)
{
    $req = "UPDATE customer set id_tag = $idTag WHERE name = $customer;";
    $pdo->exec($req);
}
function updateTicket($pdo, $idCustomer, $idTag)
{
    $req = "UPDATE ticket set id_tag = $idTag WHERE id_customer = $idCustomer;";
    $pdo->exec($req);
}
