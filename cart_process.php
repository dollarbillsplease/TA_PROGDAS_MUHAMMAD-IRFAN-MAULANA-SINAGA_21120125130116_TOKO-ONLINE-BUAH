<?php
session_start();


$products_data = [
    0 => ["name" => "Apel", "price" => 6000, "image" => "apples.png"],
    1 => ["name" => "Pisang", "price" => 8000, "image" => "bananas.png"],
    2 => ["name" => "Pir", "price" => 7000, "image" => "pears.png"],
    3 => ["name" => "Jeruk", "price" => 9000, "image" => "oranges.png"],
    4 => ["name" => "Mangga", "price" => 12000, "image" => "mangoes.png"],
    5 => ["name" => "Melon", "price" => 15000, "image" => "melons.png"],
    6 => ["name" => "Semangka", "price" => 14000, "image" => "watermelons.png"],
    7 => ["name" => "Kiwi", "price" => 20000, "image" => "kiwis.png"],
    8 => ["name" => "Anggur", "price" => 25000, "image" => "grapes.png"],
    9 => ["name" => "Nanas", "price" => 10000, "image" => "pineapples.png"],
    10 => ["name" => "Pepaya", "price" => 11000, "image" => "papayas.png"],
    11 => ["name" => "Blueberry", "price" => 30000, "image" => "blueberries.png"],
    12 => ["name" => "Stroberi", "price" => 28000, "image" => "strawberries.png"],
    13 => ["name" => "Delima", "price" => 22000, "image" => "pomegranates.png"],
    14 => ["name" => "Kelapa", "price" => 15000, "image" => "coconuts.png"]
];


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    
    $product_index = isset($_POST['product_index']) ? (int)$_POST['product_index'] : null;
    
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;
    $action = $_POST['action'] ?? null;
    
    
    $id_to_process = $product_index !== null ? $product_index : $product_id;

    
    if ($id_to_process !== null && array_key_exists($id_to_process, $products_data)) {
        
        
        if (isset($_POST['add_to_cart'])) {
            
            if (!isset($_SESSION['cart'][$id_to_process])) {
                $_SESSION['cart'][$id_to_process] = 1;
            } else {
                $_SESSION['cart'][$id_to_process]++;
            }
            
            header("Location: index.php");
            exit;
        }

        
        if ($action) {
            
            if ($action === 'increase') {
                
                $_SESSION['cart'][$id_to_process] = ($_SESSION['cart'][$id_to_process] ?? 0) + 1;
                
            } elseif ($action === 'decrease') {
               
                if (isset($_SESSION['cart'][$id_to_process])) {
                    $_SESSION['cart'][$id_to_process]--;
                    
                    
                    if ($_SESSION['cart'][$id_to_process] < 1) {
                        unset($_SESSION['cart'][$id_to_process]);
                    }
                }
            }
            
            
            header("Location: cart.php");
            exit;
        }

    }
    
} 


header("Location: index.php");
exit;
?>