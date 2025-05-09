<?php
// Include database connection
require_once 'db_connection.php';

// Initialize response array
$response = array(
    'status' => false,
    'message' => 'An error occurred'
);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate form data
    if (empty($name) || empty($phone) || empty($email) || empty($message)) {
        $response['message'] = "❗ Semua field wajib diisi.";
    } 
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "❗ Format email tidak valid.";
    }
    elseif (strlen($message) > 200) {
        $response['message'] = "❗ Pesan terlalu panjang. Maksimal 200 karakter.";
    } 
    else {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO inquiries (name, phone, email, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $phone, $email, $message);
        
        // Execute statement
        if ($stmt->execute()) {
            $response['status'] = true;
            $response['message'] = "✅ Pesan berhasil disimpan di database!";
        } else {
            $response['message'] = "❗ Error: " . $stmt->error;
        }
        
        // Close statement
        $stmt->close();
    }
}

// Return JSON response for AJAX requests
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// For direct form submissions, redirect back to the form with status
if (isset($response['status'])) {
    header("Location: index.html?status=" . ($response['status'] ? 'success' : 'error') . "&message=" . urlencode($response['message']));
    exit;
}

// Close database connection
$conn->close();
?>