<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VillaCare Kenya · Page Not Found</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8f6f4 0%, #ffffff 100%);
            color: #1e2a2e;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Main Box */
        .vc-404-box {
            background: #ffffff;
            border-radius: 30px;
            padding: 60px 40px;
            max-width: 700px;
            width: 100%;
            box-shadow: 0 30px 60px -30px rgba(0,0,0,0.3);
            border: 1px solid rgba(212,168,95,0.2);
            text-align: center;
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Decorative Elements */
        .vc-404-box::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(212,168,95,0.05) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 0;
        }

        .vc-404-box::after {
            content: '';
            position: absolute;
            bottom: -50%;
            left: -50%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(212,168,95,0.05) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 0;
        }

        /* Content */
        .vc-404-content {
            position: relative;
            z-index: 2;
        }

        /* Icon */
        .vc-404-icon {
            width: 100px;
            height: 100px;
            background: rgba(212,168,95,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 50px;
            color: #d4a85f;
            border: 2px dashed #d4a85f;
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* 404 Number */
        .vc-404-number {
            font-family: 'Playfair Display', serif;
            font-size: 120px;
            font-weight: 700;
            color: #1e2a2e;
            line-height: 1;
            margin-bottom: 15px;
            text-shadow: 3px 3px 0 rgba(212,168,95,0.2);
        }

        .vc-404-number span {
            color: #d4a85f;
        }

        /* Title */
        .vc-404-title {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #1e2a2e;
        }

        .vc-404-title span {
            color: #d4a85f;
        }

        /* Text */
        .vc-404-text {
            font-size: 16px;
            color: #546e7a;
            margin-bottom: 30px;
            line-height: 1.7;
            max-width: 450px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Buttons */
        .vc-404-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .vc-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #d4a85f;
            color: #ffffff;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid #d4a85f;
        }

        .vc-btn-primary:hover {
            background: transparent;
            color: #d4a85f;
            transform: translateY(-3px);
        }

        .vc-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            color: #1e2a2e;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid #1e2a2e;
        }

        .vc-btn-secondary:hover {
            background: #1e2a2e;
            color: #ffffff;
            transform: translateY(-3px);
        }

        /* Quick Links */
        .vc-quick-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            padding-top: 20px;
            border-top: 1px solid rgba(212,168,95,0.2);
        }

        .vc-quick-links a {
            color: #546e7a;
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: color 0.3s ease;
        }

        .vc-quick-links a i {
            color: #d4a85f;
            font-size: 12px;
        }

        .vc-quick-links a:hover {
            color: #d4a85f;
        }
    </style>
</head>
<body>

    <div class="vc-404-box">
        <div class="vc-404-content">
            
            <!-- Icon -->
            <div class="vc-404-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>

            <!-- 404 Number -->
            <div class="vc-404-number">
                4<span>0</span>4
            </div>

            <!-- Title -->
            <h1 class="vc-404-title">
                Oops! Page <span>Not Found</span>
            </h1>

            <!-- Description -->
            <p class="vc-404-text">
                The page you are looking for might have been removed, 
                had its name changed, or is temporarily unavailable.
            </p>

            <!-- Buttons -->
            <div class="vc-404-buttons">
                <a href="index.php" class="vc-btn-primary">
                    <i class="bi bi-house"></i> Back to Home
                </a>
                <a href="contact.php" class="vc-btn-secondary">
                    <i class="bi bi-envelope"></i> Contact Us
                </a>
            </div>

            <!-- Quick Links -->
            <div class="vc-quick-links">
                <a href="property.php"><i class="bi bi-chevron-right"></i> Properties</a>
                <a href="services.php"><i class="bi bi-chevron-right"></i> Services</a>
                <a href="blog.php"><i class="bi bi-chevron-right"></i> Blog</a>
                <a href="about.php"><i class="bi bi-chevron-right"></i> About</a>
            </div>

        </div>
    </div>

</body>
</html>