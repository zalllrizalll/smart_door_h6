<?php 
require_once __DIR__ . "/header.php"; 
require_once __DIR__ . "/../database.php";

$data_mhs   = mysqli_query($conn, "SELECT DATE_FORMAT(tanggal, '%D''%M') AS tgl FROM log_akses where role = 'mahasiswa'  GROUP BY tanggal ASC");
$mhs_in = mysqli_query($conn, "SELECT COUNT(role) AS jumlah FROM log_akses  where status ='check in' && role = 'mahasiswa' GROUP BY tanggal ASC");
$mhs_out = mysqli_query($conn, "SELECT COUNT(role) AS jumlah FROM log_akses  where status ='check out' && role = 'mahasiswa' GROUP BY tanggal ASC");
$data_dsn   = mysqli_query($conn, "SELECT DATE_FORMAT(tanggal, '%D''%M') AS tgl FROM log_akses_staf GROUP BY tanggal ASC");
$dsn_in = mysqli_query($conn, "SELECT COUNT(id) AS jumlah FROM log_akses_staf where status ='check in' GROUP BY tanggal ASC");
$dsn_out = mysqli_query($conn, "SELECT COUNT(id) AS jumlah FROM log_akses_staf  where status ='check out' GROUP BY tanggal ASC");


?>
<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
      <h1 class="mt-4">Chart</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Chart</li>
      </ol>
      
      <div class="row">
        <div class="col-xl-6">
          <div class="card mb-4">
            <div class="card-header">
              <i class="fas fa-chart-area me-1"></i>
              Chart Mahasiswa Masuk dan Keluar
            </div>
            <div class="card-body">
                <canvas id="barchartmhs" width="100%" height="40"></canvas>
            </div>          
          </div>
        </div>
        <div class="col-xl-6">
          <div class="card mb-4">
            <div class="card-header">
              <i class="fas fa-chart-area me-1"></i>
              Chart Staf Masuk dan Keluar
            </div>
            <div class="card-body">
                <canvas id="barchartdsn" width="100%" height="40"></canvas>
            </div>          
          </div>
        </div>
      </div>
    </div>
  </main>
  <footer class="py-4 bg-light mt-auto">
    <div class="container-fluid px-4">
      <div class="d-flex align-items-center justify-content-between small">
        <div class="text-muted">Copyright &copy; Bengkel Koding 2023</div>
        <div>
          <a href="#">Privacy Policy</a>
          &middot;
          <a href="#">Terms &amp; Conditions</a>
        </div>
      </div>
    </div>
  </footer>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="js/Chart.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>
<script  type="text/javascript">
  var ctx = document.getElementById("barchartmhs").getContext("2d");
  var data = {
    labels: [<?php while($data = mysqli_fetch_array($data_mhs)) {echo '"'.$data['tgl'].'",';}?>],
            datasets: [
            {
              label: "Mahasiswa Check In",
              data: [<?php while($data = mysqli_fetch_array($mhs_in)) {echo '"'.$data['jumlah'].'",';}?>],
              backgroundColor: ['rgb(255, 99, 132)', 'rgba(56, 86, 255, 0.87)', 'rgb(60, 179, 113)','rgb(175, 238, 239)'],
              borderColor: ['rgb(255, 99, 132)']
            },
            {
              label: "Mahasiswa Check Out",
              data: [<?php while($data = mysqli_fetch_array($mhs_out)) {echo '"'.$data['jumlah'].'",';}?>],
              backgroundColor: ['rgb(255, 99, 132)', 'rgba(56, 86, 255, 0.87)', 'rgb(60, 179, 113)','rgb(175, 238, 239)'],
              borderColor: ['rgb(255, 99, 132)']
            }
            ]        
            };

  var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
            legend: {
              display: false
            },
            barValueSpacing: 20,
            scales: {
              yAxes: [{
                  ticks: {
                      min: 0,
                  }
              }],
              xAxes: [{
                          gridLines: {
                              color: "rgba(0, 0, 0, 0)",
                          }
                      }]
              }
          }
        });
</script>
<script  type="text/javascript">
  var ctx = document.getElementById("barchartdsn").getContext("2d");
  var data = {
    labels: [<?php while($data = mysqli_fetch_array($data_dsn)) {echo '"'.$data['tgl'].'",';}?>],
            datasets: [
            {
              label: "Staf Check In",
              data: [<?php while($data = mysqli_fetch_array($dsn_in)) {echo '"'.$data['jumlah'].'",';}?>],
              backgroundColor: ['rgb(255, 99, 132)', 'rgba(56, 86, 255, 0.87)', 'rgb(60, 179, 113)','rgb(175, 238, 239)'],
              borderColor: ['rgb(255, 99, 132)']
            },
            {
              label: "Staf Check Out",
              data: [<?php while($data = mysqli_fetch_array($dsn_out)) {echo '"'.$data['jumlah'].'",';}?>],
              backgroundColor: ['rgb(255, 99, 132)', 'rgba(56, 86, 255, 0.87)', 'rgb(60, 179, 113)','rgb(175, 238, 239)'],
              borderColor: ['rgb(255, 99, 132)']
            }
            ]        
            };

  var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
            legend: {
              display: false
            },
            barValueSpacing: 20,
            scales: {
              yAxes: [{
                  ticks: {
                      min: 0,
                  }
              }],
              xAxes: [{
                          gridLines: {
                              color: "rgba(0, 0, 0, 0)",
                          }
                      }]
              }
          }
        });
</script>

</body>

</html>