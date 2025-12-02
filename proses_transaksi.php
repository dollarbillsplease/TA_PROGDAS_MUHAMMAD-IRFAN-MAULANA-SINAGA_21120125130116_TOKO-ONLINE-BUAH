<?php
session_start();
header('Content-Type: application/json'); 


$saldo_user = $_SESSION['saldo'] ?? 0;
$cart       = $_SESSION['cart'] ?? [];
$total_pembelian = isset($_POST['total_pembelian']) ? (int)$_POST['total_pembelian'] : 0; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {

    if ($total_pembelian <= 0) {
        echo json_encode(["status" => "error", "message" => "Total pembelian tidak valid."]);
        exit;
    }

    if (empty($cart)) {
        echo json_encode(["status" => "error", "message" => "Keranjang kosong. Tidak ada yang dibeli."]);
        exit;
    }

    
    if ($saldo_user < $total_pembelian) {
        
        echo json_encode([
            "status" => "error", 
            "message" => "Saldo Anda (Rp " . number_format($saldo_user) . ") tidak cukup untuk transaksi senilai Rp " . number_format($total_pembelian) . "."
        ]);
        exit;
    }

    
    $_SESSION['saldo'] = $saldo_user - $total_pembelian;
    
    
    $_SESSION['last_transaction'] = [
        'items' => $cart,
        'total' => $total_pembelian,
        'tanggal' => date('Y-m-d H:i:s')
    ];
    
    
    unset($_SESSION['cart']);
    
    
    header("Location: struk.php?status=success");
    exit;
    
} else {
    
    echo json_encode(["status" => "error", "message" => "Akses tidak sah."]);
    exit;
}
?>