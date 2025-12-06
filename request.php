<?php
// request.php
require "db.php";

$merchant_id = "YOUR-MERCHANT-ID-HERE";

$amount = intval($_POST['amount'] ?? 0);
$description = $_POST['description'] ?? '';
$email = $_POST['email'] ?? '';
$mobile = $_POST['mobile'] ?? '';

if ($amount <= 0 || !$description || !$email || !$mobile) {
    die("Invalid input");
}

// Prepare data for Zarinpal
$data = [
    "merchant_id" => $merchant_id,
    "amount" => $amount,
    "callback_url" => "https://yourdomain.com/verify.php",
    "description" => $description,
    "metadata" => ["email" => $email, "mobile" => $mobile]
];

$ch = curl_init('https://api.zarinpal.com/pg/v4/payment/request.json');
curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v4');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen(json_encode($data))
]);

$result = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

$result = json_decode($result, true);

if ($err) {
    die("cURL Error #: " . $err);
}

// Save initial transaction
$authority = $result['data']['authority'] ?? '';
$status = isset($result['data']['code']) && $result['data']['code'] == 100 ? 'pending' : 'failed';

$stmt = $conn->prepare("INSERT INTO transactions (authority, amount, description, email, mobile, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("sissss", $authority, $amount, $description, $email, $mobile, $status);
$stmt->execute();
$stmt->close();

// Redirect to Zarinpal
if ($status === 'pending' && $authority) {
    header('Location: https://www.zarinpal.com/pg/StartPay/' . $authority);
    exit;
} else {
    echo "Payment request failed.";
}
