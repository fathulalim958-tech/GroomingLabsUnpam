<div class="booking-card {{ $type ?? 'barber' }}-item status-{{ $status ?? 'menunggu' }} {{ $hidden ?? '' }}" data-category="{{ $type ?? 'barber' }}" data-status="{{ $status ?? 'menunggu' }}" @isset($completeUrl) role="button" tabindex="0" aria-haspopup="dialog" data-customer="{{ $customerName }}" data-code="{{ $bookingCode }}" data-service="{{ $service }}" data-time="{{ $time }}" data-staff="{{ $staff }}" data-price="{{ $price }}" data-complete-url="{{ $completeUrl }}" onclick="showBookingDetails(this)" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); showBookingDetails(this); }" @endisset>
    <div>
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="cust-name">{{ $customerName }}</div>
                <div class="booking-code">{{ $bookingCode }}</div>
            </div>
            <span class="badge-status badge-status-{{ $status ?? 'menunggu' }}">
                <span class="dot"></span> {{ ucfirst($status ?? 'menunggu') }}
            </span>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-2.5">
            <div class="card-detail-item">
                <i class="fa-solid {{ $icon ?? 'fa-scissors' }} text-gold"></i>
                <span>{{ $service }}</span>
            </div>
            <div class="card-time">{{ $time }}</div>
        </div>
    </div>

    <div>
        <div class="divider-line"></div>
        <div class="d-flex justify-content-between align-items-center">
            <div class="card-detail-item">
                <i class="fa-solid fa-user text-muted"></i>
                <span class="text-white-50">{{ $staff }}</span>
            </div>
            <div class="card-price">{{ $price }}</div>
        </div>
    </div>
</div>
