<!DOCTYPE html>

<html>
	<head>
		<title>Poker-2000 Prisijungimas</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" charset="UTF-8"/>

        <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="assets/css/user-login.css">
		<link rel="stylesheet" type="text/css" href="assets/css/base.css">
		<link rel="stylesheet" type="text/css" href="assets/css/shared/button.css">
        <link rel="stylesheet" type="text/css" href="assets/css/shared/header.css">
		<link rel="stylesheet" type="text/css" href="assets/css/shared/input-group.css">
		<link rel="stylesheet" type="text/css" href="assets/css/shared/loader-mask.css">
        <link rel="stylesheet" type="text/css" href="assets/css/shared/notification.css">
        <link rel="stylesheet" type="text/css" href="assets/css/shared/pagination.css">
        <link rel="stylesheet" type="text/css" href="assets/css/shared/search.css">
	</head>

	<body>
		<div class="wrapper">
			<?php include('./App/Views/components/header.php')?>

			<div class="page-login">
				<?php include('./App/Views/components/notification.php')?>

				<div class="header">
					PRISIJUNGIMAS
				</div>
				
				<form method="post" action="login">
					<div class="input-group">
						<input type="username" placeholder="Prisijungimo vardas" name="username" >
					</div>
					<div class="input-group">
						<input type="password" placeholder="Slaptažodis" name="password">
					</div>
					<div class="input-group">
						<button type="submit" class="btn submit" name="login_user">Prisijungti</button>
						<a class="btn register" href="register">
							Registruotis <i class="fa fa-angle-right"></i>
						</a>
					</div>
				</form>
			</div>
		</div>
	</body>
	<script src="assets/js/update-poke-popup.js"></script>
    <script src="assets/js/popup-handler.js"></script>
</html>