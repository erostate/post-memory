<?php
include_once '../../inc/functions.inc.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $checkMail = checkMail($email);

    if (!$checkMail) {
        echo json_encode(['status' => 'success', 'message' => 'no-account']);
    } else {
        if ($checkMail['company_id']) {
            $isActive = isActiveCompany($checkMail['company_id'], true);
            if ($isActive == 'available') {
                echo json_encode(['status' => 'success', 'message' => 'account', 'data' => $checkMail]);
            } elseif ($isActive == 'pending') {
                echo json_encode(['status' => 'success', 'message' => 'pending-company', 'data' => $checkMail]);
            } else {
                echo json_encode(['status' => 'success', 'message' => 'refused-company', 'data' => $checkMail]);
            }
        } else {
            echo json_encode(['status' => 'success', 'message' => 'no-company', 'data' => $checkMail]);
        }
    }
} else {
    // Retourner une réponse d'erreur si la méthode n'est pas POST
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée, contactez le support technique']);
}
