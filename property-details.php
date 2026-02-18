<?php 
include 'config/properties.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$property = isset($properties[$id]) ? $properties[$id] : $properties[1];

// SEO meta for this property
$meta_title = $property['title'] . ' | VillaCare Kenya';
$meta_description = substr($property['description'], 0, 155);
$meta_keywords = implode(', ', array_filter([$property['title'], $property['location'], $property['type'], $property['badge']]));
$og_title = $meta_title;
$og_description = $meta_description;
$og_image = !empty($property['gallery'][0]) ? $property['gallery'][0] : $property['featured_image'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($property['title']) . ' - VillaCare Kenya'; ?></title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- AOS CSS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<?php include 'layout/link.php'; ?>
</head>

<body>

<?php include 'layout/header.php'; ?>

<section class="propdt-wrapper-2026">
<div class="container">

<!-- Breadcrumb -->
<div class="propdt-breadcrumb-2026" data-aos="fade-down">
  <a href="#">Home</a> <i class="bi bi-chevron-right"></i> 
  <a href="#">Properties</a> <i class="bi bi-chevron-right"></i> 
  <span>Property Details</span>
</div>

<div class="row">
  <div class="col-lg-8">

<!-- ===== IMAGE GALLERY ===== -->
<div class="propdt-gallery-2026" data-aos="fade-up">
  <img src="<?php echo htmlspecialchars($property['featured_image']); ?>" class="propdt-main-img-2026" id="mainImage">

  <div class="propdt-thumbs-2026">
    <?php foreach ($property['gallery'] as $index => $image): ?>
    <img src="<?php echo htmlspecialchars($image); ?>" onclick="changeImage(this)" data-aos="fade-up" data-aos-delay="<?php echo (100 + $index * 50); ?>">
    <?php endforeach; ?>
  </div>
</div>

<!-- ===== DETAILS CARD ===== -->
<div class="propdt-card-2026" data-aos="fade-up" data-aos-delay="200">

  <div class="propdt-title-2026">
    Property <span>Details</span>
  </div>

  <!-- Title -->
  <div class="row propdt-row-2026">
    <div class="col-md-3 propdt-label-2026">
      <i class="bi bi-building"></i> Property Name
    </div>
    <div class="col-md-9 propdt-value-2026">
      <?php echo htmlspecialchars($property['title']); ?>
    </div>
  </div>

  <!-- Description -->
  <div class="row propdt-row-2026">
    <div class="col-md-3 propdt-label-2026">
      <i class="bi bi-house-door"></i> Description
    </div>
    <div class="col-md-9 propdt-value-2026">
      <?php echo htmlspecialchars($property['description']); ?>
    </div>
  </div>

  <!-- Location -->
  <div class="row propdt-row-2026">
    <div class="col-md-3 propdt-label-2026">
      <i class="bi bi-geo-alt"></i> Location
    </div>
    <div class="col-md-9 propdt-value-2026">
      <i class="bi bi-pin-map-fill me-2" style="color: #d4a85f;"></i> <?php echo htmlspecialchars($property['location']); ?>
    </div>
  </div>

  <!-- Amenities -->
  <div class="row propdt-row-2026">
    <div class="col-md-3 propdt-label-2026">
      <i class="bi bi-grid"></i> Amenities
    </div>
    <div class="col-md-9 propdt-value-2026 propdt-amenities-2026">
      <ul>
        <?php foreach ($property['amenities'] as $amenity): ?>
        <li><?php echo htmlspecialchars($amenity); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>

  <!-- Size -->
  <div class="row propdt-row-2026">
    <div class="col-md-3 propdt-label-2026">
      <i class="bi bi-rulers"></i> Size
    </div>
    <div class="col-md-9 propdt-value-2026"><?php echo htmlspecialchars($property['size']); ?></div>
  </div>

  <!-- Price -->
  <div class="row propdt-row-2026">
    <div class="col-md-3 propdt-label-2026">
      <i class="bi bi-cash-stack"></i> Price
    </div>
    <div class="col-md-9 propdt-price-row-2026">
      <div class="propdt-price-box">
        <div class="propdt-price-2026"><?php echo htmlspecialchars($property['price_display']); ?></div>
        <div class="propdt-install-2026">Posted: <?php echo htmlspecialchars($property['posted']); ?></div>
      </div>
      <button class="propdt-btn-2026"><i class="bi bi-cart"></i> Buy Now</button>
    </div>
  </div>

  <!-- Tabs -->
  <div class="propdt-tabs-2026">
    <button class="active" onclick="showTab('map')"><i class="bi bi-map"></i> Google Map</button>
    <button onclick="showTab('video')"><i class="bi bi-play-circle"></i> Property Video</button>
    <button onclick="showTab('arch')"><i class="bi bi-building"></i> Architecture Map</button>
  </div>

  <!-- Map Tab -->
  <div class="propdt-map-2026 active" id="mapTab">
    <iframe src="https://www.google.com/maps?q=malindi&output=embed" allowfullscreen loading="lazy"></iframe>
  </div>

  <!-- Video Tab -->
  <div class="propdt-video-2026" id="videoTab">
    <video controls poster="https://images.unsplash.com/photo-1560185007-cde436f6a4d0?q=80&w=1200&auto=format&fit=crop">
      <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>
  </div>

  <!-- Architecture Map Tab -->
  <div class="propdt-arch-2026" id="archTab">
    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1200&auto=format&fit=crop" alt="Architecture Map">
  </div>

</div>

<!-- ===== SIMILAR PROPERTIES SECTION ===== -->
<div class="similar-properties-section">

  <div class="similar-header" data-aos="fade-up">
    <h2>Similar <span>Properties</span></h2>
    <p>You might also be interested in these properties</p>
  </div>

  <div class="row g-4">
    
    <!-- Similar Property 1 -->
    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
      <div class="similar-card">
        <div class="similar-image">
          <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=600&auto=format&fit=crop" alt="Property">
          <div class="similar-badge">FOR RENT</div>
          <div class="similar-price">Ksh.95,000/mo</div>
        </div>
        <div class="similar-content">
          <h3>2 Bedroom Apartment</h3>
          <div class="similar-location">
            <i class="bi bi-geo-alt-fill"></i> Kilimani, Nairobi
          </div>
          <div class="similar-meta">
            <div><i class="bi bi-door-open"></i> 2 Beds</div>
            <div><i class="bi bi-droplet"></i> 2 Baths</div>
          </div>
          <a href="property-details.php?id=1" class="similar-link">View Details <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
    </div>

    <!-- Similar Property 2 -->
    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
      <div class="similar-card">
        <div class="similar-image">
          <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?q=80&w=600&auto=format&fit=crop" alt="Property">
          <div class="similar-badge">FOR RENT</div>
          <div class="similar-price">Ksh.120,000/mo</div>
        </div>
        <div class="similar-content">
          <h3>3 Bedroom Apartment</h3>
          <div class="similar-location">
            <i class="bi bi-geo-alt-fill"></i> Kileleshwa, Nairobi
          </div>
          <div class="similar-meta">
            <div><i class="bi bi-door-open"></i> 3 Beds</div>
            <div><i class="bi bi-droplet"></i> 2 Baths</div>
          </div>
          <a href="property-details.php?id=2" class="similar-link">View Details <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
    </div>

    <!-- Similar Property 3 -->
    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
      <div class="similar-card">
        <div class="similar-image">
          <img src="https://images.unsplash.com/photo-1560448204-603b3fc33ddc?q=80&w=600&auto=format&fit=crop" alt="Property">
          <div class="similar-badge">FOR SALE</div>
          <div class="similar-price">Ksh.18.5M</div>
        </div>
        <div class="similar-content">
          <h3>4 Bedroom Villa</h3>
          <div class="similar-location">
            <i class="bi bi-geo-alt-fill"></i> Karen, Nairobi
          </div>
          <div class="similar-meta">
            <div><i class="bi bi-door-open"></i> 4 Beds</div>
            <div><i class="bi bi-droplet"></i> 3 Baths</div>
          </div>
          <a href="property-details.php?id=3" class="similar-link">View Details <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
    </div>

  </div>

  <!-- View All Button -->
  <div class="view-all-btn" data-aos="fade-up" data-aos-delay="400">
    <a href="property.php" class="btn-view">View All Properties <i class="bi bi-arrow-right"></i></a>
  </div>

</div>

  </div>

  <aside class="col-lg-4 d-none d-lg-block">
    <div class="sticky-column">
      <div class="contact-box p-4 mb-3">
        <h5 class="mb-3">Get in touch</h5>
        <form>
          <input type="text" class="form-control mb-2" placeholder="Your name">
          <input type="email" class="form-control mb-2" placeholder="Your email">
          <textarea class="form-control mb-2" rows="3" placeholder="Message"></textarea>
          <button class="btn btn-sm text-white" type="submit">Send</button>
        </form>
      </div>

      <div class="contact-details p-4">
        <h6 class="mb-2">Contact Details</h6>
        <p><i class="bi bi-geo-alt-fill me-2" style="color:#d4a85f;"></i> New Rehema House, 6th Floor, Rhapta Road</p>
        <p><i class="bi bi-telephone-fill me-2" style="color:#d4a85f;"></i> +254 712 345 678</p>
        <p><i class="bi bi-envelope-fill me-2" style="color:#d4a85f;"></i> info@villacarekenya.com</p>
      </div>
    </div>
  </aside>

</div>
</section>

<?php include 'layout/footer.php'; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
// Image Gallery
function changeImage(element){
  document.getElementById("mainImage").src = element.src;
}

// Tab Switching
function showTab(tabName) {
  // Hide all tabs
  document.getElementById('mapTab').classList.remove('active');
  document.getElementById('videoTab').classList.remove('active');
  document.getElementById('archTab').classList.remove('active');
  
  // Remove active class from all buttons
  document.querySelectorAll('.propdt-tabs-2026 button').forEach(btn => {
    btn.classList.remove('active');
  });
  
  // Show selected tab
  document.getElementById(tabName + 'Tab').classList.add('active');
  
  // Add active class to clicked button
  event.target.classList.add('active');
}

// AOS Initialization
AOS.init({
  duration: 800,
  once: true,
  offset: 40,
  easing: 'ease-out'
});
</script>

</body>
</html>