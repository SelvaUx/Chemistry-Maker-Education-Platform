<?php
// test_hash.php
$password = 'password';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Password: " . $password . "\n";
echo "Hash: " . $hash . "\n";

if (password_verify($password, $hash)) {
    echo "VERIFY: SUCCESS\n";
} else {
    echo "VERIFY: FAILED\n";
}

// Test against manual hash just in case
// $2y$10$w... is standard bcrypt
?>
