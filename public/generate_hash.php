<?php
// Set the plain text password you want to hash
$password = 'admin123';

// Generate the hash
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Output the result
echo "Hashed password for 'admin123':<br><code>$hashedPassword</code>";
?>