<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>OBI - Modern Online Banking</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Modern Styles -->
    <link rel="stylesheet" href="assets/css/modern-style.css">
    
    <style>
        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
        }
        
        .hero-section {
            padding: 100px 0 80px;
            text-align: center;
            color: white;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            animation: fadeIn 0.8s ease-out;
        }
        
        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 2rem;
            opacity: 0.95;
            animation: fadeIn 1s ease-out 0.2s both;
        }
        
        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeIn 1.2s ease-out 0.4s both;
        }
        
        .hero-btn {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .hero-btn-primary {
            background: white;
            color: var(--primary-color);
        }
        
        .hero-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.3);
        }
        
        .hero-btn-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .hero-btn-outline:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-3px);
        }
        
        .features-section {
            padding: 60px 0;
        }
        
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        
        .feature-description {
            color: var(--text-secondary);
            line-height: 1.8;
        }
        
        .stats-section {
            padding: 60px 0;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .stat-item {
            text-align: center;
            color: white;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .cta-section {
            padding: 80px 0;
            text-align: center;
            color: white;
        }
        
        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .cta-subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .footer-modern {
            background: rgba(0, 0, 0, 0.2);
            color: white;
            padding: 2rem 0;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .hero-btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>

<body>
    <!-- Loading Screen -->
    <div class="loading-screen">
        <div class="loader"></div>
        <div class="loading-text">Loading OBI Banking...</div>
    </div>

    <!-- Modern Navbar -->
    <nav class="modern-navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <i class="fas fa-university"></i> OBI Banking
            </div>
            <ul class="navbar-menu">
                <li><a href="index.php" class="navbar-link">Home</a></li>
                <li><a href="PHP_Dashboard.php" class="navbar-link">Dashboard</a></li>
                <li><a href="transfermoney.php" class="navbar-link">Transfer</a></li>
                <li><a href="transactionhistory.php" class="navbar-link">History</a></li>
                <li><a href="PHP_Login.php" class="btn-modern btn-primary" style="color: white;">Login</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container-modern">
            <h1 class="hero-title">
                <i class="fas fa-shield-alt"></i> Modern Banking Made Simple
            </h1>
            <p class="hero-subtitle">
                Experience the future of banking with secure, fast, and reliable financial services
            </p>
            <div class="hero-buttons">
                <a href="PHP_Signup.php" class="hero-btn hero-btn-primary">
                    <i class="fas fa-user-plus"></i> Get Started
                </a>
                <a href="PHP_Login.php" class="hero-btn hero-btn-outline">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container-modern">
            <div class="grid grid-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="feature-title">Instant Transfers</h3>
                    <p class="feature-description">
                        Send money to anyone, anywhere in seconds. Fast, secure, and hassle-free transactions.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3 class="feature-title">Bank-Level Security</h3>
                    <p class="feature-description">
                        Your data is protected with advanced encryption and multi-layer security protocols.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="feature-title">Track Everything</h3>
                    <p class="feature-description">
                        Monitor your transactions, analyze spending patterns, and manage your finances effortlessly.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="feature-title">Mobile Ready</h3>
                    <p class="feature-description">
                        Access your account from any device. Responsive design for seamless banking on the go.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="feature-title">24/7 Access</h3>
                    <p class="feature-description">
                        Bank anytime, anywhere. Our services are available round the clock for your convenience.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="feature-title">Support Team</h3>
                    <p class="feature-description">
                        Get help when you need it. Our dedicated support team is always ready to assist you.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container-modern">
            <div class="grid grid-3">
                <div class="stat-item">
                    <div class="stat-number"><i class="fas fa-users"></i> 10K+</div>
                    <div class="stat-label">Active Users</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><i class="fas fa-exchange-alt"></i> 50K+</div>
                    <div class="stat-label">Transactions</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><i class="fas fa-dollar-sign"></i> ₱1M+</div>
                    <div class="stat-label">Transferred</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container-modern">
            <h2 class="cta-title">Ready to Get Started?</h2>
            <p class="cta-subtitle">Join thousands of users who trust OBI Banking for their financial needs</p>
            <a href="PHP_Signup.php" class="hero-btn hero-btn-primary">
                <i class="fas fa-rocket"></i> Create Account Now
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-modern">
        <div class="container-modern">
            <p>2024 © OBI Banking. All rights reserved. | Secure • Fast • Reliable</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="assets/js/loading.js"></script>
</body>
</html>