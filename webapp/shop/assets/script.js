// JS for shop page only
// Author: Erostate (Yohan C.)
// Date: 2024-30-05
// Version: 1.0.0

// Search product
function searchProductInput(event) {
    if (event.key === "Enter") {
        searchProduct();
    }
}
function searchProduct() {
    const searchProductValue = document.getElementById('searchProductValue').value;

    if (searchProductValue === '' || searchProductValue === null) {
        alert('Please enter a product name');
        return;
    }
    alert('You are searching for: ' + searchProductValue);
}

// Change the product quantity in the cart
function changeCountProduct(pId, action) {
    const item = document.getElementById('item-' + pId);
    const productCount = document.getElementById('count-p-' + pId);
    const price = item.getElementsByClassName('price')[0];
    const unitPrice = item.getElementsByClassName('unitPrice')[0].value;
    if (action === 'add') {
        productCount.value = parseInt(productCount.value) + 1;
        price.innerText = (productCount.value * parseFloat(unitPrice)).toFixed(2) + '€';
    } else if (action === 'remove') {
        if (productCount.value > 1) {
            productCount.value = parseInt(productCount.value) - 1;
            price.innerText = (productCount.value * parseFloat(unitPrice)).toFixed(2) + '€';
        }
    } else if (action === 'delete') {
        removeToCart(false, pId);
    }

    updateTotalPrice();
}
function typeNewQuantity(pId) {
    if (event.key !== "0" && event.key !== "1" && event.key !== "2" && event.key !== "3" && event.key !== "4" && event.key !== "5" && event.key !== "6" && event.key !== "7" && event.key !== "8" && event.key !== "9" && event.key !== "Backspace" && event.key !== "Delete") {
        document.getElementById('count-p-' + pId).value = 1;
        return;
    }
    if (document.getElementById('count-p-' + pId).value == 0) {
        return;
    }
    const item = document.getElementById('item-' + pId);
    const productCount = document.getElementById('count-p-' + pId);
    const price = item.getElementsByClassName('price')[0];
    const unitPrice = item.getElementsByClassName('unitPrice')[0].value;
    if (productCount.value < 1) {
        productCount.value = 1;
    }
    price.innerText = (productCount.value * parseFloat(unitPrice)).toFixed(2) + '€';

    updateTotalPrice();
}

// Open and close the mobile menu
function mobileMenu(action) {
    if (action === 'open') {
        document.getElementById('mobileMenu').style.display = 'block';
    } else {
        document.getElementById('mobileMenu').style.display = 'none';
    }
}

// Cart management
function addToCart(btn, productId) {
    const done = btn.getElementsByClassName('done')[0];
    const error = btn.getElementsByClassName('error')[0];

    const cartArea = document.getElementsByClassName('cart-area')[0];
    $.ajax({
        url: '../action/add_to_cart.php',
        type: 'POST',
        data: {
            product_id: productId,
            quantity: 1
        },
        success: function(response) {
            data = JSON.parse(response);
            if (data.status === 'success') {
                btn.enabled = false;
                btn.onclick = function() {
                    window.location.href = '../cart';
                };
                done.style.transform = "translate(0px)";

                if (cartArea.getElementsByTagName('span')[0]) {
                    cartArea.getElementsByTagName('span')[0].innerText = parseInt(cartArea.getElementsByTagName('span')[0].innerText) + 1;
                } else {
                    cartArea.innerHTML += `<span>1</span>`;
                }

                generateNotif('success', 'Succès', 'Le produit a bien été ajouté à votre panier.', 3000);
            } else {
                error.style.transform = "translate(0px)";
                setTimeout(function() {
                    error.style.transform = "translate(-110%) skew(-40deg)";
                }, 3000);

                generateNotif('error', 'Erreur', data.message, 3000);
            }
            console.log(data.message);
        },
        error: function() {
            error.style.transform = "translate(0px)";
            setTimeout(function() {
                error.style.transform = "translate(-110%) skew(-40deg)";
            }, 3000);
        }
    });
}
function removeToCart(removeAll, productId = null) {
    console.log("removeAll : ", removeAll, "productId : ", productId);
    $.ajax({
        url: 'action/remove_to_cart.php',
        type: 'POST',
        data: {
            remove_all: removeAll,
            product_id: productId,
        },
        success: function(response) {
            console.log(response);
            data = JSON.parse(response);
            if (data.status === 'success') {
                if (removeAll == true) {
                    document.getElementsByClassName('list')[0].innerHTML = `
                        <p style="text-align: center; margin-top: 50px;">Votre panier est vide.</p>
                        <button style="margin: 0 5px; width: 300px; align-self: center;" onclick="window.location.href='index'" class="btn">Passez commande</button>
                    `;
                    generateNotif('success', 'Succès', 'Votre panier a bien été vidé.', 3000);
                } else {
                    productNb = parseInt(document.getElementById('productNb').innerText);
                    document.getElementById('item-' + productId).remove();
                    document.getElementById('productNb').innerText = productNb - 1;

                    if (document.getElementById('productNb').innerText == 0) {
                        document.getElementsByClassName('list')[0].innerHTML = `
                            <p style="text-align: center; margin-top: 50px;">Votre panier est vide.</p>
                            <button style="margin: 0 5px; width: 300px; align-self: center;" onclick="window.location.href='index'" class="btn">Passez commande</button>
                        `;
                    }

                    generateNotif('success', 'Succès', 'Le produit a bien été retiré de votre panier.', 3000);
                }
            } else {
                generateNotif('error', 'Erreur', data.message);
            }
        },
        error: function() {
            alert('Error: Une erreur c\'est produite. Veuillez réessayer plus tard.');
        }
    });
}

