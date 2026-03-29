<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $targetDir = "uploads/";
    if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

    // Kontrollera att filen finns
    if (!isset($_FILES['image'])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Ingen fil skickad"]);
        exit;
    }

    $tmpName = $_FILES['image']['tmp_name'];
    $originalName = basename($_FILES['image']['name']);
    $uploadFile = $targetDir . $originalName;

    // Kontrollera att filen är bild
    $imageInfo = getimagesize($tmpName);
    if ($imageInfo === false) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Filen är inte en bild"]);
        exit;
    }

    // Flytta filen till uploads-mappen
    if (!move_uploaded_file($tmpName, $uploadFile)) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Misslyckades med att spara bilden"]);
        exit;
    }

    // Spara produktinfo
    $productsFile = "products.json";
    $products = [];
    if (file_exists($productsFile)) {
        $products = json_decode(file_get_contents($productsFile), true);
    }

    $products[] = [
        "title" => $_POST["title"] ?? "",
        "price" => $_POST["price"] ?? "",
        "image" => $uploadFile,
        "description" => $_POST["description"] ?? "",
        "players" => $_POST["players"] ?? "",
        "time" => $_POST["time"] ?? "",
        "age" => $_POST["age"] ?? ""
    ];

    file_put_contents($productsFile, json_encode($products));

    echo json_encode(["success" => true]);

} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Endast POST tillåtet."]);
}
?>