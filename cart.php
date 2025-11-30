<?php
session_start();

// 1. DATA PRODUK (HARUS SAMA PERSIS dengan index.php dan cart_process.php)
// Indeks array (0, 1, 2...) adalah ID produk.
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


// 2. Ambil data user dari Session
$nama   = $_SESSION['nama'] ?? "User";
$lokasi = $_SESSION['lokasi'] ?? "-";
$saldo  = $_SESSION['saldo'] ?? 0;

// 3. Ambil data keranjang dari Session
$cart = $_SESSION['cart'] ?? [];
$cart_items_display = [];
$grand_total = 0;

// 4. Hitung Total dan Siapkan Data Tampilan
if (!empty($cart)) {
    // Kunci $product_id di $cart adalah indeks (0, 1, 2, dst.)
    foreach ($cart as $product_id => $qty) {
        
        // Cek apakah ID produk yang tersimpan di session ada di array produk
        if (array_key_exists($product_id, $products_data)) {
            $item = $products_data[$product_id];
            $subtotal = $item['price'] * $qty;
            $grand_total += $subtotal;
            
            $cart_items_display[] = [
                'id' => $product_id, // Gunakan ID ini untuk tombol +/-
                'name' => $item['name'],
                'price' => $item['price'],
                'qty' => $qty,
                'subtotal' => $subtotal
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>

    <link rel="stylesheet" href="css/style.css">

    <style>
        .cart-container {
            width: 70%;
            margin: 30px auto;
            background: rgba(255, 255, 255, 0.85);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            backdrop-filter: blur(4px);
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center; 
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .qty-form {
            display: inline-block;
        }

        .qty-btn {
            padding: 4px 10px;
            background: #a0d8ff;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            margin: 0 5px;
            border: none; 
            font-size: 14px;
        }

        #checkoutBtn {
            margin-top: 20px;
            background: #ffb37d;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            border: none;
            font-size: 18px;
            width: 100%; 
        }

        .saldo-box {
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: bold;
        }
        
        .item-info {
            width: 40%;
        }

    </style>
</head>
<body>

    <div class="bg-blur"></div>
    <div class="cart-container">
        <h2>Keranjang Belanja</h2>

        <div class="saldo-box">
            Saldo Anda: <span style="color:green;">Rp <?= number_format($saldo, 0, ',', '.') ?></span>
        </div>

        <div id="cart-items">
            <?php if (empty($cart_items_display)): ?>
                <p>Keranjang masih kosong...</p>
            <?php else: ?>
                
                <?php foreach ($cart_items_display as $item): ?>
                <div class="cart-item">
                    <div class="item-info">
                        <strong><?= $item['name'] ?></strong><br>
                        Rp <?= number_format($item['price'], 0, ',', '.') ?>
                    </div>

                    <div style="width: 60%; text-align: right;">
                        
                        <form method="POST" action="cart_process.php" class="qty-form">
                            <input type="hidden" name="product_id" value="<?= $item['id']; ?>">
                            <button class="qty-btn" type="submit" name="action" value="decrease">-</button>
                        </form>
                        
                        <?= $item['qty'] ?>
                        
                        <form method="POST" action="cart_process.php" class="qty-form">
                            <input type="hidden" name="product_id" value="<?= $item['id']; ?>">
                            <button class="qty-btn" type="submit" name="action" value="increase">+</button>
                        </form>
                        
                        <span style="display: inline-block; min-width: 80px;">
                            Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
                
            <?php endif; ?>
        </div>

        <h3>Total: <span id="cart-total">Rp <?= number_format($grand_total, 0, ',', '.') ?></span></h3>
        
        <?php if ($grand_total > 0): ?>
            <form method="POST" action="proses_transaksi.php">
                <input type="hidden" name="total_pembelian" value="<?= $grand_total; ?>">
                <button id="checkoutBtn" type="submit" name="checkout">Bayar</button>
            </form>
        <?php else: ?>
             <button id="checkoutBtn" disabled style="background:#ccc;">Bayar</button>
        <?php endif; ?>

    </div>
</body>
</html>