<!DOCTYPE html>
<html>
<head>
    <title>Brädspelsmarknaden</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        
    </header>

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

<div id="popup" class="popup">
  <div class="popup-content">
    <span id="closePopup">&times;</span>
    <img id="popupImage">
    <h2 id="popupTitle"></h2>
    <p><strong style="font-size: large;">Pris:</strong> <span id="popupPrice"></span></p>
    <p><strong>Beskrivning:</strong> <span id="popupDescription"></span></p>
    <p><strong>Antal spelare:</strong> <span id="popupPlayers"></span></p>
    <p><strong>Speltid (min):</strong> <span id="popupTime"></span></p>
    <p><strong>Rek. ålder:</strong> <span id="popupAge"></span></p>    
  </div>
</div>

<script>
// JavaScript för att skicka formulär och ladda produkter
const form = document.getElementById("form");

form.addEventListener("submit", async e => {
    e.preventDefault();

    const data = new FormData(form);

    try {
        const response = await fetch("upload.php", {
            method: "POST",
            body: data
        });

        if (!response.ok) throw new Error("Upload misslyckades");

        loadProducts();
        form.reset(); // töm formuläret efter uppladdning
    } catch (err) {
        console.error(err);
        alert("Något gick fel vid uppladdning!");
    }
});

async function loadProducts() {
    try {
        const res = await fetch("products.php");
        if (!res.ok) throw new Error("Kunde inte hämta produkter");

        const products = await res.json();
        const container = document.getElementById("products");
        container.innerHTML = "";

        products.forEach((p, index) => {
            const card = document.createElement("div");
            card.className = "card";

            card.innerHTML = `
                <img src="${p.image}" loading="lazy" alt="${p.title}">
                <h3>${p.title}</h3>
                <p>${p.price} SEK</p>
            `;
            card.addEventListener("click", () => {
                document.getElementById("popupImage").src = p.image;
                document.getElementById("popupTitle").textContent = p.title;
                document.getElementById("popupPrice").textContent = p.price + " SEK";
                document.getElementById("popupDescription").textContent = p.description;
                document.getElementById("popupPlayers").textContent = p.players;
                document.getElementById("popupTime").textContent = p.time;
                document.getElementById("popupAge").textContent = p.age;
                document.getElementById("popup").style.display = "block";
            });

            document.getElementById("closePopup").onclick = () => {
                document.getElementById("popup").style.display = "none";
            };

            const btn = document.createElement("button");
            btn.textContent = "Ta bort";
            btn.className = "delete-btn";  // <-- lägg till klass här
            // bind rätt index via arrow function
            btn.addEventListener("click", async () => {
                if (!confirm("Är du säker på att du vill ta bort produkten?")) return;

                try {
                    const res = await fetch("delete.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ index })
                    });

                    const result = await res.json();
                    if (result.success) {
                        loadProducts(); // ladda om produkter
                    } else {
                        alert("Kunde inte ta bort produkten: " + (result.message || ""));
                    }
                } catch (err) {
                    console.error(err);
                    alert("Fel vid borttagning!");
                }
            });

            card.appendChild(btn);
            container.appendChild(card);
        });

    } catch (err) {
        console.error(err);
        document.getElementById("products").innerHTML = "<p>Misslyckades med att ladda produkter.</p>";
    }
}

// Ladda produkter när sidan öppnas
loadProducts();


</script>

</body>
<footer>
    <span>
        Alla bilder i detta examensarbete är tagna av författaren och används enbart i utbildningssyfte,
        och inget material kommer att säljas eller användas kommersiellt.
    </span>
</footer>
</html>
