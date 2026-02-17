<?php $page_seo_name = 'blog'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'layout/link.php'; ?>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <?php include 'layout/link.php'; ?>
    
    <style>
        /* * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: #1e2a2e;
            overflow-x: hidden;
        } */

        /* ===== BLOG BANNER ===== */
        .vc-blog-banner {
            position: relative;
            height: 450px;
            overflow: hidden;
            margin: 0;
        }

        .vc-blog-banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(40%);
            transition: transform 8s ease;
        }

        .vc-blog-banner:hover img {
            transform: scale(1.1);
        }

        .vc-banner-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(15,26,36,0.85) 0%, rgba(30,47,60,0.7) 100%);
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
            animation: fadeInUp 1s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate(-50%, 30%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        .vc-banner-title {
            font-family: 'Playfair Display', serif;
            font-size: 56px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 15px;
            text-shadow: 2px 2px 20px rgba(0,0,0,0.3);
        }

        .vc-banner-title span {
            color: #d4a85f;
            position: relative;
            display: inline-block;
        }

        .vc-banner-title span::after {
            content: '';
            position: absolute;
            bottom: 10px;
            left: 0;
            width: 100%;
            height: 12px;
            background: rgba(212,168,95,0.3);
            z-index: -1;
            border-radius: 4px;
        }

        .vc-banner-breadcrumb {
            font-size: 15px;
            letter-spacing: 2px;
            color: #e0e0e0;
        }

        .vc-banner-breadcrumb a {
            color: #d4a85f;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .vc-banner-breadcrumb a:hover {
            color: #ffffff;
        }

        /* ===== BLOG SECTION ===== */
        .vc-blog-section {
            padding: 80px 0;
            background: #f8f6f4;
            position: relative;
            overflow: hidden;
        }

        .vc-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Section Header */
        .vc-section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .vc-section-tag {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(212,168,95,0.1);
            padding: 8px 25px;
            border-radius: 50px;
            margin-bottom: 20px;
            border: 1px solid rgba(212,168,95,0.3);
        }

        .vc-section-tag span {
            color: #d4a85f;
            font-size: 13px;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .vc-section-tag i {
            color: #d4a85f;
        }

        .vc-section-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            margin-bottom: 15px;
            color: #1e2a2e;
        }

        .vc-section-header h2 span {
            color: #d4a85f;
        }

        .vc-section-header p {
            color: #546e7a;
            max-width: 600px;
            margin: 0 auto;
            font-size: 16px;
        }

        /* Blog Grid */
        .vc-blog-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 50px;
        }

        /* Blog Card */
        .vc-blog-card {
            background: #ffffff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 30px -15px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            border: 1px solid rgba(212,168,95,0.1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .vc-blog-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 30px 50px -20px rgba(212,168,95,0.3);
            border-color: #d4a85f;
        }

        /* Blog Image */
        .vc-blog-image {
            position: relative;
            height: 240px;
            overflow: hidden;
        }

        .vc-blog-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .vc-blog-card:hover .vc-blog-image img {
            transform: scale(1.1);
        }

        .vc-blog-category {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #d4a85f;
            color: #ffffff;
            padding: 6px 15px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            z-index: 2;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .vc-blog-date {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: rgba(0,0,0,0.6);
            color: #ffffff;
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 11px;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        /* Blog Content */
        .vc-blog-content {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .vc-blog-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            font-size: 12px;
            color: #8a9aa5;
        }

        .vc-blog-meta i {
            color: #d4a85f;
            margin-right: 5px;
        }

        .vc-blog-title {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
            line-height: 1.4;
            color: #1e2a2e;
            transition: color 0.3s ease;
        }

        .vc-blog-card:hover .vc-blog-title {
            color: #d4a85f;
        }

        .vc-blog-excerpt {
            color: #546e7a;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        .vc-blog-link {
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
            border-bottom: 2px solid transparent;
            padding-bottom: 5px;
            width: fit-content;
        }

        .vc-blog-link:hover {
            color: #d4a85f;
            border-bottom-color: #d4a85f;
            gap: 12px;
        }

        .vc-blog-link i {
            font-size: 14px;
            transition: transform 0.3s ease;
        }

        .vc-blog-link:hover i {
            transform: translateX(5px);
        }

        /* Featured Blog Card */
        .vc-blog-card.featured {
            grid-column: span 2;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
        }

        .vc-blog-card.featured .vc-blog-image {
            height: 100%;
            min-height: 300px;
        }

        .vc-blog-card.featured .vc-blog-content {
            padding: 35px;
        }

        .vc-blog-card.featured .vc-blog-title {
            font-size: 24px;
        }

        /* Sidebar */
        .vc-blog-sidebar {
            margin-top: 50px;
        }

        .vc-sidebar-widget {
            background: #ffffff;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(212,168,95,0.1);
        }

        .vc-widget-title {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(212,168,95,0.2);
            position: relative;
        }

        .vc-widget-title::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 50px;
            height: 2px;
            background: #d4a85f;
        }

        /* Search Widget */
        .vc-search-form {
            display: flex;
            gap: 10px;
        }

        .vc-search-input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            outline: none;
            transition: all 0.3s ease;
        }

        .vc-search-input:focus {
            border-color: #d4a85f;
            box-shadow: 0 0 0 3px rgba(212,168,95,0.1);
        }

        .vc-search-btn {
            background: #d4a85f;
            color: #ffffff;
            border: none;
            width: 50px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .vc-search-btn:hover {
            background: #1e2f3c;
            transform: scale(1.05);
        }

        /* Categories Widget */
        .vc-categories-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .vc-categories-list li {
            margin-bottom: 12px;
            border-bottom: 1px dashed #e5e5e5;
            padding-bottom: 12px;
        }

        .vc-categories-list li:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .vc-categories-list a {
            display: flex;
            justify-content: space-between;
            color: #546e7a;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .vc-categories-list a:hover {
            color: #d4a85f;
            transform: translateX(5px);
        }

        .vc-categories-list span {
            background: #f8f6f4;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        /* Popular Posts */
        .vc-popular-post {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f0f0f0;
        }

        .vc-popular-post:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .vc-popular-img {
            width: 70px;
            height: 70px;
            border-radius: 10px;
            overflow: hidden;
        }

        .vc-popular-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .vc-popular-post:hover .vc-popular-img img {
            transform: scale(1.1);
        }

        .vc-popular-info h4 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
            line-height: 1.4;
        }

        .vc-popular-info h4 a {
            color: #1e2a2e;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .vc-popular-info h4 a:hover {
            color: #d4a85f;
        }

        .vc-popular-info span {
            font-size: 11px;
            color: #8a9aa5;
        }

        /* Tags Widget */
        .vc-tags-cloud {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .vc-tag {
            background: #f8f6f4;
            color: #546e7a;
            padding: 6px 15px;
            border-radius: 30px;
            font-size: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .vc-tag:hover {
            background: #d4a85f;
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* Pagination */
        .vc-pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 50px;
        }

        .vc-page-link {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e5e5e5;
            color: #546e7a;
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 10px;
        }

        .vc-page-link:hover,
        .vc-page-link.active {
            background: #d4a85f;
            color: #ffffff;
            border-color: #d4a85f;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .vc-banner-title {
                font-size: 42px;
            }
            
            .vc-blog-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .vc-blog-card.featured {
                grid-column: span 1;
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .vc-banner-title {
                font-size: 32px;
            }
            
            .vc-blog-grid {
                grid-template-columns: 1fr;
            }
            
            .vc-section-header h2 {
                font-size: 32px;
            }
            
            .vc-blog-sidebar {
                margin-top: 30px;
            }
        }

        @media (max-width: 576px) {
            .vc-banner-title {
                font-size: 28px;
            }
            
            .vc-blog-card.featured .vc-blog-title {
                font-size: 20px;
            }
            
            .vc-search-form {
                flex-direction: column;
            }
            
            .vc-search-btn {
                width: 100%;
                height: 45px;
            }
        }
    </style>
</head>
<body>

    <!-- Header (include your header here) -->
    <?php include 'layout/header.php'; ?>

    <!-- Blog Banner -->
    <section class="vc-blog-banner">
        <img src="https://images.unsplash.com/photo-1499750310107-5fef28a66643?q=80&w=1600&auto=format&fit=crop" alt="Blog Banner">
        <div class="vc-banner-overlay"></div>
        <div class="vc-banner-content">
            <h1 class="vc-banner-title">Villacare <span>Blog</span></h1>
            <div class="vc-banner-breadcrumb">
                <a href="#">Home</a> <i class="bi bi-chevron-right" style="font-size: 12px; margin: 0 5px;"></i> Blog
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="vc-blog-section">
        <div class="vc-container">
            
            <!-- Section Header -->
            <div class="vc-section-header" data-aos="fade-up">
                <div class="vc-section-tag">
                    <i class="bi bi-pencil"></i>
                    <span>VILLACARE INSIGHTS</span>
                </div>
                <h2>Market <span>Insights</span> & Guidance</h2>
                <p>Villacare Kenya's latest analysis on property trends, investing and coastal & city living.</p>
            </div>

            <!-- Blog Grid -->
            <div class="vc-blog-grid">
                
                <!-- Featured Blog Post 1 -->
                <div class="vc-blog-card featured" data-aos="fade-up" data-aos-delay="100">
                    <div class="vc-blog-image">
                        <img src="https://images.unsplash.com/photo-1560448204-603b3fc33ddc?q=80&w=1200&auto=format&fit=crop" alt="Luxury Living">
                        <div class="vc-blog-category">Featured</div>
                        <div class="vc-blog-date"><i class="bi bi-calendar3"></i> Feb 15, 2026</div>
                    </div>
                    <div class="vc-blog-content">
                            <div class="vc-blog-meta">
                                <span><i class="bi bi-person"></i> By Sarah Njeri</span>
                                <span><i class="bi bi-chat"></i> 6 Comments</span>
                            </div>
                            <h3 class="vc-blog-title">The Future of Luxury Living in Nairobi</h3>
                            <p class="vc-blog-excerpt">How modern architecture and sustainable design are shaping premium developments across Nairobi — Villacare Kenya's perspective.</p>
                        <a href="#" class="vc-blog-link">Read More <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Blog Post 2 -->
                <div class="vc-blog-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="vc-blog-image">
                        <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?q=80&w=1200&auto=format&fit=crop" alt="Investment Tips">
                        <div class="vc-blog-category">Investment</div>
                        <div class="vc-blog-date"><i class="bi bi-calendar3"></i> Feb 10, 2026</div>
                    </div>
                    <div class="vc-blog-content">
                        <div class="vc-blog-meta">
                            <span><i class="bi bi-person"></i> By Michael Omondi</span>
                            <span><i class="bi bi-chat"></i> 4 Comments</span>
                        </div>
                        <h3 class="vc-blog-title">Top 5 Real Estate Investment Strategies for 2026</h3>
                        <p class="vc-blog-excerpt">Villacare Kenya's actionable strategies to maximise returns in Nairobi and the Coast.</p>
                        <a href="#" class="vc-blog-link">Read More <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Blog Post 3 -->
                <div class="vc-blog-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="vc-blog-image">
                        <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1200&auto=format&fit=crop" alt="Interior Design">
                        <div class="vc-blog-category">Design</div>
                        <div class="vc-blog-date"><i class="bi bi-calendar3"></i> Feb 5, 2026</div>
                    </div>
                    <div class="vc-blog-content">
                        <div class="vc-blog-meta">
                            <span><i class="bi bi-person"></i> By Elizabeth Wanjiku</span>
                            <span><i class="bi bi-chat"></i> 9 Comments</span>
                        </div>
                        <h3 class="vc-blog-title">Interior Design Trends That Never Go Out of Style</h3>
                        <p class="vc-blog-excerpt">Timeless design principles and practical tips for Kenyan homes and holiday properties.</p>
                        <a href="#" class="vc-blog-link">Read More <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Blog Post 4 -->
                <div class="vc-blog-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="vc-blog-image">
                        <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=1200&auto=format&fit=crop" alt="First Time Home Buyer">
                        <div class="vc-blog-category">Buyers Guide</div>
                        <div class="vc-blog-date"><i class="bi bi-calendar3"></i> Jan 28, 2026</div>
                    </div>
                    <div class="vc-blog-content">
                        <div class="vc-blog-meta">
                            <span><i class="bi bi-person"></i> By David Kimani</span>
                            <span><i class="bi bi-chat"></i> 11 Comments</span>
                        </div>
                        <h3 class="vc-blog-title">A Complete Guide for First-Time Home Buyers in Kenya</h3>
                        <p class="vc-blog-excerpt">Step-by-step guidance for first-time buyers — from budgeting to conveyancing with Villacare Kenya.</p>
                        <a href="#" class="vc-blog-link">Read More <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Blog Post 5 -->
                <div class="vc-blog-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="vc-blog-image">
                        <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=1200&auto=format&fit=crop" alt="Sustainable Living">
                        <div class="vc-blog-category">Sustainability</div>
                        <div class="vc-blog-date"><i class="bi bi-calendar3"></i> Jan 20, 2026</div>
                    </div>
                    <div class="vc-blog-content">
                        <div class="vc-blog-meta">
                            <span><i class="bi bi-person"></i> By Grace Mwangi</span>
                            <span><i class="bi bi-chat"></i> 3 Comments</span>
                        </div>
                        <h3 class="vc-blog-title">Eco-Friendly Homes: The Future of Real Estate</h3>
                        <p class="vc-blog-excerpt">Practical sustainability features that add value to Kenyan homes and lower long-term running costs.</p>
                        <a href="#" class="vc-blog-link">Read More <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Blog Post 6 -->
                <div class="vc-blog-card" data-aos="fade-up" data-aos-delay="600">
                    <div class="vc-blog-image">
                        <img src="https://images.unsplash.com/photo-1600607687644-c7171b42498e?q=80&w=1200&auto=format&fit=crop" alt="Market Analysis">
                        <div class="vc-blog-category">Market News</div>
                        <div class="vc-blog-date"><i class="bi bi-calendar3"></i> Jan 15, 2026</div>
                    </div>
                    <div class="vc-blog-content">
                        <div class="vc-blog-meta">
                            <span><i class="bi bi-person"></i> By James Kariuki</span>
                            <span><i class="bi bi-chat"></i> 5 Comments</span>
                        </div>
                        <h3 class="vc-blog-title">Nairobi Property Market: Q1 2026 Analysis</h3>
                        <p class="vc-blog-excerpt">Villacare Kenya's Q1 analysis highlighting price movement, rental yields and up-and-coming suburbs.</p>
                        <a href="#" class="vc-blog-link">Read More <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

            </div>

            <!-- Pagination -->
            <div class="vc-pagination" data-aos="fade-up">
                <a href="#" class="vc-page-link active">1</a>
                <a href="#" class="vc-page-link">2</a>
                <a href="#" class="vc-page-link">3</a>
                <a href="#" class="vc-page-link">4</a>
                <a href="#" class="vc-page-link"><i class="bi bi-chevron-right"></i></a>
            </div>

            <!-- Sidebar (Optional - can be removed if not needed) -->
            <div class="vc-blog-sidebar">
                <div class="row g-4">
                    <!-- Search Widget -->
                    <div class="col-md-6 col-lg-3">
                        <div class="vc-sidebar-widget" data-aos="fade-up">
                            <h4 class="vc-widget-title">Search</h4>
                            <form class="vc-search-form">
                                <input type="text" class="vc-search-input" placeholder="Search articles...">
                                <button class="vc-search-btn"><i class="bi bi-search"></i></button>
                            </form>
                        </div>
                    </div>

                    <!-- Categories Widget -->
                    <div class="col-md-6 col-lg-3">
                        <div class="vc-sidebar-widget" data-aos="fade-up" data-aos-delay="100">
                            <h4 class="vc-widget-title">Categories</h4>
                            <ul class="vc-categories-list">
                                <li><a href="#">Luxury Living <span>12</span></a></li>
                                <li><a href="#">Investment Tips <span>8</span></a></li>
                                <li><a href="#">Interior Design <span>15</span></a></li>
                                <li><a href="#">Buyers Guide <span>10</span></a></li>
                                <li><a href="#">Market News <span>7</span></a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Popular Posts Widget -->
                    <div class="col-md-6 col-lg-3">
                        <div class="vc-sidebar-widget" data-aos="fade-up" data-aos-delay="200">
                            <h4 class="vc-widget-title">Popular Posts</h4>
                            
                            <div class="vc-popular-post">
                                <div class="vc-popular-img">
                                    <img src="https://images.unsplash.com/photo-1560448204-603b3fc33ddc?q=80&w=200&auto=format&fit=crop" alt="Post">
                                </div>
                                <div class="vc-popular-info">
                                    <h4><a href="#">The Future of Luxury Living in Nairobi</a></h4>
                                        <span><i class="bi bi-calendar3"></i> Feb 15, 2026</span>
                                </div>
                            </div>

                            <div class="vc-popular-post">
                                <div class="vc-popular-img">
                                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=200&auto=format&fit=crop" alt="Post">
                                </div>
                                <div class="vc-popular-info">
                                    <h4><a href="#">Top 5 Real Estate Investment Strategies</a></h4>
                                    <span><i class="bi bi-calendar3"></i> Feb 10, 2026</span>
                                </div>
                            </div>

                            <div class="vc-popular-post">
                                <div class="vc-popular-img">
                                    <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=200&auto=format&fit=crop" alt="Post">
                                </div>
                                <div class="vc-popular-info">
                                    <h4><a href="#">Guide for First-Time Home Buyers</a></h4>
                                    <span><i class="bi bi-calendar3"></i> Jan 28, 2026</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tags Widget -->
                    <div class="col-md-6 col-lg-3">
                        <div class="vc-sidebar-widget" data-aos="fade-up" data-aos-delay="300">
                            <h4 class="vc-widget-title">Popular Tags</h4>
                            <div class="vc-tags-cloud">
                                <a href="#" class="vc-tag">Luxury</a>
                                <a href="#" class="vc-tag">Investment</a>
                                <a href="#" class="vc-tag">Nairobi</a>
                                <a href="#" class="vc-tag">Interior Design</a>
                                <a href="#" class="vc-tag">Real Estate</a>
                                <a href="#" class="vc-tag">Sustainability</a>
                                <a href="#" class="vc-tag">Market Trends</a>
                                <a href="#" class="vc-tag">First Time Buyer</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Footer (include your footer here) -->
    <?php include 'layout/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 50,
            easing: 'ease-out-cubic'
        });
    </script>
</body>
</html>