<?php
$koin = explode(",", $_GET['coin']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <title>Perbandingan Harga Koin</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="style.css">
</head>
<style>
    body {
      background: #ffff;
    }

    .container {
      width: 100%;
      height: 100%;
      display: flex;
    }

    .left-panel {
      width: 50%;
      height: 100%;
    }

    .right-panels {
      width: 50%;
      height: 100%;
      display: flex;
      flex-direction: column;
    }

    .right-panel {
      width: 100%;
      height: 50%;
    }
  </style>
  <script type="text/javascript">
    function zoom() {
      document.body.style.zoom = "100%"
    }
  </script>

<body onload="zoom()">
  <center>
    <h1>Perbandingan Harga Koin</h1>
  </center>

  <div class="container">
    <div class="left-panel">
      <!-- TradingView Widget BEGIN -->
      <div class="tradingview-widget-container">
        <div class="tradingview-widget-container__widget"></div>
        <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/" rel="noopener nofollow" target="_blank"><span class="blue-text"></span></a></div>
        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-symbol-overview.js" async>
          {
            "symbols": [
              [
                "Bitcoin",
                "BINANCE:<?php echo $koin[0] . 'USDT'; ?>"
              ]
            ],
            "chartOnly": true,
            "width": "100%",
            "height": "100%",
            "locale": "en",
            "colorTheme": "light",
            "autosize": true,
            "showVolume": false,
            "showMA": false,
            "hideDateRanges": false,
            "hideMarketStatus": true,
            "hideSymbolLogo": true,
            "scalePosition": "right",
            "scaleMode": "Percentage",
            "fontFamily": "-apple-system, BlinkMacSystemFont, Trebuchet MS, Roboto, Ubuntu, sans-serif",
            "fontSize": "10",
            "noTimeScale": false,
            "valuesTracking": "1",
            "changeMode": "price-only",
            "chartType": "line",
            "maLineColor": "#2962FF",
            "maLineWidth": 1,
            "maLength": 9,
            "lineWidth": 2,
            "lineType": 0,
            "compareSymbol": {
              "symbol": "BINANCE:<?php echo $koin[1] . 'USDT'; ?>",
              "lineColor": "rgba(41, 98, 255, 1)",
              "lineWidth": 2
            },
            "dateRanges": [
              "1w|15",
              "1m|60",
              "3m|60",
              "12m|1D"
            ],
            "bottomColor": "rgba(255, 255, 255, 0)",
            "dateFormat": "MMM dd, yyyy",
            "timeHoursFormat": "12-hours"
          }
        </script>
      </div>
      <!-- TradingView Widget END -->
    </div>

    <div class="right-panels">
      <div class="right-panel">
        <!-- TradingView Widget BEGIN -->
        <div class="tradingview-widget-container" style="height:100%;width:100%">
          <div class="tradingview-widget-container__widget" style="height:calc(100% - 32px);width:100%"></div>
          <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/" rel="noopener nofollow" target="_blank"><span class="blue-text"></span></a></div>
          <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js" async>
            {
              "autosize": true,
              "symbol": "BINANCE:<?php echo $koin[0] . 'USDT'; ?>",
              "interval": "D",
              "timezone": "Asia/Jakarta",
              "theme": "light",
              "style": "1",
              "locale": "en",
              "allow_symbol_change": true,
              "calendar": false,
              "support_host": "https://www.tradingview.com"
            }
          </script>
        </div>
        <!-- TradingView Widget END -->
      </div>

      <div class="right-panel">
        <!-- TradingView Widget BEGIN -->
        <div class="tradingview-widget-container" style="height:100%;width:100%">
          <div class="tradingview-widget-container__widget" style="height:calc(100% - 32px);width:100%"></div>
          <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/" rel="noopener nofollow" target="_blank"><span class="blue-text"></span></a></div>
          <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js" async>
            {
              "autosize": true,
              "symbol": "BINANCE:<?php echo $koin[1] . 'USDT'; ?>",
              "interval": "D",
              "timezone": "Asia/Jakarta",
              "theme": "light",
              "style": "1",
              "locale": "en",
              "allow_symbol_change": true,
              "calendar": false,
              "support_host": "https://www.tradingview.com"
            }
          </script>
        </div>
        <!-- TradingView Widget END -->
      </div>
    </div>
</body>
</html>


