<?php
require_once('includes/config.php');
include 'config/properties.php';

$page_data = [
    'categories' => $boj->getQuery("SELECT id, type FROM property_type WHERE category = 'main' ORDER BY type ASC"),
    'types' => $boj->getQuery("SELECT id, type FROM property_type WHERE category = 'sub' ORDER BY type ASC"),
    'locations' => $boj->getQuery("SELECT DISTINCT location FROM locations WHERE location != '' ORDER BY location ASC"),
    'cities' => $boj->getQuery("SELECT DISTINCT city FROM city WHERE city != '' ORDER BY city ASC"),
    'price' => $boj->getQuery("SELECT MAX(property_price) as max_price FROM property_listing WHERE status != '7'")
];

// 1. Categories
$dyn_categories = [];
if (!empty($page_data['categories'])) {
    foreach ($page_data['categories'] as $cat) {
        $dyn_categories[strtolower($cat->type)] = $cat->type;
    }
}

// 2. Types
$dyn_types = [];
if (!empty($page_data['types'])) {
    foreach ($page_data['types'] as $t) {
        $dyn_types[strtolower($t->type)] = $t->type;
    }
}

// 3. Locations
$dyn_locations = [];
if (!empty($page_data['locations'])) {
    foreach ($page_data['locations'] as $loc) {
        $dyn_locations[strtolower($loc->location)] = $loc->location;
    }
}
if (!empty($page_data['cities'])) {
    foreach ($page_data['cities'] as $c) {
        $dyn_locations[strtolower($c->city)] = $c->city;
    }
}
asort($dyn_locations);

// 4. Price Ranges
$max_price_val = 0;
if (!empty($page_data['price'])) {
    $max_price_val = floatval($page_data['price'][0]->max_price);
}

$price_options = ['' => 'Any Price'];
if ($max_price_val > 0) {
    $price_options['0-5m'] = 'Under KES 5M';
    if ($max_price_val > 5000000)
        $price_options['5m-10m'] = 'KES 5M – 10M';
    if ($max_price_val > 10000000)
        $price_options['10m-20m'] = 'KES 10M – 20M';
    if ($max_price_val > 20000000)
        $price_options['20m-50m'] = 'KES 20M – 50M';
    if ($max_price_val > 50000000)
        $price_options['50m+'] = 'Above KES 50M';
}

