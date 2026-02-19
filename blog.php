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
                        <a href="blog-details.php" class="vc-blog-link">Read More <i class="bi bi-arrow-right"></i></a>
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
                        <a href="blog-details.php" class="vc-blog-link">Read More <i class="bi bi-arrow-right"></i></a>
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
                        <a href="blog-details.php" class="vc-blog-link">Read More <i class="bi bi-arrow-right"></i></a>
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
                        <a href="blog-details.php" class="vc-blog-link">Read More <i class="bi bi-arrow-right"></i></a>
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