<?php
include('koneksi.php');
$produk = mysqli_query($koneksi,"SELECT * FROM tb_covid19");
while($row = mysqli_fetch_array($produk)){
	$nama_produk[] = $row['negara'];
	
	$query = mysqli_query($koneksi,"SELECT sum(total_cases) AS jumlah FROM tb_covid19 WHERE total_cases='".$row['total_cases']."'");
	$row = $query->fetch_array();
	$jumlah_produk[] = $row['jumlah'];
}
?>
<!doctype html>
<html>

<head>
    <title>GRAFIK PIE "Total Kasus" COVID-19</title>
    <script type="text/javascript" src="Chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <style>
    body {
        padding-top: 5%;
    }
    </style>
</head>

<body>
    <center>
        <h2>LAPORAN GRAFIK PIE "Total Kasus" COVID-19</h2>
    </center>
    <br>
    <center>
        <div id="canvas-holder" style="width:50%">
            <canvas id="chart-area"></canvas>
        </div>
    </center>

    <script>
    var config = {
        type: 'pie',
        data: {
            datasets: [{
                data: <?php echo json_encode($jumlah_produk); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(133, 196, 155, 0.2)',
                    'rgba(93, 33, 148, 0.2)',
                    'rgba(20, 4, 160, 0.2)',
                    'rgba(117, 48, 15, 0.2)',
                    'rgba(234, 214, 77, 0.2)',
                    'rgba(50, 96, 17, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(133, 196, 155, 1)',
                    'rgba(93, 33, 148, 1)',
                    'rgba(20, 4, 160, 1)',
                    'rgba(117, 48, 15, 1)',
                    'rgba(234, 214, 77, 1)',
                    'rgba(50, 96, 17, 1)'
                ],
                label: 'Presentase Kasus Covid'
            }],
            labels: <?php echo json_encode($nama_produk); ?>
        },
        options: {
            responsive: true
        }
    };

    window.onload = function() {
        var ctx = document.getElementById('chart-area').getContext('2d');
        window.myPie = new Chart(ctx, config);
    };

    document.getElementById('randomizeData').addEventListener('click', function() {
        config.data.datasets.forEach(function(dataset) {
            dataset.data = dataset.data.map(function() {
                return randomScalingFactor();
            });
        });

        window.myPie.update();
    });

    var colorNames = Object.keys(window.chartColors);
    document.getElementById('addDataset').addEventListener('click', function() {
        var newDataset = {
            backgroundColor: [],
            data: [],
            label: 'New dataset ' + config.data.datasets.length,
        };

        for (var index = 0; index < config.data.labels.length; ++index) {
            newDataset.data.push(randomScalingFactor());

            var colorName = colorNames[index % colorNames.length];
            var newColor = window.chartColors[colorName];
            newDataset.backgroundColor.push(newColor);
        }

        config.data.datasets.push(newDataset);
        window.myPie.update();
    });

    document.getElementById('removeDataset').addEventListener('click', function() {
        config.data.datasets.splice(0, 1);
        window.myPie.update();
    });
    </script>
</body>

</html>