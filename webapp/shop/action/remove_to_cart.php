<?php
include_once '../../inc/functions.inc.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $remove_all = $_POST['remove_all'];

    // echo [
    //     "remove_all" => $remove_all.": ".gettype($remove_all),
    //     "product_id" => $_POST['product_id']??'none',
    //     "session_cart" => $_SESSION['cart'].": ".gettype($_SESSION['cart']),
    // ];
    // die();

    if ($remove_all == 'true') {
        clearCart();
        if (empty($_SESSION['cart'])) {
            echo json_encode(['status' => 'success', 'message' => 'Panier vidé']);
            return;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la suppression du panier']);
            return;
        }
    } else {
        $productId = $_POST['product_id'];
        
        removeFromCart($productId);
        if (!isset($_SESSION['cart'][$productId])) {
            echo json_encode(['status' => 'success', 'message' => 'Produit retiré du panier']);
            return;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la suppression du produit']);
            return;
        }
    }
} else {
    // Retourner une réponse d'erreur si la méthode n'est pas POST
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée, contactez le support technique']);
}
