<?php
require_once '../../inc/functions.inc.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $couponCodeIsValid = couponCodeIsValid($_POST['coupon_code']);
    $stillCouponCode = $_POST['still_coupon_code'];
    $allCc = json_decode($_POST['all_coupon_codes'], true);
    $aCodeIsNotCumulative = false;
    $sameCode = false;
    foreach ($allCc as $cc) {
        $code = getCouponCode($cc['code']);
        if ($code['cumulative'] == 'false') {
            $aCodeIsNotCumulative = true;
        }
        if (isset($couponCodeIsValid['code']) && $couponCodeIsValid['code'] == $cc['code']) {
            $sameCode = true;
        }
    }

    // $sameCodeTest = $sameCode == true ? 'true' : 'false';
    // $aCodeIsNotCumulativeTest = $aCodeIsNotCumulative == true ? 'true' : 'false';
    // echo json_encode(
    //     [
    //         "couponCode" => $_POST['coupon_code']." : ".gettype($_POST['coupon_code']),
    //         "stillCouponCode" => $stillCouponCode.": ".gettype($stillCouponCode),
    //         "aCodeIsNotCumulative" => $aCodeIsNotCumulativeTest.": ".gettype($aCodeIsNotCumulative),
    //         "couponCodeIsValid" => $couponCodeIsValid,
    //         "sameCode" => $sameCodeTest.": ".gettype($sameCode),
    //     ]
    // );
    // die();

    if ($couponCodeIsValid['available'] == true) {
        if ($sameCode == true) {
            echo json_encode(['status' => 'error', 'message' => 'Vous avez déjà appliqué ce code de réduction']);
            return;
        }
        if ($couponCodeIsValid['cumulative'] == 'false' && $stillCouponCode == 'true') {
            echo json_encode(['status' => 'error', 'message' => 'Vous ne pouvez pas cumuler les codes de réduction']);
            return;
        }
        if ($aCodeIsNotCumulative == true) {
            echo json_encode(['status' => 'error', 'message' => 'Vous ne pouvez pas cumuler les codes de réduction']);
            return;
        }
        echo json_encode(['status' => 'success', 'available' => $couponCodeIsValid['available'], 'code' => $couponCodeIsValid['code'], 'type' => $couponCodeIsValid['type'], 'cumulative' => $couponCodeIsValid['cumulative'], 'value' => $couponCodeIsValid['value']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Code de réduction invalide']);
    }
} else {
    // Retourner une réponse d'erreur si la méthode n'est pas POST
    echo json_encode(['status' => 'errorDev', 'message' => 'Méthode non autorisée']);
}
