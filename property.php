<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>VillaCare Kenya · Premium Property Listings</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<?php 
include 'config/properties.php';

// Page-level SEO
$meta_title = 'Premium Property Listings | VillaCare Kenya';
$meta_description = 'Browse selected premium properties for sale and rent from VillaCare Kenya.';
$meta_keywords = 'properties, real estate, villas, apartments, for sale, for rent, VillaCare';
$og_title = $meta_title;
$og_description = $meta_description;
$og_image = isset($properties[1]) ? $properties[1]['featured_image'] : '';

include 'layout/link.php'; 
?>
    
</head>

<body>

<!-- header -->
<?php include 'layout/header.php'; ?>

<!-- Banner Section with Parallax -->
<section class="prb3-wrapper-2026" data-aos="fade-in">
    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1600&auto=format&fit=crop" alt="Luxury Property Banner">
    <div class="prb3-overlay-2026"></div>
    <div class="prb3-content-2026">
        <h2 class="prb3-title-2026">Premium Property Listings</h2>
        <div class="prb3-breadcrumb-2026">
            <a href="#">Home</a> <i class="bi bi-chevron-right" style="font-size: 12px; margin: 0 5px;"></i> Properties
        </div>
    </div>
</section>

<!-- Main Listing Section -->
<section class="plx-wrapper">
    <div class="container">

        <?php 
        $count = 0;
        foreach (array_slice($properties, 0, 5) as $property): 
            $count++;
            $carouselId = 'plxSlider' . $count;
            $isEven = $count % 2 == 0;
            $delay = $count > 1 ? 200 : 0;
        ?>

        <!-- PROPERTY LISTING -->
        <div class="row align-items-center plx-row" data-aos="fade-up" data-aos-duration="1000" <?php if($delay) echo "data-aos-delay=\"$delay\""; ?>>
            <div class="col-lg-5 mb-4" data-aos="fade-<?php echo $isEven ? 'left' : 'right'; ?>" data-aos-delay="<?php echo 200 + ($count-1)*100; ?>">
                <div class="plx-image-box">
                    <span class="plx-badge"><i class="bi bi-star-fill me-2"></i><?php echo htmlspecialchars($property['badge']); ?></span>
                    
                    <div id="<?php echo $carouselId; ?>" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-indicators">
                            <?php foreach ($property['gallery'] as $idx => $image): ?>
                            <button type="button" data-bs-target="#<?php echo $carouselId; ?>" data-bs-slide-to="<?php echo $idx; ?>" <?php echo $idx === 0 ? 'class="active"' : ''; ?>></button>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="carousel-inner">
                            <?php foreach ($property['gallery'] as $idx => $image): ?>
                            <div class="carousel-item <?php echo $idx === 0 ? 'active' : ''; ?>">
                                <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>">
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#<?php echo $carouselId; ?>" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#<?php echo $carouselId; ?>" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-7" data-aos="fade-<?php echo $isEven ? 'right' : 'left'; ?>" data-aos-delay="<?php echo 300 + ($count-1)*100; ?>">
                <h6 class="plx-heading">
                    <?php echo htmlspecialchars($property['title']); ?>
                </h6>

                <div class="plx-meta">
                    <div><span>TYPE</span> <i class="bi bi-house me-2" style="color: #d4a85f;"></i> <?php echo htmlspecialchars($property['type']); ?></div>
                    <div><span>PRICE</span> <i class="bi bi-cash-stack me-2" style="color: #d4a85f;"></i> <?php echo htmlspecialchars($property['price_display']); ?></div>
                    <div><span>LOCATION</span> <i class="bi bi-geo-alt me-2" style="color: #d4a85f;"></i> <?php echo htmlspecialchars($property['location']); ?></div>
                </div>

                <p class="plx-text">
                    <?php echo htmlspecialchars($property['description']); ?>
                </p>

                <?php if($count % 2 == 0): ?>
                <div class="mt-3">
                    <a href="contact.php" class="plx-btn me-3"><i class="bi bi-chat-dots me-2"></i> ENQUIRE</a>
                    <a href="property-details.php?id=<?php echo $property['id']; ?>" class="plx-btn"><i class="bi bi-eye me-2"></i> VIEW DETAILS</a>
                </div>
                <?php else: ?>
                <a href="property-details.php?id=<?php echo $property['id']; ?>" class="plx-btn"><i class="bi bi-eye me-2"></i> VIEW DETAILS</a>
                <?php endif; ?>
            </div>
        </div>

        <?php endforeach; ?>

    </div>
</section>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true,
        offset: 50,
        easing: 'ease-out-cubic'
    });

    // Initialize all carousels with autoplay
    document.addEventListener('DOMContentLoaded', function() {
        var carousels = document.querySelectorAll('.carousel');
        carousels.forEach(function(carousel) {
            new bootstrap.Carousel(carousel, {
                interval: 3000,
                wrap: true,
                pause: 'hover'
            });
        });
    });
</script>

<?php include 'layout/footer.php'; ?>

</body>
</html>