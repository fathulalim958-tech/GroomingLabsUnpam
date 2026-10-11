<header class="d-flex justify-content-between align-items-center mb-1">
    <div>
        <div class="header-sub">GLOWCUT &nbsp;&bull;&nbsp; KASIR</div>
        <h1 class="header-title font-serif">{{ $title }}</h1>
        <div class="header-date">{{ $date ?? \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</div>
    </div>
    <div class="d-flex gap-3 align-items-center">
        @if(isset($userName))
            <div class="text-end me-2">
                <div class="text-white fw-bold" style="font-size: 0.95rem;">{{ $userName }}</div>
                <div class="text-gold" style="font-size: 0.75rem;"><i class="fa-solid fa-user-tag me-1"></i>{{ $userRole ?? 'Kasir' }}</div>
            </div>
        @endif
        <div class="d-flex gap-2">
            @if (!empty($backRoute))
                <a href="{{ $backRoute }}" class="btn-circle-icon" title="Kembali ke Dashboard">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            @endif
            @if ($showLogout ?? true)
                <a href="/logout" class="btn-circle-icon" title="Keluar">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </a>
            @endif
        </div>
    </div>
</header>
