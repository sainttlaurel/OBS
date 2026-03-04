<?php 
session_start();

	include("PHP_Connection.php");
	include("PHP_Functions.php");


	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		//something was posted
		$user_name = mysqli_real_escape_string($con, $_POST['user_name']);
		$password = mysqli_real_escape_string($con, $_POST['password']);

		if(!empty($user_name) && !empty($password))
		{

			//read from database using prepared statement
			$query = "select * from users where user_name = ? limit 1";
			$stmt = mysqli_prepare($con, $query);
			mysqli_stmt_bind_param($stmt, "s", $user_name);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);

			if($result)
			{
				if($result && mysqli_num_rows($result) > 0)
				{

					$user_data = mysqli_fetch_assoc($result);
					
					if($user_data['password'] === $password)
					{

						$_SESSION['user_id'] = $user_data['user_id'];
						header("Location: PHP_Dashboard.php");
						die;
					}
					else
					{
						$error_message = "Invalid username or password!";
					}
				}
				else
				{
					$error_message = "Invalid username or password!";
				}
			}
			

		}else
		{
			$error_message = "Please enter both username and password!";
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login - OBI Banking</title>
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

		.login-container {
			background: white;
			border-radius: 24px;
			box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
			overflow: hidden;
			max-width: 1000px;
			width: 100%;
			display: grid;
			grid-template-columns: 1fr 1fr;
		}

		.login-left {
			background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
			padding: 60px 40px;
			color: white;
			display: flex;
			flex-direction: column;
			justify-content: center;
		}

		.login-left h1 {
			font-size: 2.5rem;
			font-weight: 800;
			margin-bottom: 1rem;
		}

		.login-left p {
			font-size: 1.1rem;
			opacity: 0.9;
			line-height: 1.6;
		}

		.login-features {
			margin-top: 2rem;
		}

		.feature-item {
			display: flex;
			align-items: center;
			gap: 1rem;
			margin-bottom: 1rem;
		}

		.feature-icon {
			width: 40px;
			height: 40px;
			background: rgba(255, 255, 255, 0.2);
			border-radius: 10px;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.login-right {
			padding: 60px 40px;
		}

		.login-header {
			text-align: center;
			margin-bottom: 2rem;
		}

		.login-header h2 {
			font-size: 2rem;
			font-weight: 700;
			color: var(--text-primary);
			margin-bottom: 0.5rem;
		}

		.login-header p {
			color: var(--text-secondary);
		}

		.form-group {
			margin-bottom: 1.5rem;
		}

		.form-label {
			display: block;
			font-weight: 600;
			margin-bottom: 0.5rem;
			color: var(--text-primary);
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

		.btn-login {
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

		.btn-login:hover {
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

		.signup-link {
			text-align: center;
			margin-top: 1.5rem;
			color: var(--text-secondary);
		}

		.signup-link a {
			color: var(--primary-color);
			font-weight: 600;
			text-decoration: none;
		}

		.signup-link a:hover {
			text-decoration: underline;
		}

		@media (max-width: 768px) {
			.login-container {
				grid-template-columns: 1fr;
			}

			.login-left {
				display: none;
			}

			.login-right {
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

	<div class="login-container">
		<div class="login-left">
			<h1><i class="fas fa-university"></i> OBI Banking</h1>
			<p>Welcome back! Sign in to access your account and manage your finances securely.</p>
			
			<div class="login-features">
				<div class="feature-item">
					<div class="feature-icon">
						<i class="fas fa-shield-alt"></i>
					</div>
					<div>
						<strong>Secure Access</strong><br>
						<small>Bank-level encryption</small>
					</div>
				</div>
				<div class="feature-item">
					<div class="feature-icon">
						<i class="fas fa-bolt"></i>
					</div>
					<div>
						<strong>Instant Transfers</strong><br>
						<small>Send money in seconds</small>
					</div>
				</div>
				<div class="feature-item">
					<div class="feature-icon">
						<i class="fas fa-chart-line"></i>
					</div>
					<div>
						<strong>Track Everything</strong><br>
						<small>Monitor all transactions</small>
					</div>
				</div>
			</div>
		</div>

		<div class="login-right">
			<div class="login-header">
				<h2>Sign In</h2>
				<p>Enter your credentials to continue</p>
			</div>

			<?php if(isset($error_message)): ?>
			<div class="alert alert-danger">
				<i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
			</div>
			<?php endif; ?>

			<form method="post">
				<div class="form-group">
					<label class="form-label">Username</label>
					<div class="input-wrapper">
						<i class="fas fa-user input-icon"></i>
						<input type="text" name="user_name" class="form-input" placeholder="Enter your username" required>
					</div>
				</div>

				<div class="form-group">
					<label class="form-label">Password</label>
					<div class="input-wrapper">
						<i class="fas fa-lock input-icon"></i>
						<input type="password" name="password" class="form-input" placeholder="Enter your password" required>
					</div>
				</div>

				<button type="submit" class="btn-login">
					<i class="fas fa-sign-in-alt"></i> Sign In
				</button>
			</form>

			<div class="divider">OR</div>

			<div class="signup-link">
				Don't have an account? <a href="PHP_Signup.php">Create Account</a>
			</div>

			<div class="signup-link" style="margin-top: 1rem;">
				<a href="index.php"><i class="fas fa-home"></i> Back to Home</a>
			</div>
		</div>
	</div>

	<script src="assets/js/loading.js"></script>
</body>
</html>