@extends('layouts.app')

@section('content')

<div class="header">
    <h2>Dashboard Penjualan</h2>
</div>

<div class="cards">

    <div class="card">
        <h4>Total Revenue</h4>
        <h2 id="total-revenue">-</h2>
    </div>

    <div class="card">
        <h4>Total Transaksi</h4>
        <h2 id="total-trx">-</h2>
    </div>

    <div class="card">
        <h4>Produk Terlaris</h4>
        <h2 id="top-product">-</h2>
    </div>

</div>

<div class="chart-box">
    <h3>Trend Penjualan</h3>
    <canvas id="chart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const formatRupiah = val => "Rp " + Number(val).toLocaleString();
    const formatNumber = val => Number(val).toLocaleString();

    const data = @json($data);

    let totalRevenue = 0;
    data.forEach(d => totalRevenue += Number(d.total));

    document.getElementById("total-revenue").innerText = formatRupiah(totalRevenue);
    document.getElementById("total-trx").innerText = data.length;

    document.getElementById("top-product").innerText = @json($topProduct);

    new Chart(document.getElementById("chart"), {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Penjualan Aktual',
                    data: @json(array_slice($qty, 0, -1)),
                    backgroundColor: '#4e73df'
                },
                {
                    label: 'Prediksi Bulan Depan',
                    data: Array(@json(count($qty))).fill(null).map((_, i) =>
                        i === @json(count($qty) - 1) ? @json($qty)[@json(count($qty) - 1)] : null
                    ),
                    backgroundColor: '#ff6b6b'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: ctx => formatNumber(ctx.raw)
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: val => formatNumber(val)
                    }
                }
            }
        }
    });

});
</script>

@endsection