<div class="sidebar">
    <h2>Retail Pro</h2>

    <div class="menu">
        <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="/transaksi" class="{{ request()->is('transaksi') ? 'active' : '' }}">Transaksi</a>
        <a href="/predictions" class="{{ request()->is('predictions') ? 'active' : '' }}">Prediksi</a>
    </div>

    <div class="profile">
        <a href="/profil" class="{{ request()->is('profil') ? 'active' : '' }}">Profil</a>
    </div>
</div>