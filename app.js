const form = document.getElementById("form");

form.addEventListener("submit", async e => {
    e.preventDefault();

    const data = new FormData(form);
    console.log("Skickar data:", [...data.entries()]);


    try {
    const response = await fetch("/upload", {
        method: "POST",
        body: data
    });

    if (!response.ok) {
        console.error("Upload misslyckades:", response.status, response.statusText);
        return; // stoppar om det går fel
    }

    console.log("Upload klar!");
} catch (err) {
    console.error("Fel vid upload:", err);
    return;
}


    loadProducts();
});

async function loadProducts() {
    try {
        const res = await fetch("products.php");
        if (!res.ok) throw new Error("Kunde inte hämta produkter");

        const products = await res.json();
        const container = document.getElementById("products");
        container.innerHTML = "";

        products.forEach((p, index) => {
            container.innerHTML += `
                <div class="card">
                    <img src="${p.image}" loading="lazy" alt="${p.title}">
                    <h3>${p.title}</h3>
                    <p>${p.price} SEK</p>
                    <button onclick="deleteProduct(${index})">Ta bort</button>
                </div>
            `;
        });
    } catch (err) {
        console.error(err);
        document.getElementById("products").innerHTML = "<p>Misslyckades med att ladda produkter.</p>";
    }
}

