<?php
require_once '../functions.inc.php';

// Check if the user is logged in
$logged_in = $_SESSION['logged_in'] ?? null;
if (!$logged_in) {
    header('Location: ../../d/');
    exit;
}

// Get the post data
$solutionLabel = $_POST['solutionLabel'] ?? null;
$description = $_POST['description'] ?? null;
$solutionType = $_POST['solutionType'] ?? null;

// Get the available solutions by label
$aSolution = getAvailableSolutionsByLabel($solutionType);

if (!$aSolution) {
    echo "not-available";
    exit;
}

// Add the customer to the database
$result = addSolution($solutionLabel, $aSolution['available_solution_id']);

// Check if the customer was added successfully
if ($result) {
    echo "added";
} else {
    echo "not-added";
}
exit;
