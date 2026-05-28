<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Transaksi</title>

<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

</head>

<body>

@include('components.sidebar')

<div class="main">

    <div class="topbar">
        <div>
            <h2>MANAJEMEN TRANSAKSI</h2>
            <small>Data dari Database</small>
        </div>

        <input placeholder="Cari transaksi...">
    </div>

    <div class="filters">
        <input type="text" value="Data Transaksi">
        <select>
            <option>Semua</option>
        </select>
    </div>

    <div class="table-box">
        <h3>Data Transaksi</h3>

    <table>
    <thead>
        <tr>
            <th>Invoice</th>
            <th>Stock Code</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Invoice Date</th>
            <th>Price</th>
            <th>Customer ID</th>
            <th>Country</th>
        </tr>
    </thead>
        <tbody>
            @forelse($data as $d)
            <tr>
                <td>{{ $d->Invoice }}</td>
                <td>{{ $d->StockCode }}</td>
                <td>{{ $d->Description }}</td>
                <td>{{ $d->Quantity }}</td>
                <td>{{ $d->InvoiceDate }}</td>
                <td>Rp {{ number_format($d->Price, 0, ',', '.') }}</td>
                <td>{{ $d->CustomerID }}</td>
                <td>{{ $d->Country }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8">Data tidak tersedia</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{ $data->links() }}

    </div>

    <button class="btn" onclick="toggleForm()">+ Buat Transaksi</button>

    <div id="form-box" class="form-box hidden">
        <h3>Tambah Transaksi</h3>

        <form action="{{ route('transaksi.store') }}" method="POST">
            @csrf

            <label>Invoice</label>
            <input type="text" name="Invoice" placeholder="INV-001" required>

            <label>Stock Code</label>
            <input type="text" name="StockCode" placeholder="Kode Produk" required>

            <label>Nama Produk</label>
            <input type="text" name="Description" placeholder="Nama Produk" required>

            <label>Jumlah</label>
            <input type="number" name="Quantity" min="1" required>

            <label>Tanggal Transaksi</label>
            <input type="datetime-local" name="InvoiceDate" required>

            <label>Harga</label>
            <input type="number" name="Price" min="0" required>

            <label>Customer ID</label>
            <input type="number" name="CustomerID" required>

            <label>Country</label>
            <input type="text" name="Country" placeholder="Negara" required>

            <button type="submit" class="btn">Simpan</button>
        </form>
    </div>
</div>

<script>
function toggleForm() {
    document.getElementById("form-box").classList.toggle("hidden");
}
</script>

</body>
</html>