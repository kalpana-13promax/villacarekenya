<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villacare Kenya · Our Services</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- AOS CSS -->
    <?php include 'layout/link.php'; ?>
    
    <style>
        /* ===== BANNER SECTION (Matching Template) ===== */
        .vc-page-banner {
            position: relative;
            height: 350px;
            overflow: hidden;
            margin: 0;
        }

        .vc-page-banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(45%);
        }

        .vc-banner-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(15,26,36,0.8) 0%, rgba(30,47,60,0.6) 100%);
        }

        .vc-banner-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #fff;
            width: 90%;
            z-index: 2;
        }

        .vc-banner-title {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .vc-banner-title span {
            color: #d4a85f;
        }

        .vc-banner-breadcrumb {
            font-size: 14px;
            letter-spacing: 2px;
            color: #e0e0e0;
            text-transform: uppercase;
        }

        .vc-banner-breadcrumb a {
            color: #d4a85f;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .vc-banner-breadcrumb a:hover {
            color: #ffffff;
        }

        .vc-banner-breadcrumb i {
            font-size: 12px;
            margin: 0 8px;
            color: #d4a85f;
        }

        /* ===== MAIN CONTAINER ===== */
        .vc-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ===== SERVICES INTRO ===== */
        .vc-services-intro {
            padding: 80px 0 40px;
            background: #ffffff;
            text-align: center;
        }

        .vc-intro-tag {
            display: inline-block;
            background: rgba(212,168,95,0.1);
            color: #d4a85f;
            padding: 6px 20px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 20px;
            border: 1px solid rgba(212,168,95,0.2);
        }

        .vc-intro-title {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1e2a2e;
        }

        .vc-intro-title span {
            color: #d4a85f;
            position: relative;
            display: inline-block;
        }

        .vc-intro-title span::after {
            content: '';
            position: absolute;
            bottom: 8px;
            left: 0;
            width: 100%;
            height: 10px;
            background: rgba(212,168,95,0.2);
            z-index: -1;
        }

        .vc-intro-text {
            max-width: 700px;
            margin: 0 auto;
            color: #546e7a;
            font-size: 16px;
            line-height: 1.8;
        }

        /* ===== SERVICES GRID (Matching Template Style) ===== */
        .vc-services-grid {
            padding: 60px 0;
            background: #f8f6f4;
        }

        .vc-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .vc-service-item {
            background: #ffffff;
            border: 1px solid rgba(212,168,95,0.1);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .vc-service-item:hover {
            border-color: #d4a85f;
            box-shadow: 0 20px 40px -15px rgba(212,168,95,0.3);
            transform: translateY(-5px);
        }

        .vc-service-image {
            height: 240px;
            overflow: hidden;
            position: relative;
        }

        .vc-service-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .vc-service-item:hover .vc-service-image img {
            transform: scale(1.1);
        }

        .vc-service-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);
            height: 50%;
        }

        .vc-service-icon {
            position: absolute;
            bottom: 20px;
            left: 20px;
            width: 50px;
            height: 50px;
            background: #d4a85f;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 24px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .vc-service-content {
            padding: 30px 25px;
        }

        .vc-service-content h3 {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #1e2a2e;
            transition: color 0.3s ease;
        }

        .vc-service-item:hover .vc-service-content h3 {
            color: #d4a85f;
        }

        .vc-service-content p {
            color: #546e7a;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .vc-service-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #1e2a2e;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .vc-service-link i {
            font-size: 14px;
            transition: transform 0.3s ease;
        }

        .vc-service-link:hover {
            color: #d4a85f;
            gap: 12px;
        }

        .vc-service-link:hover i {
            transform: translateX(5px);
        }

        /* ===== FEATURED SERVICE SECTION ===== */
        .vc-featured-section {
            padding: 80px 0;
            background: #ffffff;
        }

        .vc-featured-row {
            display: flex;
            align-items: center;
            gap: 60px;
            background: #f8f6f4;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px -20px rgba(0,0,0,0.2);
        }

        .vc-featured-content {
            flex: 1;
            padding: 50px;
        }

        .vc-featured-tag {
            display: inline-block;
            background: #d4a85f;
            color: #ffffff;
            padding: 6px 20px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 20px;
            border-radius: 30px;
        }

        .vc-featured-content h2 {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1e2a2e;
        }

        .vc-featured-content h2 span {
            color: #d4a85f;
        }

        .vc-featured-content p {
            color: #546e7a;
            line-height: 1.8;
            margin-bottom: 25px;
            font-size: 15px;
        }

        .vc-featured-list {
            list-style: none;
            padding: 0;
            margin: 0 0 30px 0;
        }

        .vc-featured-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            color: #1e2a2e;
        }

        .vc-featured-list li i {
            color: #d4a85f;
            font-size: 18px;
        }

        .vc-featured-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #1e2f3c;
            color: #ffffff;
            padding: 14px 35px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: 2px solid #1e2f3c;
        }

        .vc-featured-btn:hover {
            background: transparent;
            color: #1e2f3c;
        }

        .vc-featured-image {
            flex: 1;
            height: 500px;
        }

        .vc-featured-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ===== PROCESS SECTION ===== */
        .vc-process-section {
            padding: 80px 0;
            background: #f8f6f4;
        }

        .vc-process-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .vc-process-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #1e2a2e;
        }

        .vc-process-header h2 span {
            color: #d4a85f;
        }

        .vc-process-header p {
            color: #546e7a;
            max-width: 600px;
            margin: 0 auto;
        }

        .vc-process-steps {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
        }

        .vc-step {
            text-align: center;
            position: relative;
        }

        .vc-step-number {
            width: 60px;
            height: 60px;
            background: #ffffff;
            border: 2px solid #d4a85f;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 22px;
            font-weight: 700;
            color: #d4a85f;
            transition: all 0.3s ease;
        }

        .vc-step:hover .vc-step-number {
            background: #d4a85f;
            color: #ffffff;
        }

        .vc-step h4 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #1e2a2e;
        }

        .vc-step p {
            color: #546e7a;
            font-size: 13px;
            line-height: 1.6;
        }

        /* ===== CTA SECTION ===== */
        .vc-cta-block {
            background: #1e2f3c;
            padding: 70px 0;
            color: #ffffff;
            text-align: center;
        }

        .vc-cta-block h2 {
            font-family: 'Playfair Display', serif;
            font-size: 38px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .vc-cta-block h2 span {
            color: #d4a85f;
        }

        .vc-cta-block p {
            max-width: 600px;
            margin: 0 auto 35px;
            color: #d0d8dd;
            font-size: 16px;
        }

        .vc-cta-group {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .vc-cta-primary {
            background: #d4a85f;
            color: #ffffff;
            padding: 14px 35px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: 2px solid #d4a85f;
        }

        .vc-cta-primary:hover {
            background: transparent;
            color: #d4a85f;
        }

        .vc-cta-secondary {
            background: transparent;
            color: #ffffff;
            padding: 14px 35px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 1px;
            border: 2px solid #ffffff;
            transition: all 0.3s ease;
        }

        .vc-cta-secondary:hover {
            background: #ffffff;
            color: #1e2f3c;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .vc-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .vc-process-steps {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .vc-featured-row {
                flex-direction: column;
            }
            
            .vc-featured-image {
                width: 100%;
                height: 350px;
            }
            
            .vc-banner-title {
                font-size: 42px;
            }
        }

        @media (max-width: 768px) {
            .vc-grid {
                grid-template-columns: 1fr;
            }
            
            .vc-process-steps {
                grid-template-columns: 1fr;
            }
            
            .vc-intro-title {
                font-size: 32px;
            }
            
            .vc-banner-title {
                font-size: 36px;
            }
            
            .vc-cta-group {
                flex-direction: column;
                gap: 15px;
            }
            
            .vc-cta-primary,
            .vc-cta-secondary {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .vc-featured-content {
                padding: 30px;
            }
            
            .vc-featured-content h2 {
                font-size: 28px;
            }
            
            .vc-intro-title {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <?php include 'layout/header.php'; ?>

    <!-- Page Banner -->
    <section class="vc-page-banner">
        <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=1600&auto=format&fit=crop" alt="Services">
        <div class="vc-banner-overlay"></div>
        <div class="vc-banner-content">
            <h1 class="vc-banner-title">Our <span>Services</span></h1>
            <div class="vc-banner-breadcrumb">
                <a href="#">Home</a> <i class="bi bi-chevron-right"></i> Services
            </div>
        </div>
    </section>

    <!-- Services Intro -->
    <section class="vc-services-intro">
        <div class="vc-container">
            <div class="vc-intro-tag">VILLACARE KENYA</div>
            <h2 class="vc-intro-title">Full-Service <span>Property</span> Solutions</h2>
            <p class="vc-intro-text">Villacare Kenya specialises in property sales, rentals, management and advisory services across Nairobi and the Coast — built for homeowners and investors.</p>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="vc-services-grid">
        <div class="vc-container">
            <div class="vc-grid">
                
                <!-- Service 1 -->
                <div class="vc-service-item" data-aos="fade-up">
                    <div class="vc-service-image">
                        <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=1200&auto=format&fit=crop" alt="Property Sales Kenya">
                        <div class="vc-service-overlay"></div>
                        <div class="vc-service-icon">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                    <div class="vc-service-content">
                        <h3>Property Sales & Acquisition</h3>
                        <p>We represent buyers and sellers across Nairobi, Mombasa and emerging suburbs — sourcing curated properties and negotiating favourable terms.</p>
                        <a href="for-sale.php" class="vc-service-link">Browse For Sale <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Service 2 -->
                <div class="vc-service-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="vc-service-image">
                        <img src="https://images.unsplash.com/photo-1556912172-45b7abe8b7e1?q=80&w=1200&auto=format&fit=crop" alt="Property Management Kenya">
                        <div class="vc-service-overlay"></div>
                        <div class="vc-service-icon">
                            <i class="bi bi-key"></i>
                        </div>
                    </div>
                    <div class="vc-service-content">
                        <h3>Property Management</h3>
                        <p>Full-service management: tenant sourcing, maintenance coordination, rent collection and transparent reporting designed for Kenyan landlords.</p>
                        <a href="services.php#management" class="vc-service-link">Our Management Plans <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Service 3 -->
                <div class="vc-service-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="vc-service-image">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=1200&auto=format&fit=crop" alt="Investment Advisory Kenya">
                        <div class="vc-service-overlay"></div>
                        <div class="vc-service-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                    </div>
                    <div class="vc-service-content">
                        <h3>Investment Advisory</h3>
                        <p>Market analysis, suburb reports and financial modelling to identify high-return opportunities and long-term growth corridors.</p>
                        <a href="services.php#advisory" class="vc-service-link">Request Advisory <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Service 4 -->
                <div class="vc-service-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="vc-service-image">
                        <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1200&auto=format&fit=crop" alt="Short & Long Term Rentals Kenya">
                        <div class="vc-service-overlay"></div>
                        <div class="vc-service-icon">
                            <i class="bi bi-house"></i>
                        </div>
                    </div>
                    <div class="vc-service-content">
                        <h3>Short & Long-Term Rentals</h3>
                        <p>Managed rental programmes for corporate clients, expatriates and local tenants — with marketing, bookings and property care included.</p>
                        <a href="for-rent.php" class="vc-service-link">View Rentals <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Service 5 -->
                <div class="vc-service-item" data-aos="fade-up" data-aos-delay="400">
                    <div class="vc-service-image">
                        <img src="https://images.unsplash.com/photo-1580048915913-4f8f5cb481c4?q=80&w=1200&auto=format&fit=crop" alt="Valuation & Appraisal Kenya">
                        <div class="vc-service-overlay"></div>
                        <div class="vc-service-icon">
                            <i class="bi bi-calculator"></i>
                        </div>
                    </div>
                    <div class="vc-service-content">
                        <h3>Valuation & Appraisal</h3>
                        <p>Accredited valuation reports for sales, mortgages and investment planning — prepared by experienced Kenyan valuers.</p>
                        <a href="services.php#valuation" class="vc-service-link">Request Valuation <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Service 6 -->
                <div class="vc-service-item" data-aos="fade-up" data-aos-delay="500">
                    <div class="vc-service-image">
                        <img src="https://images.unsplash.com/photo-1589829545856-d10d557cf95f?q=80&w=1200&auto=format&fit=crop" alt="Legal & Conveyancing Kenya">
                        <div class="vc-service-overlay"></div>
                        <div class="vc-service-icon">
                            <i class="bi bi-briefcase"></i>
                        </div>
                    </div>
                    <div class="vc-service-content">
                        <h3>Legal & Conveyancing</h3>
                        <p>Comprehensive conveyancing and transaction support including title searches, contracts and regulatory compliance for Kenyan property transfers.</p>
                        <a href="contact.php" class="vc-service-link">Contact Legal Team <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Featured Service -->
    <section class="vc-featured-section">
        <div class="vc-container">
            <div class="vc-featured-row">
                <div class="vc-featured-content">
                    <div class="vc-featured-tag">FEATURED SERVICE</div>
                    <h2>The Hass <span>Property Index</span></h2>
                    <p>Kenya's most trusted real estate data tool, providing quarterly insights into property price trends across Nairobi and the Coast.</p>
                    <ul class="vc-featured-list">
                        <li><i class="bi bi-check-circle-fill"></i> Quarterly market reports with suburb-level analysis</li>
                        <li><i class="bi bi-check-circle-fill"></i> Historical price data spanning 15+ years</li>
                        <li><i class="bi bi-check-circle-fill"></i> Investment grade insights for smart decisions</li>
                    </ul>
                    <a href="#" class="vc-featured-btn">Explore the Index <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="vc-featured-image">
                    <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=1200&auto=format&fit=crop" alt="Hass Property Index">
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="vc-process-section">
        <div class="vc-container">
            <div class="vc-process-header">
                <h2>How It <span>Works</span></h2>
                <p>A simple, transparent process from consultation to closing</p>
            </div>
            <div class="vc-process-steps">
                <div class="vc-step">
                    <div class="vc-step-number">1</div>
                    <h4>Consultation</h4>
                    <p>We meet to understand your needs, budget, and timeline</p>
                </div>
                <div class="vc-step">
                    <div class="vc-step-number">2</div>
                    <h4>Property Search</h4>
                    <p>We curate properties that match your criteria</p>
                </div>
                <div class="vc-step">
                    <div class="vc-step-number">3</div>
                    <h4>Viewings</h4>
                    <p>Accompanied visits to shortlisted properties</p>
                </div>
                <div class="vc-step">
                    <div class="vc-step-number">4</div>
                    <h4>Closing</h4>
                    <p>We guide you through negotiations and paperwork</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="vc-cta-block">
        <div class="vc-container">
            <h2>Ready to Get <span>Started?</span></h2>
            <p>Contact our team today for a free consultation and let us help you achieve your real estate goals.</p>
            <div class="vc-cta-group">
                <a href="#" class="vc-cta-primary"><i class="bi bi-telephone me-2"></i>Call Us Now</a>
                <a href="#" class="vc-cta-secondary"><i class="bi bi-calendar me-2"></i>Schedule Consultation</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'layout/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 40,
            easing: 'ease-out'
        });
    </script>
</body>
</html>