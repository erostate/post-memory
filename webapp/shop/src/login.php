<?php
    include_once '../inc/functions.inc.php';
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Memory - Connexion</title>
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
            <button onclick="window.location.href='index'" class="btn" style="width: auto; padding: 0 10px">Accueil</button>
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
            <br><hr><br>
            <button onclick="window.location.href='index'">Retour en arrière</button>
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
            <button>Tous les produits</button>
            <button>Produits populaires</button>
            <button>Produits en promotion</button>
            <br><hr><br>
            <button onclick="window.location.href='../index'">Retour en arrière</button>
        </div>
    </div>

    <main>
        <div class="header">
            <h1>Un compte, des milliers de souvenirs gravés</h1>
            <p style="width: 60%;">
                Vous êtes une entreprise basée en France, spécialisée dans le domaine des services funéraires, et souhaitez offrir à vos clients une solution innovante pour conserver la mémoire de leurs proches disparus ? Post Memory est là pour répondre à vos besoins.<br>
                Via cette accès uniquement dédiés aux professionnels, vous pourrez commander des produits personnalisés, de qualité, et à des prix compétitifs. Ne tardez pas, créez votre compte dès maintenant et accédez à notre catalogue de produits.
            </p>
        </div>
        <style>
            main .header {
                background-image: url('assets/img/hero-banner-1.png');
            }
        </style>
        <br>
        <div class="login">
            <?php
                $success = $_GET['success'] ?? null;
                if (!$success) {
                    ?>
                        <div id="loginForm">
                            <input id="email" type="text" placeholder="Entrez votre adresse mail">
                            <button class="btn" style="align-self: center;" onclick="checkMail(this)">Valider</button>
                        </div>
                    <?php
                } else {
                    if ($success == 'register') {
                        ?>
                            <div id="loginForm">
                                <h2 style="color: greenyellow; text-decoration-line: underline;">Inscription réussite</h2>
                                <p>
                                    Votre compte a été créé avec succès, mais n'a pas encore validé par notre équipe.
                                    <br>Vous recevrez un mail de confirmation dans les prochaines heures.
                                </p>
                                <button onclick="window.location.href='index'" class="btn">Accueil</button>
                            </div>
                        <?php
                    }
                }
            ?>
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
</html>