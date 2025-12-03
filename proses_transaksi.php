<?php
session_start();
header('Content-Type: application/json');



$saldo_user = $_SESSION['saldo'] ?? 0;
$cart       = $_SESSION['cart'] ?? [];
$total_pembelian = isset($_POST['total_pembelian']) ? (int)$_POST['total_pembelian'] : 0; 
$user_id = 1; 


if (!isset($_SESSION['order_queue'])) {
    $_SESSION['order_queue'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {

    
    if ($saldo_user < $total_pembelian) {
        
        exit;
    }


    $new_order = [
        'order_id' => time() . mt_rand(100, 999), 
        'user_id' => $user_id,
        'items' => $cart,
        'total' => $total_pembelian,
        'status' => 'Pending',
        'tanggal' => date('Y-m-d H:i:s')
    ];
    
        array_push($_SESSION['order_queue'], $new_order); 
    
    $_SESSION['last_transaction'] = $new_order;

    $_SESSION['saldo'] = $saldo_user - $total_pembelian;
    
    unset($_SESSION['cart']);
    
    header("Location: struk.php?status=success");
    exit;
} else {
    exit;
}
?>