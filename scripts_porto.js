document.addEventListener("DOMContentLoaded", function() {
    const tombolTambahTransaksi = document.getElementById('addTransactionBtn');
    const modalPilihanKoin = document.getElementById('coinSelectionModal');
    const tutupModalPilihanKoin = document.getElementById('closeCoinSelectionModal');
    const modalDetailTransaksi = document.getElementById('transactionDetailModal');
    const tutupModalDetailTransaksi = document.getElementById('closeTransactionDetailModal');
    const daftarKoin = document.getElementById('coinList');
    const koinTerpilih = document.getElementById('selectedCoin');
    const formulirTransaksi = document.getElementById('transactionForm');
    const tabelTransaksi = document.getElementById('transactionTable').getElementsByTagName('tbody')[0];
    const inputCariKoin = document.getElementById('searchCoin');
    const totalTampilan = document.getElementById('total');
    const inputKuantitas = document.getElementById('quantity');
    const inputHarga = document.getElementById('price');
    const totalAsetTampilan = document.getElementById('totalAssets');

    let koin = [];
    let koinSaatIni = null;
    let transaksi = JSON.parse(localStorage.getItem('transactions')) || [];

    tombolTambahTransaksi.onclick = function() {
        ambilKoin();
        modalPilihanKoin.style.display = "block";
    }

    tutupModalPilihanKoin.onclick = function() {
        modalPilihanKoin.style.display = "none";
    }

    tutupModalDetailTransaksi.onclick = function() {
        modalDetailTransaksi.style.display = "none";
    }

    async function ambilKoin() {
        try {
            const apiKey = 'b49141f7-287a-45a9-9c16-e7b0a20f746e';
            const response = await fetch('https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest?convert=IDR', {
                method: 'GET',
                headers: {
                    'X-CMC_PRO_API_KEY': apiKey,
                }
            });
            const data = await response.json();
            koin = data.data;
            tampilkanKoin(koin);
        } catch (error) {
            console.error('Error fetching coins:', error);
        }
    }

    function tampilkanKoin(koin) {
        daftarKoin.innerHTML = '';
        koin.forEach(koinItem => {
            let itemList = document.createElement('li');
            itemList.innerHTML = `<img src="https://s2.coinmarketcap.com/static/img/coins/64x64/${koinItem.id}.png" alt="${koinItem.name}" class="crypto-logo"> ${koinItem.name} (${koinItem.symbol})`;
            itemList.onclick = function() {
                pilihKoin(koinItem);
            }
            daftarKoin.appendChild(itemList);
        });
    }

    inputCariKoin.onkeyup = function() {
        const queryCari = inputCariKoin.value.toLowerCase();
        const koinTersaring = koin.filter(koinItem => koinItem.name.toLowerCase().includes(queryCari) || koinItem.symbol.toLowerCase().includes(queryCari));
        tampilkanKoin(koinTersaring);
    }

    function pilihKoin(koinItem) {
        koinSaatIni = koinItem;
        koinTerpilih.textContent = `${koinItem.name} (${koinItem.symbol})`;
        document.getElementById('quantity').value = '';
        document.getElementById('price').value = koinItem.quote.IDR.price;
        modalPilihanKoin.style.display = "none";
        modalDetailTransaksi.style.display = "block";
        perbaruiTotal();
    }

    function perbaruiTotal() {
        const kuantitas = parseFloat(inputKuantitas.value) || 0;
        const harga = parseFloat(inputHarga.value) || 0;
        const total = kuantitas * harga;
        totalTampilan.textContent = `Total: ${total.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}`;
    }

    inputKuantitas.oninput = perbaruiTotal;
    inputHarga.oninput = perbaruiTotal;

    formulirTransaksi.onsubmit = function(event) {
        event.preventDefault();
        const kuantitas = parseFloat(inputKuantitas.value) || 0;
        const harga = parseFloat(inputHarga.value) || 0;
        const total = kuantitas * harga;
        const keuntungan = (koinSaatIni.quote.IDR.price - harga) * kuantitas;

        const transaksiBaru = {
            id: koinSaatIni.id,
            name: koinSaatIni.name,
            symbol: koinSaatIni.symbol,
            currentPrice: koinSaatIni.quote.IDR.price,
            percentChange24h: koinSaatIni.quote.IDR.percent_change_24h,
            percentChange7d: koinSaatIni.quote.IDR.percent_change_7d,
            quantity: kuantitas,
            purchasePrice: harga,
            profit: keuntungan
        };

        transaksi.push(transaksiBaru);
        localStorage.setItem('transactions', JSON.stringify(transaksi));
        tambahkanTransaksiKeTabel(transaksiBaru);
        perbaruiTotalAset();

        modalDetailTransaksi.style.display = "none";
    }

    function tambahkanTransaksiKeTabel(transaksiBaru) {
        let barisBaru = tabelTransaksi.insertRow();
        barisBaru.innerHTML = `
            <td><img src="https://s2.coinmarketcap.com/static/img/coins/64x64/${transaksiBaru.id}.png" alt="${transaksiBaru.name}" class="crypto-logo"> ${transaksiBaru.name} (${transaksiBaru.symbol})</td>
            <td>${parseFloat(transaksiBaru.currentPrice).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
            <td>${transaksiBaru.percentChange24h}%</td>
            <td>${transaksiBaru.percentChange7d}%</td>
            <td>${transaksiBaru.quantity}</td>
            <td>${transaksiBaru.purchasePrice.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
            <td>${transaksiBaru.profit.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
            <td><button class="deleteBtn">Delete</button></td>
        `;

        barisBaru.querySelector('.deleteBtn').onclick = function() {
            const indeksBaris = barisBaru.rowIndex - 1;
            transaksi.splice(indeksBaris, 1);
            localStorage.setItem('transactions', JSON.stringify(transaksi));
            tabelTransaksi.deleteRow(indeksBaris);
            perbaruiTotalAset();
        };
    }

    function muatTransaksi() {
        transaksi.forEach(transaksiItem => {
            tambahkanTransaksiKeTabel(transaksiItem);
        });
        perbaruiTotalAset();
    }

    function perbaruiTotalAset() {
        const totalAset = transaksi.reduce((jumlah, transaksiItem) => jumlah + (transaksiItem.currentPrice * transaksiItem.quantity), 0);
        totalAsetTampilan.textContent = totalAset.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
    }

    muatTransaksi();

    let transaksiTersimpan = localStorage.getItem('transactions');
    let simbol = []; 

    if (transaksiTersimpan) {
        let transaksiData = JSON.parse(transaksiTersimpan);
        simbol = transaksiData.map(transaksiItem => transaksiItem.symbol);
    }

    // Event listener untuk tombol monitor
    document.getElementById('monitorBtn').addEventListener('click', () => {
        const simbolString = simbol.join(','); 
        const monitorUrl = `pantauportofolio.php?coin=${simbolString}`;
        window.open(monitorUrl, '_blank');
    });

});
