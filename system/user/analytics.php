<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/analytics.css">
</head>

<body>
    <!-- top -->
    <?php require("./top.php") ?>

    <!-- databse functions for this file --------------------------------------------- -->
    <?php

    ?>

    <!-- ---------------------------------------------------------------------------- -->

    <!-- body -->
    <section class="body_pane">
        <!-- navigation -->
        <?php require("./nav.php") ?>

        <!-- information -->
        <div class="details_pane">
            <!-- yearly details -->
            <div class="yearly_details_top">
                <div class="yearly_title">Yearly Stock Details</div>

                <!-- year choice -->
                <div class="year_choice_input">
                    <select name="year" id="year">
                        <option value="2023">2023</option>
                    </select>
                    <!-- Year -->
                </div>
            </div>

            <!-- chat -->
            <div class="year_chat">
                <canvas id="year_chat"></canvas>
            </div>
            <div class="yearly_total_stock_value">
                Total Stock Value: <span>MK 200,000</span>
            </div>

            <!-- yearly items value -->
            <div class="yearly_title">Comprehensive View Across All Years</div>
            <div class="year_chat">
                <canvas id="years_chat"></canvas>
            </div>

            <!-- years comparison -->
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // year chat
        const year = document.getElementById('year_chat');

        new Chart(year, {
            type: 'bar',
            data: {
                labels: ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ],
                datasets: [{
                    label: '2023 stock details',
                    data: [12, 19, 3, 5, 2, 3, 5, 8, 12, 19, 20, 13],
                    backgroundColor: [
                        'rgba(224, 157, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // all years
        const years = document.getElementById('years_chat');

        new Chart(years, {
            type: 'scatter',
            data: {
                labels: ["2018", "2019", "2020", "2021", "2022", "2023"],
                datasets: [{
                    type: 'line',
                    label: 'Value',
                    data: [10, 20, 30, 40, 30, 50],
                    fill: false,
                    borderColor: 'rgb(139, 90, 28)'
                }, {
                    type: 'bar',
                    label: 'Year Data',
                    data: [10, 20, 30, 40, 30, 50],
                    backgroundColor: 'rgba(224, 157, 1)'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>

</html>