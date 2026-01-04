<?php
require "config.php";
require "db.php";
require "send-mail.php";

$order_id = $_POST['razorpay_order_id'];
$payment_id = $_POST['razorpay_payment_id'];
$signature = $_POST['razorpay_signature'];
$email = $_POST['email'];
$amount = $_POST['amount']; // from DB/session

// ACK verification
$generated = hash_hmac(
  "sha256",
  $order_id . "|" . $payment_id,
  RAZORPAY_KEY_SECRET
);

if ($generated !== $signature) {
   exit("Payment verification failed");
}

// Generate token
$token = bin2hex(random_bytes(16));

// Save DB
$stmt = $conn->prepare(
 "INSERT INTO payments(order_id,payment_id,email,amount,token,status)
  VALUES (?,?,?,?,?,'PAID')"
);
$stmt->bind_param("sssds", $order_id, $payment_id, $email, $amount, $token);
$stmt->execute();

// Send emails
sendCustomerMail($email, $payment_id, $amount, $token);
sendAdminMail($email, $payment_id, $amount);

// Redirect to Calendly
header("Location: ".CALENDLY_URL."?token=".$token);
exit;
