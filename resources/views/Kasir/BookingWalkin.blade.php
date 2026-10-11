<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Catat Walk-in - GlowCut</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<style>
	:root {
		--bg-dark: #0a0a0c;
		--card-bg: #141418;
		--card-border: #23232c;
		--gold-primary: #e5be58;
		--gold-border: rgba(229, 190, 88, 0.3);
		--text-muted: #8e8e9a;
	}

	body {
		min-height: 100vh;
		margin: 0;
		background: var(--bg-dark);
		color: #fff;
		font-family: 'Plus Jakarta Sans', sans-serif;
	}

	.app-container {
		width: min(100%, 960px);
		min-height: 100vh;
		margin: 0 auto;
		padding: 1.5rem 1rem 3rem;
	}

	.font-serif {
		font-family: 'DM Serif Display', serif;
	}

	.header-sub {
		color: var(--text-muted);
		font-size: 0.72rem;
		font-weight: 700;
		letter-spacing: 1.5px;
	}

	.header-title {
		margin-bottom: 0.1rem;
		color: #fff;
		font-size: 1.7rem;
	}

	.header-date,
	.stats-sub {
		color: var(--text-muted);
		font-size: 0.78rem;
	}

	.btn-circle-icon {
		display: flex;
		width: 42px;
		height: 42px;
		align-items: center;
		justify-content: center;
		border: 1px solid #282832;
		border-radius: 50%;
		background: #17171d;
		color: #fff;
		text-decoration: none;
	}

	.stats-card {
		margin: 1.25rem 0;
		padding: 1.25rem 1rem;
		border: 1px solid var(--gold-border);
		border-radius: 1rem;
		background: linear-gradient(145deg, #181610 0%, #121216 100%);
	}

	.stats-label {
		color: #bfa152;
		font-size: 0.68rem;
		font-weight: 700;
	}

	.stats-value {
		color: var(--gold-primary);
		font-size: 1.1rem;
		font-weight: 800;
	}

	.btn-gold-action {
		width: 100%;
		padding: 0.9rem 1rem;
		border: 0;
		border-radius: 0.8rem;
		background: linear-gradient(135deg, #ebd178 0%, #e5be58 50%, #c99b33 100%);
		color: #0d0d0f;
		font-weight: 800;
	}

	.walkin-dialog {
		max-width: 560px;
		padding: 0 0.5rem;
	}

	.walkin-modal {
		position: fixed;
		inset: 0;
		z-index: 1055;
		display: none;
		overflow-y: auto;
		padding: 0.5rem;
		background: rgba(0, 0, 0, 0.78);
	}

	.walkin-modal.show {
		display: block;
	}

	.walkin-modal .modal-dialog {
		display: flex;
		align-items: center;
		min-height: calc(100% - 1rem);
		margin: 0.5rem auto;
	}

	.walkin-modal .modal-content {
		width: 100%;
	}

	body.walkin-open {
		overflow: hidden;
	}

	.walkin-content {
		background: #1c1b19;
		border: 1px solid #302f2b;
		border-radius: 1.4rem;
		color: #f6f3ec;
		box-shadow: 0 24px 70px rgba(0, 0, 0, 0.55);
	}

	.walkin-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 1rem;
		border: 0;
		padding: 1.5rem 1.5rem 0.65rem;
	}

	.walkin-header > div:first-child {
		flex: 1;
		min-width: 0;
	}

	.walkin-eyebrow {
		color: #c9b77e;
		font-size: 0.62rem;
		font-weight: 800;
		letter-spacing: 1.5px;
		margin-bottom: 0.45rem;
	}

	.walkin-title {
		color: #f6f3ec !important;
		font-family: 'DM Serif Display', serif;
		font-size: 1.8rem;
		margin: 0;
	}

	.walkin-close {
		width: 40px;
		height: 40px;
		flex: 0 0 40px;
		margin-left: auto;
		border: 1px solid #383733;
		border-radius: 0.8rem;
		display: grid;
		place-items: center;
		background: transparent;
		color: #e7e3da;
		font-size: 1.2rem;
		line-height: 1;
	}

	.walkin-close:hover {
		border-color: var(--gold-primary);
		color: var(--gold-primary);
	}

	.walkin-body {
		padding: 0.65rem 1.5rem 1.5rem;
	}

	.walkin-label {
		color: #aaa69d;
		font-size: 0.68rem;
		font-weight: 800;
		letter-spacing: 0.7px;
		margin-bottom: 0.55rem;
	}

	.walkin-input {
		background: #121212;
		border: 1px solid #2b2a27;
		border-radius: 0.8rem;
		color: #f6f3ec;
		min-height: 48px;
		padding: 0.75rem 0.9rem;
	}

	.walkin-input::placeholder {
		color: #77746e;
	}

	.walkin-input:focus {
		background: #121212;
		border-color: var(--gold-primary);
		box-shadow: 0 0 0 0.2rem rgba(229, 190, 88, 0.12);
		color: #fff;
	}

	.walkin-categories {
		display: flex;
		gap: 0.3rem;
		padding: 0.3rem;
		background: #151514;
		border: 1px solid #2b2a27;
		border-radius: 0.9rem;
	}

	.walkin-category-btn {
		flex: 1;
		min-height: 40px;
		border: 0;
		border-radius: 0.55rem;
		background: transparent;
		color: #c9c5bd;
		font-weight: 700;
	}

	.walkin-category-btn.active {
		background: var(--gold-primary);
		color: #171511;
	}

	.walkin-services {
		display: grid;
		gap: 0.45rem;
		max-height: 220px;
		overflow-y: auto;
	}

	.walkin-service-option {
		position: relative;
		display: block;
		padding: 0.6rem 0.75rem;
		border: 1px solid transparent;
		background: #191918;
		color: #e9e6df;
		cursor: pointer;
		text-align: center;
		transition: background 0.15s ease, border-color 0.15s ease;
	}

	.walkin-service-option:hover,
	.walkin-service-option:has(input:checked) {
		background: #222118;
		border-color: rgba(229, 190, 88, 0.55);
	}

	.walkin-service-option input {
		position: absolute;
		width: 1px;
		height: 1px;
		opacity: 0;
	}

	.walkin-service-option input:focus-visible + .walkin-service-name {
		outline: 2px solid var(--gold-primary);
		outline-offset: 3px;
	}

	.walkin-service-name {
		display: block;
		font-size: 0.92rem;
		font-weight: 800;
	}

	.walkin-service-detail {
		display: block;
		margin-top: 0.25rem;
		color: #99958c;
		font-size: 0.66rem;
		text-transform: uppercase;
	}

	.walkin-service-price {
		color: var(--gold-primary);
		font-size: 0.8rem;
		font-weight: 800;
		margin-left: 0.2rem;
	}

	.walkin-actions {
		display: flex;
		gap: 0.65rem;
		margin-top: 1.35rem;
	}

	.walkin-actions button {
		flex: 1;
		min-height: 48px;
		border-radius: 0.8rem;
		font-weight: 800;
	}

	.walkin-cancel {
		background: transparent;
		border: 1px solid #383733;
		color: #e7e3da;
	}

	.walkin-cancel:hover {
		border-color: #77746e;
		color: #fff;
	}

	.walkin-save {
		background: var(--gold-primary);
		border: 1px solid var(--gold-primary);
		color: #171511;
	}

	.walkin-save:hover {
		background: #f0cb65;
		border-color: #f0cb65;
		color: #171511;
	}

	@media (max-width: 575.98px) {
		.walkin-dialog {
			margin: 0.75rem auto;
		}

		.walkin-header {
			padding: 1.25rem 1.15rem 0.55rem;
		}

		.walkin-body {
			padding: 0.65rem 1.15rem 1.15rem;
		}

		.walkin-title {
			font-size: 1.6rem;
		}
	}
</style>
</head>
<body>
    <div class="app-container">
        @include('Kasir.partials.page-header', [
            'title' => 'Catat Walk-in',
            'date' => 'Input transaksi hari ini',
			'backRoute' => route('kasir.dashboard'),
			'showLogout' => false
        ])

        <section class="stats-card mb-3">
            <div class="row text-center g-0">
                @include('Kasir.partials.summary-card', [
                    'label' => 'BARBER',
                    'value' => 'Rp 215k',
                    'caption' => '3 transaksi'
                ])
                @include('Kasir.partials.summary-card', [
                    'label' => 'MUA',
                    'value' => 'Rp 180k',
                    'caption' => '2 transaksi'
                ])
                @include('Kasir.partials.summary-card', [
                    'label' => 'TOTAL',
                    'value' => 'Rp 395k',
                    'caption' => 'Hari ini'
                ])
            </div>
        </section>

        <button type="button" class="btn btn-gold-action d-flex align-items-center justify-content-center gap-2 mb-3" onclick="showWalkInModal()">
            <i class="fa-solid fa-plus fs-6"></i>
            <span>Tambah Pelanggan Walk-in</span>
        </button>
    </div>

<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
	<div id="walkInToast" class="toast align-items-center text-bg-success border-0 rounded-3 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
		<div class="d-flex">
			<div class="toast-body d-flex align-items-center gap-2">
				<i class="fa-solid fa-circle-check fs-5"></i>
				<div>
					<strong class="d-block">Berhasil!</strong>
					<span class="small" id="walkInToastMessage">Pelanggan walk-in telah ditambahkan.</span>
				</div>
			</div>
			<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
		</div>
	</div>
</div>

<div class="modal walkin-modal" id="walkInModal" tabindex="-1" aria-labelledby="walkInModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered walkin-dialog">
		<div class="modal-content walkin-content">
			<div class="modal-header walkin-header">
				<div>
					<div class="walkin-eyebrow">WALK-IN</div>
					<h2 class="walkin-title" id="walkInModalLabel">Pelanggan Baru</h2>
				</div>
				<button type="button" class="walkin-close" onclick="hideWalkInModal()" aria-label="Tutup">&times;</button>
			</div>
			<div class="modal-body walkin-body">
				<form id="walkInForm" method="POST" action="{{ route('kasir.booking.walkin.store') }}">
					@csrf
					@if ($errors->any())
						<div class="alert alert-danger small py-2" role="alert">
							<ul class="mb-0 ps-3">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					<div class="mb-3">
						<label for="walkInCustomerName" class="form-label walkin-label">NAMA PELANGGAN</label>
						<input id="walkInCustomerName" name="customer_name" value="{{ old('customer_name') }}" type="text" required maxlength="100" class="form-control walkin-input" placeholder="Masukkan nama">
					</div>
					<div class="mb-3">
						<label for="walkInCategory" class="form-label walkin-label">KATEGORI LAYANAN</label>
						<select id="walkInCategory" name="category" class="form-select walkin-input" onchange="updateWalkInServices(); updateWalkInStaff()">
							<option value="barber" @selected(old('category', 'barber') === 'barber')>Barbershop</option>
							<option value="mua" @selected(old('category') === 'mua')>MUA Wisuda</option>
						</select>
					</div>
					<div class="mb-3">
						<label for="walkInService" class="form-label walkin-label">PILIH LAYANAN</label>
						<select id="walkInService" name="service_name" class="form-select walkin-input" required></select>
					</div>
					<div class="mb-3">
						<label for="walkInStaff" class="form-label walkin-label">HAIR STYLIST / MUA ARTIST</label>
						<select id="walkInStaff" name="staff_name" class="form-select walkin-input" required></select>
					</div>
					<div class="walkin-actions">
						<button type="button" class="walkin-cancel" onclick="hideWalkInModal()">Batal</button>
						<button type="submit" class="walkin-save">Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	const walkInServiceCatalog = @json($walkInServiceCatalog);
	const walkInEmployees = @json($employees);

	function updateWalkInServices(selectedService = '') {
		const category = document.getElementById('walkInCategory').value;
		const serviceSelect = document.getElementById('walkInService');
		const services = walkInServiceCatalog[category] || [];

		serviceSelect.replaceChildren();
		Object.entries(services).forEach(([serviceName, price]) => {
			const option = new Option(
				`${serviceName} - Rp ${new Intl.NumberFormat('id-ID').format(price)}`,
				serviceName
			);
			option.dataset.price = String(price);
			option.selected = serviceName === selectedService;
			serviceSelect.add(option);
		});
	}

	function updateWalkInStaff(selectedStaff = '') {
		const category = document.getElementById('walkInCategory').value;
		const staffSelect = document.getElementById('walkInStaff');
		const requiredPosition = category === 'mua' ? 'MUA Artist' : 'Hair Stylist';
		const eligibleEmployees = walkInEmployees.filter(employee => employee.position === requiredPosition);

		staffSelect.replaceChildren();
		staffSelect.disabled = eligibleEmployees.length === 0;

		const placeholder = new Option(
			eligibleEmployees.length ? 'Pilih karyawan' : 'Belum ada karyawan terdaftar',
			''
		);
		placeholder.disabled = true;
		placeholder.selected = !selectedStaff;
		staffSelect.add(placeholder);

		eligibleEmployees.forEach(employee => {
			const option = new Option(employee.name, employee.name);
			option.selected = employee.name === selectedStaff;
			staffSelect.add(option);
		});
	}

	function showWalkInModal() {
		const modalEl = document.getElementById('walkInModal');
		modalEl.classList.add('show');
		modalEl.setAttribute('aria-hidden', 'false');
		modalEl.setAttribute('aria-modal', 'true');
		document.body.classList.add('walkin-open');
		document.getElementById('walkInCustomerName').focus();
	}

	function hideWalkInModal() {
		const modalEl = document.getElementById('walkInModal');
		modalEl.classList.remove('show');
		modalEl.setAttribute('aria-hidden', 'true');
		modalEl.removeAttribute('aria-modal');
		document.body.classList.remove('walkin-open');
	}

	document.addEventListener('keydown', event => {
		if (event.key === 'Escape' && document.getElementById('walkInModal').classList.contains('show')) {
			hideWalkInModal();
		}
	});

	document.getElementById('walkInModal').addEventListener('click', event => {
		if (event.target === event.currentTarget) {
			hideWalkInModal();
		}
	});

	updateWalkInServices(@json(old('service_name')));
	updateWalkInStaff(@json(old('staff_name')));

	@if ($errors->any())
		showWalkInModal();
	@endif

	@if (session('success'))
		document.getElementById('walkInToastMessage').textContent = @json(session('success'));
		bootstrap.Toast.getOrCreateInstance(document.getElementById('walkInToast')).show();
	@endif
</script>
</body>
</html>
