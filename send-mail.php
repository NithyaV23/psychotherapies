<?php
use PHPMailer\PHPMailer\PHPMailer;

require "vendor/autoload.php";

function baseMailer() {
  $mail = new PHPMailer(true);
  $mail->isSMTP();
  $mail->Host = "smtp.hostinger.com";
  $mail->SMTPAuth = true;
  $mail->Username = FROM_EMAIL;
  $mail->Password = "EMAIL_PASSWORD";
  $mail->SMTPSecure = "tls";
  $mail->Port = 587;
  $mail->setFrom(FROM_EMAIL, FROM_NAME);
  return $mail;
}

function sendCustomerMail($to, $payment_id, $amount, $token) {
  $mail = baseMailer();
  $mail->addAddress($to);
  $mail->Subject = "Payment Successful – Booking Confirmed";
  $mail->Body =
"Payment Successful!

Payment ID: $payment_id
Amount Paid: ₹".($amount/100)."

Schedule your meeting:
".CALENDLY_URL."?token=$token";
  $mail->send();
}

function sendAdminMail($customer, $payment_id, $amount) {
  $mail = baseMailer();
  $mail->addAddress(ADMIN_EMAIL);
  $mail->Subject = "New Payment Received – ₹".($amount/100);
  $mail->Body =
"New Payment Received

Customer: $customer
Payment ID: $payment_id
Amount: ₹".($amount/100);
  $mail->send();
}
