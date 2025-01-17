<?php
$host = 'localhost'; // ou o endereço do seu servidor
$db = 'rafael_db11'; // nome do seu banco de dados
$user = 'rafael_db11'; // seu usuário do banco de dados
$pass = 'JNKUJ2Go$w#WzpU43Y'; // sua senha do banco de dados

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Conexão falhou: " . $e->getMessage();
}
?>
