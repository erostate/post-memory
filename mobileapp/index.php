<?php
    $cardId = $_GET['id'] ?? null;

    include_once 'inc/functions.inc.php';
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Memory</title>
    <link  type="text/css" rel="stylesheet" href="assets/style.css">
    <link rel="shortcut icon" href="assets/img/logo-nobg-square.png" type="image/x-icon">
</head>
<body>
    <header>
        <div class="logo" style="cursor: pointer;" onclick="window.location.href='index'">
            <img src="assets/img/logo-nobg-header.png" alt="">
            <p>Post Memory</p>
        </div>
    </header>
    <?php
        if (!$cardId) {
            echo "
                <main>
                    <div class='no-card'>
                        <h2>Aucune carte n'a été trouvée</h2>
                        <p>Il semblerait qu'aucun identifiant n'a été saisi dans la barre de lien</p>
                    </div>
                </main>
            ";
        } else {
            $cardData = getCard($cardId);
            if (!$cardData) {
                echo "
                    <main>
                        <div class='no-card'>
                            <h2>Aucune carte n'a été trouvée</h2>
                            <p>Il semblerait que l'identifiant saisi ne correspond à aucune carte</p>
                        </div>
                    </main>
                ";
                return;
            }

            $name = $cardData['display_name'] == "true" ? $cardData['firstname'] . ' ' . $cardData['lastname'] : '<i class="private-identity">Identité privée</i>';
            $dob = $cardData['display_dob'] == "true" ? date('d/m/Y', strtotime($cardData['birth_date'])) : '<i class="private-identity">Date de naissance privée</i>';
            $dod = $cardData['display_dod'] == "true" ? date('d/m/Y', strtotime($cardData['death_date'])) : '<i class="private-identity">Date de décès privée</i>';
            $age = $cardData['display_dob'] == "true" && $cardData['display_dod'] == "true" ? (date_diff(date_create($cardData['birth_date']), date_create($cardData['death_date']))->y)." ans" : '<i class="private-identity">Âge privé</i>';
            $info = $cardData['display_info'] == "true" ? $cardData['info'] : '<i class="private-identity">Informations privées</i>';

            $cardImages = getCardImages($cardId);
            $nbImages = $cardImages ? count($cardImages) : 0;
            $nbMorePic = $nbImages > 3 ? "<p id='nbMorePic'>+".($nbImages - 3)."</p>" : "";

            function getImagePath($image) {
                return $image['location'] == "url" ? $image['path'] : ($image['location'] == "file" ? "userpic/".$image['path'] : "assets/img/default.png");
            }

            $defaultImage = "assets/img/default.png";
            $firstImage = $nbImages >= 1 ? getImagePath($cardImages[0]) : $defaultImage;
            $secondImage = $nbImages >= 2 ? getImagePath($cardImages[1]) : $defaultImage;
            $thirdImage = $nbImages >= 3 ? getImagePath($cardImages[2]) : $defaultImage;
            ?>
            <main>
                <div>
                    <h2><?= $name; ?>, <?= $age; ?></h2>
                    <p><?= $dob; ?> - <?= $dod; ?></p>
                </div>

                <div class="picture-sect">
                    <div>
                        <span onclick="seeMoreImage()" class="img" id="img1"></span>
                        <span onclick="seeMoreImage()" class="img" id="img2"></span>
                    </div>
                    <span onclick="seeMoreImage()" class="img" id="img3">
                        <?= $nbMorePic; ?>
                    </span>
                </div>
                <style>
                    span#img1 {
                        background-image: url('<?= $firstImage; ?>');
                    }
                    span#img2 {
                        background-image: url('<?= $secondImage; ?>');
                    }
                    span#img3 {
                        background-image: url('<?= $thirdImage; ?>');
                    }
                </style>

                <div style="width: 100%; margin-top: 30px; display: flex; flex-direction: row; justify-content: center;">
                    <button onclick="seeMoreImage()" class="btn" style="width: auto; padding: 2px 15px;">Voir plus d'image</button>
                </div>

                <div style="margin-top: 50px;">
                    <h3>Informations:</h3>
                    <p>
                        <?= $info; ?>
                    </p>
                </div>
            </main>

            <!-- MODAL -->
            <section id="allImageModal" class="imageModal">
                <span class="close">
                    <button onclick="closeModal('allImageModal')">Fermer</button>
                </span>
                <div class="modal-content-img">
                    <?php
                        if ($nbImages == 0) {
                            echo "<p style='text-align: center; margin-top: 10px;'>Aucune image n'a été trouvée</p>";
                        } else {
                            foreach ($cardImages as $image) {
                                echo "<img src='".getImagePath($image)."' alt='Picture'>";
                            }
                        }
                    ?>
                </div>
            </section>
            <?php
        }
    ?>
</body>
<script src="https://kit.fontawesome.com/1224050b85.js" crossorigin="anonymous"></script>
<script src="assets/script.js"></script>
</html>