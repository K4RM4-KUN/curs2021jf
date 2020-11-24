<?php
session_start();
require './stripe/init.php';
require "libraryShop.php";
$_SESSION["PrivateKey"] = newCode();

\Stripe\Stripe::setApiKey('sk_test_51HosN6JM40zfaHAMZJtQq3sgD8X7Nsjo3YrQCahTABKoZH2iml8Ua5QFU7fA0MMvtJZMNULyEwFemi9oRXyUNrnz00W7RwhBPy');
header('Content-Type: application/json');
$YOUR_DOMAIN = 'http://dawjavi.insjoaquimmir.cat';
$checkout_session = \Stripe\Checkout\Session::create([
  'payment_method_types' => ['card'],
  'line_items' => [[
    'price_data' => [
      'currency' => 'eur',
      'unit_amount' => $_SESSION["price"] ,
      'product_data' => [
        'name' => 'MyWeb',
        'images' => ["https://i.postimg.cc/x8n3ZHgf/online-shopping-bag-icon-vector.jpg"],
      ],
    ],
    'quantity' => 1,
  ]],
  'mode' => 'payment',
  'success_url' => $YOUR_DOMAIN . '/jfuentes/UF1/A7/successPurchase.php?id_compra='.$_SESSION["PrivateKey"],
  'cancel_url' => $YOUR_DOMAIN . '/jfuentes/UF1/A7/errorPurchase.php?id_compra='.$_SESSION["PrivateKey"],
]);
echo json_encode(['id' => $checkout_session->id]);
