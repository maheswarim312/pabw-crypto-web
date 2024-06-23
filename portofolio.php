<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Portfolio</title>
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
        <div id="konten-default" class="portofolio-content">
            <h2>Portofolio Crypto</h2>
            <h3>Total Aset: <span id="totalAssets">Rp 0</span></h3>
            <button id="addTransactionBtn" class="btn-tambah">+ Add Transaction</button>
            <p id="symbolsList"></p>
            <table id="transactionTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Harga</th>
                        <th>24h%</th>
                        <th>7d%</th>
                        <th>Dimiliki</th>
                        <th>Harga Beli</th>
                        <th>Profit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Transaction rows will be added here -->
                </tbody>
            </table>
            <button id="monitorBtn">Pantau</button>
        </div>
    </main>

    <!-- Coin Selection Modal -->
    <div id="coinSelectionModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeCoinSelectionModal">&times;</span>
            <h2>Select Coin</h2>
            <input type="text" id="searchCoin" placeholder="Search">
            <ul id="coinList">
                <!-- Coin list will be populated here -->
            </ul>
        </div>
    </div>

    <!-- Transaction Detail Modal -->
    <div id="transactionDetailModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeTransactionDetailModal">&times;</span>
            <h2 id="selectedCoin"></h2>
            <form id="transactionForm">
                <div class="input-group">
                    <input type="number" id="quantity" name="quantity" placeholder="Quantity" min="0" step="any">
                    <input type="number" id="price" name="price" placeholder="Price Per Coin" min="0" step="any">
                </div>
                <div id="total" class="total">Total: Rp 0</div>
                <button type="submit" class="btn-tambah">Add Transaction</button>
            </form>
        </div>
    </div>

    <script src="scripts_porto.js"></script>
</body>
</html>
