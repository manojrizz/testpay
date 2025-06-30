<?php
// process.php - Handle form submission from index.html
session_start();

$amount = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['amount'])) {
        if ($_POST['amount'] === 'custom' && isset($_POST['custom_amount'])) {
            $amount = max(50, intval($_POST['custom_amount']));
        } else {
            $amount = intval($_POST['amount']);
        }
    }
}

// Ensure minimum amount
if ($amount < 50) {
    $amount = 50;
}

// Store amount in session for payment.php
$_SESSION['payment_amount'] = $amount;

// Redirect to payment page
header('Location: payment.php');
exit();
?> 