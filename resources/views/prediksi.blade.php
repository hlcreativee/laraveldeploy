@extends('layouts.app')

@section('content')
<div class="header">
    <h2>Analisis Prediksi Penjualan</h2>
</div>

<div class="cards">
    <div class="card">
        <h4>Prediksi Bulan Depan</h4>
        <h2>{{ number_format($prediksi ?? 0, 0, ',', '.') }}</h2>
    </div>

    <div class="card">
        <h4>Produk Terlaris Prediksi</h4>
        <h2>{{ $topProduct ?? '-' }}</h2>
    </div>
</div>

<div class="chart-box">
    <h3>Trend Penjualan & Prediksi</h3>
    <canvas id="chart" height="120"></canvas>
</div>

<h3>Prediksi per Produk</h3>
<canvas id="chart-produk" height="120"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const data = @json($data ?? []);
    const prediksi = {{ $prediksi ?? 0 }};
    const prediksiProduk = @json($prediksiProduk ?? []);

    console.log("DATA:", data);

    if (!data.length) {
        console.warn("Data kosong!");
        return;
    }

    const formatNumber = val => Number(val).toLocaleString();

    const monthlyData = {};

    data.forEach(d => {
        const key = d.date ?? d.month;
        const qty = Number(d.qty ?? 0);

        if (!key) return;

        monthlyData[key] = (monthlyData[key] || 0) + qty;
    });

    const labels = Object.keys(monthlyData).sort();
    const qty = labels.map(l => monthlyData[l]);

    const last = labels[labels.length - 1];
    if (!last) return;

    const next = new Date(last + "-01");
    next.setMonth(next.getMonth() + 1);

    const nextMonth = next.getFullYear() + '-' +
        (next.getMonth()+1).toString().padStart(2,'0');

    labels.push(nextMonth);
    qty.push(null);

    const produkCount = {};

    const produkData = @json($produkData ?? []);

    produkData.forEach(d => {
        const product = d.product || 'Unknown';
        const qty = Number(d.qty || 0);

        produkCount[product] = (produkCount[product] || 0) + qty;
    });

    const totalQty = Object.values(produkCount).reduce((a,b)=>a+b,0);

    const topProduk = Object.entries(produkCount)
        .sort((a,b)=>b[1]-a[1])
        .slice(0,5);

    const prediksiPerItem = {};

    topProduk.forEach(([produk, total]) => {
        const ratio = totalQty ? total / totalQty : 0;
        prediksiPerItem[produk] = Math.round(ratio * prediksi);
    });

    const datasetProduk = Object.keys(prediksiPerItem).map((produk, i) => {
        return {
            type: 'bar',
            label: produk,
            data: [...Array(qty.length - 1).fill(null), prediksiPerItem[produk]],
            backgroundColor: `hsl(${i*60}, 70%, 55%)`
        };
    });

    new Chart(document.getElementById("chart"), {
        data: {
            labels: labels,
            datasets: [
                {
                    type: 'line',
                    label: 'Penjualan Asli',
                    data: [...qty.slice(0, -1), null],
                    borderColor: '#3b82f6',
                    tension: 0.4
                },
                {
                    type: 'line',
                    label: 'Prediksi Total',
                    data: [...Array(qty.length - 1).fill(null), prediksi],
                    borderColor: '#10b981',
                    borderDash: [5,5],
                    tension: 0.4
                },
                ...datasetProduk
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.label + ": " + formatNumber(ctx.raw)
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

    const sorted = Object.entries(prediksiPerItem)
        .sort((a,b)=>b[1]-a[1]);

    new Chart(document.getElementById("chart-produk"), {
        type: 'bar',
        data: {
            labels: sorted.map(d => d[0]),
            datasets: [{
                label: 'Prediksi per Produk',
                data: sorted.map(d => d[1]),
                backgroundColor: '#1cc88a'
            }]
        },
        options: {
            indexAxis: 'y',
            plugins: {
                tooltip: {
                    callbacks: {
                        label: ctx => formatNumber(ctx.raw)
                    }
                }
            }
        }
    });

});
</script>

@endsection