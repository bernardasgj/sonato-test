<!DOCTYPE html>
<html>
    <head>
        <title>Poker-2000 Pokes</title>

        <meta name="viewport" content="width=device-width, initial-scale=1" charset="UTF-8"/>
        <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="assets/css/base.css">
        <link rel="stylesheet" type="text/css" href="assets/css/shared/header.css">
        <link rel="stylesheet" type="text/css" href="assets/css/pokes-index.css">
        <link rel="stylesheet" type="text/css" href="assets/css/shared/loader-mask.css">
        <link rel="stylesheet" type="text/css" href="assets/css/shared/pagination.css">
        <link rel="stylesheet" type="text/css" href="assets/css/shared/input.css">
        <link rel="stylesheet" type="text/css" href="assets/css/shared/search.css">
    </head>
    <body>
        <div class="wrapper">
            <?php include('App/Views/components/header.php')?>
            <div class="page-index">
                <div class="header">
                    Poke istorija
                </div>
                <?php include('App/Views/components/notification.php')?>
                <div class="search-container">
                    <div class="search-input-container">
                        <label class="search-icon">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </label>
                        <input type="text" data-poke-search-input placeholder="Ieškoti pagal vardą" value="<?php echo $userName; ?>" >
                    </div>
                    <!-- Gan hacky solution tiek is css tiek is js. Speju geriau net default style palikt-->
                    <div class="search-input-container">
                        <input type="date" id="fromDate" data-poke-date-from-input placeholder="Date and Time" name="fromDate" value="<?php echo $fromDate ? $fromDate : ''; ?>" >
                        <div class="mock-input" data-mock-date="fromDate">Data nuo<?php echo $fromDate ? ': ' . $fromDate: '';?></div>
                    </div>
                    <div class="search-input-container">
                        <input type="date" id="toDate"data-poke-date-to-input name="toDate" value="<?php echo $fromDate ? $fromDate : date('Y-m-d'); ?>" >
                        <div class="mock-input" data-mock-date="toDate">Data iki<?php echo $toDate ? ': ' . $fromDate: '';?> </div>
                    </div>
                    </div>

                <div class="loadable-content" data-poke-data-container>
                <?php include('App/Views/components/pokes-table.php')?>
                <?php include('App/Views/components/pagination.php')?>
            </div>
        </div>
    </body>
    <script src="assets/js/poke-date-input-handler.js"></script>
    <script src="assets/js/update-poke-popup.js"></script>
    <script src="assets/js/popup-handler.js"></script>
    <script src="assets/js/poke-search.js"></script>
</html>
