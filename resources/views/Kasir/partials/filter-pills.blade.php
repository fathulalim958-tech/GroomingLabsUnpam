<section class="filter-pills-container mb-3">
    @php
        $menungguCount = $walkInBookings->where('status', 'pending')->count();
        $prosesCount = $walkInBookings->whereIn('status', ['confirmed', 'in_progress'])->count();
        $selesaiCount = $walkInBookings->where('status', 'completed')->count();
    @endphp
    <button class="pill-btn active" onclick="filterStatus('all', this)">
        <span>Semua</span>
        <span class="pill-badge">{{ $walkInBookings->count() }}</span>
    </button>
    <button class="pill-btn" onclick="filterStatus('menunggu', this)">
        <span>Menunggu</span>
        <span class="pill-badge">{{ $menungguCount }}</span>
    </button>
    <button class="pill-btn" onclick="filterStatus('proses', this)">
        <span>Proses</span>
        <span class="pill-badge">{{ $prosesCount }}</span>
    </button>
    <button class="pill-btn" onclick="filterStatus('selesai', this)">
        <span>Selesai</span>
        <span class="pill-badge">{{ $selesaiCount }}</span>
    </button>
</section>
