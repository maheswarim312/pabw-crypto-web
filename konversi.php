<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Vision</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .highlight {
            text-align: center;
            margin-top: 50px;
        }
        .conversion-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .conversion-input, .conversion-select, .conversion-button {
            padding: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Crypto Vision</div>
        <nav>
            <a href="beranda.php">Home</a>
            <a href="konversi.php">Konversi Currency</a>
            <a href="info.php">Info Currency</a>
            <a href="portofolio.php">Portofolio</a>
        </nav>
        <div class="menu-toggle">☰</div>
    </header>
<main>
    <div class="highlight">
        <h2>Konversi Harga</h2>
        <div class="conversion-container">
            <input type="number" id="jumlah-konversi" placeholder="Jumlah" class="conversion-input">
            <select id="dari-mata-uang" class="conversion-select">
                <option value="BTC">BTC</option>
                <option value="ETH">ETH</option>
                <option value="USD">USD</option>
                <option value="IDR">IDR</option>
            </select>
            <span class="conversion-label">ke</span>
            <select id="ke-mata-uang" class="conversion-select">
                <option value="USD">USD</option>
                <option value="BTC">BTC</option>
                <option value="ETH">ETH</option>
                <option value="IDR">IDR</option>
            </select>
            <button onclick="konversiHarga()" class="conversion-button">Konversi</button>
        </div>
        <div id="hasil-konversi" class="konversi-hasil"></div>
    </div>
</main>

<script>
    const apiKey = 'cb538ba5-9fda-4545-8adc-c4046cc91589'; 

    async function fetchExchangeRates() {
        const url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'X-CMC_PRO_API_KEY': apiKey,
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }

        const data = await response.json();
        const rates = {
            BTC: { USD: null, ETH: null, IDR: null },
            ETH: { USD: null, BTC: null, IDR: null },
            USD: { BTC: null, ETH: null, IDR: null },
            IDR: { BTC: null, ETH: null, USD: null }
        };

        const btcData = data.data.find(crypto => crypto.symbol === 'BTC');
        const ethData = data.data.find(crypto => crypto.symbol === 'ETH');

        if (btcData) {
            rates.BTC.USD = btcData.quote.USD.price;
        }

        if (ethData) {
            rates.ETH.USD = ethData.quote.USD.price;
        }

        if (btcData && ethData) {
            rates.BTC.ETH = btcData.quote.USD.price / ethData.quote.USD.price;
            rates.ETH.BTC = ethData.quote.USD.price / btcData.quote.USD.price;
        }

        const usdToIdrRate = 14500; // Set manual rate for USD to IDR
        rates.USD.IDR = usdToIdrRate;
        rates.IDR.USD = 1 / usdToIdrRate;

        if (btcData && usdToIdrRate) {
            rates.BTC.IDR = btcData.quote.USD.price * usdToIdrRate;
            rates.IDR.BTC = 1 / (btcData.quote.USD.price * usdToIdrRate);
        }
        if (ethData && usdToIdrRate) {
            rates.ETH.IDR = ethData.quote.USD.price * usdToIdrRate;
            rates.IDR.ETH = 1 / (ethData.quote.USD.price * usdToIdrRate);
        }

        rates.USD.BTC = 1 / btcData.quote.USD.price;
        rates.USD.ETH = 1 / ethData.quote.USD.price;

        return rates;
    }

    async function konversiHarga() {
        const jumlah = parseFloat(document.getElementById('jumlah-konversi').value);
        const dariMataUang = document.getElementById('dari-mata-uang').value;
        const keMataUang = document.getElementById('ke-mata-uang').value;
        const hasilKonversi = document.getElementById('hasil-konversi');

        if (isNaN(jumlah) || jumlah <= 0) {
            hasilKonversi.innerHTML = 'Masukkan jumlah yang valid.';
            return;
        }

        if (dariMataUang === keMataUang) {
            hasilKonversi.innerHTML = 'Pilih mata uang yang berbeda untuk konversi.';
            return;
        }

        try {
            const nilaiTukar = await fetchExchangeRates();
            const nilai = nilaiTukar[dariMataUang][keMataUang];
            const jumlahDikonversi = jumlah * nilai;

            hasilKonversi.innerHTML = `<p>${jumlah} ${dariMataUang} = ${jumlahDikonversi.toFixed(2)} ${keMataUang}</p>`;
        } catch (error) {
            hasilKonversi.innerHTML = 'Terjadi kesalahan saat mengambil data nilai tukar.';
        }
    }
</script>
</body>
</html>
