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
        <section class="crypto-list">
            <h2><center>Watchlist</center></h2>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Koin</th>
                        <th>Harga</th>
                        <th>1j</th>
                        <th>24j</th>
                        <th>7h</th>
                        <th>Volume 24J</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="watchlist-table-body"></tbody>
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
        const watchlist = JSON.parse(localStorage.getItem('watchlist')) || [];

        function populateWatchlist() {
            const watchlistTableBody = document.getElementById('watchlist-table-body');
            watchlistTableBody.innerHTML = ''; // Hapus baris yang ada

            watchlist.forEach((coin, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td><img src="https://s2.coinmarketcap.com/static/img/coins/64x64/${coin.id}.png" alt="${coin.name}" class="crypto-logo"> ${coin.name} (${coin.symbol})</td>
                    <td>${parseFloat(coin.quote.IDR.price).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
                    <td class="${coin.quote.IDR.percent_change_1h >= 0 ? 'up' : 'down'}">${parseFloat(coin.quote.IDR.percent_change_1h).toFixed(2)}%</td>
                    <td class="${coin.quote.IDR.percent_change_24h >= 0 ? 'up' : 'down'}">${parseFloat(coin.quote.IDR.percent_change_24h).toFixed(2)}%</td>
                    <td class="${coin.quote.IDR.percent_change_7d >= 0 ? 'up' : 'down'}">${parseFloat(coin.quote.IDR.percent_change_7d).toFixed(2)}%</td>
                    <td>${parseFloat(coin.quote.IDR.volume_24h).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
                    <td><button class="delete-btn" data-coin-id="${coin.id}">Delete</button></td>
                `;
                watchlistTableBody.appendChild(row);
            });

            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', (event) => {
                    const coinId = event.target.getAttribute('data-coin-id');
                    removeFromWatchlist(coinId);
                    event.target.parentElement.parentElement.remove(); // Hapus baris dari DOM
                });
            });
        }

        function removeFromWatchlist(coinId) {
            let watchlist = JSON.parse(localStorage.getItem('watchlist')) || [];
            watchlist = watchlist.filter(coin => coin.id != coinId);
            localStorage.setItem('watchlist', JSON.stringify(watchlist));
        }

        populateWatchlist();
    });
</script>

</body>
</html>
