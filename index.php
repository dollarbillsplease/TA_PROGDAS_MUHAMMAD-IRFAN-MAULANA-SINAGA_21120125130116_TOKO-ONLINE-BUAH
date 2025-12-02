<?php 

session_start();


$products = [
    ["name" => "Apel", "price" => 6000, "image" => "apples.png"],
    ["name" => "Pisang", "price" => 8000, "image" => "bananas.png"],
    ["name" => "Pir", "price" => 7000, "image" => "pears.png"],
    ["name" => "Jeruk", "price" => 9000, "image" => "oranges.png"],
    ["name" => "Mangga", "price" => 12000, "image" => "mangoes.png"],
    ["name" => "Melon", "price" => 15000, "image" => "melons.png"],
    ["name" => "Semangka", "price" => 14000, "image" => "watermelons.png"],
    ["name" => "Kiwi", "price" => 20000, "image" => "kiwis.png"],
    ["name" => "Anggur", "price" => 25000, "image" => "grapes.png"],
    ["name" => "Nanas", "price" => 10000, "image" => "pineapples.png"],
    ["name" => "Pepaya", "price" => 11000, "image" => "papayas.png"],
    ["name" => "Blueberry", "price" => 30000, "image" => "blueberries.png"],
    ["name" => "Stroberi", "price" => 28000, "image" => "strawberries.png"],
    ["name" => "Delima", "price" => 22000, "image" => "pomegranates.png"],
    ["name" => "Kelapa", "price" => 15000, "image" => "coconuts.png"]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Toko Buah Online</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

body {
    font-family: Arial, sans-serif;
    margin:0;
    padding:0;
    overflow-x:hidden;
}


.navbar {
    background: #f7c59f;
    padding:15px;
    font-size:22px;
    font-weight:bold;
    display:flex;
    justify-content:space-between;
    align-items:center;
    backdrop-filter: blur(3px);
    position:relative;
    z-index:20;
}


#toggleAkun { display:none; }

.akun-btn {
    cursor:pointer;
    font-size:20px;
    background:white;
    padding:8px 16px;
    border-radius:12px;
    box-shadow:0 2px 5px rgba(0,0,0,0.2);
}

.akun-popup {
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.5);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:9999;
}

.akun-box {
    background:white;
    width:320px;
    padding:20px;
    border-radius:12px;
    animation:fadeIn .3s;
}

#toggleAkun:checked ~ .akun-popup { display:flex; }

.akun-box input {
    width:100%;
    padding:8px;
    margin-bottom:12px;
    border-radius:6px;
    border:1px solid #aaa;
}

.save-btn {
    width:100%;
    background:#86c8bc;
    border:none;
    padding:10px;
    color:white;
    border-radius:8px;
    cursor:pointer;
}


.static-image-container {
    width: 100%;
    height: 350px; 
    overflow: hidden; 
    display: flex; 
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
}

.static-image-container img {
    width: 100%; 
    height: 100%; 
    object-fit: cover; 
    display: block;
}

.container {
    padding:20px;
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap:20px;
}

.product {
    background: rgba(255,255,255,0.85);
    padding:15px;
    border-radius:12px;
    text-align:center;
    box-shadow:0 2px 5px rgba(0,0,0,0.1);
    backdrop-filter: blur(3px);
}

.product img {
    width:100%;
    height:160px;
    object-fit:cover;
    border-radius:10px;
    margin-bottom:10px;
}

.btn {
    background:#86c8bc;
    border:none;
    padding:8px 12px;
    border-radius:8px;
    cursor:pointer;
    margin:5px;
}


.cart-panel {
    position:fixed;
    right:20px;
    bottom:20px;
    background:white;
    padding:15px;
    border-radius:12px;
    box-shadow:0 2px 10px rgba(0,0,0,0.2);
    cursor:pointer;
    z-index:30;
}

    </style>
</head>

<body>

<input type="checkbox" id="toggleAkun">

<div class="navbar">
    Toko Buah Fresh
    <label for="toggleAkun" class="akun-btn">👤 Akun</label>
</div>

<div class="static-image-container">
    <img src="images/Slider5.png" alt="Promo Buah Segar">
</div>

<div class="akun-popup">
    <div class="akun-box">
        <h2>Data Akun</h2>
        <form method="POST" action="simpan_akun.php">
            <input type="text" name="nama" placeholder="Nama anda..." required>
            <input type="text" name="lokasi" placeholder="Lokasi..." required>
            <input type="number" name="saldo" placeholder="Saldo (Rp)" required>
            <button class="save-btn">Simpan</button>
        </form>

        <label for="toggleAkun" style="cursor:pointer; display:block; margin-top:15px;">Tutup</label>
    </div>
</div>

<div class="container" id="product-list">
<?php 

foreach ($products as $index => $p) {
?>
    <div class='product'>
        <img src="images/<?php echo $p['image']; ?>">
        <h3><?php echo $p['name']; ?></h3>
        <p>Harga: Rp <?php echo number_format($p['price'], 0, ',', '.'); ?></p>
        
        <form method="POST" action="cart_process.php">
            <input type="hidden" name="product_index" value="<?php echo $index; ?>">
            <button class="btn" type="submit" name="add_to_cart">Tambah ➕</button>
        </form>
    </div>
<?php 
}
?>
</div>

<a href="cart.php" class="cart-panel" style="text-decoration:none; color:inherit;">🛒 Buka Keranjang</a>

</body>
</html>