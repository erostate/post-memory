<?php
session_start();
include_once 'db.inc.php';
function getPdo() {
    try {
        return new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    }
    catch(Exception $e) {
        die('Unable to connect to database.');
    }
}

// CARD
function getCard($cardId) {
    $pdo = getPdo();

    $stmt = $pdo->prepare("SELECT c.firstname, c.lastname, c.birth_date, c.death_date, c.info, cc.display_name, cc.display_dob, cc.display_dod, cc.display_info
        FROM cards c
        INNER JOIN config_cards cc ON c.id = cc.card_id
        WHERE c.id = :card_id");
    $stmt->bindParam(':card_id', $cardId, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return $result;
    } else {
        return null;
    }
}
function getCardImages($cardId) {
    $pdo = getPdo();

    $stmt = $pdo->prepare("SELECT path, location FROM images WHERE card_id = :card_id");
    $stmt->bindParam(':card_id', $cardId, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        return $result;
    } else {
        return null;
    }
}


// WEBHOOK
function sendWebhook($url, $username, $contentType, $content) {
    if ($contentType == 'embed') {
        $data = [
            'content' => '',
            'username' => $username,
            'embeds' => [$content],
        ];
    } else {
        $data = [
            'content' => $content,
            'username' => $username,
        ];
    }
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    return $response;
    curl_close($ch);

    return $response;
}


// MISC
function getDateBilling($type) {
    if ($type == 'sunday') {
        $today = new DateTime();
        $week = $today->format("w");
        
        $diff = 7 - $week;

        $nextSunday = $today->modify("+$diff days");

        $result = $nextSunday->format("Y-m-d");
    } else {
        $today = new DateTime();

        $result = $today->format("Y-m-d");
    }

    return $result;
}