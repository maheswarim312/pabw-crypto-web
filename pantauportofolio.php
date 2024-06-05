<?php
$coins = isset($_GET['coins']) ? $_GET['coins'] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Portofolio</title>
    <style>
        .container {
            width: 90%;
            margin: 0 auto;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .grid-item {
            border: 1px solid #ccc;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background-color: #f9f9f9;
        }
        .suggestion {
            font-size: 1.2em;
            margin-top: 10px;
        }
        .ticker-tape {
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Monitor Portofolio</h2>
        <div class="grid-container">
            <!-- TradingView Widgets BEGIN -->
            <?php foreach ($coins as $coinData): list($coin, $amount) = explode(',', $coinData); ?>
                <div class="grid-item">
                    <h3><?php echo strtoupper($coin); ?></h3>
                    <p>Jumlah: <?php echo $amount; ?></p>

                    <div class="tradingview-widget-container">
                        <div id="tradingview-widget-<?php echo $coin; ?>"></div>
                        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-symbol-overview.js" async>
                        {
                            "symbols": [
                                [
                                    "BINANCE:<?php echo strtoupper($coin); ?>USD"
                                ]
                            ],
                            "width": "100%",
                            "height": "150",
                            "locale": "en",
                            "colorTheme": "light",
                            "isTransparent": false,
                            "autosize": true,
                            "showVolume": true,
                            "showMA": true,
                            "hideDateRanges": false,
                            "hideMarketStatus": false,
                            "hideSymbolLogo": false,
                            "largeChartUrl": ""
                        }
                        </script>
                    </div>

                    <div class="tradingview-widget-container">
                        <div id="technical-analysis-widget-<?php echo $coin; ?>"></div>
                        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-technical-analysis.js" async>
                        {
                            "interval": "1m",
                            "width": "100%",
                            "isTransparent": false,
                            "height": "300",
                            "symbol": "BINANCE:<?php echo strtoupper($coin); ?>USD",
                            "showIntervalTabs": true,
                            "locale": "en",
                            "colorTheme": "light"
                        }
                        </script>
                    </div>

                    <div class="tradingview-widget-container">
                        <div id="tradingview-widget-chart-<?php echo $coin; ?>"></div>
                        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-mini-symbol-overview.js" async>
                        {
                            "symbol": "BINANCE:<?php echo strtoupper($coin); ?>USD",
                            "width": "100%",
                            "height": "400",
                            "locale": "en",
                            "dateRange": "1D",
                            "colorTheme": "light",
                            "trendLineColor": "rgba(41, 98, 255, 1)",
                            "underLineColor": "rgba(41, 98, 255, 0.3)",
                            "isTransparent": false,
                            "autosize": true,
                            "largeChartUrl": ""
                        }
                        </script>
                    </div>

                    <div class="suggestion" id="suggestion-<?php echo $coin; ?>">
                        <!-- Saran jual atau tidak akan ditampilkan di sini -->
                    </div>

                    <script>
                        const apiKey = 'cb538ba5-9fda-4545-8adc-c4046cc91589';
                        const coin = '<?php echo strtoupper($coin); ?>';
                        const amount = <?php echo $amount; ?>;

                        fetch(`https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?symbol=${coin}&CMC_PRO_API_KEY=${apiKey}`)
                            .then(response => response.json())
                            .then(data => {
                                const price = data.data[coin].quote.USD.price;
                                const totalValue = price * amount;
                                const suggestionElement = document.getElementById(`suggestion-${coin}`);

                                if (price > 10000) { // Misalnya, saran jual jika harga lebih dari 10,000 USD
                                    suggestionElement.textContent = `Harga saat ini adalah ${price.toFixed(2)} USD. Pertimbangkan untuk menjual coin Anda.`;
                                } else {
                                    suggestionElement.textContent = `Harga saat ini adalah ${price.toFixed(2)} USD. Pertahankan coin Anda.`;
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    </script>
                </div>
            <?php endforeach; ?>
            <!-- TradingView Widgets END -->
        </div>
    </div>

    <!-- Ticker Tape Widget -->
    <div class="ticker-tape">
        <div class="tradingview-widget-container">
            <div class="tradingview-widget-container__widget"></div>
            <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
            {
                "symbols": [
                    { "proName": "BINANCE:BTCUSD", "title": "Bitcoin" },
                    { "proName": "BINANCE:ETHUSD", "title": "Ethereum" },
                    { "proName": "BINANCE:XRPUSD", "title": "Ripple" },
                    { "proName": "BINANCE:ADAUSD", "title": "Cardano" },
                    { "proName": "BINANCE:SOLUSD", "title": "Solana" }
                ],
                "showSymbolLogo": true,
                "colorTheme": "dark",
                "isTransparent": true,
                "displayMode": "adaptive",
                "locale": "en"
            }
            </script>
        </div>
    </div>
</body>
</html>
