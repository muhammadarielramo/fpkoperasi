@extends('layouts.admin.app', ['title' => 'Dashboard'])

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@section('content')

<section class="section">


    <div class="container mt-5">
        <div class="row g-4">

        {{-- notifikasi --}}
        @if($notifPinjamanBaru > 0 || $notifAnggotaBaru > 0)
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <div>
                        <strong>🔔 Notifikasi:</strong>
                        <ul class="mb-0">
                            @if($notifPinjamanBaru > 0)
                                <li>{{ $notifPinjamanBaru }} pengajuan pinjaman baru menunggu ditinjau.</li>
                            @endif
                            @if($notifAnggotaBaru > 0)
                                <li>{{ $notifAnggotaBaru }} pendaftaran anggota baru menunggu verifikasi.</li>
                            @endif
                        </ul>
                    </div>
                </div>
        @endif

            <!-- Jumlah Anggota -->
        <div class="col-md-4">
          <div class="card border-primary shadow-sm">
            <div class="card-body">
              <h5 class="card-title text-primary">Jumlah Anggota</h5>
              <h3 class="card-text">{{$countMember}}</h3>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card border-success shadow-sm">
            <div class="card-body">
              <h5 class="card-title text-success">Total Simpanan</h5>
                <h3 class="card-text">Rp {{ number_format($countDeposit, 0, ',', '.') }}</h3>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card border-warning shadow-sm">
            <div class="card-body">
              <h5 class="card-title text-warning">Pinjaman Aktif</h5>
              <h3 class="card-text">Rp {{ number_format($loanActive, 0, ',', '.') }}</h3>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card border-dark shadow-sm">
            <div class="card-body">
              <h5 class="card-title text-dark">Kolektor Aktif</h5>
              <h3 class="card-text">{{$countCollector}}</h3>
            </div>
          </div>
        </div>


      </div>
    </div>


    <div class="container mt-5">
      <h2 class="mb-4">Statistik Keuangan</h2>
      <div class="row g-4">

        <!-- Grafik Simpanan per Bulan -->
        <div class="col-md-6">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Simpanan per Bulan</h5>
              <canvas id="simpananChart" height="200"></canvas>
            </div>
          </div>
        </div>

        <!-- Grafik Pinjaman per Bulan -->
        <div class="col-md-6">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Pinjaman per Bulan</h5>
              <canvas id="pinjamanChart" height="200"></canvas>
            </div>
          </div>
        </div>

      </div>
    </div>





</section>
<script>
        const labels = @json($labels);
        const dataSimpanan = @json($simpanan);
        const dataPinjaman = @json($pinjaman);

        const ctx1 = document.getElementById('simpananChart');
        if (ctx1) {
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Simpanan',
                        data: dataSimpanan,
                        backgroundColor: 'green'
                    }]
                }
            });
        }

        const ctx2 = document.getElementById('pinjamanChart');
        if (ctx2) {
            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pinjaman',
                        data: dataPinjaman,
                        borderColor: 'blue',
                        backgroundColor: 'rgba(0,0,255,0.1)',
                        fill: true
                    }]
                }
            });
        }
    </script>
@endsection
