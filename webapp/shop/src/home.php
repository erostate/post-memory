<?php
    include_once '../inc/functions.inc.php';
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Memory - Boutique</title>
    <link  type="text/css" rel="stylesheet" href="assets/style.css">
    <link rel="shortcut icon" href="assets/img/logo-nobg-square.png" type="image/x-icon">
</head>
<body>
    <header>
        <div class="logo" style="cursor: pointer;" onclick="window.location.href='index'">
            <img src="assets/img/logo-nobg-header.png" alt="">
            <p>Post Memory</p>
        </div>
        <div style="display: flex; flex-direction: row; align-items: center; gap: 10px">
            <span class="cart-area" onclick="window.location.href='cart'">
                <i class="fas fa-shopping-cart btn-cart"></i>
                <?php
                    $countCart = countCart();
                    if ($countCart > 0) {
                        echo "<span>$countCart</span>";
                    }
                ?>
            </span>
            <?php
                if (isset($_SESSION['logged_as'])) {
                    echo "<button onclick=\"window.location.href='action/logout.php'\" class=\"btn custom-width\">Se déconnecter</button>";
                } else {
                    echo "<button onclick=\"window.location.href='login'\" class=\"btn\">Se connecter</button>";
                }
            ?>
        </div>
    </header>

    <menu>
        <div class="search">
            <input id="searchProductValue" type="text" placeholder="Rechercher un produit" onkeydown="searchProductInput(event)">
            <button class="btn-search" onclick="searchProduct()">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <div class="categories">
            <button class="active">Tous les produits</button>
            <button>Produits populaires</button>
            <button>Produits en promotion</button>
        </div>
    </menu>
    
    <div class="mobile-menu">
        <button class="btn btnMobileMenu" onclick="mobileMenu('open')">Ouvrir le menu</button>
    </div>
    <div id="mobileMenu">
        <button class="btn btnMobileMenu" onclick="mobileMenu('close')">Fermer le menu</button>
        <div class="search">
            <input id="searchProductValue" type="text" placeholder="Rechercher un produit" onkeydown="searchProductInput(event)">
            <button class="btn-search" onclick="searchProduct()">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <div class="categories">
            <button class="active">Tous les produits</button>
            <button>Produits populaires</button>
            <button>Produits en promotion</button>
        </div>
    </div>

    <main>
        <div class="header">
            <h1>Nos produits</h1>
            <p>Sobre et qualitatif, nous vous proposons le meilleur que nous pouvons faire.</p>
        </div>
        <style>
            main .header {
                background-image: url('assets/img/hero-banner-1.png');
            }
        </style>
        <br>
        <div class="products">
            <?php
                $products = getAllProduct();
                if ($products) {
                    foreach ($products as $product) {
                        if ($product['image_location'] == 'url') {
                            $image = $product['image'];
                        } elseif ($product['image_location'] == 'file') {
                            $image = "../userpic/".$product['image'];
                        } else {
                            $image = 'assets/img/default.png';
                        }
                        echo "
                            <div class='product'>
                                <img src='$image' alt='Product image'>
                                <span>
                                    <h2>{$product['label']}</h2>
                                    <p>{$product['price']}€</p>
                                </span>
                                <button onclick=\"window.location.href='product/{$product['product_id']}'\">Voir le produit</button>
                            </div>
                        ";
                    }
                } else {
                    echo '<h3>Aucun produit disponible.</h3>';
                }
            ?>
        </div>
    </main>
</body>
<script src="https://kit.fontawesome.com/1224050b85.js" crossorigin="anonymous"></script>
<script src="assets/script.js"></script>
</html>