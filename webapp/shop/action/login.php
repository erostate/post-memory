<?php
include_once '../../inc/functions.inc.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['passw'];

    $login = login($email, $password);

    if (!$login) {
        echo json_encode(['status' => 'success', 'message' => 'no-account']);
    } else {
        if (accountIsActive($login) == true) {
            $_SESSION['logged_as'] = $login;
            echo json_encode(['status' => 'success', 'message' => 'logged']);
        } else {
            echo json_encode(['status' => 'success', 'message' => 'inactive-account']);
        }
    }
} else {
    // Retourner une réponse d'erreur si la méthode n'est pas POST
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée, contactez le support technique']);
}
