<?php
    include_once '../inc/functions.inc.php';

    $default_url = 'http://localhost/web/post_memory/webapp/shop/';
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Memory - Boutique</title>
    <link  type="text/css" rel="stylesheet" href="<?=$default_url;?>assets/style.css">
    <link rel="shortcut icon" href="<?=$default_url;?>assets/img/logo-nobg-square.png" type="image/x-icon">
</head>
<body>
    <header>
        <div class="logo" style="cursor: pointer;" onclick="window.location.href='index'">
            <img src="<?=$default_url;?>assets/img/logo-nobg-header.png" alt="">
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
            <button class="active">Tous les produits</button>
            <button>Produits populaires</button>
            <button>Produits en promotion</button>
        </div>
    </menu>

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
        <h1>Une erreur c'est produite</h1>
        <h3>La page que vous avez consulté n'existe pas ou rencontre un problème.</h3>
        <p><i>Si vous pensez que c'est une erreur, merci de nous contacter en <a style="color: orange;" href="<?=$default_url;?>../contact.php">cliquant ici</a></i></p>
        <br>
        <button onclick="window.location.href='<?=$default_url;?>'" class="btn" style="width: auto; padding: 5px 10px">Retour à la boutique</button>
    </main>
    
</body>
<script src="https://kit.fontawesome.com/1224050b85.js" crossorigin="anonymous"></script>
<script src="<?=$default_url;?>assets/script.js"></script>
</html>