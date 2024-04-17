<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Example</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.css">
</head>
<body>
    <div class="container">
        <canvas id="myChart"></canvas>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script>
        // สร้างข้อมูลตัวอย่างสำหรับกราฟ
        const data = {
            labels: ['Red', 'Blue', 'Yellow'],
            datasets: [{
                label: 'My Dataset',
                data: [20, 70, 10],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ],
                hoverOffset: 4
            }]
        };

        // กำหนดการตั้งค่าของกราฟ
        const config = {
            type: 'doughnut',
            data: data,
            options: {
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'My Doughnut Chart'
                    }
                }
            }
        };

        // สร้างกราฟด้วย Chart.js
        var myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
    </script>
</body>
</html>
