<?php 
session_start();

	include("PHP_Connection.php");
	include("PHP_Functions.php");


	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		//something was posted
		$user_name = mysqli_real_escape_string($con, $_POST['user_name']);
		$user_email = mysqli_real_escape_string($con, $_POST['user_email']);
		$phone = mysqli_real_escape_string($con, $_POST['phone']);
		$password = mysqli_real_escape_string($con, $_POST['password']);

		if(!empty($user_name) && !empty($user_email) && !empty($phone) && !empty($password) && !is_numeric($user_name))
		{
			// Check if email already exists
			$check_query = "SELECT * FROM users WHERE user_email = ?";
			$stmt = mysqli_prepare($con, $check_query);
			mysqli_stmt_bind_param($stmt, "s", $user_email);
			mysqli_stmt_execute($stmt);
			$check_result = mysqli_stmt_get_result($stmt);
			
			if(mysqli_num_rows($check_result) > 0)
			{
				$error_message = "Email already exists! Please use a different email.";
			}
			else
			{
				//save to database using prepared statement
				$user_id = random_num(10);
				$query = "INSERT INTO users (user_id, user_email, phone, user_name, password, balance) VALUES (?, ?, ?, ?, ?, 10000.00)";
				$stmt = mysqli_prepare($con, $query);
				mysqli_stmt_bind_param($stmt, "sssss", $user_id, $user_email, $phone, $user_name, $password);
				
				if(mysqli_stmt_execute($stmt))
				{
					$success_message = "Registration successful! Redirecting to login...";
					header("refresh:2;url=PHP_Login.php");
				}
				else
				{
					$error_message = "Registration failed! Please try again.";
				}
			}
		}else
		{
			$error_message = "Please fill all fields correctly!";
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Sign Up - OBI Banking</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	
	<!-- Modern Styles -->
	<link rel="stylesheet" href="assets/css/modern-style.css">
	
	<style>
		body {
			display: flex;
			justify-content: center;
			align-items: center;
			min-height: 100vh;
			background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
			padding: 20px;
		}

		.signup-container {
			background: white;
			border-radius: 24px;
			box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
			overflow: hidden;
			max-width: 1000px;
			width: 100%;
			display: grid;
			grid-template-columns: 1fr 1fr;
		}

		.signup-left {
			background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
			padding: 60px 40px;
			color: white;
			display: flex;
			flex-direction: column;
			justify-content: center;
		}

		.signup-left h1 {
			font-size: 2.5rem;
			font-weight: 800;
			margin-bottom: 1rem;
		}

		.signup-left p {
			font-size: 1.1rem;
			opacity: 0.9;
			line-height: 1.6;
			margin-bottom: 2rem;
		}

		.benefits-list {
			list-style: none;
		}

		.benefit-item {
			display: flex;
			align-items: center;
			gap: 1rem;
			margin-bottom: 1rem;
			padding: 0.75rem;
			background: rgba(255, 255, 255, 0.1);
			border-radius: 10px;
		}

		.benefit-icon {
			width: 40px;
			height: 40px;
			background: rgba(255, 255, 255, 0.2);
			border-radius: 10px;
			display: flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
		}

		.signup-right {
			padding: 60px 40px;
			max-height: 90vh;
			overflow-y: auto;
		}

		.signup-header {
			text-align: center;
			margin-bottom: 2rem;
		}

		.signup-header h2 {
			font-size: 2rem;
			font-weight: 700;
			color: var(--text-primary);
			margin-bottom: 0.5rem;
		}

		.signup-header p {
			color: var(--text-secondary);
		}

		.form-group {
			margin-bottom: 1.25rem;
		}

		.form-label {
			display: block;
			font-weight: 600;
			margin-bottom: 0.5rem;
			color: var(--text-primary);
			font-size: 0.9rem;
		}

		.input-wrapper {
			position: relative;
		}

		.input-icon {
			position: absolute;
			left: 1rem;
			top: 50%;
			transform: translateY(-50%);
			color: var(--text-secondary);
		}

		.form-input {
			width: 100%;
			padding: 0.875rem 1rem 0.875rem 3rem;
			font-size: 1rem;
			border: 2px solid var(--border-color);
			border-radius: 12px;
			transition: var(--transition);
		}

		.form-input:focus {
			outline: none;
			border-color: var(--primary-color);
			box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
		}

		.btn-signup {
			width: 100%;
			padding: 1rem;
			font-size: 1.1rem;
			font-weight: 600;
			background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
			color: white;
			border: none;
			border-radius: 12px;
			cursor: pointer;
			transition: var(--transition);
			margin-top: 1rem;
		}

		.btn-signup:hover {
			transform: translateY(-2px);
			box-shadow: 0 10px 20px rgba(99, 102, 241, 0.4);
		}

		.divider {
			text-align: center;
			margin: 1.5rem 0;
			color: var(--text-secondary);
			position: relative;
		}

		.divider::before,
		.divider::after {
			content: '';
			position: absolute;
			top: 50%;
			width: 45%;
			height: 1px;
			background: var(--border-color);
		}

		.divider::before {
			left: 0;
		}

		.divider::after {
			right: 0;
		}

		.login-link {
			text-align: center;
			margin-top: 1.5rem;
			color: var(--text-secondary);
		}

		.login-link a {
			color: var(--primary-color);
			font-weight: 600;
			text-decoration: none;
		}

		.login-link a:hover {
			text-decoration: underline;
		}

		@media (max-width: 768px) {
			.signup-container {
				grid-template-columns: 1fr;
			}

			.signup-left {
				display: none;
			}

			.signup-right {
				padding: 40px 30px;
			}
		}
	</style>
</head>
<body>
	<!-- Loading Screen -->
	<div class="loading-screen">
		<div class="loader"></div>
		<div class="loading-text">Loading...</div>
	</div>

	<div class="signup-container">
		<div class="signup-left">
			<h1><i class="fas fa-rocket"></i> Join OBI Banking</h1>
			<p>Start your journey to better financial management with our modern banking platform.</p>
			
			<ul class="benefits-list">
				<li class="benefit-item">
					<div class="benefit-icon">
						<i class="fas fa-check"></i>
					</div>
					<div>
						<strong>Free Account</strong><br>
						<small>No hidden fees or charges</small>
					</div>
				</li>
				<li class="benefit-item">
					<div class="benefit-icon">
						<i class="fas fa-check"></i>
					</div>
					<div>
						<strong>₱10,000 Bonus</strong><br>
						<small>Starting balance for new users</small>
					</div>
				</li>
				<li class="benefit-item">
					<div class="benefit-icon">
						<i class="fas fa-check"></i>
					</div>
					<div>
						<strong>Instant Transfers</strong><br>
						<small>Send money in seconds</small>
					</div>
				</li>
				<li class="benefit-item">
					<div class="benefit-icon">
						<i class="fas fa-check"></i>
					</div>
					<div>
						<strong>24/7 Access</strong><br>
						<small>Bank anytime, anywhere</small>
					</div>
				</li>
			</ul>
		</div>

		<div class="signup-right">
			<div class="signup-header">
				<h2>Create Account</h2>
				<p>Fill in your details to get started</p>
			</div>

			<?php if(isset($error_message)): ?>
			<div class="alert alert-danger">
				<i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
			</div>
			<?php endif; ?>

			<?php if(isset($success_message)): ?>
			<div class="alert alert-success">
				<i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
			</div>
			<?php endif; ?>

			<form method="post">
				<div class="form-group">
					<label class="form-label">Full Name</label>
					<div class="input-wrapper">
						<i class="fas fa-user input-icon"></i>
						<input type="text" name="user_name" class="form-input" placeholder="Enter your full name" required>
					</div>
				</div>

				<div class="form-group">
					<label class="form-label">Email Address</label>
					<div class="input-wrapper">
						<i class="fas fa-envelope input-icon"></i>
						<input type="email" name="user_email" class="form-input" placeholder="Enter your email" required>
					</div>
				</div>

				<div class="form-group">
					<label class="form-label">Phone Number</label>
					<div class="input-wrapper">
						<i class="fas fa-phone input-icon"></i>
						<input type="text" name="phone" class="form-input" placeholder="Enter your phone number" required>
					</div>
				</div>

				<div class="form-group">
					<label class="form-label">Password</label>
					<div class="input-wrapper">
						<i class="fas fa-lock input-icon"></i>
						<input type="password" name="password" class="form-input" placeholder="Create a password" required>
					</div>
				</div>

				<button type="submit" class="btn-signup">
					<i class="fas fa-user-plus"></i> Create Account
				</button>
			</form>

			<div class="divider">OR</div>

			<div class="login-link">
				Already have an account? <a href="PHP_Login.php">Sign In</a>
			</div>

			<div class="login-link" style="margin-top: 1rem;">
				<a href="index.php"><i class="fas fa-home"></i> Back to Home</a>
			</div>
		</div>
	</div>

	<script src="assets/js/loading.js"></script>
</body>
</html>
