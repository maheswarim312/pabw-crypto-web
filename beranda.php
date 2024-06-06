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
            <a href="watchlist.php">Watchlist</a>
            <a href="konversi.php">Konversi Currency</a>
            <a href="info.php">Info Currency</a>
            <a href="portofolio.php">Portofolio</a>
        </nav>
        <div class="menu-toggle">☰</div>
    </header>
    <main>
        <section class="highlight">
            <h2><center>Sorotan Crypto</center></h2>
            <p><center>Mata uang crypto manakah yang memiliki volume tertinggi, kenaikan tertinggi, dan penurunan terendah?</center></p>
            <div class="highlight-boxes">
                <div class="box">
                    <h3>Volume tertinggi</h3>
                    <ul id="highest-volume"></ul>
                </div>
                <div class="box">
                    <h3>Penurunan terendah</h3>
                    <ul id="lowest-drop"></ul>
                </div>
                <div class="box">
                    <h3>Kenaikan tertinggi</h3>
                    <ul id="highest-rise"></ul>
                </div>
            </div>
        </section>

        <section class="crypto-list">
            <h2><center>Cari cryptocurrency yang ingin anda pantau</center></h2>
            <div class="search-monitor">
                <button id="compareBtn" class="btn btn-primary mb-3">Compare</button>
                <input type="text" id="search-input" placeholder="Cari cryptocurrency...">
                <button id="search-btn">Cari</button>
                <select id="sort-options">
                    <option value="name">Nama</option>
                    <option value="price">Harga</option>
                    <option value="percent_change_24h">Perubahan 24j</option>
                    <option value="volume_24h">Volume 24j</option>
                </select>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th></th>
                        <th>Koin</th>
                        <th>Harga</th>
                        <th>1j</th>
                        <th>24j</th>
                        <th>7h</th>
                        <th>Volume 24J</th>
                    </tr>
                </thead>
                <tbody id="crypto-table-body"></tbody>
            </table>
            <div class="pagination">
                <a href="#" data-page="1">1</a>
                <a href="#" data-page="2">2</a>
                <a href="#" data-page="3">3</a>
                <a href="#" data-page="4">4</a>
                <a href="#" data-page="5">5</a>
            </div>
        </section>
    </main>
    <footer>
        <img src="assets/telkom.png" alt="Telkom University">
        <div class="credits">
            <p>Dibuat oleh:</p>
            <p>Raihan Muhamad Syawal</p>
            <p>Maheswari Maharani Mahfud</p>
            <p>Salma Nida Ul Jannah</p>
        </div>
    </footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
            const limit = 100;

            const headers = {
                'Accept': 'application/json',
                'X-CMC_PRO_API_KEY': "cb538ba5-9fda-4545-8adc-c4046cc91589"
            };

            let top5Highlights = null;

            function fetchData(page = 1, sortBy = 'market_cap') {
                const start = 1
                const parameters = {
                    start: start.toString(),
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

                        if (!top5Highlights) {
                            top5Highlights = sortedData;
                            populateHighlights(top5Highlights);
                        }
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
                        return data; // Default sorting by API's order (e.g., market cap)
                }
            }

            function populateTable(data) {
                const cryptoTableBody = document.getElementById('crypto-table-body');
                cryptoTableBody.innerHTML = ''; // Clear existing rows

                data.forEach((coin, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td><input type="checkbox" name="${coin.symbol}" value="${coin.symbol}"></td>
                        <td><img src="https://s2.coinmarketcap.com/static/img/coins/64x64/${coin.id}.png" alt="${coin.name}" class="crypto-logo"> ${coin.name} (${coin.symbol})</td>
                        <td>${parseFloat(coin.quote.IDR.price).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
                        <td class="${coin.quote.IDR.percent_change_1h >= 0 ? 'up' : 'down'}">${parseFloat(coin.quote.IDR.percent_change_1h).toFixed(2)}%</td>
                        <td class="${coin.quote.IDR.percent_change_24h >= 0 ? 'up' : 'down'}">${parseFloat(coin.quote.IDR.percent_change_24h).toFixed(2)}%</td>
                        <td class="${coin.quote.IDR.percent_change_7d >= 0 ? 'up' : 'down'}">${parseFloat(coin.quote.IDR.percent_change_7d).toFixed(2)}%</td>
                        <td>${parseFloat(coin.quote.IDR.volume_24h).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
                        <td><button class="watchlist-btn" data-coin='${JSON.stringify(coin)}'>+</button></td>
                    `;
                    cryptoTableBody.appendChild(row);
                });

                document.querySelectorAll('.watchlist-btn').forEach(button => {
                    button.addEventListener('click', (event) => {
                        const coin = JSON.parse(event.target.getAttribute('data-coin'));
                        addToWatchlist(coin);
                    });
    });
            }

            function addToWatchlist(coin) {
                let watchlist = JSON.parse(localStorage.getItem('watchlist')) || [];
                if (!watchlist.find(c => c.id === coin.id)) {
                    watchlist.push(coin);
                    localStorage.setItem('watchlist', JSON.stringify(watchlist));
                    alert(`${coin.name} telah ditambahkan ke dalam watchlist`);
                } else {
                    alert(`${coin.name} telah ada didalam watchlist`);
                }
            }

            function populateHighlights(data) {
                const highestVolumeList = document.getElementById('highest-volume');
                const lowestDropList = document.getElementById('lowest-drop');
                const highestRiseList = document.getElementById('highest-rise');

                highestVolumeList.innerHTML = '';
                lowestDropList.innerHTML = '';
                highestRiseList.innerHTML = '';

                let highestVolumeCoins = [...data].sort((a, b) => b.quote.IDR.volume_24h - a.quote.IDR.volume_24h).slice(0, 5);
                let highestRiseCoins = [...data].sort((a, b) => b.quote.IDR.percent_change_7d - a.quote.IDR.percent_change_7d).slice(0, 5);
                let lowestDropCoins = [...data].sort((a, b) => a.quote.IDR.percent_change_7d - b.quote.IDR.percent_change_7d).slice(0, 5);

                highestVolumeCoins.forEach(coin => {
                    const listItem = document.createElement('li');
                    listItem.innerHTML = `
                        <img src="https://s2.coinmarketcap.com/static/img/coins/64x64/${coin.id}.png" alt="${coin.name}" class="crypto-logo">
                        <b>${coin.name}</b> (${coin.symbol}) - ${parseFloat(coin.quote.IDR.volume_24h).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}
                    `;
                    highestVolumeList.appendChild(listItem);
                });

                highestRiseCoins.forEach(coin => {
                    const listItem = document.createElement('li');
                    listItem.innerHTML = `
                        <img src="https://s2.coinmarketcap.com/static/img/coins/64x64/${coin.id}.png" alt="${coin.name}" class="crypto-logo">
                        <b>${coin.name}</b> (${coin.symbol}) - ${parseFloat(coin.quote.IDR.price).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })} - ↑${parseFloat(coin.quote.IDR.percent_change_7d).toFixed(2)}%
                    `;
                    highestRiseList.appendChild(listItem);
                });

                lowestDropCoins.forEach(coin => {
                    const listItem = document.createElement('li');
                    listItem.innerHTML = `
                        <img src="https://s2.coinmarketcap.com/static/img/coins/64x64/${coin.id}.png" alt="${coin.name}" class="crypto-logo">
                        <b>${coin.name}</b> (${coin.symbol}) - ${parseFloat(coin.quote.IDR.price).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })} - ↓${parseFloat(coin.quote.IDR.percent_change_7d).toFixed(2)}%
                    `;
                    lowestDropList.appendChild(listItem);
                });
            }

            function searchCrypto() {
                const searchInput = document.getElementById('search-input').value.toLowerCase();
                const parameters = {
                    start: '1',
                    limit: '100', // Search in a large dataset to find matches
                    convert: 'IDR'
                };

                const qs = new URLSearchParams(parameters).toString();
                const requestUrl = `${url}?${qs}`;

                fetch(requestUrl, { method: 'GET', headers: headers })
                    .then(response => response.json())
                    .then(data => {
                        const filteredData = data.data.filter(coin => coin.name.toLowerCase().includes(searchInput) || coin.symbol.toLowerCase().includes(searchInput));
                        const sortBy = document.getElementById('sort-options').value;
                        const sortedData = sortData(filteredData, sortBy);
                        populateTable(sortedData);
                    })
                    .catch(error => console.error('Terjadi kesalahan ', error));
            }

            document.getElementById('search-btn').addEventListener('click', searchCrypto);
            document.getElementById('sort-options').addEventListener('change', () => fetchData(1, document.getElementById('sort-options').value));
            document.querySelectorAll('.pagination a').forEach(paginationLink => {
                paginationLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = parseInt(this.getAttribute('data-page'));
                    fetchData(page, document.getElementById('sort-options').value);
                });
            });

            document.getElementById('compareBtn').addEventListener('click', () => {
                const selectedSymbols = Array.from(document.querySelectorAll('input[type="checkbox"]:checked')).map(checkbox => checkbox.value);
                const symbolsQuery = selectedSymbols.join(',');
                const compareUrl = `comparedetail.php?coin=${symbolsQuery}`;
                window.open(compareUrl, '_blank');
            });

            fetchData();
        });
    </script>
</body>
</html>
