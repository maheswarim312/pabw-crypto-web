document.addEventListener("DOMContentLoaded", function() {
    const addTransactionBtn = document.getElementById('addTransactionBtn');
    const coinSelectionModal = document.getElementById('coinSelectionModal');
    const closeCoinSelectionModal = document.getElementById('closeCoinSelectionModal');
    const transactionDetailModal = document.getElementById('transactionDetailModal');
    const closeTransactionDetailModal = document.getElementById('closeTransactionDetailModal');
    const coinList = document.getElementById('coinList');
    const selectedCoin = document.getElementById('selectedCoin');
    const transactionForm = document.getElementById('transactionForm');
    const transactionTable = document.getElementById('transactionTable').getElementsByTagName('tbody')[0];
    const searchCoinInput = document.getElementById('searchCoin');
    const totalDisplay = document.getElementById('total');
    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('price');
    const totalAssetsDisplay = document.getElementById('totalAssets');

    let coins = [];
    let currentCoin = null;
    let transactions = JSON.parse(localStorage.getItem('transactions')) || [];

    addTransactionBtn.onclick = function() {
        fetchCoins();
        coinSelectionModal.style.display = "block";
    }

    closeCoinSelectionModal.onclick = function() {
        coinSelectionModal.style.display = "none";
    }

    closeTransactionDetailModal.onclick = function() {
        transactionDetailModal.style.display = "none";
    }

    async function fetchCoins() {
        try {
            const apiKey = 'b49141f7-287a-45a9-9c16-e7b0a20f746e';
            const response = await fetch('https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest?convert=IDR', {
                method: 'GET',
                headers: {
                    'X-CMC_PRO_API_KEY': apiKey,
                }
            });
            const data = await response.json();
            coins = data.data;
            displayCoins(coins);
        } catch (error) {
            console.error('Error fetching coins:', error);
        }
    }

    function displayCoins(coins) {
        coinList.innerHTML = '';
        coins.forEach(coin => {
            let listItem = document.createElement('li');
            listItem.innerHTML = `<img src="https://s2.coinmarketcap.com/static/img/coins/64x64/${coin.id}.png" alt="${coin.name}" class="crypto-logo"> ${coin.name} (${coin.symbol})`;
            listItem.onclick = function() {
                selectCoin(coin);
            }
            coinList.appendChild(listItem);
        });
    }

    searchCoinInput.onkeyup = function() {
        const searchQuery = searchCoinInput.value.toLowerCase();
        const filteredCoins = coins.filter(coin => coin.name.toLowerCase().includes(searchQuery) || coin.symbol.toLowerCase().includes(searchQuery));
        displayCoins(filteredCoins);
    }

    function selectCoin(coin) {
        currentCoin = coin;
        selectedCoin.textContent = `${coin.name} (${coin.symbol})`;
        document.getElementById('quantity').value = '';
        document.getElementById('price').value = coin.quote.IDR.price;
        coinSelectionModal.style.display = "none";
        transactionDetailModal.style.display = "block";
        updateTotal();
    }

    function updateTotal() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const total = quantity * price;
        totalDisplay.textContent = `Total: ${total.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}`;
    }

    quantityInput.oninput = updateTotal;
    priceInput.oninput = updateTotal;

    transactionForm.onsubmit = function(event) {
        event.preventDefault();
        const quantity = parseFloat(quantityInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const total = quantity * price;
        const profit = (currentCoin.quote.IDR.price - price) * quantity;

        const transaction = {
            id: currentCoin.id,
            name: currentCoin.name,
            symbol: currentCoin.symbol,
            currentPrice: currentCoin.quote.IDR.price,
            percentChange24h: currentCoin.quote.IDR.percent_change_24h,
            percentChange7d: currentCoin.quote.IDR.percent_change_7d,
            quantity: quantity,
            purchasePrice: price,
            profit: profit
        };

        transactions.push(transaction);
        localStorage.setItem('transactions', JSON.stringify(transactions));
        addTransactionToTable(transaction);
        updateTotalAssets();

        transactionDetailModal.style.display = "none";
    }

    function addTransactionToTable(transaction) {
        let newRow = transactionTable.insertRow();
        newRow.innerHTML = `
            <td><img src="https://s2.coinmarketcap.com/static/img/coins/64x64/${transaction.id}.png" alt="${transaction.name}" class="crypto-logo"> ${transaction.name} (${transaction.symbol})</td>
            <td>${parseFloat(transaction.currentPrice).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
            <td>${transaction.percentChange24h}%</td>
            <td>${transaction.percentChange7d}%</td>
            <td>${transaction.quantity}</td>
            <td>${transaction.purchasePrice.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
            <td>${transaction.profit.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
            <td><button class="deleteBtn">Delete</button></td>
        `;

        newRow.querySelector('.deleteBtn').onclick = function() {
            const rowIndex = newRow.rowIndex - 1;
            transactions.splice(rowIndex, 1);
            localStorage.setItem('transactions', JSON.stringify(transactions));
            transactionTable.deleteRow(rowIndex);
            updateTotalAssets();
        };
    }

    function loadTransactions() {
        transactions.forEach(transaction => {
            addTransactionToTable(transaction);
        });
        updateTotalAssets();
    }

    function updateTotalAssets() {
        const totalAssets = transactions.reduce((sum, transaction) => sum + (transaction.currentPrice * transaction.quantity), 0);
        totalAssetsDisplay.textContent = totalAssets.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
    }

    loadTransactions();

    let storedTransactions = localStorage.getItem('transactions');
    let symbols = []; 

    if (storedTransactions) {
        let transactions = JSON.parse(storedTransactions);
        symbols = transactions.map(transaction => transaction.symbol);
    }

    // Event listener untuk tombol monitor
    document.getElementById('monitorBtn').addEventListener('click', () => {
        const symbolsString = symbols.join(','); 
        const monitorUrl = `pantauportofolio.php?coin=${symbolsString}`;
        window.open(monitorUrl, '_blank');
    });


});
