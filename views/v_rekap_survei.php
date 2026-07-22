<!-- Load Chart.js di footer/template -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row">
  <!-- Box IKM -->
  <div class="col-lg-3 col-xs-6">
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3><?= $ikm ?>%</h3>
        <p>Indeks Kepuasan Masyarakat</p>
      </div>
      <div class="icon"><i class="ion ion-stats-bars"></i></div>
    </div>
  </div>
  <!-- Box Total Responden -->
  <div class="col-lg-3 col-xs-6">
    <div class="small-box bg-green">
      <div class="inner">
        <h3><?= $total_responden ?></h3>
        <p>Total Responden</p>
      </div>
      <div class="icon"><i class="ion ion-person"></i></div>
    </div>
  </div>
</div>

<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Grafik Rata-rata Skor per Pertanyaan</h3>
    <div class="box-tools"><button class="btn btn-success btn-sm" onclick="window.print()"><i class="fa fa-print"></i> Print</button></div>
  </div>
  <div class="box-body">
    <canvas id="chartSurvei" style="height: 400px;"></canvas>
  </div>
</div>

<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">Saran & Masukan dari Peneliti</h3>
  </div>
  <div class="box-body">
    <table class="table table-bordered table-striped">
      <thead><tr><th>No</th><th>Tanggal</th><th>Saran</th></tr></thead>
      <tbody>
        <?php $no=1; foreach($rekap_text as $s): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= date('d-m-Y H:i', strtotime($s->tanggal_isi)) ?></td>
          <td><?= $s->jawaban ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($rekap_text)): ?>
        <tr><td colspan="3" class="text-center">Belum ada saran</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
// Data dari PHP ke JS
const labels = <?= json_encode(array_column($rekap_skala, 'pertanyaan')) ?>;
const data = <?= json_encode(array_column($rekap_skala, 'rata2')) ?>;

const ctx = document.getElementById('chartSurvei').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Rata-rata Skor 1-5',
            data: data,
            backgroundColor: 'rgba(60, 141, 188, 0.8)',
            borderColor: 'rgba(60, 141, 188, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true, max: 5 }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
</script>
