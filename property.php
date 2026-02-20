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

// Capture Filter Parameters
$f_search   = isset($_GET['search'])   ? trim($_GET['search'])   : '';
$f_category = isset($_GET['category']) ? trim($_GET['category']) : '';
$f_type     = isset($_GET['type'])     ? trim($_GET['type'])     : '';
$f_location = isset($_GET['location']) ? trim($_GET['location']) : '';
$f_price    = isset($_GET['price'])    ? trim($_GET['price'])    : '';

// Helper to convert price strings to numbers
function parsePriceValue($priceStr) {
    if (empty($priceStr)) return 0;
    // Remove Ksh, commas, and /Month
    $val = str_ireplace(['Ksh.', ',', '/Month'], '', $priceStr);
    $val = trim($val);
    
    // Handle M (Million)
    if (stripos($val, 'M') !== false) {
        return (float)str_ireplace('M', '', $val) * 1000000;
    }
    return (float)$val;
}

// Filter properties
$filtered_properties = array_filter($properties, function($p) use ($f_search, $f_category, $f_type, $f_location, $f_price) {
    // Search match (Keyword)
    if ($f_search) {
        $searchMatch = stripos($p['title'], $f_search) !== false || 
                       stripos($p['description'], $f_search) !== false || 
                       stripos($p['location'], $f_search) !== false;
        if (!$searchMatch) return false;
    }
    
    // Category match
    if ($f_category && strtolower($p['type']) !== strtolower($f_category)) {
        // Handle "furnished" as "apartment" category if needed, or stick to exact matches
        if ($f_category == 'apartment' && strtolower($p['type']) == 'furnished') {
            // Match
        } else {
            return false;
        }
    }
    
    // Type (Bedrooms) match
    if ($f_type) {
        $beds = (int)$p['beds'];
        if ($f_type == 'studio' && $beds != 1) return false;
        if ($f_type == '1bed' && $beds != 1) return false;
        if ($f_type == '2bed' && $beds != 2) return false;
        if ($f_type == '3bed' && $beds != 3) return false;
        if ($f_type == '4bed+' && $beds < 4) return false;
        if ($f_type == 'penthouse' && strtolower($p['type']) != 'penthouse') return false;
    }
    
    // Location match
    if ($f_location && stripos($p['location'], $f_location) === false) {
        return false;
    }
    
    // Price range match
    if ($f_price) {
        $price = parsePriceValue($p['price_display']);
        if ($f_price == '0-5m' && $price > 5000000) return false;
        if ($f_price == '5m-10m' && ($price < 5000000 || $price > 10000000)) return false;
        if ($f_price == '10m-20m' && ($price < 10000000 || $price > 20000000)) return false;
        if ($f_price == '20m-50m' && ($price < 20000000 || $price > 50000000)) return false;
        if ($f_price == '50m+' && $price < 50000000) return false;
    }
    
    return true;
});

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
        <h2 class="prb3-title-2026">Property Listings</h2>
        <div class="prb3-breadcrumb-2026 text-white opacity-75">
            Showing <strong><?php echo count($filtered_properties); ?></strong> Results
        </div>
    </div>
</section>

<!-- Main Listing Section -->
<section class="plx-wrapper">
    <div class="container">

        <?php 
        if (empty($filtered_properties)):
        ?>
        <div class="text-center py-5" data-aos="fade-up">
            <i class="bi bi-search" style="font-size: 4rem; color: #d4a85f; opacity: 0.3;"></i>
            <h3 class="mt-4">No matching properties found</h3>
            <p class="text-muted">Try adjusting your filters or search keywords.</p>
            <a href="property.php" class="plx-btn mt-3">View All Properties</a>
        </div>
        <?php
        else:
            $count = 0;
            foreach ($filtered_properties as $property): 
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

        <?php endforeach; endif; ?>

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