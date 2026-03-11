<!DOCTYPE html>
<html>
<head>
    <title>Brädspelsmarknaden</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Sälj ditt brädspel</h1>

<form id="form">
    <input name="title" placeholder="Spelnamn" required>
    <input name="price" placeholder="Pris" required>

    <textarea name="description" placeholder="Beskrivning av spelet"></textarea>

    <input name="players" placeholder="Antal spelare">
    <input name="time" placeholder="Speltid (min)">
    <input name="age" placeholder="Rekommenderad ålder">

    <input type="file" name="image" required>

    <button id="post-btn">Lägg upp</button>
</form>

<h2>Produkter</h2>
<div id="products"></div>