// Coupon code management
function applyCouponCode(btn) {
    const couponCode = document.getElementById('couponCode');
    const container = document.getElementsByClassName('coupon-code')[0];
    const reducAmount = document.getElementById('reducAmount');
    const stillCouponCode = reducAmount.classList.contains('active') ? true : false;
    const productsPrice = document.getElementById('productsPrice');
    const allCouponCodes = document.getElementById('allCouponCodes');
    allCc = [];
    for (let i = 0; i < allCouponCodes.children.length; i++) {
        const item = allCouponCodes.children[i];
        const code = item.getElementsByClassName('code')[0].innerText;
        const type = item.getElementsByClassName('type')[0].innerText;
        const value = parseFloat(item.getElementsByClassName('value')[0].innerText);
        allCc.push({code: code, type: type, value: value});
    }
    if (couponCode.value === '' || couponCode.value === null) {
        wrongCouponCode(btn, container);
        return;
    }
    $.ajax({
        url: 'action/apply_coupon_code.php',
        type: 'POST',
        data: {
            coupon_code: couponCode.value,
            still_coupon_code: stillCouponCode,
            all_coupon_codes: JSON.stringify(allCc),
        },
        success: function(response) {
            console.log(response);
            data = JSON.parse(response);
            console.log(data);
            if (data.status === 'success') {
                ccApply = document.getElementsByClassName('coupon-code-apply')[0];
                correctCouponCode(btn, container);
                ccApply.style.display = 'block';
                ccApply.innerHTML = `${ccApply.innerHTML} <span style="font-weight: bold;">${data.code}</span>`;

                if (data.type == 'percent') {
                    reduc = parseFloat(productsPrice.innerText.replace('€', '')) * parseFloat(data.value) / 100;
                    reducAmount.classList.add('active');
                } else if (data.type == 'amount') {
                    reduc = parseFloat(data.value);
                    reducAmount.classList.add('active');
                } else {
                    reduc = 0;
                }
                reducAmount.innerText = reduc.toFixed(2) + '€';

                allCouponCodes.innerHTML += `
                    <div class="item">
                        <p class="code">${data.code}</p>
                        <p class="type">${data.type === 'percent' ? 'percent' : 'amount'}</p>
                        <p class="value">${data.value}</p>
                    </div>
                `;

                couponCode.value = '';
                updateTotalPrice();

                generateNotif('success', 'Succès', 'Le code promo a bien été appliqué à votre commande.', 2000);
            } else if (data.status === 'error') {
                wrongCouponCode(btn, container);
                couponCode.value = '';
                generateNotif('error', 'Erreur', data.message, 4000);
            } else {
                couponCode.value = '';
                generateNotif('error', 'Erreur', data.message, 4000);
            }
        },
        error: function() {
            wrongCouponCode(btn, container);
            couponCode.value = '';
            generateNotif('error', 'Erreur', 'Une erreur c\'est produite. Veuillez réessayer plus tard.', 4000);
        }
    });
}
function wrongCouponCode(btn, container) {
    container.style.border = '1px solid rgb(177, 36, 36)';
    container.style.borderRadius = '0.4rem';
    container.style.animation = 'wrongCode 0.16s 0s 3';
    btn.style.backgroundColor = 'rgb(177, 36, 36)';
    setTimeout(function() {
        btn.style.backgroundColor = '#3067eb';
        container.style.border = 'solid 1px #181818';
        container.style.animation = 'none';
    }, 480);
}
function correctCouponCode(btn, container) {
    container.style.border = '1px solid rgb(36, 177, 36)';
    container.style.borderRadius = '0.4rem';
    container.style.animation = 'correctCode 0.16s 0s 3';
    btn.style.backgroundColor = 'rgb(36, 177, 36)';
    setTimeout(function() {
        btn.style.backgroundColor = '#3067eb';
        container.style.border = 'solid 1px #181818';
        container.style.animation = 'none';
    }, 800);
}

