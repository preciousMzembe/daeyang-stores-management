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
    $Year = date("Y");
    if (isset($_POST['year'])) {
        $Year = $_POST['year'];
    }
    // get analytics
    $analytics = $database->get_analytics($Year);
    $yearly = $analytics['yearly'];
    $comparisone_years = $analytics['comparisone_years'];
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
                        <?php foreach ($analytics['years'] as $year) { ?>
                            <option value="<?php echo $year['year'] ?>" <?php if ($Year == $year['year']) {
                                                                            echo "selected";
                                                                        } ?> onclick="change_year('<?php echo $year['year'] ?>')">
                                <?php echo $year['year'] ?>
                            </option>
                        <?php } ?>
                    </select>
                    <!-- Year -->
                </div>
            </div>

            <!-- yearly items value -->
            <div class="year_chat">
                <canvas id="year_chat"></canvas>
            </div>
            <div class="yearly_total_stock_value">
                Total Inventory Value: <span class="total_stock_value">MK</span>
            </div>

            <!-- years comparison -->
            <div class="yearly_title">Comprehensive View Across All Years</div>
            <div class="year_chat">
                <canvas id="years_chat"></canvas>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // year chat
        const year = document.getElementById('year_chat');
        const yearly_values = [
            <?php
            $total_stock = 0;
            foreach ($yearly as $key => $value) {
                (float)$total_stock += (float)$value;
                echo $value . ",";
            }
            ?>
        ];

        const total_stock = '<?php echo "MK " . number_format($total_stock) ?>'
        $(".total_stock_value").text(total_stock)

        new Chart(year, {
            type: 'bar',
            data: {
                labels: ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ],
                datasets: [{
                    label: '<?php echo $Year ?> stock details',
                    data: yearly_values,
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
        let years_labels = [
            <?php
            foreach ($comparisone_years as $key => $value) {
                echo $key . ",";
            }
            ?>
        ];
        let years_values = [
            <?php
            foreach ($comparisone_years as $key => $value) {
                echo $value . ",";
            }
            ?>
        ];
        years_labels.reverse()
        years_values.reverse()

        new Chart(years, {
            type: 'scatter',
            data: {
                labels: years_labels,
                datasets: [{
                    type: 'line',
                    label: 'Value',
                    data: years_values,
                    fill: false,
                    borderColor: 'rgb(139, 90, 28)'
                }, {
                    type: 'bar',
                    label: 'Year Data',
                    data: years_values,
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
                },
            }
        });

        // change year
        function change_year(year) {
            let url = window.location.href;

            const form = document.createElement('form');
            form.method = "post";
            form.action = url;

            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = "year";
            hiddenField.value = year;

            form.appendChild(hiddenField);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>

</html>