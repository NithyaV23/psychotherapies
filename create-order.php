<?php
require "config.php";

// 🔐 Server-side pricing
$pricing = [
  "30" => 99900,
  "60" => 179900
];

$duration = $_POST['duration'];
$email = $_POST['email'];

if (!isset($pricing[$duration])) {
   http_response_code(400);
   exit("Invalid plan");
}

$amount = $pricing[$duration];

$data = [
  "amount" => $amount,
  "currency" => "INR",
  "receipt" => "booking_" . time()
];

$ch = curl_init("https://api.razorpay.com/v1/orders");
curl_setopt_array($ch, [
  CURLOPT_USERPWD => RAZORPAY_KEY_ID . ":" . RAZORPAY_KEY_SECRET,
  CURLOPT_POSTFIELDS => json_encode($data),
  CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
  CURLOPT_RETURNTRANSFER => true
]);

echo curl_exec($ch);
curl_close($ch);
