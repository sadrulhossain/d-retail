@extends('admin.index')
@section('title')
Admin Dashboard
@endsection

@section('content')
<div class="content mt-3">


    <div id="chart">
    </div>
    <div id="column">
    </div>
    <div id="gradient">
    </div>
    <div id="barChart">
    </div>
    <div id="pieChart">
    </div>


</div>
<script src="{{asset('public/admin/vendors/apexChart/apexcharts.js')}}"></script>
<script>


var options = {
    series: [{
            name: "Sale",
            data: [
<?php
if (!empty($monthArr)) {
    foreach ($monthArr as $month => $monthName) {
        $quantity = !empty($quantitySumArr[$month]) ? $quantitySumArr[$month] : 0;
        echo "'$quantity',";
    }
}
?>
            ]
        }],

    chart: {
        height: 350,
        type: 'line',
        zoom: {
            enabled: false
        }
    },
    dataLabels: {
        enabled: true,
		offsetY: -10,
    },
    stroke: {
        curve: 'smooth'
    },
    title: {
        text: 'Last 6 Month Report',
        align: 'left'
    },
    dropShadow: {
        enabled: true,
        top: 0,
        left: 0,
        blur: 3,
        opacity: 0.5
    },
    grid: {
        row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
        },
        
    },
    markers: {
        colors: ['#249EFA', '#E91E63', '#9C27B0'],
		size: 5
    },
    xaxis: {
        categories: [
<?php
if (!empty($monthArr)) {
    foreach ($monthArr as $month => $monthName) {
        echo "'$monthName',";
    }
}
?>
        ],
    }
};
var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();

var options = {
    series: [{
            name: 'Inflation',
            data: [

<?php
if (!empty($monthArr)) {
    foreach ($monthArr as $month => $monthName) {
        $quantity = !empty($quantitySumArr[$month]) ? $quantitySumArr[$month] : 0;
        echo "'$quantity',";
    }
}
?>
            ]
        }],
    chart: {
        height: 350,
        type: 'bar',
    },
    plotOptions: {
        bar: {
            dataLabels: {
                position: 'top', // top, center, bottom
            },
        }
    },
    dataLabels: {
        enabled: true,
        formatter: function (val) {
            return val + "%";
        },
        offsetY: -20,
        style: {
            fontSize: '12px',
            colors: ["#304758"]
        }
    },

    xaxis: {
        categories: [
<?php
if (!empty($monthArr)) {
    foreach ($monthArr as $month => $monthName) {
        echo "'$monthName',";
    }
}
?>
        ],
        position: 'top',
        axisBorder: {
            show: false
        },
        axisTicks: {
            show: false
        },
        crosshairs: {
            fill: {
                type: 'gradient',
                gradient: {
                    colorFrom: '#D8E3F0',
                    colorTo: '#BED1E6',
                    stops: [0, 100],
                    opacityFrom: 0.4,
                    opacityTo: 0.5,
                }
            }
        },
        tooltip: {
            enabled: true,
        }
    },
    yaxis: {
        axisBorder: {
            show: false
        },
        axisTicks: {
            show: false,
        },
        labels: {
            show: false,
            formatter: function (val) {
                return val + "%";
            }
        }

    },

    title: {
        text: 'Last 6 Month Report',
        floating: true,
        offsetY: 330,
        align: 'center',
        style: {
            color: '#444'
        }
    }
};

var chart = new ApexCharts(document.querySelector("#column"), options);
chart.render();

var options = {
    chart: {
        height: 280,
        type: "area"
    },
    dataLabels: {
        enabled: false
    },
    series: [
        {
            name: "Series 1",
            data: [
<?php
if (!empty($monthArr)) {
    foreach ($monthArr as $month => $monthName) {
        $quantity = !empty($quantitySumArr[$month]) ? $quantitySumArr[$month] : 0;
        echo "'$quantity',";
    }
}
?>
            ]
        }
    ],
    fill: {
        type: "gradient",
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.9,
            stops: [0, 90, 100]
        }
    },
    xaxis: {
        categories: [
<?php
if (!empty($monthArr)) {
    foreach ($monthArr as $month => $monthName) {
        echo "'$monthName',";
    }
}
?>
        ]
    }
};

var chart = new ApexCharts(document.querySelector("#gradient"), options);

chart.render();

var options = {
    series: [{
            data: [
<?php
if (!empty($monthArr)) {
    foreach ($monthArr as $month => $monthName) {
        $quantity = !empty($quantitySumArr[$month]) ? $quantitySumArr[$month] : 0;
        echo "'$quantity',";
    }
}
?>
            ]
        }],
    chart: {
        type: 'bar',
        height: 350
    },
    plotOptions: {
        bar: {
            horizontal: true,
        }
    },
    dataLabels: {
        enabled: false
    },
    xaxis: {
        categories: [
<?php
if (!empty($monthArr)) {
    foreach ($monthArr as $month => $monthName) {
        echo "'$monthName',";
    }
}
?>
        ],
    }
};

var chart = new ApexCharts(document.querySelector("#barChart"), options);
chart.render();


var options = {
    series: [
<?php
if (!empty($monthArr)) {
    foreach ($monthArr as $month => $monthName) {
        $quantity = !empty($quantitySumArr[$month]) ? $quantitySumArr[$month] : 0;
        echo "'$quantity',";
    }
}
?>
    ],
    chart: {
        width: 380,
        type: 'pie',
    },
    labels: [
<?php
if (!empty($monthArr)) {
    foreach ($monthArr as $month => $monthName) {
        echo "'$monthName',";
    }
}
?>
    ],
    responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
};

var chart = new ApexCharts(document.querySelector("#pieChart"), options);
chart.render();




</script>
@endsection
