<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VillaCare Kenya · About Us</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <?php include 'layout/link.php'; ?>
    <style>
        .vc-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ===== VC HERO SECTION ===== */
        .vc-about-hero {
            background: linear-gradient(135deg, #0f1a24 0%, #1e2f3c 100%);
            padding: 100px 0;
            color: #ffffff;
            border-bottom: 3px solid #d4a85f;
        }

        .vc-hero-content {
            display: flex;
            align-items: center;
            gap: 60px;
        }

        .vc-hero-text {
            flex: 1;
        }

        .vc-hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(212, 168, 95, 0.2);
            padding: 8px 20px;
            border-radius: 50px;
            margin-bottom: 25px;
            border: 1px solid rgba(212, 168, 95, 0.3);
            font-size: 13px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .vc-hero-tag i {
            color: #d4a85f;
        }

        .vc-hero-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 52px;
            font-weight: 700;
            margin-bottom: 25px;
            line-height: 1.2;
        }

        .vc-hero-text h1 span {
            color: #d4a85f;
            position: relative;
            display: inline-block;
        }

        .vc-hero-text h1 span::after {
            content: '';
            position: absolute;
            bottom: 8px;
            left: 0;
            width: 100%;
            height: 12px;
            background: rgba(212, 168, 95, 0.3);
            z-index: 0;
            border-radius: 4px;
        }

        .vc-hero-text p {
            font-size: 16px;
            line-height: 1.8;
            color: #d0d8dd;
            margin-bottom: 30px;
            max-width: 500px;
        }

        .vc-hero-stats {
            display: flex;
            gap: 40px;
            margin-top: 40px;
        }

        .vc-stat-box {
            text-align: left;
        }

        .vc-stat-box h3 {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 700;
            color: #d4a85f;
            margin-bottom: 5px;
        }

        .vc-stat-box p {
            font-size: 13px;
            color: #a0b0b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .vc-hero-image {
            flex: 1;
            position: relative;
        }

        .vc-hero-image img {
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 30px 60px -20px rgba(0,0,0,0.5);
            border: 3px solid rgba(212, 168, 95, 0.3);
        }

        .vc-experience-badge {
            position: absolute;
            bottom: -20px;
            left: -20px;
            background: #d4a85f;
            color: #0f1a24;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.3);
        }

        .vc-experience-badge h2 {
            font-size: 42px;
            font-weight: 700;
            margin: 0;
            line-height: 1;
        }

        .vc-experience-badge p {
            font-size: 12px;
            margin: 5px 0 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ===== VC MISSION SECTION ===== */
        .vc-mission-section {
            padding: 100px 0;
            background: #ffffff;
        }

        .vc-section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .vc-section-tag {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(212, 168, 95, 0.1);
            padding: 8px 20px;
            border-radius: 50px;
            margin-bottom: 20px;
            border: 1px solid rgba(212, 168, 95, 0.3);
        }

        .vc-section-tag span {
            color: #d4a85f;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .vc-section-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            margin-bottom: 15px;
            color: #0f1a24;
        }

        .vc-section-header h2 span {
            color: #d4a85f;
        }

        .vc-section-header p {
            color: #546e7a;
            max-width: 900px;
            margin: 0 auto;
            font-size: 16px;
        }

        .vc-mission-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 50px;
        }

        .vc-mission-card {
            background: #f8f6f4;
            padding: 40px 30px;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(212, 168, 95, 0.1);
        }

        .vc-mission-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 50px -30px rgba(212, 168, 95, 0.4);
            border-color: #d4a85f;
        }

        .vc-mission-icon {
            width: 80px;
            height: 80px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 36px;
            color: #d4a85f;
            border: 1px solid rgba(212, 168, 95, 0.2);
        }

        .vc-mission-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            margin-bottom: 15px;
            color: #0f1a24;
        }

        .vc-mission-card p {
            color: #546e7a;
            line-height: 1.7;
            font-size: 14px;
        }

        /* ===== VC STORY SECTION ===== */
        .vc-story-section {
            padding: 80px 0;
            background: #f8f6f4;
        }

        .vc-story-content {
            display: flex;
            gap: 50px;
            align-items: center;
        }

        .vc-story-text {
            flex: 1;
        }

        .vc-story-text h2 {
            font-family: 'Playfair Display', serif;
            font-size: 38px;
            margin-bottom: 25px;
            color: #0f1a24;
        }

        .vc-story-text h2 span {
            color: #d4a85f;
        }

        .vc-story-text p {
            color: #546e7a;
            margin-bottom: 20px;
            font-size: 15px;
            line-height: 1.8;
        }

        .vc-story-image {
            flex: 1;
        }

        .vc-story-image img {
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 20px 40px -15px rgba(0,0,0,0.2);
        }

        /* ===== VC VALUES SECTION ===== */
        .vc-values-section {
            padding: 100px 0;
            background: #ffffff;
        }

        .vc-values-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            margin-top: 50px;
        }

        .vc-value-item {
            display: flex;
            gap: 20px;
            padding: 30px;
            background: #f8f6f4;
            border-radius: 15px;
            transition: all 0.3s ease;
            border: 1px solid rgba(212, 168, 95, 0.1);
        }

        .vc-value-item:hover {
            transform: translateX(10px);
            border-color: #d4a85f;
            box-shadow: 0 20px 40px -20px rgba(212, 168, 95, 0.3);
        }

        .vc-value-icon {
            width: 60px;
            height: 60px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #d4a85f;
            border: 1px solid rgba(212, 168, 95, 0.2);
        }

        .vc-value-text h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #0f1a24;
        }

        .vc-value-text p {
            color: #546e7a;
            line-height: 1.6;
            font-size: 14px;
            margin: 0;
        }

        /* ===== VC TEAM SECTION ===== */
        .vc-team-section {
            padding: 100px 0;
            background: #f8f6f4;
        }

        .vc-team-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-top: 50px;
        }

        .vc-team-card {
            background: #ffffff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 30px -15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(212, 168, 95, 0.1);
        }

        .vc-team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 50px -20px rgba(212, 168, 95, 0.3);
            border-color: #d4a85f;
        }

        .vc-team-img {
            height: 280px;
            overflow: hidden;
        }

        .vc-team-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .vc-team-card:hover .vc-team-img img {
            transform: scale(1.1);
        }

        .vc-team-info {
            padding: 25px 20px;
            text-align: center;
        }

        .vc-team-info h4 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #0f1a24;
        }

        .vc-team-info p {
            color: #d4a85f;
            font-size: 13px;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .vc-team-social {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .vc-team-social a {
            width: 35px;
            height: 35px;
            background: #f8f6f4;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0f1a24;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(212, 168, 95, 0.1);
        }

        .vc-team-social a:hover {
            background: #d4a85f;
            color: #ffffff;
        }

        /* ===== VC CONTACT INFO SECTION ===== */
        .vc-contact-section {
            padding: 80px 0;
            background: #ffffff;
            border-top: 1px solid rgba(212, 168, 95, 0.2);
        }

        .vc-contact-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .vc-contact-card {
            text-align: center;
            padding: 40px 30px;
            background: #f8f6f4;
            border-radius: 15px;
            transition: all 0.3s ease;
            border: 1px solid rgba(212, 168, 95, 0.1);
        }

        .vc-contact-card:hover {
            transform: translateY(-5px);
            border-color: #d4a85f;
            box-shadow: 0 20px 40px -20px rgba(212, 168, 95, 0.3);
        }

        .vc-contact-icon {
            width: 70px;
            height: 70px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 30px;
            color: #d4a85f;
            border: 1px solid rgba(212, 168, 95, 0.2);
        }

        .vc-contact-card h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #0f1a24;
        }

        .vc-contact-card p {
            color: #546e7a;
            line-height: 1.6;
            margin: 0;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .vc-hero-content {
                flex-direction: column;
            }
            
            .vc-team-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .vc-mission-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .vc-story-content {
                flex-direction: column;
            }
            
            .vc-contact-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .vc-hero-text h1 {
                font-size: 42px;
            }
            
            .vc-mission-grid,
            .vc-values-grid,
            .vc-team-grid,
            .vc-contact-grid {
                grid-template-columns: 1fr;
            }
            
            .vc-value-item {
                flex-direction: column;
                text-align: center;
            }
            
            .vc-value-icon {
                margin: 0 auto;
            }
        }

        @media (max-width: 576px) {
            .vc-hero-text h1 {
                font-size: 32px;
            }
            
            .vc-hero-stats {
                flex-direction: column;
                gap: 20px;
            }
            
            .vc-experience-badge {
                left: 10px;
                padding: 15px;
            }
            
            .vc-experience-badge h2 {
                font-size: 32px;
            }
        }
    </style>
    
</head>
<body>

    <?php include 'layout/header.php'; ?>
    
    <!-- About Hero Section - vc prefix -->
    <section class="vc-about-hero">
        <div class="vc-container">
            <div class="vc-hero-content">
                <div class="vc-hero-text" data-aos="fade-right">
                    <div class="vc-hero-tag">
                        <i class="bi bi-building"></i>
                        <span>ABOUT VILLACARE KENYA</span>
                    </div>
                    
                    <h1>Your Trusted Partner in <span>Real Estate</span></h1>
                    
                    <p>VillaCare Kenya has been transforming the real estate landscape with premium properties, transparent dealings, and exceptional customer service since 2010.</p>
                    
                    <div class="vc-hero-stats">
                        <div class="vc-stat-box">
                            <h3>15+</h3>
                            <p>Years Experience</p>
                        </div>
                        <div class="vc-stat-box">
                            <h3>500+</h3>
                            <p>Properties Sold</p>
                        </div>
                        <div class="vc-stat-box">
                            <h3>98%</h3>
                            <p>Happy Clients</p>
                        </div>
                    </div>
                </div>
                
                <div class="vc-hero-image" data-aos="fade-left">
                    <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=1200&auto=format&fit=crop" alt="VillaCare Kenya Property">
                    
                    <div class="vc-experience-badge">
                        <h2>15+</h2>
                        <p>Years of Excellence</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Story Section -->
    <section class="vc-story-section">
        <div class="vc-container">
            <div class="vc-story-content">
                <div class="vc-story-text" data-aos="fade-right">
                    <h2>Our <span>Vision</span></h2>
                    <p>To become the recognized leader in providing integrated real estate services to every client we serve. To be able to identify new value added real estate investment opportunities for our clients with the use of our strong market intelligence.</p>
                </div>
                <div class="vc-story-image" data-aos="fade-left">
                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1200&auto=format&fit=crop" alt="VillaCare Kenya Office">
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="vc-mission-section">
        <div class="vc-container">
            <div class="vc-section-header" data-aos="fade-up">
                <div class="vc-section-tag">
                    <i class="bi bi-bullseye"></i>
                    <span>MISSION</span>
                </div>
                <h2>Our<span> Mission</span></h2>
                <p>Villa Care is committed to providing the finest integrated real estate solutions, by building relationships with our clients, employees and business providers in a professional, ethical and transparent manner.</p>
            </div>
            
            <div class="vc-mission-grid">
                <div class="vc-mission-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="vc-mission-icon">
                        <i class="bi bi-gem"></i>
                    </div>
                    <h3>Quality First</h3>
                    <p>We never compromise on quality. Every development meets the highest standards of construction and design.</p>
                </div>
                
                <div class="vc-mission-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="vc-mission-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h3>Data Driven</h3>
                    <p>Our market insights provide accurate information for informed investment decisions.</p>
                </div>
                
                <div class="vc-mission-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="vc-mission-icon">
                        <i class="bi bi-heart"></i>
                    </div>
                    <h3>Client Focused</h3>
                    <p>Your satisfaction is our priority. We guide you every step of the way with complete transparency.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="vc-values-section">
        <div class="vc-container">
            <div class="vc-section-header" data-aos="fade-up">
                <div class="vc-section-tag">
                    <i class="bi bi-star"></i>
                    <span>OUR VALUES</span>
                </div>
                <h2>The Principles We <span>Live By</span></h2>
                <p>Our core values shape everything we do at VillaCare Kenya</p>
            </div>
            
            <div class="vc-values-grid">
                <div class="vc-value-item" data-aos="fade-right" data-aos-delay="100">
                    <div class="vc-value-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="vc-value-text">
                        <h3>Integrity</h3>
                        <p>We conduct business with honesty and transparency, building trust with every client.</p>
                    </div>
                </div>
                
                <div class="vc-value-item" data-aos="fade-left" data-aos-delay="200">
                    <div class="vc-value-icon">
                        <i class="bi bi-lightbulb"></i>
                    </div>
                    <div class="vc-value-text">
                        <h3>Innovation</h3>
                        <p>We embrace new ideas and technologies to deliver cutting-edge developments.</p>
                    </div>
                </div>
                
                <div class="vc-value-item" data-aos="fade-right" data-aos-delay="300">
                    <div class="vc-value-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="vc-value-text">
                        <h3>Community</h3>
                        <p>We create spaces that bring people together and foster vibrant communities.</p>
                    </div>
                </div>
                
                <div class="vc-value-item" data-aos="fade-left" data-aos-delay="400">
                    <div class="vc-value-icon">
                        <i class="bi bi-award"></i>
                    </div>
                    <div class="vc-value-text">
                        <h3>Excellence</h3>
                        <p>We strive for excellence in everything we do, from design to customer service.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
 <section class="vc-team-section">
    <div class="vc-container">
        <div class="vc-section-header" data-aos="fade-up">
            <div class="vc-section-tag">
                <i class="bi bi-people"></i>
                <span>OUR TEAM - KENYA</span>
            </div>
            <h2>Meet VillaCare <span>Kenya Experts</span></h2>
            <p>Trusted real estate professionals serving Nairobi and across Kenya</p>
        </div>
        
        <div class="vc-team-grid">
            
            <!-- Team Member 1 -->
            <div class="vc-team-card" data-aos="fade-up" data-aos-delay="100">
                <div class="vc-team-img">
                    <img src="https://images.pexels.com/photos/3778680/pexels-photo-3778680.jpeg"
                         alt="Kenyan Business Professional - CEO VillaCare Kenya"
                         loading="lazy">
                </div>
                <div class="vc-team-info">
                    <h4>David Mwangi</h4>
                    <p>CEO & Founder - VillaCare Kenya</p>
                </div>
            </div>
            
            <!-- Team Member 2 -->
            <div class="vc-team-card" data-aos="fade-up" data-aos-delay="200">
                <div class="vc-team-img">
                    <img src="https://images.pexels.com/photos/3756679/pexels-photo-3756679.jpeg"
                         alt="Kenyan Female Professional - Sales Director Nairobi"
                         loading="lazy">
                </div>
                <div class="vc-team-info">
                    <h4>Grace Wanjiku</h4>
                    <p>Head of Sales - Nairobi Region</p>
                </div>
            </div>
            
            <!-- Team Member 3 -->
            <div class="vc-team-card" data-aos="fade-up" data-aos-delay="300">
                <div class="vc-team-img">
                    <img src="https://images.pexels.com/photos/4065158/pexels-photo-4065158.jpeg"
                         alt="Kenyan Property Consultant"
                         loading="lazy">
                </div>
                <div class="vc-team-info">
                    <h4>Samuel Otieno</h4>
                    <p>Senior Property Consultant</p>
                </div>
            </div>
            
            <!-- Team Member 4 -->
            <div class="vc-team-card" data-aos="fade-up" data-aos-delay="400">
                <div class="vc-team-img">
                    <img src="https://images.pexels.com/photos/3760067/pexels-photo-3760067.jpeg"
                         alt="Kenyan Marketing Director"
                         loading="lazy">
                </div>
                <div class="vc-team-info">
                    <h4>Faith Njeri</h4>
                    <p>Marketing Director - VillaCare Kenya</p>
                </div>
            </div>

        </div>
    </div>
</section>



    <!-- Contact Info Section -->
    <section class="vc-contact-section">
        <div class="vc-container">
            <div class="vc-section-header" data-aos="fade-up">
                <div class="vc-section-tag">
                    <i class="bi bi-geo-alt"></i>
                    <span>GET IN TOUCH</span>
                </div>
                <h2>Connect With <span>VillaCare Kenya</span></h2>
                <p>We're here to help you with all your real estate needs</p>
            </div>
            
            <div class="vc-contact-grid">
                <div class="vc-contact-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="vc-contact-icon">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <h3>Phone</h3>
                    <p>+254 721 325 902<br>+254 711 789 012</p>
                </div>
                
                <div class="vc-contact-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="vc-contact-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <h3>Email</h3>
                    <p>info@villacarekenya.com<br>sales@villacarekenya.com</p>
                </div>
                
                <div class="vc-contact-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="vc-contact-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <h3>Address</h3>
                    <p>New Rehema House,6th Floor, <br> Rhapta Road, Westlands. Nairobi, Kenya</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <?php include 'layout/footer.php'; ?>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 50
        });
    </script>
</body>
</html>