<?php
    include_once '../inc/functions.inc.php';
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Memory - Panier</title>
    <link  type="text/css" rel="stylesheet" href="assets/style.css">
    <link rel="shortcut icon" href="assets/img/logo-nobg-square.png" type="image/x-icon">
</head>
<body>
    <header>
        <div class="logo" style="cursor: pointer;" onclick="window.location.href='index'">
            <img src="assets/img/logo-nobg-header.png" alt="">
            <p>Post Memory</p>
        </div>
        <?php
            if (isset($_SESSION['logged_as'])) {
                echo "<button onclick=\"window.location.href='action/logout.php'\" class=\"btn custom-width\">Se déconnecter</button>";
            } else {
                echo "<button onclick=\"window.location.href='login'\" class=\"btn\">Se connecter</button>";
            }
        ?>
    </header>

    <menu>
        <div class="search">
            <input id="searchProductValue" type="text" placeholder="Rechercher un produit" onkeydown="searchProductInput(event)">
            <button class="btn-search" onclick="searchProduct()">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <div class="categories">
            <button>Tous les produits</button>
            <button>Produits populaires</button>
            <button>Produits en promotion</button>
            <br><hr><br>
            <button onclick="window.location.href='index'">Retour en arrière</button>
        </div>
    </menu>

    <main class="cart">
        <div class="shopping-cart">
            <div class="header">
                <h1>Panier</h1>
                <span>
                    <p><b id="productNb"><?= countCart(); ?></b> produits</p>
                    <i onclick="removeToCart(true)" class="fas fa-trash delete"></i>
                </span>
            </div>
            <div class="list">
                <?php
                    $products = getCart();
                    if ($products) {
                        foreach ($products as $productId => $quantity) {
                            $productData = getProduct($productId);
                            if ($productData) {
                                $productImage = $productData['image_location'] == 'url' ? $productData['image'] : "../userpic/" . $productData['image'];
                                $price = number_format($productData['price'] * $quantity, 2);
                                echo "
                                    <div class=\"item product-list\" id=\"item-$productId\">
                                        <span>
                                            <img src=\"$productImage\" alt=\"Product image\">
                                            <p>{$productData['label']}</p>
                                        </span>
                                        <span style='display: flex; flex-direction: row; justify-content: space-evenly; width: 50%; height: 100%;'>
                                            <span class=\"nb-selection\">
                                                <button onclick=\"changeCountProduct('$productId', 'remove')\">-</button>
                                                <input class=\"quantity\" id=\"count-p-$productId\" type=\"text\" value=\"$quantity\" onkeyup=\"typeNewQuantity('$productId')\">
                                                <button onclick=\"changeCountProduct('$productId', 'add')\">+</button>
                                            </span>
                                            <p class=\"price\">$price €</p>
                                            <button onclick=\"changeCountProduct('$productId', 'delete')\" class=\"btn-delete-product\">
                                                <i class=\"fa-solid fa-trash\"></i>
                                            </button>
                                        </span>
                                        <input type=\"hidden\" class=\"tva\" value=\"{$productData['tva']}\">
                                        <input type=\"hidden\" class=\"unitPrice\" value=\"{$productData['price']}\">
                                    </div>
                                ";
                            }
                        }
                    } else {
                        echo "<p style=\"text-align: center; margin-top: 50px;\">Votre panier est vide.</p>";
                        echo "<button style=\"margin: 0 5px; width: 300px; align-self: center;\" onclick=\"window.location.href='index'\" class=\"btn\">Passez commande</button>";
                    }
                ?>
            </div>
        </div>
        <div class="summary">
            <?php
                $totalPrice = getCartPrice();
            ?>
            <h1>Total</h1>
            <hr><br>
            <p>Code de réduction ?</p>
            <span class="coupon-code">
                <input id="couponCode" type="text" placeholder="Entrer le code" onkeyup="sendInEnter(this, 'applyCouponCode')">
                <button onclick="applyCouponCode(this)">Appliquer</button>
            </span>
            <div id="allCouponCodes"></div>
            <p class="coupon-code-apply">Code appliqué : </p>
            <br><hr style="border: solid 1px #707070;"><br>
            <span class="recap">
                <span>
                    <p>Prix des produits :</p>
                    <p id="productsPrice" style="color: #b4b4b4">00.00€</p>
                </span>
                <span style="margin-top: -15px; color: rgb(160, 160, 160); font-size: 80%;">
                    <p>Dont TVA :</p>
                    <p id="tvaAmount">00.00€</p>
                </span>
                <span>
                    <p>Réduction :</p>
                    <p id="reducAmount">00.00€</p>
                    <!-- <p id="reducAmount" class="active">0€</p> -->
                </span>
                <span style="font-size: 120%; font-weight: bold;">
                    <p>
                        Prix final :<br>
                        <i style="font-size: 70%; font-weight: normal; color: rgb(160, 160, 160);">(Sans frais de livraison)</i>
                    </p>
                    <p id="finalPrice">00.00€</p>
                </span>
            </span>
            <br><hr style="border: solid 1px #707070;"><br>
            <span>
                <b style="color: rgb(177, 36, 36); font-size: 120%;">Important:</b>
                <p style="font-style: italic;">
                    Aucun paiement se fait automatiquement. Chaque commande passée sont enregistrées et vous devrez effectuer un virement bancaire en fin de mois lorsque vous recevrez un e-mail.<br>
                    Vous avez un délais de 7 jours pour faire le paiement de vos commandes effectués le mois-ci. Si un paiement n'est pas effectué dans les 7 jours, votre compte sera bloqué et vous ne pourrez plus passer de commande.
                </p>
                <br>
                <?php
                    if (isset($_SESSION['logged_as'])) {
                        echo "<button class=\"btn-purchase\">Passer la commande</button>";
                    } else {
                        echo "<button onclick=\"window.location.href='login'\" class=\"btn-purchase\">Passer la commande</button>";
                    }
                ?>
            </span>
        </div>
    </main>
    
    <div id="notif">
        <span class="icon">
            <i class="fas fa-exclamation-circle"></i>
        </span>
        <span>
            <h3>Title</h3>
            <p>Description</p>
        </span>
    </div>
</body>
<script src="https://kit.fontawesome.com/1224050b85.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/script.js"></script>
<script>
    updateTotalPrice();
</script>
</html>