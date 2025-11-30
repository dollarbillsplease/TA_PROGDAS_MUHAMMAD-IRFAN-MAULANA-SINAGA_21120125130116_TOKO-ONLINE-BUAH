<?php

session_start();
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cart_count += $qty;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Toko Buah Online</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2 class="title">Toko Buah Online</h2>

<div class="product-grid">
    <?php foreach ($products as $p): ?>
        <div class="product-card">
            <img src="<?= $p['gambar']; ?>" alt="">
            <h3><?= $p['nama']; ?></h3>
            <div class="price">Rp <?= number_format($p['harga']); ?></div>

            <form method="POST" action="cart_process.php">
                <input type="hidden" name="product_id" value="<?= $p['id']; ?>">
                <button 
                    class="add-btn" 
                    type="submit" 
                    name="add_to_cart">
                    +
                </button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<a href="cart.php" class="cart-btn">
    🛒 Keranjang <span id="cartCount"><?= $cart_count; ?></span>
</a>

</body>
</html>