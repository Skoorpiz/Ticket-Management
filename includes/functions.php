<?php
function moyMinuteCustomer($pdo, $customer, $year)
{
    for ($i = 1; $i <= 12; $i++) {
        for ($b = 0; $b < count($customer); $b++) {

            $req = "SELECT SUM(time_minute) / COUNT(time_minute) FROM ticket WHERE id_customer = $customer[$b] AND year = $year AND month = $i;";
            $res = $pdo->query($req);
            $moyMinute[$i][$b] = $res->fetchColumn();
            if (empty($moyMinute[$i][$b])) {
                $moyMinute[$i][$b] = 0;
            }
            $moyMinute[$i][$b] = number_format($moyMinute[$i][$b], 0, '.', ' ');
        }
    }
    return $moyMinute;
}

function maxMinuteCustomer($pdo, $customer, $year)
{
    for ($i = 1; $i <= 12; $i++) {
        for ($b = 0; $b < count($customer); $b++) {

            $req = "SELECT MAX(time_minute) FROM ticket WHERE id_customer = $customer[$b] AND year = $year AND month = $i;";
            $res = $pdo->query($req);
            $maxMinute[$i][$b] = $res->fetchColumn();
            if (empty($maxMinute[$i][$b])) {
                $maxMinute[$i][$b] = 0;
            }
        }
    }
    return $maxMinute;
}

function minMinuteCustomer($pdo, $customer, $year)
{
    for ($i = 1; $i <= 12; $i++) {
        for ($b = 0; $b < count($customer); $b++) {

            $req = "SELECT MIN(time_minute) FROM ticket WHERE id_customer = $customer[$b] AND year = $year AND month = $i;";
            $res = $pdo->query($req);
            $minMinute[$i][$b] = $res->fetchColumn();
            if (empty($minMinute[$i][$b])) {
                $minMinute[$i][$b] = 0;
            }
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
    $req = "SELECT DISTINCT year FROM ticket ORDER BY ticket.year DESC";
    $res = $pdo->query($req);
    $allYear = $res->fetchAll();
    for ($i = 0; $i < count($allYear); $i++) {
        for ($b = 0; $b < count($customer); $b++) {
            $year = $allYear[$i][0];
            $req = "SELECT SUM(time_minute) FROM ticket WHERE id_customer = $customer[$b] AND year = $year;";
            $res = $pdo->query($req);
            $nbMinute[$i][$b] = $res->fetchColumn();
            if (empty($nbMinute[$i])) {
                $nbMinute[$i][$b] = 0;
            }
            $nbMinute[$i][$b] = $nbMinute[$i][$b] / 60;
            $nbMinute[$i][$b] = number_format($nbMinute[$i][$b], 0, '.', ' ');
        }
    }
    return $nbMinute;
}
function nbMinuteOperator($pdo, $operator)
{
    $req = "SELECT DISTINCT year FROM ticket ORDER BY ticket.year DESC";
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
    $req = "SELECT DISTINCT year FROM ticket ORDER BY ticket.year DESC";
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
