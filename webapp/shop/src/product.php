<?php
    include_once '../inc/functions.inc.php';

    if (!$productId) {
        header('Location: ../index');
        die();
    }

    $product = getProduct($productId);
    if (!$product) {
        header('Location: ../index');
        die();
    }
    $productImage = $product['image_location'] == 'url' ? $product['image'] : "../../userpic/".$product['image'];
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Memory - <?= $product["label"]; ?></title>
    <link  type="text/css" rel="stylesheet" href="../assets/style.css">
    <link  type="text/css" rel="stylesheet" href="../assets/edit_product.css">
    <link rel="shortcut icon" href="../assets/img/logo-nobg-square.png" type="image/x-icon">
</head>
<body>
    <header>
        <div class="logo" style="cursor: pointer;" onclick="window.location.href='../index'">
            <img src="../assets/img/logo-nobg-header.png" alt="">
            <p>Post Memory</p>
        </div>
        <div style="display: flex; flex-direction: row; align-items: center; gap: 10px">
            <span class="cart-area" onclick="window.location.href='../cart'">
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
                    echo "<button onclick=\"window.location.href='../action/logout.php'\" class=\"btn custom-width\">Se déconnecter</button>";
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
            <button>Tous les produits</button>
            <button>Produits populaires</button>
            <button>Produits en promotion</button>
            <br><hr><br>
            <button onclick="window.location.href='../index'">Retour en arrière</button>
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
        <?php
            echo "
                <style>
                    main .header {
                        background-image: url('$productImage');
                    }
                </style>
            ";
        ?>
        <div class="header" style="height: 150px;">
            <h1><?= $product["label"]; ?></h1>
            <p>Description du produit 1</p>
        </div>
        <br>
        <div class="view-product">
            <img src="<?= $productImage; ?>" alt="">
            <span>
                <h1><?= $product["label"]; ?></h1>
                <p>
                <?= $product["description"]; ?>
                </p>
                <br>
                <h2><?= number_format($product["price"], 2); ?>€</h2>
                <?php
                    $isInCart = getProductInCart($productId);
                    if ($isInCart > 0) {
                        ?>
                        <button id="isInCart" class="addtocart" onclick="window.location.href='../cart'">
                            <div class="pretext">
                                <i class="fas fa-check"></i> AJOUTÉ
                            </div>
                        </button>
                        <style>
                            #isInCart .pretext {
                                background: #be2edd;
                            }
                        </style>
                        <?php
                    } else {
                        ?>
                        <button id="editProduct" onclick="customProduct(this, '<?= $productId; ?>')" class="editProduct">
                            <div class="pretext">
                                <i class="fa-solid fa-pen-to-square"></i> PERSONNALISER
                            </div>
                            <div class="pretext done">
                                <div class="posttext"><i class="fas fa-check"></i> AJOUTÉ</div>
                            </div>
                            <div class="pretext error">
                                <div class="posttext"><i class="fas fa-times"></i> ERREUR</div>
                            </div>
                        </button>
                        <button id="addToCart" onclick="addToCart(this, '<?= $productId; ?>')" class="addtocart">
                            <div class="pretext">
                                <i class="fas fa-cart-plus"></i> AJOUTER AU PANIER
                            </div>
                            <div class="pretext done">
                                <div class="posttext"><i class="fas fa-check"></i> AJOUTÉ</div>
                            </div>
                            <div class="pretext error">
                                <div class="posttext"><i class="fas fa-times"></i> ERREUR</div>
                            </div>
                        </button>
                        <?php
                    }
                ?>
            </span>
        </div>
    </main>

    <div id="modalCustomProduct">
        <div class="popup">
            <div style="display: flex; flex-direction: row; gap: 10px;">
                <?php
                    echo "<style>
                        .plaque-container {
                            background-image: url('$productImage');
                        }
                        </style>";
                        // "../assets/img/plaque.png"
                ?>
                <div class="plaque-container" id="plaqueContainer">
                    <!-- Draggable text will be appended here -->
                </div>
                <div class="opt-controls">
                    <input type="hidden" id="currentText" value="">
                    <span>
                        <label for="textSize">Taille du texte</label>
                        <input type="range" min="10" max="50" value="20" id="textSize" oninput="optChange('size', this)">
                    </span>
                    <span>
                        <label for="textColor">Couleur du texte</label>
                        <input style="width: auto !important;" type="color" id="textColor" oninput="optChange('color', this)">
                    </span>
                    <span>
                        <label for="textWeight">Épaisseur du texte</label>
                        <div class="radio-inputs">
                            <label>
                                <input checked="" class="radio-input" type="radio" name="engine" data-value="light" onchange="optChange('weight', this)">
                                <span class="radio-tile">
                                    <span class="radio-icon">
                                        <i class="fa-regular fa-sun"></i>
                                    </span>
                                    <span class="radio-label">Mince</span>
                                </span>
                            </label>
                            <label>
                                <input class="radio-input" type="radio" name="engine" data-value="normal" onchange="optChange('weight', this)">
                                <span class="radio-tile">
                                    <span class="radio-icon">
                                        <i class="fa-regular fa-sun"></i>
                                    </span>
                                    <span class="radio-label">Moyen</span>
                                </span>
                            </label>
                            <label>
                                <input class="radio-input" type="radio" name="engine" data-value="bold" onchange="optChange('weight', this)">
                                <span class="radio-tile">
                                    <span class="radio-icon">
                                    <i class="fa-solid fa-sun"></i>
                                    </span>
                                    <span class="radio-label">Gras</span>
                                </span>
                            </label>
                        </div>
                    </span>
                </div>
            </div>
            <div class="controls">
                <button onclick="addText()">Ajouter un texte</button>
                <button onclick="addDate()">Ajouter une date</button>
            </div>
        </div>
    </div>
    
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
<script src="../assets/script.js"></script>
<script src="../assets/edit_product.js"></script>
</html>