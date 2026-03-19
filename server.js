const express = require('express');
const multer = require('multer');
const upload = multer({ dest: 'uploads/' });

const app = express();

app.post('/upload', upload.single('image'), (req, res) => {
    const { title, price } = req.body;
    const image = `/uploads/${req.file.filename}`; // URL till bilden
    // Spara produkten i en databas eller en array
    res.json({ message: 'Produkt uppladdad!' });
});


/* ---------- Bildupload ---------- */

app.post("/upload", upload.single("image"), async (req, res) => {

    const outputPath = `/images/${req.file.filename}.webp`;

    await sharp(req.file.path)
        .resize(800)
        .webp({ quality: 75 })
        .toFile(outputPath);

    fs.unlinkSync(req.file.path); // ta bort original

    const product = {
        title: req.body.title,
        price: req.body.price,
        image: `/images/${req.file.filename}.webp`
    };

    let products = [];

    if (fs.existsSync("products.json")) {
        products = JSON.parse(fs.readFileSync("products.json"));
    }

    products.push(product);

    fs.writeFileSync("products.json", JSON.stringify(products, null, 2));

    res.send("Produkt upplagd!");
});

/* ---------- Hämta produkter ---------- */

app.get("/products", (req, res) => {

    if (!fs.existsSync("products.json"))
        return res.json([]);

    const data = JSON.parse(fs.readFileSync("products.json"));
    res.json(data);
});

app.listen(3000, () =>
    console.log("Server kör på http://localhost:8000")
);
