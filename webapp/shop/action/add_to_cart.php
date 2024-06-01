<?php
include_once '../../inc/functions.inc.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = $_POST['product_id'];
    $quantity = intval($_POST['quantity']);

    addToCart($productId, $quantity);

    if (getProductInCart($productId) == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'ajout du produit']);
        return;
    }

    // Retourner une réponse pour confirmer l'ajout
    echo json_encode(['status' => 'success', 'message' => 'Produit ajouté au panier: '. $productId]);
} else {
    // Retourner une réponse d'erreur si la méthode n'est pas POST
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée, contactez le support technique']);
}
