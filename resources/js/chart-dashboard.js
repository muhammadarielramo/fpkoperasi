import Chart from 'chart.js/auto';
  // Data Dummy — bisa diganti dari database pakai AJAX / Blade variable
  const bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
  const simpananData = [5000000, 7500000, 8000000, 9000000, 10000000, 9500000];
  const pinjamanData = [3000000, 4500000, 4000000, 7000000, 6000000, 5000000];
  const angsuran = 12000000;
  const tunggakan = 4000000;

  // Grafik Simpanan
  new Chart(document.getElementById('simpananChart'), {
    type: 'line',
    data: {
      labels: bulan,
      datasets: [{
        label: 'Simpanan',
        data: simpananData,
        borderColor: 'green',
        backgroundColor: 'rgba(0, 128, 0, 0.1)',
        fill: true,
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });

  // Grafik Pinjaman
  new Chart(document.getElementById('pinjamanChart'), {
    type: 'bar',
    data: {
      labels: bulan,
      datasets: [{
        label: 'Pinjaman',
        data: pinjamanData,
        backgroundColor: 'orange'
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });

  // Grafik Angsuran vs Tunggakan
  new Chart(document.getElementById('angsuranChart'), {
    type: 'doughnut',
    data: {
      labels: ['Angsuran Dibayar', 'Tunggakan'],
      datasets: [{
        data: [angsuran, tunggakan],
        backgroundColor: ['#0d6efd', '#dc3545']
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'bottom' }
      }
    }
  });
