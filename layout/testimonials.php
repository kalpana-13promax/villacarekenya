<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HassConsult · Testimonials Slider</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
        }

        /* ===== TESTIMONIAL SECTION ===== */
        .testimonial-section {
            background: #f8f6f4;
            padding: 80px 0;
            position: relative;
        }

        /* Bootstrap Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
        }

        /* Section Header */
        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-subtitle {
            font-size: 11px;
            letter-spacing: 5px;
            color: #8a9aa5;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: 400;
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .section-subtitle span {
            width: 30px;
            height: 1px;
            background: #d4b08c;
        }

        .section-title1 {
            font-family: 'Playfair Display', serif;
            font-size: 38px;
            font-weight: 500;
            color: #1e2a2e;
            margin-bottom: 15px;
            letter-spacing: -0.5px;
        }

        .section-title1 span {
            color: #b59b7b;
        }

        .section-desc {
            color: #546e7a;
            max-width: 500px;
            margin: 0 auto;
            font-size: 14px;
            line-height: 1.6;
        }

        /* Swiper Container */
        .testimonial-swiper {
            padding: 15px 35px 50px;
            margin: -15px 0;
        }

        .swiper-slide {
            height: auto;
            transition: all 0.3s ease;
        }

        /* Testimonial Card */
        .testimonial-card {
            background: #ffffff;
            padding: 30px 25px;
            box-shadow: 0 10px 25px -8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            border-radius: 0;
            border: 1px solid #f0eae3;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 35px -10px rgba(181, 155, 123, 0.15);
            border-color: #b59b7b;
        }

        /* Quote Icon */
        .quote-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 45px;
            color: #f0eae3;
            font-family: 'Playfair Display', serif;
            line-height: 1;
            z-index: 1;
        }

        /* Client Rating */
        .client-rating {
            color: #b59b7b;
            font-size: 12px;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
        }

        .client-rating i {
            margin-right: 2px;
        }

        /* Testimonial Text */
        .testimonial-text {
            font-size: 14px;
            line-height: 1.6;
            color: #546e7a;
            margin-bottom: 20px;
            font-style: italic;
            position: relative;
            z-index: 2;
            flex-grow: 1;
        }

        /* Client Info */
        .client-info {
            display: flex;
            align-items: center;
            gap: 12px;
            border-top: 1px solid #f0eae3;
            padding-top: 18px;
            margin-top: 5px;
        }

        .client-img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #b59b7b;
        }

        .client-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .client-details h4 {
            font-size: 14px;
            font-weight: 600;
            color: #1e2a2e;
            margin-bottom: 3px;
        }

        .client-details p {
            font-size: 10px;
            color: #8a9aa5;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 0;
        }

        .client-location {
            font-size: 10px;
            color: #b59b7b;
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 3px;
        }

        .client-location i {
            font-size: 10px;
        }

        /* Featured Testimonial */
        .testimonial-card.featured {
            background: #1e2f3c;
            border-color: #1e2f3c;
        }

        .testimonial-card.featured .quote-icon {
            color: rgba(255, 255, 255, 0.1);
        }

        .testimonial-card.featured .testimonial-text {
            color: #ffffff;
        }

        .testimonial-card.featured .client-details h4 {
            color: #ffffff;
        }

        .testimonial-card.featured .client-details p {
            color: #b59b7b;
        }

        /* Swiper Navigation Buttons */
        .swiper-button-prev,
        .swiper-button-next {
            color: #b59b7b;
            background: #ffffff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #f0eae3;
        }

        .swiper-button-prev:after,
        .swiper-button-next:after {
            font-size: 16px;
            font-weight: 600;
        }

        .swiper-button-prev:hover,
        .swiper-button-next:hover {
            background: #b59b7b;
            color: #ffffff;
            border-color: #b59b7b;
            transform: scale(1.1);
        }

        .swiper-button-prev {
            left: -15px;
        }

        .swiper-button-next {
            right: -15px;
        }

        /* Swiper Pagination */
        .swiper-pagination {
            bottom: 0 !important;
        }

        .swiper-pagination-bullet {
            width: 8px;
            height: 8px;
            background: #d4b08c;
            opacity: 0.3;
            transition: all 0.3s ease;
            margin: 0 4px !important;
        }

        .swiper-pagination-bullet-active {
            opacity: 1;
            background: #b59b7b;
            width: 20px;
            border-radius: 10px;
        }

        /* Stats Section */
        .stats-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            background: #ffffff;
            padding: 30px 25px;
            border: 1px solid #f0eae3;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 600;
            color: #b59b7b;
            line-height: 1;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 10px;
            letter-spacing: 1.5px;
            color: #8a9aa5;
            text-transform: uppercase;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .swiper-button-prev {
                left: -10px;
            }

            .swiper-button-next {
                right: -10px;
            }
        }

        @media (max-width: 992px) {
            .section-title1 {
                font-size: 34px;
            }

            .stats-section {
                flex-wrap: wrap;
                gap: 20px;
            }

            .swiper-button-prev,
            .swiper-button-next {
                width: 35px;
                height: 35px;
            }

            .swiper-button-prev:after,
            .swiper-button-next:after {
                font-size: 14px;
            }
        }

        @media (max-width: 768px) {
            .testimonial-section {
                padding: 60px 0;
            }

            .section-title1 {
                font-size: 30px;
            }

            .stat-number {
                font-size: 28px;
            }

            .testimonial-card {
                padding: 25px 20px;
            }

            .swiper-button-prev,
            .swiper-button-next {
                display: none;
            }

            .testimonial-swiper {
                padding: 15px 15px 45px;
            }
        }

        @media (max-width: 576px) {
            .section-title1 {
                font-size: 26px;
            }

            .section-subtitle {
                font-size: 10px;
                letter-spacing: 4px;
            }

            .testimonial-card {
                padding: 20px 15px;
            }

            .client-info {
                flex-direction: column;
                text-align: center;
            }

            .stats-section {
                padding: 20px 15px;
            }

            .stat-number {
                font-size: 24px;
            }

            .stat-label {
                font-size: 9px;
                letter-spacing: 1px;
            }

            .quote-icon {
                font-size: 35px;
                top: 15px;
                right: 15px;
            }
        }
    </style>
