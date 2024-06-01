<?php
require_once '../functions.inc.php';

// Check if the user is logged in
$logged_in = $_SESSION['logged_in'] ?? null;
if (!$logged_in) {
    header('Location: ../../d/');
    exit;
}

// Get the post data
$firstname = $_POST['firstname'] ?? null;
$lastname = $_POST['lastname'] ?? null;
if (!$firstname || !$lastname) {
    echo "missing-parameters : ";
    if (!$firstname) {
        echo "missing-firstname";
    }
    if (!$lastname) {
        echo ", missing-lastname";
    }
    exit;
}

// Update the user's name
$result = editUserName($_SESSION['userDb']['id'], $firstname, $lastname);

// Check if the customer was added successfully
if ($result) {
    echo "added";
} else {
    echo "not-added";
}
exit;
