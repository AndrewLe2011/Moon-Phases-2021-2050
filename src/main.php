<?php
require "config.php";

// handle year search request. Otherwise, display result for current year
if (isset($_GET['save']) && $_GET['save'] == "search") {
    $year = $_GET['year'];
    $query1 = "SELECT * FROM `bigquery-public-data.moon_phases.moon_phases`
    WHERE `peak_datetime` > '".$year."-01-01 00:00:00' 
    AND `peak_datetime` < '".$year."-12-31 23:59:59'
    ORDER BY `peak_datetime`";
    $jobConfig = $bigQuery->query($query1);
    $job = $bigQuery->startQuery($jobConfig);
    $queryResult1 = $job->queryResults();
    $displayThis1 = $queryResult1;
} else {
    $fullCurrentDate = date("Y-m-i H:m:s");
    $currentYear = date("Y");
    $query1 = "SELECT * FROM `bigquery-public-data.moon_phases.moon_phases`
    WHERE `peak_datetime` > '".$fullCurrentDate."' 
    AND `peak_datetime` < '".$currentYear."-12-31 23:59:59'
    ORDER BY `peak_datetime`";
    $jobConfig = $bigQuery->query($query1);
    $job = $bigQuery->startQuery($jobConfig);
    $queryResult1 = $job->queryResults();
    $displayThis1 = $queryResult1;
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/table.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" />
    <!-- styling -->

    <!-- bootstrap 4.6.0 -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <!-- bootstrap 4.6.0 -->

    <!-- other handlers for table and form -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src=" https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $(window).on('load', function() {
                setTimeout(() => {
                    $('#on-loading').removeClass("d-block");
                    $('#on-loading').addClass("d-none");
                    $('#done-loading').removeClass("d-none");
                    $('#done-loading').addClass("d-block");
                    $('table').removeClass("invisible");
                    $('table').addClass("display");
                }, 2000);

                $("#form-input-area").submit(function(event) {
                    var vForm = $(this);
                    if (vForm[0].checkValidity() === false) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    vForm.addClass('was-validated');
                });
            })

            $('#data-table').DataTable({
                "dom": '<"top"l>rt<"bottom"ip><"clear">',
                "order": [
                    [2, "asc"]
                ],
                "scrollX": true,
                "oLanguage": {
                    "sLengthMenu": 'Display <select>' +
                        '<option value="10">10</option>' +
                        '<option value="25">25</option>' +
                        '<option value="50">All</option>' +
                        '</select> records'
                }
            });
        });
    </script>
    <!-- other handlers for table and form -->

    <title>Moon Phases</title>
</head>

<body style="background-color: #141727">
    <!-- navigation bar start -->
    <div id="navbar-container">
        <nav class="navbar navbar-expand-md navbar-light d-flex justify-content-center align-items-center" style="font-family:Georgia, 'Times New Roman', Times, serif">
            <a href="https://asm1cc21bp2.et.r.appspot.com/" class="navbar-brand mx-0">
                🌚<span id="navbar-title" class="h3" style="color: #ff6242; font-style: italic; font-weight: bold;"> For all the moon enthusiasts out there</span> 🌝
            </a>
        </nav>
    </div>
    <!-- navigation bar end -->

    <!-- messages while loading and when done loading -->
    <?php
        echo '<div id="on-loading" class="alert alert-primary d-block text-center py-3 mb-5">Loading';
        echo '<span class="spinner-border spinner-border-sm text-primary ml-2"></span>';
        echo '</div>';
        echo '<div id="done-loading" class="d-none alert alert-success alert-dimissible text-center py-3 mb-5 fade show">';
        echo 'All set! 😊';
        echo '
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>';
        echo '</div>';
    ?>
    <!-- messages while loading and when done loading -->

    <!-- table start -->
    <div class="container bg-light my-5 px-4 py-4" id="container1">
        <h3 id="table-purpose" class="text-center mb-3" style="color: #ff6242; font-size: 1.75em; 
        font-weight: bold;font-family:Georgia, 'Times New Roman', Times, serif">
            Upcoming Moon Phases
        </h3>
        <table id="data-table" class="nowrap invisible" style="width:100%;">
            <thead>
                <tr>
                    <?php
                    foreach ($displayThis1 as $key => $value) {
                        foreach ($value as $header => $column) {
                            echo "<th>" . $header . "</th>";
                        }
                        break;
                    }
                    ?>
                </tr>
            </thead>

            <tbody>
                <?php
                foreach ($displayThis1 as $key => $value) {
                    $count = 0;
                    echo "<tr>";
                    foreach ($value as $header => $column) {
                        if ($count < 2) {
                            echo '<td>' . $column . '</td>';
                        } else {
                            echo '<td>' . date_format($column, "Y-m-d H:i:s") . '</td>';
                        }
                        $count++;
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- table end -->
    
    <!-- form to search by year start -->
    <div class="container bg-light mb-5 px-3 py-3 d-flex flex-column align-items-center" id="container2">
        <h2 id="form-purpose" class="text-center" style="color: #ff6242; font-size: 1.75em; 
        font-weight: bold; font-family:Georgia, 'Times New Roman', Times, serif">Pick a Year</h2>
        <form id="form-input-area" action="main.php" method="GET" class="row w-100 justify-content-center align-items-center my-0" autocomplete="off" novalidate>
            <div class="col-md-12 mb-3">
                <input class="form-control" required min="2021" max="2050" type="number" id="year" name="year" placeholder="<?php echo date("Y"); ?> to 2050" value="<?php echo date("Y");?>">
                <div class="valid-feedback">Here we go!</div>
                <div class="invalid-feedback">Please enter a year from <?php echo date("Y"); ?> to 2050</div>
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center w-100">
                <button class="btn btn-primary btn-md btn-block w-50" type="submit" name="save" value="search" style="border-radius: 30px;">Bring it</button>
            </div>
        </form>
    </div>
     <!-- form to search by year end -->
</body>

</html>