function updateTotalPrice() {
    const finalPrice = document.getElementById('finalPrice');
    const productsPrice = document.getElementById('productsPrice');
    const tvaAmount = document.getElementById('tvaAmount');
    const reducAmount = document.getElementById('reducAmount');

    const allItems = document.getElementsByClassName('product-list');
    const allCouponCodes = document.getElementById('allCouponCodes');
    
    let totalPrice = 0;
    let totalReduc = 0;
    let totalTva = 0;

    // Calculate the products price
    for (let i = 0; i < allItems.length; i++) {
        const item = allItems[i];
        const price = item.getElementsByClassName('price')[0].innerText;
        const unitPrice = item.getElementsByClassName('unitPrice')[0].value;
        const quantity = item.getElementsByClassName('quantity')[0].value;
        const totalItemPrice = parseFloat(unitPrice) * parseFloat(quantity);
        totalPrice += totalItemPrice;
    }

    // Calculate the reduction amount
    for (let i = 0; i < allCouponCodes.children.length; i++) {
        const item = allCouponCodes.children[i];
        const type = item.getElementsByClassName('type')[0].innerText;
        const value = parseFloat(item.getElementsByClassName('value')[0].innerText);
        if (type == 'percent') {
            totalReduc += (parseFloat(totalPrice) * parseFloat(value)) / 100;
        } else if (type == 'amount') {
            totalReduc += parseFloat(value);
        } else {
            totalReduc += 0;
        }
        console.log('Reduc: ', totalReduc);
    }

    // Calculate the tva amount
    for (let i = 0; i < allItems.length; i++) {
        const item = allItems[i];
        const tva = parseFloat(item.getElementsByClassName('tva')[0].value);
        const quantity = item.getElementsByClassName('quantity')[0].value;
        totalTva += parseFloat(tva * quantity);
    }

    console.log('Total price: ', totalPrice);
    console.log('Total reduc: ', totalReduc);
    console.log('Total tva: ', totalTva);

    productsPrice.innerText = totalPrice.toFixed(2) + '€';
    tvaAmount.innerText = totalTva.toFixed(2) + '€';
    reducAmount.innerText = totalReduc.toFixed(2) + '€';
    finalPrice.innerText = (totalPrice - totalReduc).toFixed(2) + '€';
}

