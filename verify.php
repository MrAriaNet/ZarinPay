<?php
// verify.php
require "db.php";

$merchant_id = "YOUR-MERCHANT-ID-HERE";
$authority = $_GET['Authority'] ?? '';

if (!$authority) {
    die("Invalid request");
}

// Get transaction info
$txn = $conn->query("SELECT * FROM transactions WHERE authority='" . $conn->real_escape_string($authority) . "'")->fetch_assoc();
if (!$txn) die("Transaction not found");

$amount = $txn['amount'];

$data = [
    "merchant_id" => $merchant_id,
    "authority" => $authority,
    "amount" => $amount
];

$ch = curl_init('https://api.zarinpal.com/pg/v4/payment/verify.json');
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

$status = 'failed';
$ref_id = null;

if ($err) {
    $status = 'failed';
} elseif (!empty($result['errors'])) {
    $status = 'failed';
} elseif (isset($result['data']['code']) && $result['data']['code'] == 100) {
    $status = 'success';
    $ref_id = $result['data']['ref_id'];
}

// Update transaction
$stmt = $conn->prepare("UPDATE transactions SET status=?, ref_id=? WHERE authority=?");
$stmt->bind_param("sss", $status, $ref_id, $authority);
$stmt->execute();
$stmt->close();

// Show result
if ($status === 'success') {
    echo "<h3>Payment successful. RefID: " . htmlspecialchars($ref_id) . "</h3>";
} else {
    echo "<h3>Payment failed.</h3>";
}
?>
