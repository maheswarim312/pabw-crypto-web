<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Vision</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
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
        <div class="search-bar">
            <input type="text" id="input-pencarian" placeholder="Masukkan kode koin ex: BTC">
            <button id="tombol-pencarian">Submit</button>
        </div>
        <div id="konten-default" class="default-content">
            <h2>Selamat datang di Crypto Vision</h2>
            <p>Masukkan kode koin pada kotak pencarian untuk melihat informasi detail mengenai cryptocurrency favorit
                Anda.</p>
            <p>Contoh: BTC untuk Bitcoin, ETH untuk Ethereum, dll.</p>
        </div>
        <div id="detail-crypto" class="crypto-info" style="display:none;">
            <div class="crypto-header">
                <img id="logo" src="" alt="Crypto Logo">
                <div>
                    <h1 id="nama"></h1>
                    <p id="deskripsi"></p>
                </div>
            </div>
            <div class="crypto-details">
                <p id="tgl-lahir"></p>
                <p id="harga"></p>
                <p id="market-cap"></p>
                <p id="volume"></p>
                <p id="perubahan"></p>
            </div>
            <div class="social-links">
                <a id="website" href="" target="_blank"><img src="assets/website.png" alt="Website"></a>
                <a id="twitter" href="" target="_blank"><img src="assets/twitter_icon.png" alt="Twitter"></a>
                <a id="source-code" href="" target="_blank"><img src="assets/source_code.png" alt="Source Code"></a>
                <a id="chat" href="" target="_blank"><img src="assets/chat.png" alt="Chat"></a>
                <a id="dok-teknis" href="" target="_blank"><img src="assets/technical_doc.png" alt="Technical Doc"></a>
            </div>
            <!-- TradingView Widget BEGIN -->
            <div id="widget-container" class="tradingview-widget-container" style="display:none;">
                <div id="tradingview_charts"></div>
                <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
            </div>
            <!-- TradingView Widget END -->
        </div>
        <div id="pesan-error" class="error-message">Kode koin tidak ditemukan. Silakan coba lagi.</div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('tombol-pencarian').addEventListener('click', () => {
                const simbol = document.getElementById('input-pencarian').value.trim().toUpperCase();
                if (simbol) {
                    document.getElementById('konten-default').style.display = 'none';
                    ambilDataCrypto(simbol);
                    updateWidgetTradingView(simbol);
                }
            });
        });

        async function ambilDataCrypto(simbol) {
            const apiKey = 'b49141f7-287a-45a9-9c16-e7b0a20f746e';
            const urlMetaData = `https://pro-api.coinmarketcap.com/v1/cryptocurrency/info?symbol=${simbol}`;
            const urlQuotes = `https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?symbol=${simbol}&convert=IDR`;

            try {
                const [responsMetadaData, responsQuotes] = await Promise.all([
                    fetch(urlMetaData, { headers: { 'X-CMC_PRO_API_KEY': apiKey } }),
                    fetch(urlQuotes, { headers: { 'X-CMC_PRO_API_KEY': apiKey } })
                ]);

                const dataMetaData = await responsMetadaData.json();
                const dataQuotes = await responsQuotes.json();

                if (dataMetaData.status.error_code === 0 && dataQuotes.status.error_code === 0) {
                    tampilkanDetailCrypto(dataMetaData.data[simbol], dataQuotes.data[simbol]);
                    document.getElementById('pesan-error').style.display = 'none';
                } else {
                    console.error('Error fetching data:', dataMetaData.status.error_message || dataQuotes.status.error_message);
                    document.getElementById('detail-crypto').style.display = 'none';
                    document.getElementById('pesan-error').style.display = 'block';
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('detail-crypto').style.display = 'none';
                document.getElementById('pesan-error').style.display = 'block';
            }
        }

        function tampilkanDetailCrypto(coin, quote) {
            document.getElementById('logo').src = coin.logo;
            document.getElementById('nama').textContent = `${coin.name} (${coin.symbol})`;
            document.getElementById('deskripsi').textContent = coin.description;
            document.getElementById('tgl-lahir').textContent = `Ada sejak: ${coin.date_launched || 'N/A'}`;
            document.getElementById('harga').textContent = `Harga: Rp${quote.quote.IDR.price.toLocaleString('id-ID')}`;
            document.getElementById('market-cap').textContent = `Market Cap: Rp${quote.quote.IDR.market_cap.toLocaleString('id-ID')}`;
            document.getElementById('volume').textContent = `Volume (24j): Rp${quote.quote.IDR.volume_24h.toLocaleString('id-ID')}`;
            document.getElementById('perubahan').textContent = `Perubahan (24j): ${quote.quote.IDR.percent_change_24h.toFixed(2)}%`;

            document.getElementById('website').href = coin.urls.website ? coin.urls.website[0] : '#';
            document.getElementById('twitter').href = coin.urls.twitter ? coin.urls.twitter[0] : '#';
            document.getElementById('source-code').href = coin.urls.source_code ? coin.urls.source_code[0] : '#';
            document.getElementById('chat').href = coin.urls.chat ? coin.urls.chat[0] : '#';
            document.getElementById('dok-teknis').href = coin.urls.technical_doc ? coin.urls.technical_doc[0] : '#';

            document.getElementById('detail-crypto').style.display = 'flex';
        }

        function updateWidgetTradingView(simbol) {
            document.getElementById('widget-container').style.display = 'block';
            document.getElementById('tradingview_charts').innerHTML = '';
            new TradingView.widget({
                "width": 980,
                "height": 610,
                "symbol": `BINANCE:${simbol}USDT`,
                "interval": "D",
                "timezone": "Asia/Jakarta",
                "theme": "light",
                "style": "1",
                "locale": "en",
                "toolbar_bg": "#f1f3f6",
                "enable_publishing": false,
                "allow_symbol_change": true,
                "container_id": "tradingview_charts"
            });
        }
    </script>
</body>

</html>