</head>

<body>

    <section class="testimonial-section">
        <!-- Bootstrap Container - Yes, this is the container -->
        <div class="container">

            <!-- Section Header -->
            <div class="section-header" data-aos="fade-up">
                <div class="section-subtitle">
                    <span></span> TESTIMONIALS <span></span>
                </div>
                <h2 class="section-title1">
                    WHAT OUR <span>CLIENTS</span> SAY
                </h2>
                <p class="section-desc">
                    Real stories from real clients who found their dream homes with us
                </p>
            </div>

            <!-- Testimonial Slider (Inside Container) -->
            <div class="swiper testimonial-swiper" data-aos="fade-up" data-aos-delay="200">
                <div class="swiper-wrapper">

                    <!-- Slide 1 - Featured -->
                    <div class="swiper-slide">
                        <div class="testimonial-card featured">
                            <div class="quote-icon">"</div>
                            <div class="client-rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <p class="testimonial-text">
                                "Villacare Kenya helped us find the perfect beachfront villa in Diani. From site visits
                                to final documentation, their team handled everything professionally. The transparency
                                and quality standards exceeded our expectations."
                            </p>
                            <div class="client-info">
                                <div class="client-img">
                                    <img src="https://images.unsplash.com/photo-1494790108777-466d853b773d?q=80&w=200&auto=format&fit=crop"
                                        alt="Sarah Johnson">
                                </div>
                                <div class="client-details">
                                    <h4>Sarah Johnson</h4>
                                    <p>Homeowner</p>
                                    <div class="client-location">
                                        <i class="bi bi-geo-alt-fill"></i> KAI | Diani
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <div class="quote-icon">"</div>
                            <div class="client-rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <p class="testimonial-text">
                                "As a property investor in Nairobi, I rely on market knowledge and ROI insights.
                                Villacare Kenya provided accurate guidance and helped me secure a high-yield apartment
                                in Westlands. The process was seamless and efficient."
                            </p>
                            <div class="client-info">
                                <div class="client-img">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop"
                                        alt="Michael Omondi">
                                </div>
                                <div class="client-details">
                                    <h4>Michael Omondi</h4>
                                    <p>Investor</p>
                                    <div class="client-location">
                                        <i class="bi bi-geo-alt-fill"></i> 1870 West | Westlands
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 3 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <div class="quote-icon">"</div>
                            <div class="client-rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <p class="testimonial-text">
                                "We were searching for a secure and modern family home in Karen, and Villacare Kenya
                                delivered beyond expectations. The attention to detail, finishing quality, and customer
                                service were outstanding."
                            </p>
                            <div class="client-info">
                                <div class="client-img">
                                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?q=80&w=200&auto=format&fit=crop"
                                        alt="Elizabeth Wanjiku">
                                </div>
                                <div class="client-details">
                                    <h4>Elizabeth Wanjiku</h4>
                                    <p>Homeowner</p>
                                    <div class="client-location">
                                        <i class="bi bi-geo-alt-fill"></i> Coco | Brookside
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 4 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <div class="quote-icon">"</div>
                            <div class="client-rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <p class="testimonial-text">
                                "Buying property in Upperhill felt overwhelming at first, but Villacare Kenya simplified
                                every step. Their legal support, valuation advice, and project updates made the
                                experience stress-free."
                            </p>
                            <div class="client-info">
                                <div class="client-img">
                                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=200&auto=format&fit=crop"
                                        alt="David Kimani">
                                </div>
                                <div class="client-details">
                                    <h4>David Kimani</h4>
                                    <p>Homeowner</p>
                                    <div class="client-location">
                                        <i class="bi bi-geo-alt-fill"></i> Pembe | Upperhill
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 5 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card featured">
                            <div class="quote-icon">"</div>
                            <div class="client-rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <p class="testimonial-text">
                                "As a first-time homebuyer, I appreciated how Villacare Kenya explained every detail
                                clearly. They guided me through financing options and helped me secure a beautiful
                                apartment in Kilimani."
                            </p>
                            <div class="client-info">
                                <div class="client-img">
                                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop"
                                        alt="Jane Muthoni">
                                </div>
                                <div class="client-details">
                                    <h4>Jane Muthoni</h4>
                                    <p>First-time Buyer</p>
                                    <div class="client-location">
                                        <i class="bi bi-geo-alt-fill"></i> Nouveau | Lenana
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Navigation Buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>

                <!-- Pagination -->
                <div class="swiper-pagination"></div>
            </div>

            <!-- Stats Section (Inside Container) -->
            <div class="stats-section" data-aos="fade-up" data-aos-delay="400">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">HOMES DELIVERED</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">SATISFIED CLIENTS</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">15+</div>
                    <div class="stat-label">YEARS EXP.</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">4.9</div>
                    <div class="stat-label">CLIENT RATING</div>
                </div>
            </div>

        </div> <!-- Container Ends Here -->
    </section>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        AOS.init({
            duration: 900,
            once: true,
            offset: 40
        });

        // Swiper with Left-Right Navigation
        const swiper = new Swiper('.testimonial-swiper', {
            slidesPerView: 1,
            spaceBetween: 25,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 25,
                },
            },
        });
    </script>
</body>

</html>