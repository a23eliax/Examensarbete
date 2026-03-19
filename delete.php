<?php
// delete.php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$index = $data['index'] ?? null;

$productsFile = "products.json";

if ($index === null || !file_exists($productsFile)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Ogiltigt index eller ingen produkter"]);
    exit;
}

$products = json_decode(file_get_contents($productsFile), true);

// Ta bort bilden från uploads
if (isset($products[$index])) {
    $imagePath = $products[$index]['image'];
    if (file_exists($imagePath)) unlink($imagePath);

    // Ta bort produkten från arrayen
    array_splice($products, $index, 1);

    // Spara tillbaka JSON
    file_put_contents($productsFile, json_encode($products));

    echo json_encode(["success" => true]);
    exit;
}

http_response_code(400);
echo json_encode(["success" => false, "message" => "Produkten hittades inte"]);
?>
