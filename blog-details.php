<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VillaCare Kenya · Blog Details</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- AOS CSS -->
    <?php include 'layout/link.php'; ?>
    
    <style>
        /* * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8f6f4;
            color: #1e2a2e;
            line-height: 1.6;
        } */

        .vc-container {
            /* max-width: 1000px;
            margin: 0 auto; */
            padding: 60px 20px;
        }

        /* ===== BREADCRUMB ===== */
        .vc-breadcrumb {
            margin-bottom: 30px;
            font-size: 14px;
            color: #546e7a;
        }

        .vc-breadcrumb a {
            color: #d4a85f;
            text-decoration: none;
        }

        .vc-breadcrumb i {
            font-size: 12px;
            margin: 0 8px;
            color: #d4a85f;
        }

        /* ===== BLOG CARD ===== */
        .vc-blog-card {
            background: #ffffff;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid #e5e5e5;
        }

        /* Blog Meta */
        .vc-blog-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #546e7a;
            flex-wrap: wrap;
        }

        .vc-blog-meta i {
            color: #d4a85f;
            margin-right: 5px;
        }

        .vc-blog-meta span {
            color: #1e2a2e;
            font-weight: 600;
            margin-right: 5px;
        }

        .vc-blog-category {
            display: inline-block;
            background: rgba(212,168,95,0.1);
            color: #d4a85f;
            padding: 5px 15px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            margin-right: 15px;
        }

        /* Blog Title */
        .vc-blog-title {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #1e2a2e;
            line-height: 1.2;
        }

        /* Featured Image */
        .vc-blog-image {
            margin-bottom: 30px;
            border-radius: 10px;
            overflow: hidden;
        }

        .vc-blog-image img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Blog Content */
        .vc-blog-content {
            font-size: 16px;
            color: #546e7a;
        }

        .vc-blog-content h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 600;
            margin: 30px 0 15px;
            color: #1e2a2e;
        }

        .vc-blog-content p {
            margin-bottom: 20px;
        }

        .vc-blog-content ul {
            margin-bottom: 20px;
            padding-left: 20px;
        }

        .vc-blog-content li {
            margin-bottom: 8px;
        }

        /* Tags */
        .vc-blog-tags {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e5e5e5;
        }

        .vc-tags-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #1e2a2e;
        }

        .vc-tags-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .vc-tag {
            background: #f8f6f4;
            color: #546e7a;
            padding: 6px 15px;
            border-radius: 30px;
            font-size: 13px;
            text-decoration: none;
            transition: 0.3s;
            border: 1px solid #e5e5e5;
        }

        .vc-tag:hover {
            background: #d4a85f;
            color: #ffffff;
        }

        /* Share */
        .vc-blog-share {
            margin-top: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .vc-share-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e2a2e;
        }

        .vc-share-links {
            display: flex;
            gap: 10px;
        }

        .vc-share-link {
            width: 35px;
            height: 35px;
            background: #f8f6f4;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1e2a2e;
            text-decoration: none;
            transition: 0.3s;
            border: 1px solid #e5e5e5;
        }

        .vc-share-link:hover {
            background: #d4a85f;
            color: #ffffff;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .vc-container {
                padding: 40px 20px;
            }
            
            .vc-blog-card {
                padding: 30px;
            }
            
            .vc-blog-title {
                font-size: 30px;
            }
            
            .vc-blog-meta {
                gap: 15px;
            }
        }

        @media (max-width: 576px) {
            .vc-blog-card {
                padding: 20px;
            }
            
            .vc-blog-title {
                font-size: 26px;
            }
            
            .vc-blog-meta {
                flex-direction: column;
                gap: 8px;
            }
            
            .vc-blog-share {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

    <?php include 'layout/header.php'; ?>

    <div class="vc-container">
        
        <!-- Breadcrumb -->
        <div class="vc-breadcrumb">
            <a href="#">Home</a> <i class="bi bi-chevron-right"></i>
            <a href="#">Blog</a> <i class="bi bi-chevron-right"></i>
            <span>Blog Details</span>
        </div>

        <!-- Blog Card -->
        <div class="vc-blog-card" data-aos="fade-up">
            
            <!-- Meta Information -->
            <div class="vc-blog-meta">
                <span><i class="bi bi-folder"></i> <span>Category:</span> Luxury Living</span>
                <span><i class="bi bi-calendar3"></i> <span>Date:</span> February 15, 2026</span>
                <span><i class="bi bi-person"></i> <span>By:</span> Sarah Johnson</span>
                <span><i class="bi bi-chat"></i> <span>Comments:</span> 12</span>
            </div>

            <!-- Title -->
            <h1 class="vc-blog-title">The Future of Luxury Living in Nairobi</h1>

            <!-- Featured Image -->
            <div class="vc-blog-image">
                <img src="https://images.unsplash.com/photo-1560448204-603b3fc33ddc?q=80&w=1200&auto=format&fit=crop" alt="Blog Image">
            </div>

            <!-- Content -->
            <div class="vc-blog-content">
                <p>Nairobi's luxury real estate market is experiencing a remarkable transformation. With new developments reshaping the city's skyline, the future of high-end living in Kenya's capital has never looked more promising. From sustainable architecture to smart home technology, here's what you need to know about the evolution of luxury living in Nairobi.</p>

                <h2>The Rise of Sustainable Luxury</h2>
                <p>Modern luxury homebuyers in Nairobi are increasingly prioritizing sustainability without compromising on comfort or style. Developers are responding by incorporating eco-friendly features such as solar panels, rainwater harvesting systems, and energy-efficient appliances. Properties in Karen, Runda, and other prestigious neighborhoods now often include green spaces, organic gardens, and sustainable building materials.</p>

                <p>Today's luxury properties in Nairobi are becoming smarter than ever. Home automation systems that control lighting, security, climate, and entertainment are now standard in high-end developments. Voice-activated assistants, automated blinds, and smart security systems provide residents with unprecedented convenience and peace of mind.</p>

                <h2>Prime Locations for Luxury Living</h2>
                <p>While traditional luxury neighborhoods like Karen and Runda remain popular, new hotspots are emerging. Areas such as Westlands, Kilimani, and Riverside are attracting buyers with their convenient locations and modern developments. The demand for properties in these areas continues to grow as more people seek the perfect balance between urban convenience and residential tranquility.</p>

                <ul>
                    <li>Integrated shopping and dining experiences</li>
                    <li>On-site fitness centers and spas</li>
                    <li>Concierge services and 24/7 security</li>
                    <li>Landscaped gardens and recreational areas</li>
                </ul>

                <p>Whether you're looking for a contemporary apartment in the heart of the city or a sprawling estate in the suburbs, Nairobi's luxury real estate market offers something for every discerning buyer. With new developments constantly raising the bar, the future of luxury living in Nairobi is bright indeed.</p>
            </div>

            <!-- Tags -->
            <div class="vc-blog-tags">
                <div class="vc-tags-title">Tags:</div>
                <div class="vc-tags-list">
                    <a href="#" class="vc-tag">Luxury Living</a>
                    <a href="#" class="vc-tag">Nairobi Real Estate</a>
                    <a href="#" class="vc-tag">Smart Homes</a>
                    <a href="#" class="vc-tag">Sustainable Design</a>
                    <a href="#" class="vc-tag">Property Investment</a>
                </div>
            </div>

            <!-- Share -->
            <div class="vc-blog-share">
                <span class="vc-share-title">Share:</span>
                <div class="vc-share-links">
                    <a href="#" class="vc-share-link"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="vc-share-link"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="vc-share-link"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="vc-share-link"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>

        </div>

    </div>

    <?php include 'layout/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>
</html>