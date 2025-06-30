<?php
// index.php - Pricing Selection Page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Your Plan</title>
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
            margin-bottom: 32px;
            color: #333;
        }
        .pricing-options {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 24px;
        }
        .option {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f0f4f8;
            border-radius: 8px;
            padding: 12px 16px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .option input[type="radio"] {
            accent-color: #007bff;
        }
        .option:hover {
            background: #e2e8f0;
        }
        .custom-amount {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .custom-amount input[type="number"] {
            width: 80px;
            padding: 6px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .pay-btn {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background 0.2s;
        }
        .pay-btn:hover {
            background: #0056b3;
        }
    </style>
    <script>
        function enableCustomAmount() {
            document.getElementById('customAmount').disabled = false;
            document.getElementById('customAmount').focus();
        }
        function disableCustomAmount() {
            document.getElementById('customAmount').disabled = true;
        }
        function validateForm() {
            const customRadio = document.getElementById('customRadio');
            const customAmount = document.getElementById('customAmount');
            if (customRadio.checked) {
                if (customAmount.value < 50) {
                    alert('Minimum amount is ₹50');
                    return false;
                }
            }
            return true;
        }
    </script>
</head>
<body>
    <form class="container" action="payment.php" method="POST" onsubmit="return validateForm();">
        <h2>Select Your Plan</h2>
        <div class="pricing-options">
            <label class="option">
                <input type="radio" name="amount" value="50" onclick="disableCustomAmount()" required> ₹50
            </label>
            <label class="option">
                <input type="radio" name="amount" value="100" onclick="disableCustomAmount()"> ₹100
            </label>
            <label class="option">
                <input type="radio" name="amount" value="200" onclick="disableCustomAmount()"> ₹200
            </label>
            <label class="option">
                <input type="radio" id="customRadio" name="amount" value="custom" onclick="enableCustomAmount()"> 
                <span class="custom-amount">
                    Custom: ₹<input type="number" id="customAmount" name="custom_amount" min="50" placeholder="Min 50" disabled>
                </span>
            </label>
        </div>
        <button class="pay-btn" type="submit">Pay Now</button>
    </form>
</body>
</html> 