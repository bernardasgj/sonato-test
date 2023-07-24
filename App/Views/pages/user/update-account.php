<!DOCTYPE html>
<html>
	<head>
		<title>Poker-2000 Paskyros atnaujinimas</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" charset="UTF-8"/>

        <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="assets/css/user-register.css">
		<link rel="stylesheet" type="text/css" href="assets/css/base.css">
		<link rel="stylesheet" type="text/css" href="assets/css/shared/button.css">
		<link rel="stylesheet" type="text/css" href="assets/css/shared/header.css">
		<link rel="stylesheet" type="text/css" href="assets/css/shared/input-group.css">
		<link rel="stylesheet" type="text/css" href="assets/css/shared/loader-mask.css">
        <link rel="stylesheet" type="text/css" href="assets/css/shared/notification.css">
	</head>
	<body>
		<div class="wrapper">
			<!-- header component -->
			<?php include('App/Views/components/header.php')?>

			<div class="page-register">
			<?php include('App/Views/components/notification.php')?>

				<!-- register form header -->
				<div class="header">
					PASKYROS ATNAUJINIMAS
				</div>

				<!-- register form fields -->
				<form method="post" action="update_account" novalidate>
					<div class="input-group">
						<div class="input-field">
							<label>Prisijungimo vardas</label>
							<input disabled type="text" name="username" value="<?php echo isset($inputData['username']) ? $inputData['username'] : ''; ?>">
						</div>
						<?php if (isset($errors['username'])) echo '<div class="error">' . $errors['username'] . '</div>'; ?>
					</div>
					<div class="input-group">
						<div class="input-field">
							<label>Vardas</label>
							<input type="text" name="first_name" value="<?php echo isset($inputData['first_name']) ? $inputData['first_name'] : ''; ?>">
						</div>
						<?php if (isset($errors['first_name'])) echo '<div class="error">' . $errors['first_name'] . '</div>'; ?>
					</div>

					<div class="input-group">
						<div class="input-field">
							<label>Pavardė</label>
							<input type="text" name="last_name" value="<?php echo isset($inputData['last_name']) ? $inputData['last_name'] : ''; ?>">
						</div>
						<?php if (isset($errors['last_name'])) echo '<div class="error">' . $errors['last_name'] . '</div>'; ?>
					</div>

					<div class="input-group">
						<div class="input-field">
							<label>El. paštas</label>
							<input type="email" name="email" value="<?php echo isset($inputData['email']) ? $inputData['email'] : ''; ?>">
						</div>
						<?php if (isset($errors['email'])) echo '<div class="error">' . $errors['email'] . '</div>'; ?>
					</div>
					<div class="input-group">
						<div class="input-field">
							<label>Slaptažodis</label>
							<input type="password" name="password_1">
						</div>
						<?php if (isset($errors['password_1'])) echo '<div class="error">' . $errors['password_1'] . '</div>'; ?>
					</div>
					<div class="input-group">
						<div class="input-field">
							<label>Slaptažodžio pakartojimas</label>
							<input type="password" name="password_2">
						</div>
						<?php if (isset($errors['password_2'])) echo '<div class="error">' . $errors['password_2'] . '</div>'; ?>
					</div>
					<div class="input-group">
						<button type="submit" class="btn submit" name="update_user">Saugoti <i class="fa fa-angle-right"></i></button>
					</div>
				</form>
			</div>
		</div>
	</body>

	<!-- Scripts -->
	<script src="assets/js/update-poke-popup.js"></script>
	<script src="assets/js/popup-handler.js"></script>
</html>