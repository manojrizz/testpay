<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Payment Method</title>
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 40px 32px;
            max-width: 400px;
            width: 100%;
        }
        h2 {
            text-align: center;
            margin-bottom: 24px;
            color: #333;
        }
        .amount {
            text-align: center;
            font-size: 1.3em;
            margin-bottom: 32px;
            color: #007bff;
        }
        .payment-options {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .pay-option {
            display: flex;
            align-items: center;
            gap: 16px;
            background: #f0f4f8;
            border-radius: 8px;
            padding: 14px 18px;
            cursor: pointer;
            transition: background 0.2s;
            border: none;
            font-size: 1.1em;
        }
        .pay-option:hover {
            background: #e2e8f0;
        }
        .pay-icon {
            width: 32px;
            height: 32px;
        }
    </style>
    <script>
        function payWith(app) {
            var upi = document.getElementById('upiLink').value;
            if (app === 'paytm') {
                window.location.href = upi + '&pn=Paytm';
            } else if (app === 'phonepe') {
                window.location.href = upi + '&pn=PhonePe';
            } else if (app === 'gpay') {
                window.location.href = upi + '&pn=GooglePay';
            } else {
                window.location.href = upi;
            }
        }
    </script>
</head>
<body>
<?php
session_start();

$amount = 0;
if (isset($_SESSION['payment_amount'])) {
    $amount = intval($_SESSION['payment_amount']);
}

// Ensure minimum amount
if ($amount < 50) {
    $amount = 50;
}

// Clear the session data after use
unset($_SESSION['payment_amount']);
// UPI details (replace with your own UPI ID and name)
$payee_vpa = 'your-upi-id@okicici';
$payee_name = 'Your Name';
$txn_note = 'Payment for Service';
$upi_link = 'upi://pay?pa=' . urlencode($payee_vpa) . '&pn=' . urlencode($payee_name) . '&am=' . $amount . '&tn=' . urlencode($txn_note) . '&cu=INR';
?>
    <div class="container">
        <h2>Select Payment Method</h2>
        <div class="amount">Amount to Pay: <b>₹<?php echo $amount; ?></b></div>
        <input type="hidden" id="upiLink" value="<?php echo $upi_link; ?>">
        <div class="payment-options">
            <button class="pay-option" onclick="payWith('paytm')">
                <img class="pay-icon" src="https://upload.wikimedia.org/wikipedia/commons/5/55/Paytm_logo.png" alt="Paytm">Paytm
            </button>
            <button class="pay-option" onclick="payWith('phonepe')">
                <img class="pay-icon" src="https://upload.wikimedia.org/wikipedia/commons/f/f0/PhonePe-Logo.png" alt="PhonePe">PhonePe
            </button>
            <button class="pay-option" onclick="payWith('gpay')">
                <img class="pay-icon" src="https://upload.wikimedia.org/wikipedia/commons/0/09/Google_Pay_Logo.svg" alt="GPay">GPay
            </button>
        </div>
    </div>
</body>
</html> 