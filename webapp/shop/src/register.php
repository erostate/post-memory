<?php
    include_once '../inc/functions.inc.php';
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Memory - Connexion</title>
    <link  type="text/css" rel="stylesheet" href="assets/login.css">
    <link rel="shortcut icon" href="assets/img/logo-nobg-square.png" type="image/x-icon">
</head>
<body>
    <header>
        <div class="logo" style="cursor: pointer;" onclick="window.location.href='index'">
            <img src="assets/img/logo-nobg-header.png" alt="">
            <p>Post Memory</p>
        </div>
        <button onclick="window.location.href='index'" class="btn" style="width: auto; padding: 0 10px">Accueil</button>
        <div style="display: flex; flex-direction: row; align-items: center; gap: 10px; margin-right: 10px;">
            <span class="cart-area" onclick="window.location.href='cart'">
                <i class="fas fa-shopping-cart btn-cart"></i>
                <?php
                    $countCart = countCart();
                    if ($countCart > 0) {
                        echo "<span>$countCart</span>";
                    }
                ?>
            </span>
        </div>
    </header>
    
    <main>
        <div class="form-v10-content">
            <form class="form-detail" action="action/register.php" method="POST" id="register">
                <div class="form-left">
                    <h2>Infomations personnels</h2>
                    <!-- <div class="form-row">
                        <select name="title">
                            <option class="option" value="title">Title</option>
                            <option class="option" value="businessman">Businessman</option>
                            <option class="option" value="reporter">Reporter</option>
                            <option class="option" value="secretary">Secretary</option>
                        </select>
                        <span class="select-btn">
                            <i class="fa-solid fa-chevron-down"></i>
                        </span>
                    </div> -->
                    <div class="form-group">
                        <div class="form-row form-row-1">
                            <input type="text" name="first_name" id="first_name" onkeyup="checkDataContent(this)" class="input-text" placeholder="Prénom" required>
                        </div>
                        <div class="form-row form-row-2">
                            <input type="text" name="last_name" id="last_name" onkeyup="checkDataContent(this)" class="input-text" placeholder="Nom" required>
                        </div>
                    </div>
                    <!-- <div class="form-row">
                        <select name="position">
                            <option value="position">Position</option>
                            <option value="director">Director</option>
                            <option value="manager">Manager</option>
                            <option value="employee">Employee</option>
                        </select>
                        <span class="select-btn">
                            <i class="fa-solid fa-chevron-down"></i>
                        </span>
                    </div> -->
                    <div class="form-row">
                        <input type="email" name="user_mail" class="input-text" onkeyup="checkDataContent(this)" id="user_mail" placeholder="Adresse mail" required>
                    </div>
                    <br>
                    <!-- <div class="form-group">
                        <div class="form-row form-row-1">
                            <input type="password" name="password" id="password" class="input-text" placeholder="Mot de passe" required>
                        </div>
                        <div class="form-row form-row-2">
                            <input type="password" name="conf_password" id="conf_password" class="input-text" placeholder="Confirmer le mot de passe" required>
                        </div>
                    </div> -->
                    <div class="form-row password">
                        <input type="password" onkeyup="checkStrength(this)" name="password" id="password" class="input-text" placeholder="Mot de passe" required>
                        <span class="show-pass" onclick="togglePassw(this)"><i class="fa-solid fa-eye"></i></span>
                    </div>
                    <input type="hidden" name="strong_password" id="strong_password" value="false">
                    <div id="popover-password">
                        <p style="margin: 0 0 10px;"><span id="result"></span></p>
                        <div class="progress">
                            <div id="password-strength" 
                                class="progress-bar" 
                                role="progressbar" 
                                aria-valuenow="40" 
                                aria-valuemin="0" 
                                aria-valuemax="100" 
                                style="width:0%">
                            </div>
                        </div>
                        <ul class="list-unstyled">
                            <li class="">
                                <span class="low-upper-case">
                                    <i class="fa fa-circle" aria-hidden="true"></i>
                                    &nbsp;Minuscule &amp; Majuscule
                                </span>
                            </li>
                            <li class="">
                                <span class="one-number">
                                    <i class="fa fa-circle" aria-hidden="true"></i>
                                    &nbsp;Nombre (0-9)
                                </span> 
                            </li>
                            <li class="">
                                <span class="one-special-char">
                                    <i class="fa fa-circle" aria-hidden="true"></i>
                                    &nbsp;Caractère spécial (!@#$%^&*)
                                </span>
                            </li>
                            <li class="">
                                <span class="eight-character">
                                    <i class="fa fa-circle" aria-hidden="true"></i>
                                    &nbsp;Au moins 8 caractères
                                </span>
                            </li>
                        </ul>
                    </div>
                    <br>
                    <div class="form-row password">
                        <input type="password" onkeyup="checkSamePassw(this)" name="password_conf" id="password_conf" class="input-text" placeholder="Confirmation du mot de passe" required>
                        <span class="show-pass" onclick="togglePassw(this)"><i class="fa-solid fa-eye"></i></span>
                    </div>
                </div>
                <div class="form-right">
                    <h2>Informations de l'entreprise</h2>
                    <div class="form-row">
                        <input type="text" name="company_name" class="name" onkeyup="checkDataContent(this)" id="company_name" placeholder="Nom de l'entreprise" required>
                    </div>
                    <label style="margin-left: 75px; color: #d4d4d4; text-decoration-line: underline;">Numéro de téléphone</label>
                    <div class="form-group">
                        <div class="form-row form-row-1">
                            <select style="text-align: center;" name="company_code_phone">
                                <option selected value="33">France +33</option>
                                <option value="32">Belgique +32</option>
                                <option value="352">Luxembourg +352</option>
                                <option value="41">Suisse +41</option>
                                <option value="377">Monaco +377</option>
                                <option value="376">Andorre +376</option>
                            </select>
                        </div>
                        <div class="form-row form-row-2">
                            <input type="text" name="company_phone" onkeyup="checkDataContent(this)" class="phone" id="company_phone" placeholder="_ __ __ __ __" maxlength="14" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <input type="text" name="company_mail" class="mail" onkeyup="checkDataContent(this)" id="company_mail" placeholder="Adresse mail" required>
                    </div>
                    <div class="form-group">
                        <div class="form-row form-row-1">
                            <input type="text" name="company_zip" onkeyup="geoApi('searchCommune', this)" class="zip" id="company_zip" placeholder="Code postal" required>
                        </div>
                        <div class="form-row form-row-2">
                            <select disabled name="company_city" id="company_city">
                                <option disabled selected>Ville</option>
                            </select>
                            <span class="select-btn">
                                <i class="fa-solid fa-chevron-down"></i>
                            </span>
                        </div>
                    </div>
                    <div class="form-row">
                        <input disabled type="text" name="company_address" onclick="addressList('open')" onkeyup="geoApi('searchAddress', this)" class="street" id="company_address" placeholder="Adresse" required>
                        <span id="company_address_list"></span>
                    </div>
                    <!-- <div class="form-row">
                        <select name="country">
                            <option value="country">Country</option>
                            <option value="Vietnam">Vietnam</option>
                            <option value="Malaysia">Malaysia</option>
                            <option value="India">India</option>
                        </select>
                        <span class="select-btn">
                            <i class="fa-solid fa-chevron-down"></i>
                        </span>
                    </div> -->
                    <!-- <div class="form-row">
                        <input type="text" name="your_email" id="your_email" class="input-text" required pattern="[^@]+@[^@]+.[a-zA-Z]{2,6}" placeholder="Your Email">
                    </div> -->
                    <div class="form-checkbox">
                        <label class="container"><p>J'accepte les <a href="../terms_and_conditions.php" class="text">Conditions d'utilisations</a> du site.</p>
                            <input type="checkbox" name="consent" required>
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div class="form-row-last">
                        <input type="submit" name="register" class="register" value="Créer son compte">
                    </div>
                </div>
            </form>
        </div>
    </main>
</body>
<script src="https://kit.fontawesome.com/1224050b85.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/login.js"></script>
</html>