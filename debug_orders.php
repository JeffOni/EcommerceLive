<?php
// Script simple para verificar las órdenes

// Realizar conexión directa a la BD
$host = 'localhost';
$dbname = 'ecommercelive';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Consultar las últimas 5 órdenes
    $sql = "SELECT o.id, o.user_id, o.total, o.status, o.pdf_path, o.shipping_address, o.created_at, u.name as user_name 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC 
            LIMIT 5";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "=== ÚLTIMAS 5 ÓRDENES ===\n";
    foreach ($orders as $order) {
        echo "ID: {$order['id']}\n";
        echo "Usuario: {$order['user_name']}\n";
        echo "Total: {$order['total']}\n";
        echo "Status: {$order['status']}\n";
        echo "PDF Path: " . ($order['pdf_path'] ?? 'NO EXISTE') . "\n";
        echo "Shipping Address: " . ($order['shipping_address'] ?? 'NO EXISTE') . "\n";
        echo "Creado: {$order['created_at']}\n";
        echo "---\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