// Login
function checkMail(btn) {
    const email = document.getElementById('email').value;
    btn.enabled = false;

    if (email === '' || email === null) {
        btn.enabled = true;
        generateNotif('error', 'Erreur', 'Veuillez entrer une adresse email.', 3000);
        return;
    }
    if (!email.includes('@') || !email.includes('.')) {
        btn.enabled = true;
        generateNotif('error', 'Erreur', 'Veuillez entrer une adresse email valide.', 3000);
        return;
    }

    $.ajax({
        url: 'action/check_email.php',
        type: 'POST',
        data: {
            email: email
        },
        success: function(response) {
            console.log(response);
            data = JSON.parse(response);
            if (data.status === 'success') {
                if (data.message == 'no-account') {
                    generateNotif('other', 'Redirection', 'Vous allez être redirigé vers la page d\'inscription.', 2000);
                    setTimeout(function() {
                        window.location.href = 'register';
                    }, 2000);
                } else if (data.message == 'account') {
                    generateLoginForm('login', email);
                } else if (data.message == 'no-company') {
                    generateNotif('warning', 'Info', 'Vous n\'avez pas les permissions d\'accéder à ceci. Rendez-vous dans votre espace client.', 3000);
                } else if (data.message == 'pending-company') {
                    generateNotif('warning', 'Info', 'Votre compte est en attente de validation. Vous recevrez un email de confirmation d\'ici quelques heures.', 3000);
                } else if (data.message == 'refused-company') {
                    generateNotif('error', 'Erreur', 'Votre compte a été refusé. Veuillez contacter le support pour plus d\'informations.', 3000);
                }
            } else {
                btn.enabled = true;
                generateNotif('error', 'Erreur', data.message, 3000);
            }
        },
        error: function() {
            btn.enabled = true;
            generateNotif('error', 'Erreur', 'Une erreur c\'est produite. Veuillez réessayer plus tard.', 3000);
        }
    });
}
function generateLoginForm(type, email) {
    const loginForm = document.getElementById('loginForm');

    if (type == 'login') {
        loginForm.innerHTML = `
            <h2>Connexion</h2>
            <span>
                <label for="email">Adresse email<b style="color: red;">*</b></label>
                <input type="text" id="email" value="${email}" placeholder="Adresse email" disabled>
            </span>
            <span style="position: relative;">
                <label for="passw">Mot de passe<b style="color: red;">*</b></label>
                <span class="password">
                    <input type="password" id="passw" placeholder="Mot de passe*">
                    <span class="show-pass" onclick="togglePassw(this)"><i class="fa-solid fa-eye"></i></span>
                </span>
            </span>
            <button onclick="login(this)" class="btn" style="align-self: flex-end;">Se connecter</button>
        `;
    }
}
function togglePassw(btn) {
    var input = btn.previousElementSibling;

    if (input.type === "password") {
        input.type = "text";
        btn.innerHTML = "<i class=\"fa-solid fa-eye-slash\"></i>";
    } else {
        input.type = "password";
        btn.innerHTML = "<i class=\"fa-solid fa-eye\"></i>";
    }
}
function login(btn) {
    const loginForm = document.getElementById('loginForm');
    const email = document.getElementById('email').value;
    const passw = document.getElementById('passw').value;

    if (email === '' || email === null) {
        generateNotif('error', 'Erreur', 'Veuillez entrer une adresse email.', 3000);
        return;
    }
    if (passw === '' || passw === null) {
        generateNotif('error', 'Erreur', 'Veuillez entrer un mot de passe.', 3000);
        return;
    }
    if (!email.includes('@') || !email.includes('.')) {
        generateNotif('error', 'Erreur', 'Veuillez entrer une adresse email valide.', 3000);
        return;
    }
    
    $.ajax({
        url: 'action/login.php',
        type: 'POST',
        data: {
            email: email,
            passw: passw
        },
        success: function(response) {
            console.log(response);
            data = JSON.parse(response);
            if (data.status === 'success') {
                if (data.message == 'logged') {
                    generateNotif('success', 'Succès', 'Vous êtes maintenant connecté.', 1500);
                    setTimeout(function() {
                        window.location.href = 'index';
                    }, 1500);
                } else if (data.message == 'no-account') {
                    generateNotif('error', 'Erreur', 'Adresse email ou mot de passe incorrect.', 3000);
                } else if (data.message == 'inactive-account') {
                    generateNotif('error', 'Erreur', 'Votre compte est inactif. Veuillez vérifier vos emails.', 3000);
                }
            } else {
                generateNotif('error', 'Erreur', data.message, 3000);
            }
        },
        error: function() {
            generateNotif('error', 'Erreur', 'Une erreur c\'est produite. Veuillez réessayer plus tard.', 3000);
        }
    });
}


// MISC
function sendInEnter(inp, target) {
    if (event.key === "Enter") {
        if (target === 'applyCouponCode') {
            btn = document.getElementsByClassName('coupon-code')[0].getElementsByTagName('button')[0];
            applyCouponCode(btn);
        }
    }
}
function generateNotif(type, title, message, duration = 5000) {
    const notif = document.getElementById('notif');
    const icon = notif.getElementsByClassName('icon')[0];
    const titleEl = notif.getElementsByTagName('h3')[0];
    const messageEl = notif.getElementsByTagName('p')[0];

    if (message.length > 80) {
        notif.style.width = '400px';
    } else if (message.length > 150) {
        notif.style.width = '500px';
    }

    if (type === 'success') {
        icon.innerHTML = '<i class="fas fa-check-circle"></i>';
        notif.style.backgroundColor = 'rgba(36, 177, 36, 0.3)';
        icon.style.color = 'rgb(36, 177, 36)';
    } else if (type === 'error') {
        icon.innerHTML = '<i class="fas fa-times-circle"></i>';
        notif.style.backgroundColor = 'rgba(177, 36, 36, 0.3)';
        icon.style.color = 'rgb(177, 36, 36)';
    } else if (type === 'warning') {
        icon.innerHTML = '<i class="fas fa-exclamation-circle"></i>';
        notif.style.backgroundColor = 'rgba(177, 177, 36, 0.3)';
        icon.style.color = 'rgb(177, 177, 36)';
    } else {
        icon.innerHTML = '<i class="fas fa-info-circle"></i>';
        notif.style.backgroundColor = 'rgba(36, 36, 177, 0.3)';
        icon.style.color = 'rgb(255, 255, 255)';
    }
    
    titleEl.innerText = title;
    messageEl.innerText = message;
    
    notif.classList.add('show');

    setTimeout(function() {
        notif.classList.remove('show');
    }, duration);
}