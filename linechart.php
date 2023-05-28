<?php
include('koneksi.php');

//untuk menyimpan data negara
$countries = array(
    'India',
    'Japan',
    'S.Korea',
    'Turkey',
    'Vietnam',
    'Taiwan',
    'Iran',
    'Indonesia',
    'Malaysia',
    'Israel'
);

$data = array();
//untuk mengambil database dari tabel covid_19
foreach ($countries as $country) {
    $query = mysqli_query($koneksi, "SELECT * FROM tb_covid19 WHERE negara='$country'");
    $countryData = array();
    while ($p = mysqli_fetch_array($query)) {
        $countryData[] = $p['total_cases'];
        $countryData[] = $p['total_death'];
        $countryData[] = $p['total_recover'];
        $countryData[] = $p['active_cases'];
        $countryData[] = $p['total_test'];
    }
    $data[] = array(
        'label' => $country,
        'backgroundColor' => getRandomColor(),
        'borderColor' => getRandomColor(),
        'pointHoverBackgroundColor' => getRandomColor(),
        'pointHoverBorderColor' => getRandomColor(),
        'data' => $countryData
    );
}

function getRandomColor()
{
    $letters = '0123456789ABCDEF';
    $color = 'rgba(';
    for ($i = 0; $i < 3; $i++) {
        $color .= rand(0, 255) . ',';
    }
    $color .= '0.5)'; // Opacity set to 0.5 for transparency
    return $color;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>GRAFIK LINE COVID-19</title>
    <script src="Chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <style type="text/css">
    body {
        padding-top: 2%;
    }

    .container {
        width: 1400px;
        height: 625px;
    }
    </style>
</head>

<body>
    <center>
        <h2>LAPORAN GRAFIK LINE COVID-19</h2>
    </center>
    <br>
    <div class="container">
        <canvas id="linechart" width="100" height="100"></canvas>
    </div>
</body>

</html>

<script type="text/javascript">
var ctx = document.getElementById("linechart").getContext("2d");
var data = {
    labels: ["total_cases", "total_death", "total_recover", "active_cases", "total_test"],
    datasets: <?php echo json_encode($data); ?>
};

var myBarChart = new Chart(ctx, {
    type: 'line',
    data: data,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            display: true
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