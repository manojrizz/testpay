<?php
// process_verification.php - Handle payment verification requests
session_start();

// Database configuration (you can use a simple file-based system or MySQL)
$verification_file = 'verifications.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $amount = intval($_POST['amount']);
    $payment_method = filter_var($_POST['payment_method'], FILTER_SANITIZE_STRING);
    $transaction_id = filter_var($_POST['transaction_id'], FILTER_SANITIZE_STRING);
    $payment_date = $_POST['payment_date'];
    $notes = filter_var($_POST['notes'], FILTER_SANITIZE_STRING);
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($transaction_id) || $amount < 1) {
        header('Location: verify_payment.html?error=invalid_data');
        exit();
    }
    
    // Handle file upload if provided
    $screenshot_path = '';
    if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_extension = pathinfo($_FILES['screenshot']['name'], PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array(strtolower($file_extension), $allowed_extensions)) {
            $filename = 'payment_' . time() . '_' . $transaction_id . '.' . $file_extension;
            $screenshot_path = $upload_dir . $filename;
            
            if (move_uploaded_file($_FILES['screenshot']['tmp_name'], $screenshot_path)) {
                // File uploaded successfully
            } else {
                $screenshot_path = '';
            }
        }
    }
    
    // Create verification record
    $verification = [
        'id' => uniqid('VER_'),
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'amount' => $amount,
        'payment_method' => $payment_method,
        'transaction_id' => $transaction_id,
        'payment_date' => $payment_date,
        'screenshot' => $screenshot_path,
        'notes' => $notes,
        'status' => 'pending',
        'submitted_at' => date('Y-m-d H:i:s'),
        'verified_at' => null
    ];
    
    // Load existing verifications
    $verifications = [];
    if (file_exists($verification_file)) {
        $verifications = json_decode(file_get_contents($verification_file), true) ?: [];
    }
    
    // Add new verification
    $verifications[] = $verification;
    
    // Save to file
    file_put_contents($verification_file, json_encode($verifications, JSON_PRETTY_PRINT));
    
    // Send email notification to admin
    sendAdminNotification($verification);
    
    // Send confirmation email to user
    sendUserConfirmation($verification);
    
    // Redirect to success page
    header('Location: verification_success.html?id=' . $verification['id']);
    exit();
}

function sendAdminNotification($verification) {
    $admin_email = 'your-email@example.com'; // Replace with your email
    $subject = 'New Payment Verification Request - ' . $verification['id'];
    
    $message = "
    New payment verification request received:
    
    Verification ID: {$verification['id']}
    Name: {$verification['name']}
    Email: {$verification['email']}
    Phone: {$verification['phone']}
    Amount: ₹{$verification['amount']}
    Payment Method: {$verification['payment_method']}
    Transaction ID: {$verification['transaction_id']}
    Payment Date: {$verification['payment_date']}
    Notes: {$verification['notes']}
    Submitted: {$verification['submitted_at']}
    
    To verify this payment, check your bank account or UPI app for the transaction.
    ";
    
    $headers = 'From: noreply@yoursite.com' . "\r\n" .
               'Reply-To: ' . $verification['email'] . "\r\n" .
               'X-Mailer: PHP/' . phpversion();
    
    mail($admin_email, $subject, $message, $headers);
}

function sendUserConfirmation($verification) {
    $user_email = $verification['email'];
    $subject = 'Payment Verification Submitted - ' . $verification['id'];
    
    $message = "
    Dear {$verification['name']},
    
    Thank you for submitting your payment verification request.
    
    Verification Details:
    - Verification ID: {$verification['id']}
    - Amount: ₹{$verification['amount']}
    - Transaction ID: {$verification['transaction_id']}
    - Status: Pending verification
    
    We will verify your payment and contact you within 24 hours.
    
    If you have any questions, please reply to this email.
    
    Best regards,
    Your Payment Team
    ";
    
    $headers = 'From: noreply@yoursite.com' . "\r\n" .
               'Reply-To: support@yoursite.com' . "\r\n" .
               'X-Mailer: PHP/' . phpversion();
    
    mail($user_email, $subject, $message, $headers);
}
?> 