<?php
session_set_cookie_params(604800); // 7 days
session_start();
include_once 'db.inc.php';
function getPdo() {
    try {
        return new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    }
    catch(Exception $e) {
        die('Unable to connect to database.');
    }
}

// CARD
function getCard($cardId) {
    $pdo = getPdo();

    $stmt = $pdo->prepare("SELECT c.firstname, c.lastname, c.birth_date, c.death_date, c.info, cc.display_name, cc.display_dob, cc.display_dod, cc.display_info
        FROM cards c
        INNER JOIN config_cards cc ON c.id = cc.card_id
        WHERE c.id = :card_id");
    $stmt->bindParam(':card_id', $cardId, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return $result;
    } else {
        return null;
    }
}
function getCardImages($cardId) {
    $pdo = getPdo();

    $stmt = $pdo->prepare("SELECT path, location FROM images WHERE card_id = :card_id");
    $stmt->bindParam(':card_id', $cardId, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        return $result;
    } else {
        return null;
    }
}

// PRODUCT
function getAllProduct() {
    $pdo = getPdo();

    $stmt = $pdo->prepare("SELECT * FROM products");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        return $result;
    } else {
        return null;
    }
}
function getProduct($productId) {
    $pdo = getPdo();

    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = :product_id OR id = :product_id");
    $stmt->bindParam(':product_id', $productId, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return $result;
    } else {
        return null;
    }
}


// CART
function addToCart($productId, $quantity) {
    if(isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}
function removeFromCart($productId) {
    if(isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}
function clearCart() {
    $_SESSION['cart'] = [];
}
function getCart() {
    return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
}
function getProductInCart($productId) {
    return isset($_SESSION['cart'][$productId]) ? $_SESSION['cart'][$productId] : 0;
}
function getCartPrice($action = "total") {
    $cart = getCart();

    if ($cart) {
        if ($action == "total") {
            $price = 0;
            $tva = 0;
            foreach ($cart as $productId => $quantity) {
                $product = getProduct($productId);
                $price += $product['price'] * $quantity;
                $tva += $product['tva'] * $quantity;
            }
        } else {
            return $cart;
        }
    } else {
        $price = 0;
        $tva = 0;
    }

    return [
        'price' => $price,
        'tva' => $tva,
    ];
}
function countCart() {
    return count(getCart());
}

// COUPON CODE
function getCouponCode($code) {
    $pdo = getPdo();

    $stmt = $pdo->prepare("SELECT * FROM discount_codes WHERE code = :code");
    $stmt->bindParam(':code', $code, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return $result;
    } else {
        return null;
    }
}
function couponCodeIsValid($code) {
    $coupon = getCouponCode($code);

    if ($coupon) {
        $today = new DateTime();
        $start = new DateTime($coupon['date_start']);
        $end = new DateTime($coupon['date_end']);
        $active = $coupon['active'];

        if ($today >= $start && ($today <= $end || $coupon['date_end'] == null)) {
            if ($active == true) {
                return [
                    'available' => true,
                    'code' => $coupon['code'],
                    'type' => $coupon['type'],
                    'cumulative' => $coupon['cumulative'],
                    'value' => $coupon['value'],
                ];
            }
        }
    }

    return [
        'available' => false,
    ];
}

// ACCOUNT
function checkMail($email) {
    $pdo = getPdo();

    $stmt = $pdo->prepare("SELECT firstname, lastname, role, company_id FROM account WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return $result;
    } else {
        return null;
    }
}
function createAccount($firstname, $lastname, $email, $password, $role, $company_id) {
    $pdo = getPdo();

    $stmt = $pdo->prepare("INSERT INTO account (firstname, lastname, email, password, role, company_id) VALUES (:firstname, :lastname, :email, :password, :role, :company_id)");
    $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':role', $role, PDO::PARAM_STR);
    $stmt->bindParam(':company_id', $company_id, PDO::PARAM_STR);
    $stmt->execute();

    return $pdo->lastInsertId();
}
function getAccount($id) {
    $pdo = getPdo();

    $stmt = $pdo->prepare("SELECT * FROM account WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return $result;
    } else {
        return null;
    }
}
function accountIsActive($accountId = null) {
    if ($accountId) {
        $account = getAccount($accountId);
    } else {
        $account = getAccount($_SESSION['logged_as']);
    }

    if ($account) {
        if ($account['role'] == 'user') {
            $company = getCompany($account['company_id']);

            if ($company) {
                if ($company['account_status'] == 'available') {
                    return true;
                }
            }
        } else {
            return true;
        }
    }

    return false;
}
function login($email, $password) {
    $pdo = getPdo();

    $stmt = $pdo->prepare("SELECT id, password FROM account WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        if (password_verify($password, $result['password'])) {
            return $result['id'];
        } else {
            return 'bad-info';
        }
    } else {
        return null;
    }
}

// COMPANY
function createCompany($name, $address, $city, $zip, $phone, $email) {
    $pdo = getPdo();

    $stmt = $pdo->prepare("INSERT INTO company (name, address, city, zip, phone, email) VALUES (:name, :address, :city, :zip, :phone, :email)");
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->bindParam(':city', $city, PDO::PARAM_STR);
    $stmt->bindParam(':zip', $zip, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    return $pdo->lastInsertId();
}
function getCompany($id) {
    $pdo = getPdo();

    $stmt = $pdo->prepare("SELECT * FROM company WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return $result;
    } else {
        return null;
    }
}
function isActiveCompany($companyId, $details = false) {
    $company = getCompany($companyId);

    $temp = false;
    if ($company) {
        if ($company['account_status'] == 'available') {
            $temp = true;
        }
        
        if ($details == true) {
            return $company['account_status'];
        }
    }

    return $temp;
}

// WEBHOOK
function sendWebhook($url, $username, $contentType, $content) {
    if ($contentType == 'embed') {
        $data = [
            'content' => '',
            'username' => $username,
            'embeds' => [$content],
        ];
    } else {
        $data = [
            'content' => $content,
            'username' => $username,
        ];
    }
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    return $response;
    curl_close($ch);

    return $response;
}


// MISC
function getDateBilling($type) {
    if ($type == 'sunday') {
        $today = new DateTime();
        $week = $today->format("w");
        
        $diff = 7 - $week;

        $nextSunday = $today->modify("+$diff days");

        $result = $nextSunday->format("Y-m-d");
    } else {
        $today = new DateTime();

        $result = $today->format("Y-m-d");
    }

    return $result;
}