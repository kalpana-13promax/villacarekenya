    <style>
        /* * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #ffffff;
            font-family: 'Inter', sans-serif;
        } */

        /* ============================= */
        /*   PROFESSIONAL NEW FEATURES    */
        /* ============================= */
        .new-features-section {
            background: #ffffff;
            padding: 5rem 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .container {
            max-width: 1100px;
            /* optimal width */
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* ----- SECTION TITLE (Professional) ----- */
        .section-title {
            margin-bottom: 3rem;
            display: flex;
            align-items: center;
        }

        .section-title span {
            font-size: 0.8rem;
            letter-spacing: 5px;
            text-transform: uppercase;
            color: #6b7c85;
            font-weight: 400;
            position: relative;
            display: inline-block;
        }

        .section-title span::after {
            content: "";
            position: absolute;
            left: 105%;
            top: 50%;
            transform: translateY(-50%);
            width: 60px;
            height: 1px;
            background: #d0b7a0;
            /* subtle gold tone */
        }

        /* ----- FEATURE CARDS (Professional & Clean) ----- */
        .feature-card {
            background: transparent;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Image container - perfectly sized */
        .feature-img {
            width: 100%;
            height: 340px;
            overflow: hidden;
            background-color: #f0eae3;
            aspect-ratio: 3 / 4;
            /* elegant portrait ratio */
            border-radius: 2px;
            box-shadow: 0 10px 30px -15px rgba(0, 0, 0, 0.1);
        }

        .feature-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.7s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .feature-card:hover .feature-img img {
            transform: scale(1.06);
        }

        /* Collage grid - professional layout */
        .collage-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 5px;
            height: 100%;
            width: 100%;
            background: #e5dbd1;
        }

        .collage-grid img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: grayscale(0.15) brightness(1.01);
            transition: 0.4s;
        }

        .collage-grid img:hover {
            filter: grayscale(0) brightness(1.05);
        }

        /* Card content - refined spacing */
        .feature-content {
            margin-top: 1.8rem;
            padding: 0 0.2rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .feature-content h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.7rem;
            font-weight: 450;
            line-height: 1.25;
            letter-spacing: -0.3px;
            color: #1e2a2e;
            margin-bottom: 0.8rem;
        }

        .feature-content p {
            font-size: 0.9rem;
            color: #546e7a;
            line-height: 1.6;
            margin-bottom: 1.8rem;
            max-width: 95%;
            font-weight: 350;
        }

        /* Professional Gold Button - Same for all cards */
        .feature-btn {
            display: inline-block;
            padding: 0.7rem 2.2rem;
            font-size: 0.7rem;
            letter-spacing: 3.5px;
            text-transform: uppercase;
            text-decoration: none;
            border: 1.2px solid #b59b7b;
            color: #2c3a40;
            background: transparent;
            transition: all 0.3s ease;
            align-self: flex-start;
            margin-top: auto;
            border-radius: 0;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .feature-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: #b59b7b;
            transition: left 0.3s ease;
            z-index: -1;
        }

        .feature-btn:hover {
            color: #ffffff;
            border-color: #b59b7b;
        }

        .feature-btn:hover::before {
            left: 0;
        }

        /* Link styling */
        a {
            text-decoration: none;
        }

        /* Responsive Excellence */
        @media (max-width: 1100px) {
            .feature-content h3 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 992px) {
            .container {
                max-width: 900px;
            }

            .feature-img {
                aspect-ratio: 4 / 5;
            }

            .feature-content h3 {
                font-size: 1.4rem;
            }
        }

        @media (max-width: 768px) {
            .new-features-section {
                padding: 4rem 0;
                min-height: auto;
            }

            .row.g-5 {
                --bs-gutter-y: 2.5rem;
            }

            .feature-img {
                aspect-ratio: 3 / 2;
            }

            .feature-content h3 {
                font-size: 1.5rem;
            }

            .section-title span::after {
                width: 40px;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding: 0 1.5rem;
            }

            .section-title span {
                font-size: 0.7rem;
                letter-spacing: 4px;
            }

            .section-title span::after {
                width: 25px;
            }

            .feature-content h3 {
                font-size: 1.4rem;
            }

            .feature-img {
                aspect-ratio: 4 / 3;
            }

            .feature-btn {
                padding: 0.6rem 1.8rem;
                font-size: 0.65rem;
                letter-spacing: 3px;
            }

            .collage-grid {
                gap: 3px;
            }
        }

        /* Extra small devices */
        @media (max-width: 375px) {
            .feature-content h3 {
                font-size: 1.3rem;
            }

            .feature-btn {
                padding: 0.5rem 1.5rem;
                font-size: 0.6rem;
                letter-spacing: 2.5px;
            }
        }

        /* Smooth loading for images */
        img {
            opacity: 0;
            animation: fadeIn 0.5s ease-in forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body>

    <!-- Villacare Kenya - Featured Properties Section -->
    <section class="new-features-section">
        <div class="container">

            <!-- Title with fade-down -->
            <div class="row mb-4" data-aos="fade-down" data-aos-duration="900">
                <div class="col-12">
                    <div class="section-title">
                        <span>FEATURED PROPERTIES — VILLACARE KENYA</span>
                    </div>
                </div>
            </div>

            <!-- Properties Grid -->
            <div class="row g-4 g-lg-5">
                <!-- CARD 1: Lavington Family Villa -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="900" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-img">
                            <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=1200&q=80"
                                alt="Lavington family villa" loading="lazy">
                        </div>
                        <div class="feature-content">
                            <h3>Lavington Family Villa</h3>
                            <p>Spacious 4-bedroom family villa in Lavington with landscaped garden, secure compound
                                and contemporary finishes. Ideal for families seeking privacy and convenience.</p>
                            <a href="property-details.php?id=1" class="feature-btn">VIEW DETAILS</a>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: Prime Nairobi Apartments -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="900" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-img">
                            <div class="collage-grid">
                                <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80"
                                    alt="Nairobi apartment interior" loading="lazy">
                                <img src="https://images.unsplash.com/photo-1505691723518-36a66b0f5a1d?auto=format&fit=crop&w=800&q=80"
                                    alt="modern apartment" loading="lazy">
                                <img src="https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=800&q=80"
                                    alt="city skyline" loading="lazy">
                                <img src="https://images.unsplash.com/photo-1572120360610-d971b9b20021?auto=format&fit=crop&w=800&q=80"
                                    alt="living area" loading="lazy">
                            </div>
                        </div>
                        <div class="feature-content">
                            <h3>Prime Nairobi Apartments</h3>
                            <p>Contemporary 2–3 bedroom apartments in Kilimani and Westlands — secure developments
                                with great rental yield potential and easy access to amenities.</p>
                            <a href="for-sale.php" class="feature-btn">BROWSE FOR SALE</a>
                        </div>
                    </div>
                </div>

                <!-- CARD 3: Coastal Holiday Home -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="900" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-img">
                            <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80"
                                alt="Diani beachfront home" loading="lazy">
                        </div>
                        <div class="feature-content">
                            <h3>Coastal Holiday Home — Diani</h3>
                            <p>Beachfront 3-bedroom duplex in Diani Beach with direct access to the shore — perfect
                                for holiday rentals or a private coastal retreat.</p>
                            <a href="contact.php?ref=diani" class="feature-btn">ENQUIRE NOW</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subtle bottom line -->
            <div class="row mt-5" data-aos="fade-up" data-aos-duration="800" data-aos-delay="400">
                <div class="col-12">
                    <div style="border-top: 1px solid #e0d6cc; width: 100%;"></div>
                </div>
            </div>

        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // AOS initialization for Villacare Kenya
        AOS.init({
            duration: 900,
            once: true,
            mirror: false,
            offset: 40,
            easing: 'ease-out',
            delay: 0
        });

        // Refresh on resize
        window.addEventListener('resize', () => {
            AOS.refresh();
        });
    </script>


</body>

</html>