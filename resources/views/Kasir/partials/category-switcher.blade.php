<div class="category-switcher">
    @php
        $barberCount = $walkInBookings->where("category", "barber")->count();
        $muaCount = $walkInBookings->where("category", "mua")->count();
    @endphp
    <button class="cat-btn active" onclick="switchCategory('barber', this)">
        <span><i class="fa-solid fa-scissors"></i> Barber</span>
        <span class="cat-badge">{{ $barberCount }}</span>
    </button>
    <button class="cat-btn" onclick="switchCategory('mua', this)">
        <span><i class="fa-solid fa-wand-magic-sparkles"></i> MUA</span>
        <span class="cat-badge">{{ $muaCount }}</span>
    </button>
</div>
