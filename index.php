<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'layout/link.php'; ?>
</head>
<body>


<!-- top and main header start -->

<?php include 'layout/header.php'; ?>

<!-- top and main header end -->

<!-- ===== SLIDER ===== -->
<?php include 'layout/slider.php'; ?>


<!-- Professional New Features Section -->
<?php include 'layout/property-featured.php'; ?>

<section class="dev-section">
<div class="container">
<div class="row align-items-center g-4">

<div class="col-lg-8">
<div class="row g-4">

<div class="col-md-6">
<div data-aos="fade-up">
<a href="property-details.php?id=10" class="dev-card large">
<img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?q=80&w=1200&auto=format&fit=crop" alt="Lavington family villa">
<div class="dev-label">PROPERTY</div>
</a>
<div class="dev-caption">LAVINGTON | FAMILY VILLA</div>
<div class="dev-location">Lavington, Nairobi</div>
</div>

<div class="mt-4" data-aos="fade-up" data-aos-delay="150">
<a href="property-details.php?id=9" class="dev-card small">
<img src="https://images.unsplash.com/photo-1560185007-cde436f6a4d0?q=80&w=1200&auto=format&fit=crop" alt="Kilimani apartment">
<div class="dev-label">PROPERTY</div>
</a>
<div class="dev-caption">KILIMANI | CONTEMPORARY APARTMENTS</div>
<div class="dev-location">Kilimani, Nairobi</div>
</div>

</div>

<div class="col-md-6">

<div data-aos="fade-up" data-aos-delay="75">
<a href="property-details.php?id=8" class="dev-card medium">
<img src="https://images.unsplash.com/photo-1507089947368-19c1da9775ae?q=80&w=1200&auto=format&fit=crop" alt="Brookside residence">
<div class="dev-label">PROPERTY</div>
</a>
<div class="dev-caption">BROOKSIDE | URBAN RESIDENCES</div>
<div class="dev-location">Brookside, Nairobi</div>
</div>

<div class="mt-4" data-aos="fade-up" data-aos-delay="225">
<a href="property-details.php?id=7" class="dev-card medium">
<img src="https://images.unsplash.com/photo-1560448204-603b3fc33ddc?q=80&w=1200&auto=format&fit=crop" alt="Upperhill tower">
<div class="dev-label">PROPERTY</div>
</a>
<div class="dev-caption">UPPERHILL | EXECUTIVE TOWERS</div>
<div class="dev-location">Upperhill, Nairobi</div>
</div>
</div>
</div>
</div>

<div class="col-lg-4">
<div class="dev-content" data-aos="fade-left">

<div class="dev-subtitle">
PROPERTIES
</div>

<h2 class="dev-title">
<span>TOP</span>
<span>PROPERTIES</span>
</h2>

<p class="dev-text">
Explore our premium real estate properties designed to provide a luxurious and comfortable lifestyle.
</p>

<a href="property.php" class="dev-btn">
VIEW ALL PROPERTIES
</a>

</div>
</div>
</div>
</div>
</section>
<br>

<!-- ===== TESTIMONIAL ===== -->
<?php include 'layout/testimonials.php'; ?>

<!-- CALL TO ACTION SECTION -->
<section class="villacare-cta">
    <div class="container">
        <div class="cta-content text-center">

            <h2 class="cta-title">
                Ready to Invest in Your <span>Dream Property?</span>
            </h2>

            <p class="cta-desc">
                Let our experienced property advisors guide you through every step — 
                from viewing to ownership with complete transparency.
            </p>

            <div class="cta-buttons">
                <a href="#" class="btn-cta-primary">Schedule Viewing</a>
                <a href="#" class="btn-cta-secondary">Talk to Expert</a>
            </div>

        </div>
    </div>
</section>


<!-- footer -->
<?php include 'layout/footer.php'; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>