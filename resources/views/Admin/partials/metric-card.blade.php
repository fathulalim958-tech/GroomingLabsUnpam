<div class="col-12 col-md-3">
    <div class="metric-card h-100">
        <div class="small text-muted">{{ $label }}</div>
        <div class="fs-3 fw-bold text-gold mt-2">{{ $value }}</div>
        @if (!empty($trend))
            <div class="small {{ $trendClass ?? 'text-success' }} mt-2">
                <i class="fa-solid {{ $trendIcon ?? 'fa-arrow-up' }} me-1"></i>{{ $trend }}
            </div>
        @endif
    </div>
</div>
