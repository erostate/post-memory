<?php
    include_once '../inc/functions.inc.php';
    
    var_dump($_GET);
    var_dump($_GET['id']);
    die();
    $productId = $_GET['id'];
    // if (!$productId) {
    //     header('Location: ../index');
    // }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Memory - Accueil</title>
    <link  type="text/css" rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header>
        <div class="logo" style="cursor: pointer;" onclick="window.location.href='index'">
            <img src="assets/img/logo-nobg-header.png" alt="">
            <p>Post Memory</p>
        </div>
        <button onclick="window.location.href='login'" class="btn">Se connecter</button>
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
            <button onclick="window.location.href='index.html'">Retour en arrière</button>
            <button onclick="window.location.href='cart.html'">Panier</button>
        </div>
    </menu>

    <main>
        <div class="header" style="height: 150px;">
            <h1>Produit 1</h1>
            <p>Description du produit 1</p>
        </div>
        <br>
        <div class="view-product">
            <img src="assets/img/product1.png" alt="">
            <span>
                <h1>Produit 1</h1>
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur eum ab aperiam quis ipsum minus nisi provident accusamus amet perferendis, dolorum aliquid assumenda fugit laudantium impedit, consequatur, ipsam vero excepturi.<br>
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Pariatur, quasi. Laborum, tenetur ratione quibusdam quod rerum reiciendis quidem commodi ducimus totam nulla, labore error, nemo temporibus necessitatibus molestiae architecto reprehenderit?<br>
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Expedita totam aliquid velit sint illum doloremque quidem, veniam fugit libero sapiente cupiditate quibusdam. A, eligendi? Nisi iusto a placeat delectus cupiditate.
                </p>
                <br>
                <h2>15,00€</h2>
                <button onclick="window.location.href='product.html?r=a1z2f4s7'">Ajouter au panier</button>
            </span>
        </div>
    </main>
    
</body>
<script src="https://kit.fontawesome.com/1224050b85.js" crossorigin="anonymous"></script>
<script src="assets/script.js"></script>
</html>