document.addEventListener('DOMContentLoaded', function() {
    const apiKey = 'cb538ba5-9fda-4545-8adc-c4046cc91589';
    const url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
    const headers = {
        'Accept': 'application/json',
        'X-CMC_PRO_API_KEY': apiKey
    };
    const limit = 100;

    let portfolio = [];

    function fetchData(page = 1, sortBy = 'market_cap') {
        const parameters = {
            start: '1',
            limit: limit.toString(),
            convert: 'IDR'
        };

        const qs = new URLSearchParams(parameters).toString();
        const requestUrl = `${url}?${qs}`;

        fetch(requestUrl, { method: 'GET', headers: headers })
            .then(response => response.json())
            .then(data => {
                const sortedData = sortData(data.data, sortBy);
                populateTable(sortedData);
            })
            .catch(error => console.error('Terjadi kesalahan ', error));
    }

    function sortData(data, sortBy) {
        switch (sortBy) {
            case 'name':
                return data.sort((a, b) => a.name.localeCompare(b.name));
            case 'price':
                return data.sort((a, b) => b.quote.IDR.price - a.quote.IDR.price);
            case 'percent_change_24h':
                return data.sort((a, b) => b.quote.IDR.percent_change_24h - a.quote.IDR.percent_change_24h);
            case 'volume_24h':
                return data.sort((a, b) => b.quote.IDR.volume_24h - a.quote.IDR.volume_24h);
            default:
                return data;
        }
    }

    function populateTable(data) {
        const cryptoTableBody = document.getElementById('crypto-table-body');
        cryptoTableBody.innerHTML = '';

        data.forEach((coin, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${index + 1}</td>
                <td><img src="https://s2.coinmarketcap.com/static/img/coins/64x64/${coin.id}.png" alt="${coin.name}" class="crypto-logo"> ${coin.name} (${coin.symbol})</td>
                <td>${parseFloat(coin.quote.IDR.price).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
                <td><button data-coin-id="${coin.id}" data-coin-name="${coin.name}" data-coin-symbol="${coin.symbol}" data-coin-price="${coin.quote.IDR.price}">Beli</button></td>
            `;
            cryptoTableBody.appendChild(row);
        });

        document.querySelectorAll('button[data-coin-id]').forEach(button => {
            button.addEventListener('click', addToPortfolio);
        });
    }

    function addToPortfolio(event) {
        const button = event.target;
        const coinId = button.getAttribute('data-coin-id');
        const coinName = button.getAttribute('data-coin-name');
        const coinSymbol = button.getAttribute('data-coin-symbol');
        const coinPrice = parseFloat(button.getAttribute('data-coin-price'));

        const amount = prompt(`Berapa banyak ${coinName} yang ingin Anda beli?`);

        if (amount && !isNaN(amount)) {
            portfolio.push({ coinId, coinName, coinSymbol, coinPrice, amount: parseFloat(amount) });
            updatePortfolio();
            updateTradingViewWidget();
        } else {
            alert('Masukkan jumlah yang valid.');
        }
    }

    function updatePortfolio() {
        const portfolioBody = document.getElementById('portfolio-body');
        portfolioBody.innerHTML = '';

        portfolio.forEach(coin => {
            const totalValue = coin.amount * coin.coinPrice;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><img src="https://s2.coinmarketcap.com/static/img/coins/64x64/${coin.coinId}.png" alt="${coin.coinName}" class="crypto-logo"> ${coin.coinName} (${coin.coinSymbol})</td>
                <td>${coin.amount}</td>
                <td>${coin.coinPrice.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
                <td>${totalValue.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
            `;
            portfolioBody.appendChild(row);
        });
    }

    function updateTradingViewWidget() {
        const widgetContainer = document.getElementById('tradingview-widget-container');
        widgetContainer.innerHTML = '';

        portfolio.forEach(coin => {
            const widgetDiv = document.createElement('div');
            widgetDiv.id = `tradingview_${coin.coinSymbol}`;
            widgetContainer.appendChild(widgetDiv);

            new TradingView.MediumWidget({
                "symbols": [[`${coin.coinSymbol}IDR|12M`]],
                "chartOnly": true,
                "width": "100%",
                "height": "400",
                "locale": "id",
                "colorTheme": "light",
                "gridLineColor": "rgba(240, 243, 250, 0.06)",
                "trendLineColor": "#2962FF",
                "fontColor": "#787B86",
                "underLineColor": "rgba(41, 98, 255, 0.3)",
                "isTransparent": false,
                "autosize": true,
                "container_id": `tradingview_${coin.coinSymbol}`
            });
        });
    }

    document.getElementById('search-btn').addEventListener('click', () => {
        const searchInput = document.getElementById('search-input').value.toLowerCase();
        fetchData(1, searchInput);
    });

    document.getElementById('sort-options').addEventListener('change', () => {
        fetchData(1, document.getElementById('sort-options').value);
    });

    fetchData();
});