// 5. Dynamic Page Content
$page_content = [
    'top_props' => [
        'subtitle' => 'PROPERTIES',
        'title1' => 'TOP',
        'title2' => 'PROPERTIES',
        'desc' => 'Explore our premium real estate properties designed to provide a luxurious and comfortable lifestyle.',
        'btn_text' => 'VIEW ALL PROPERTIES',
        'btn_link' => 'property/'
    ],
    'cta' => [
        'title' => 'Ready to Invest in Your <span>Dream Property?</span>',
        'desc' => 'Let our experienced property advisors guide you through every step — from viewing to ownership with complete transparency.',
        'btn1_text' => 'Schedule Viewing',
        'btn1_link' => 'contact/',
        'btn2_text' => 'Talk to Expert',
        'btn2_link' => 'contact/'
    ]
];
?>

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

    <!-- ===== PROPERTY FILTER BAR ===== -->
    <section class="pf-section">
        <div class="container">
            <div class="pf-card">

                <!-- Filter Fields -->
                <form action="property.php" method="GET" id="pfForm">
                    <div class="row g-0 pf-fields align-items-stretch">

                        <!-- Keyword -->
                        <div class="col-lg col-md-6 col-12">
                            <div class="pf-field">
                                <label class="pf-label"><i class="fas fa-search"></i> Keyword</label>
                                <input type="text" name="search" class="pf-input" placeholder="Area, property name…">
                            </div>
                        </div>

                        <!-- Property Category -->
                        <div class="col-lg col-md-6 col-12">
                            <div class="pf-field pf-field--sep">
                                <label class="pf-label"><i class="fas fa-th-large"></i> Category</label>
                                <select name="category" class="pf-input">
                                    <option value="">All Categories</option>
                                    <?php foreach ($dyn_categories as $val => $label): ?>
                                        <option value="<?php echo htmlspecialchars($val); ?>"><?php echo htmlspecialchars($label); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <i class="fas fa-angle-down pf-arrow"></i>
                            </div>
                        </div>

                        <!-- Property Type -->
                        <div class="col-lg col-md-6 col-12">
                            <div class="pf-field pf-field--sep">
                                <label class="pf-label"><i class="fas fa-bed"></i> Property Type</label>
                                <select name="type" class="pf-input">
                                    <option value="">Any Type</option>
                                    <?php foreach ($dyn_types as $val => $label): ?>
                                        <option value="<?php echo htmlspecialchars($val); ?>"><?php echo htmlspecialchars($label); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <i class="fas fa-angle-down pf-arrow"></i>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="col-lg col-md-6 col-12">
                            <div class="pf-field pf-field--sep">
                                <label class="pf-label"><i class="fas fa-map-marker-alt"></i> Location</label>
                                <select name="location" class="pf-input">
                                    <option value="">Any Location</option>
                                    <?php foreach ($dyn_locations as $val => $label): ?>
                                        <option value="<?php echo htmlspecialchars($val); ?>"><?php echo htmlspecialchars($label); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <i class="fas fa-angle-down pf-arrow"></i>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="col-lg col-md-6 col-12">
                            <div class="pf-field pf-field--sep">
                                <label class="pf-label"><i class="fas fa-tag"></i> Price Range</label>
                                <select name="price" class="pf-input">
                                    <?php foreach ($price_options as $val => $label): ?>
                                        <option value="<?php echo htmlspecialchars($val); ?>"><?php echo htmlspecialchars($label); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <i class="fas fa-angle-down pf-arrow"></i>
                            </div>
                        </div>

                        <!-- Search Button -->
                        <div class="col-lg-auto col-12">
                            <button type="submit" class="pf-btn">
                                <i class="fas fa-search"></i>
                                <span>Search</span>
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </section>

    <script>
        function pfTab(el, val) {
            document.querySelectorAll('.pf-tab').forEach(t => t.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('pfIntent').value = val;
        }
    </script>

    <!-- Professional New Features Section -->
    <?php include 'layout/property-featured.php'; ?>

    <section class="dev-section">
        <div class="container">
            <div class="row align-items-center g-4">

                <div class="col-lg-8">
                    <div class="row g-4">

                        <?php
                        $top_props = array_values($properties);
                        $t1 = isset($top_props[0]) ? $top_props[0] : null;
                        $t2 = isset($top_props[1]) ? $top_props[1] : null;
                        $t3 = isset($top_props[2]) ? $top_props[2] : null;
                        $t4 = isset($top_props[3]) ? $top_props[3] : null;
                        ?>
                        <div class="col-md-6">

                            <?php if ($t1): ?>
                                <div data-aos="fade-up">
                                    <a href="<?php echo DOMAIN; ?>property/<?php echo htmlspecialchars($t1['slug']); ?>/" class="dev-card large">
                                        <img src="<?php echo htmlspecialchars($t1['featured_image']); ?>" alt="<?php echo htmlspecialchars($t1['title']); ?>">
                                        <div class="dev-label"><?php echo htmlspecialchars($t1['type']); ?></div>
                                    </a>
                                    <div class="dev-caption"><?php echo htmlspecialchars(strtoupper($t1['title'])); ?></div>
                                    <div class="dev-location"><?php echo htmlspecialchars($t1['location']); ?></div>
                                </div>
                            <?php endif; ?>

                            <?php if ($t2): ?>
                                <div class="mt-4" data-aos="fade-up" data-aos-delay="150">
                                    <a href="<?php echo DOMAIN; ?>property/<?php echo htmlspecialchars($t2['slug']); ?>/" class="dev-card small">
                                        <img src="<?php echo htmlspecialchars($t2['featured_image']); ?>" alt="<?php echo htmlspecialchars($t2['title']); ?>">
                                        <div class="dev-label"><?php echo htmlspecialchars($t2['type']); ?></div>
                                    </a>
                                    <div class="dev-caption"><?php echo htmlspecialchars(strtoupper($t2['title'])); ?></div>
                                    <div class="dev-location"><?php echo htmlspecialchars($t2['location']); ?></div>
                                </div>
                            <?php endif; ?>

                        </div>

                        <div class="col-md-6">

                            <?php if ($t3): ?>
                                <div data-aos="fade-up" data-aos-delay="75">
                                    <a href="<?php echo DOMAIN; ?>property/<?php echo htmlspecialchars($t3['slug']); ?>/" class="dev-card medium">
                                        <img src="<?php echo htmlspecialchars($t3['featured_image']); ?>" alt="<?php echo htmlspecialchars($t3['title']); ?>">
                                        <div class="dev-label"><?php echo htmlspecialchars($t3['type']); ?></div>
                                    </a>
                                    <div class="dev-caption"><?php echo htmlspecialchars(strtoupper($t3['title'])); ?></div>
                                    <div class="dev-location"><?php echo htmlspecialchars($t3['location']); ?></div>
                                </div>
                            <?php endif; ?>

                            <?php if ($t4): ?>
                                <div class="mt-4" data-aos="fade-up" data-aos-delay="225">
                                    <a href="<?php echo DOMAIN; ?>property/<?php echo htmlspecialchars($t4['slug']); ?>/" class="dev-card medium">
                                        <img src="<?php echo htmlspecialchars($t4['featured_image']); ?>" alt="<?php echo htmlspecialchars($t4['title']); ?>">
                                        <div class="dev-label"><?php echo htmlspecialchars($t4['type']); ?></div>
                                    </a>
                                    <div class="dev-caption"><?php echo htmlspecialchars(strtoupper($t4['title'])); ?></div>
                                    <div class="dev-location"><?php echo htmlspecialchars($t4['location']); ?></div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="dev-content" data-aos="fade-left">

                        <div class="dev-subtitle">
                            <?php echo htmlspecialchars($page_content['top_props']['subtitle']); ?>
                        </div>

                        <h2 class="dev-title">
                            <span><?php echo htmlspecialchars($page_content['top_props']['title1']); ?></span>
                            <span><?php echo htmlspecialchars($page_content['top_props']['title2']); ?></span>
                        </h2>

                        <p class="dev-text">
                            <?php echo htmlspecialchars($page_content['top_props']['desc']); ?>
                        </p>

                        <a href="<?php echo htmlspecialchars($page_content['top_props']['btn_link']); ?>" class="dev-btn">
                            <?php echo htmlspecialchars($page_content['top_props']['btn_text']); ?>
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
                    <?php echo $page_content['cta']['title']; ?>
                </h2>

                <p class="cta-desc">
                    <?php echo htmlspecialchars($page_content['cta']['desc']); ?>
                </p>

                <div class="cta-buttons">
                    <a href="<?php echo htmlspecialchars($page_content['cta']['btn1_link']); ?>" class="btn-cta-primary"><?php echo htmlspecialchars($page_content['cta']['btn1_text']); ?></a>
                    <a href="<?php echo htmlspecialchars($page_content['cta']['btn2_link']); ?>" class="btn-cta-secondary"><?php echo htmlspecialchars($page_content['cta']['btn2_text']); ?></a>
                </div>

            </div>
        </div>
    </section>

    <!-- footer -->
    <?php include 'layout/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="<?php echo DOMAIN; ?>assets/js/main.js"></script>
</body>

</html>