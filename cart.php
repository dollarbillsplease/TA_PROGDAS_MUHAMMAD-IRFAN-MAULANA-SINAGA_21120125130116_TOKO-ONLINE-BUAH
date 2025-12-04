<?php
session_start();

function calculateVAT(float $total_price): float {
    $vat_rate = 0.11;
    return $total_price * $vat_rate;
}

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


$nama  = $_SESSION['nama'] ?? "User";
$lokasi = $_SESSION['lokasi'] ?? "-";
$saldo = $_SESSION['saldo'] ?? 0;


$cart = $_SESSION['cart'] ?? [];
$cart_items_display = [];
$grand_total = 0;


if (!empty($cart)) {
    
    foreach ($cart as $product_id => $qty) {
        
        
        if (array_key_exists($product_id, $products_data)) {
            $item = $products_data[$product_id];
            $subtotal = $item['price'] * $qty;
            $grand_total += $subtotal;
            
            $cart_items_display[] = [
                'id' => $product_id, 
                'name' => $item['name'],
                'price' => $item['price'],
                'qty' => $qty,
                'subtotal' => $subtotal
            ];
        }
    }
}

$vat_amount = calculateVAT($grand_total);
$total_after_vat = $grand_total + $vat_amount;

$is_balance_enough = $total_after_vat <= $saldo;
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
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            border: none;
            font-size: 18px;
            width: 100%; 
        }

        #checkoutBtn:not([disabled]) {
            background: #ffb37d;
        }

        #checkoutBtn[disabled] {
            background: #ccc;
            cursor: not-allowed;
        }

        .saldo-box {
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: bold;
        }
        
        .item-info {
            width: 40%;
        }

        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
            padding: 8px;
            border: 1px solid red;
            background: #ffe6e6;
            border-radius: 5px;
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

        <hr>
        <p>Subtotal (Harga Dasar): <span style="float:right;">Rp <?= number_format($grand_total, 0, ',', '.') ?></span></p>
        <p>PPN 11% (Dihitung dari Subtotal): <span style="float:right;">Rp <?= number_format($vat_amount, 0, ',', '.') ?></span></p>
        <h3>Total Akhir: <span id="cart-total" style="float:right;">Rp <?= number_format($total_after_vat, 0, ',', '.') ?></span></h3>
        <hr>
        
        <?php if ($total_after_vat > 0): ?>
            
            <?php if (!$is_balance_enough): ?>
                <p class="error-message">⚠️ Saldo kamu tidak mencukupi untuk melakukan pembayaran ini!</p>
                <button id="checkoutBtn" disabled>Bayar</button>
            <?php else: ?>
                <form method="POST" action="proses_transaksi.php">
                    <input type="hidden" name="total_pembelian" value="<?= $total_after_vat; ?>">
                    <button id="checkoutBtn" type="submit" name="checkout">Bayar</button>
                </form>
            <?php endif; ?>

        <?php else: ?>
             <button id="checkoutBtn" disabled>Bayar</button>
        <?php endif; ?>

    </div>
</body>
</html>