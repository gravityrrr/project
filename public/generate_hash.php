<?php
// Set the plain text password you want to hash
$password = 'ssss';

// Generate the hash
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Output the result
echo "Hashed password for 'ssss':<br><code>$hashedPassword</code>";
$pass_check = password_verify($password, "$2y$10$PDFABkoPYmchvddN3hn1Du8WmuDyg2rxFykjxNwDvx5LoH7s1MLg");
echo("\n" . $pass_check. "\n");
?>