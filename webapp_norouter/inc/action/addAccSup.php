<?php
require_once '../functions.inc.php';

// Check if the user is logged in
$logged_in = $_SESSION['logged_in'] ?? null;
if (!$logged_in) {
    header('Location: ../../d/');
    exit;
}

// Get the post data
$customerType = $_POST['customerType'] ?? null;
$customerName = $_POST['customerName'] ?? null;
$customerAddress = $_POST['customerAddress'] ?? null;
$owner = $_POST['owner'] ?? null;

// Add the customer to the database
$result = createCustomer(
    $_SESSION['userDb']['id'],
    $customerType,
    $customerName,
    $customerAddress,
    $owner,
    NULL,
    NULL
);

// Check if the customer was added successfully
if ($result) {
    echo "added";
} else {
    echo "not-added";
}
exit;