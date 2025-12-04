<?php
session_start();


$products_data = [
    0 => ["name" => "Apel", "price" => 6000],
    1 => ["name" => "Pisang", "price" => 8000],
    2 => ["name" => "Pir", "price" => 7000],
    3 => ["name" => "Jeruk", "price" => 9000],
    4 => ["name" => "Mangga", "price" => 12000],
    5 => ["name" => "Melon", "price" => 15000],
    6 => ["name" => "Semangka", "price" => 14000],
    7 => ["name" => "Kiwi", "price" => 20000],
    8 => ["name" => "Anggur", "price" => 25000],
    9 => ["name" => "Nanas", "price" => 10000],
    10 => ["name" => "Pepaya", "price" => 11000],
    11 => ["name" => "Blueberry", "price" => 30000],
    12 => ["name" => "Stroberi", "price" => 28000],
    13 => ["name" => "Delima", "price" => 22000],
    14 => ["name" => "Kelapa", "price" => 15000]
];


$transaction = $_SESSION['last_transaction'] ?? null;
$user_name = $_SESSION['nama'] ?? "Nama Tidak Ada";
$user_lokasi = $_SESSION['lokasi'] ?? "Lokasi Tidak Ada";

if(!$transaction){
    echo "Tidak ada riwayat transaksi yang tersimpan!";
    exit;
}


$items_bought = $transaction['items'];
$total_bayar = $transaction['total']; // Ini adalah Total Akhir (Subtotal + PPN 11%)
$tanggal_transaksi = $transaction['tanggal'];
$id_transaksi = time(); 

// --- PERHITUNGAN PPN (11%) ---
$ppn_rate = 0.11;
// Subtotal = Total Akhir / (1 + Rate PPN)
$subtotal = $total_bayar / (1 + $ppn_rate); 
// Nilai PPN = Total Akhir - Subtotal
$ppn_amount = $total_bayar - $subtotal;
// -----------------------------

// Juga hitung ulang subtotal item dari data produk untuk memastikan konsistensi
$subtotal_items_check = 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Struk Pembelian</title>
<style>
body{
    font-family: Arial;
    background:#f9f9f9;
}

.box{
    background:white;
    width:340px;
    margin:20px auto;
    padding:20px;
    border-radius:12px;
    box-shadow:0 2px 10px rgba(0,0,0,0.2);
}

.row {
    display:flex;
    justify-content:space-between;
    margin:8px 0;
}

.item-row {
    display: grid;
    grid-template-columns: 1fr 50px 80px; 
    gap: 5px;
    margin-bottom: 5px;
    font-size: 14px;
}

h2 { margin-bottom:5px; }
hr { margin:15px 0; }
</style>
</head>
<body>

<div class="box">

    <h2>STRUK PEMBAYARAN</h2>
    <p><?= $id_transaksi ?></p>
    <hr>

    <h3>Detail User:</h3>
    <div class="row">
        <span>Nama</span><span><?= htmlspecialchars($user_name) ?></span>
    </div>

    <div class="row">
        <span>Lokasi</span><span><?= htmlspecialchars($user_lokasi) ?></span>
    </div>
    <hr>
    
    <h3>Item Dibeli:</h3>
    <?php foreach ($items_bought as $product_id => $qty): 
        
        if (array_key_exists($product_id, $products_data)):
            $item_info = $products_data[$product_id];
            $subtotal_item = $item_info['price'] * $qty;
            $subtotal_items_check += $subtotal_item; // Hitung ulang subtotal barang
    ?>
        <div class="item-row">
            <span><?= $item_info['name'] ?></span>
            <span style="text-align: right;"><?= $qty ?>x</span>
            <span style="text-align: right;">Rp <?= number_format($subtotal_item) ?></span>
        </div>
    <?php 
        endif;
    endforeach; 
    ?>
    <hr>
    
    <div class="row">
        <span>Subtotal (Harga Barang)</span><span>Rp <?= number_format($subtotal) ?></span>
    </div>
    <div class="row">
        <span>PPN (11%)</span><span>Rp <?= number_format($ppn_amount) ?></span>
    </div>
    
    <div class="row" style="font-weight: bold; margin-top: 15px;">
        <span>TOTAL PEMBAYARAN</span><span>Rp <?= number_format($total_bayar) ?></span>
    </div>
    <div class="row" style="margin-top: 15px;">
        <span>Tanggal</span><span><?= htmlspecialchars($tanggal_transaksi) ?></span>
    </div>
    
    <hr>
    <p style="text-align: center; font-size: 12px; color: #555;">Terima kasih telah berbelanja!</p>

</div>

</body>
</html>