<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <div class="text-uppercase small text-warning fw-bold mb-1">Admin</div>
        <h1 class="mb-0 text-white fw-bold">{{ $title }}</h1>
    </div>
    @if (!empty($backRoute))
        <a href="{{ $backRoute }}" class="btn btn-outline-light rounded-pill px-3">
            <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    @endif
</div>
