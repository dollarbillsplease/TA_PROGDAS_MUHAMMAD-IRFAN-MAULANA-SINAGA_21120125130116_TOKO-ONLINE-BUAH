<?php
session_start();
header('Content-Type: application/json'); // Tetap gunakan JSON untuk respon

// --- 1. Ambil Data (Akses Aman) ---
// Perbaikan: Menggunakan coalesce operator (??) untuk menghindari 'Trying to access array offset on value of type null'
$saldo_user = $_SESSION['saldo'] ?? 0;
$cart       = $_SESSION['cart'] ?? [];
$total_pembelian = isset($_POST['total_pembelian']) ? (int)$_POST['total_pembelian'] : 0; 
// Anda mungkin perlu mengambil nama dan lokasi juga, tapi kita fokus ke saldo

// --- 2. Logika Pemrosesan Transaksi ---

// Cek apakah ada proses checkout yang dilakukan (melalui tombol Bayar)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {

    if ($total_pembelian <= 0) {
        echo json_encode(["status" => "error", "message" => "Total pembelian tidak valid."]);
        exit;
    }

    if (empty($cart)) {
        echo json_encode(["status" => "error", "message" => "Keranjang kosong. Tidak ada yang dibeli."]);
        exit;
    }

    // --- Cek Saldo ---
    if ($saldo_user < $total_pembelian) {
        // Jika saldo tidak cukup, kirim error dan tidak lanjutkan transaksi
        echo json_encode([
            "status" => "error", 
            "message" => "Saldo Anda (Rp " . number_format($saldo_user) . ") tidak cukup untuk transaksi senilai Rp " . number_format($total_pembelian) . "."
        ]);
        exit;
    }

    // --- Proses Transaksi Berhasil ---
    
    // 1. Kurangi Saldo
    $_SESSION['saldo'] = $saldo_user - $total_pembelian;
    
    // 2. Simpan Struk/Data Transaksi (Opsional, tergantung kebutuhan Anda)
    // Di sini Anda bisa menyimpan data pembelian ke $_SESSION['last_transaction']
    $_SESSION['last_transaction'] = [
        'items' => $cart,
        'total' => $total_pembelian,
        'tanggal' => date('Y-m-d H:i:s')
    ];
    
    // 3. Kosongkan Keranjang
    unset($_SESSION['cart']);
    
    // 4. Kirim Respon Sukses dan Alihkan (Redirect)
    // Karena Anda mem-posting data menggunakan form HTML biasa, lebih baik lakukan redirect:
    
    header("Location: struk.php?status=success");
    exit;
    
} else {
    // Jika diakses tanpa POST atau tanpa tombol 'checkout'
    echo json_encode(["status" => "error", "message" => "Akses tidak sah."]);
    exit;
}
?>