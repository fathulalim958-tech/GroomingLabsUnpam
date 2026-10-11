<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - GlowCut</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg-dark: #08080b;
            --card-bg: #121217;
            --card-border: #202029;
            --gold-primary: #e5be58;
            --text-muted: #8c8c9a;
            --accent-green: #34d399;
        }

        body {
            background-color: var(--bg-dark);
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
        }

        .page-shell {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem 4rem;
        }

        .panel {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 1rem;
        }

        .metric-card {
            background: linear-gradient(145deg, #181510 0%, #121217 100%);
            border: 1px solid rgba(229, 190, 88, 0.3);
            border-radius: 1rem;
            padding: 1.25rem;
        }

        .text-gold {
            color: var(--gold-primary);
        }

        .report-total {
            border: 1px solid rgba(229, 190, 88, 0.3);
            border-radius: 0.85rem;
            background: linear-gradient(145deg, #181510 0%, #121217 100%);
        }

        .report-filters select {
            width: auto;
            min-width: 150px;
        }

        .report-range-form {
            display: flex;
            flex-wrap: wrap;
            align-items: end;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .report-date-fields {
            display: grid;
            flex: 1 1 320px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .report-date-fields label {
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 700;
        }

        .report-date-input {
            width: 100%;
            min-height: 42px;
            margin-top: 0.35rem;
            border: 1px solid var(--card-border);
            border-radius: 0.55rem;
            background: #19191f;
            color: #fff;
            color-scheme: dark;
            padding: 0.5rem 0.65rem;
        }

        .report-range-error {
            width: 100%;
            color: #f87171;
            font-size: 0.78rem;
        }

        .report-category-btn {
            border-color: var(--card-border);
            color: var(--text-muted);
        }

        .report-category-btn.active {
            border-color: var(--gold-primary);
            background: var(--gold-primary);
            color: #111;
        }

        .report-list {
            overflow: hidden;
            border: 1px solid var(--card-border);
            border-radius: 0.8rem;
        }

        .report-list-heading,
        .report-transaction {
            display: grid;
            grid-template-columns: 4.5rem minmax(0, 1fr) auto;
            align-items: center;
            gap: 0.75rem;
            padding: 0.8rem 1rem;
        }

        .report-list-heading {
            background: #17171d;
            color: var(--text-muted);
            font-size: 0.72rem;
            font-weight: 700;
        }

        .report-transaction + .report-transaction {
            border-top: 1px solid var(--card-border);
        }

        .report-date-time,
        .report-service-meta {
            color: var(--text-muted);
            font-size: 0.72rem;
        }

        .report-service-name {
            color: #fff;
            font-size: 0.84rem;
            font-weight: 700;
        }

        .report-type-badge {
            display: inline-block;
            margin-right: 0.4rem;
            color: var(--gold-primary);
            font-size: 0.65rem;
            text-transform: uppercase;
        }

        .report-type-badge.walkin {
            color: var(--accent-green);
        }

        .report-amount {
            color: #fff;
            font-size: 0.82rem;
            font-weight: 800;
            text-align: right;
            white-space: nowrap;
        }

        @media (max-width: 575.98px) {
            .report-date-fields {
                grid-template-columns: 1fr;
            }

            .report-list-heading,
            .report-transaction {
                grid-template-columns: 3.5rem minmax(0, 1fr) auto;
                gap: 0.45rem;
                padding: 0.7rem 0.55rem;
            }

            .report-service-name {
                font-size: 0.75rem;
            }

            .report-amount {
                font-size: 0.72rem;
            }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        @include('Admin.partials.admin-page-header', [
            'title' => 'Laporan Pendapatan',
            'backRoute' => route('admin.dashboard')
        ])

        <section class="report-total p-3 p-md-4 mb-3">
            <div class="small text-muted mb-1">Pendapatan Selesai · {{ $startDate }} s.d. {{ $endDate }}</div>
            <div class="h3 fw-bold text-gold mb-2">Rp {{ number_format($reportTotal, 0, ',', '.') }}</div>
            <div class="d-flex flex-wrap gap-3 small">
                <span class="text-muted">Barbershop <strong class="text-white ms-1">Rp {{ number_format($barberTotal, 0, ',', '.') }}</strong></span>
                <span class="text-muted">MUA <strong class="text-white ms-1">Rp {{ number_format($muaTotal, 0, ',', '.') }}</strong></span>
            </div>
        </section>

        <section class="panel p-3 p-md-4">
            @if ($errors->any())
                <div class="alert alert-danger small" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="reportFilterForm" class="report-range-form" method="GET" action="{{ route('admin.laporan') }}" onsubmit="return validateReportRange('filterStartDate', 'filterEndDate', 'filterRangeError')">
                <div class="report-date-fields">
                    <label for="filterStartDate">Dari tanggal
                        <input id="filterStartDate" class="report-date-input" type="date" name="start_date" value="{{ old('start_date', $startDate) }}" max="{{ $today }}" required>
                    </label>
                    <label for="filterEndDate">Sampai tanggal
                        <input id="filterEndDate" class="report-date-input" type="date" name="end_date" value="{{ old('end_date', $endDate) }}" max="{{ $today }}" required>
                    </label>
                </div>
                <button type="submit" class="btn btn-outline-light">Terapkan Filter</button>
                <div id="filterRangeError" class="report-range-error" role="alert"></div>
            </form>

            <div class="report-filters d-flex flex-wrap align-items-center gap-2 mb-3">
                <div class="btn-group" role="group" aria-label="Filter kategori laporan">
                    <button type="button" class="btn report-category-btn active" data-category="all" aria-pressed="true" onclick="filterReportTransactions('all')">Semua</button>
                    <button type="button" class="btn report-category-btn" data-category="barber" aria-pressed="false" onclick="filterReportTransactions('barber')">Barber</button>
                    <button type="button" class="btn report-category-btn" data-category="mua" aria-pressed="false" onclick="filterReportTransactions('mua')">MUA</button>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 class="h6 text-white fw-bold mb-0">Tabel Pemasukan</h2>
                <span class="small text-muted" id="reportTransactionCount">{{ $transactions->count() }} transaksi</span>
            </div>
            <div class="report-list" id="reportTransactionList">
                <div class="report-list-heading"><span>Tanggal</span><span>Layanan</span><span class="text-end">Nominal</span></div>
                @forelse ($transactions as $transaction)
                    <article class="report-transaction" data-category="{{ $transaction->category ?? 'barber' }}">
                        <div class="report-date-time">{{ $transaction->appointment_at?->format('d/m/Y') }}<br>{{ $transaction->appointment_at?->format('H:i') }}</div>
                        <div class="min-w-0">
                            <div class="report-service-name"><span class="report-type-badge {{ $transaction->cashier_id ? 'walkin' : '' }}">{{ $transaction->cashier_id ? 'Walk-in' : 'Online' }}</span>{{ $transaction->service_name ?? 'Layanan booking' }}</div>
                            <div class="report-service-meta">{{ $transaction->customer_name ?? 'Pelanggan online' }} · {{ $transaction->staff_name ?? 'Staff belum ditentukan' }} · {{ ucfirst($transaction->status) }}</div>
                        </div>
                        <div class="report-amount">Rp {{ number_format($transaction->service_price ?? 0, 0, ',', '.') }}</div>
                    </article>
                @empty
                    <div class="text-center text-muted small py-4">Tidak ada transaksi pada rentang tanggal ini.</div>
                                @endforelse
            </div>
            
            <div class="mt-4 d-flex justify-content-center" data-bs-theme="dark">
                {{ $transactions->links('pagination::bootstrap-5') }}
            </div>
            <button type="button" class="btn btn-warning fw-bold mt-3" data-bs-toggle="modal" data-bs-target="#exportReportModal">
                <i class="fa-solid fa-file-excel me-2"></i>Unduh Excel
            </button>
        </section>
    </div>

    <div class="modal fade" id="exportReportModal" tabindex="-1" aria-labelledby="exportReportTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content panel text-white">
                <div class="modal-header border-secondary">
                    <div>
                        <div class="small text-gold fw-bold">EXPORT SPREADSHEET</div>
                        <h2 class="modal-title fs-5" id="exportReportTitle">Pilih Periode Excel</h2>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form method="GET" action="{{ route('admin.laporan.export') }}" onsubmit="return validateReportRange('exportStartDate', 'exportEndDate', 'exportRangeError')">
                    <div class="modal-body">
                        <div class="report-date-fields">
                            <label for="exportStartDate">Dari tanggal
                                <input id="exportStartDate" class="report-date-input" type="date" name="start_date" value="{{ old('start_date', $startDate) }}" max="{{ $today }}" required>
                            </label>
                            <label for="exportEndDate">Sampai tanggal
                                <input id="exportEndDate" class="report-date-input" type="date" name="end_date" value="{{ old('end_date', $endDate) }}" max="{{ $today }}" required>
                            </label>
                        </div>
                        <div id="exportRangeError" class="report-range-error mt-2" role="alert"></div>
                        <div class="small text-muted mt-2">Maksimal 31 hari, termasuk tanggal awal dan akhir.</div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning fw-bold"><i class="fa-solid fa-download me-2"></i>Unduh CSV Excel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let reportCategoryFilter = 'all';

        function filterReportTransactions(category) {
            if (category) {
                reportCategoryFilter = category;
            }

            document.querySelectorAll('.report-category-btn').forEach((button) => {
                const isActive = button.dataset.category === reportCategoryFilter;
                button.classList.toggle('active', isActive);
                button.setAttribute('aria-pressed', String(isActive));
            });

            let visibleTransactions = 0;

            document.querySelectorAll('.report-transaction').forEach((transaction) => {
                const matchesCategory = reportCategoryFilter === 'all' || transaction.dataset.category === reportCategoryFilter;
                const isVisible = matchesCategory;

                transaction.classList.toggle('d-none', !isVisible);
                visibleTransactions += isVisible ? 1 : 0;
            });

            document.getElementById('reportTransactionCount').textContent = `${visibleTransactions} transaksi`;
        }

        function validateReportRange(startId, endId, errorId) {
            const startValue = document.getElementById(startId).value;
            const endValue = document.getElementById(endId).value;
            const errorElement = document.getElementById(errorId);

            errorElement.textContent = '';
            if (!startValue || !endValue) {
                errorElement.textContent = 'Pilih tanggal awal dan akhir.';
                return false;
            }

            const start = new Date(`${startValue}T00:00:00Z`);
            const end = new Date(`${endValue}T00:00:00Z`);
            const days = Math.floor((end - start) / 86400000) + 1;

            if (days < 1 || days > 31) {
                errorElement.textContent = 'Rentang tanggal maksimal 31 hari.';
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
