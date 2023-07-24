<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" charset="UTF-8"/>

        <title>Poker-2000 Vartotojai</title>
        <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">

        <link rel="stylesheet" type="text/css" href="assets/css/base.css">
        <link rel="stylesheet" type="text/css" href="assets/css/user-index.css">

        <link rel="stylesheet" type="text/css" href="assets/css/shared/button.css">
        <link rel="stylesheet" type="text/css" href="assets/css/shared/header.css">
        <link rel="stylesheet" type="text/css" href="assets/css/shared/loader-mask.css">
        <link rel="stylesheet" type="text/css" href="assets/css/shared/notification.css">
        <link rel="stylesheet" type="text/css" href="assets/css/shared/pagination.css">
        <link rel="stylesheet" type="text/css" href="assets/css/shared/search.css">
    </head>
    <body>
        <div class="wrapper">
            <?php include('App/Views/components/header.php')?>

            <div class="page-index">
                <?php include('App/Views/components/notification.php')?>

                <div class="header">
                    Vartotojai
                </div>

                <div class="search-container">
                    <div class="search-input-container">
                        <label class="search-icon">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </label>
                        <input class="search-input" data-user-search-input type="text" placeholder="Ieškoti pagal vardą" value="<?php echo $userName; ?>">
                    </div>
                </div>

                <div class="loadable-content" data-user-data-container>
                    <?php include('App/Views/components/user-table.php')?>
                    <?php include('App/Views/components/pagination.php')?>
                </div>
            </div>
        </div>
        <!-- Scripts -->
        <script src="assets/js/update-poke-popup.js"></script>
        <script src="assets/js/popup-handler.js"></script>
        <script src="assets/js/poke-handler.js"></script>
        <script src="assets/js/user-search.js"></script>
    </body>
</html>