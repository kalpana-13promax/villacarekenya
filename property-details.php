<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>VillaCare Kenya · Property Details</title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- AOS CSS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<?php include 'layout/link.php'; ?>

<style>

/* ===== PAGE BACKGROUND - REMOVED ===== */
.propdt-wrapper-2026 {
  padding: 80px 0;
  font-family: 'Inter', sans-serif;
}

/* ===== BREADCRUMB ===== */
.propdt-breadcrumb-2026 {
  margin-bottom: 30px;
  font-size: 14px;
  color: #546e7a;
}

.propdt-breadcrumb-2026 a {
  color: #d4a85f;
  text-decoration: none;
  transition: color 0.3s ease;
}

.propdt-breadcrumb-2026 a:hover {
  color: #1e2a2e;
}

.propdt-breadcrumb-2026 i {
  font-size: 12px;
  margin: 0 8px;
  color: #d4a85f;
}

/* ===== IMAGE GALLERY ===== */
.propdt-gallery-2026 {
  margin-bottom: 40px;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 20px 40px -15px rgba(0,0,0,0.1);
}

.propdt-main-img-2026 {
  width: 100%;
  height: 450px;
  object-fit: cover;
  transition: transform 0.6s ease;
}

.propdt-main-img-2026:hover {
  transform: scale(1.02);
}

.propdt-thumbs-2026 {
  margin-top: 15px;
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.propdt-thumbs-2026 img {
  width: 100px;
  height: 80px;
  object-fit: cover;
  border-radius: 8px;
  cursor: pointer;
  opacity: 0.7;
  transition: all 0.3s ease;
  border: 2px solid transparent;
  box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.propdt-thumbs-2026 img:hover {
  opacity: 1;
  transform: scale(1.05);
  border-color: #d4a85f;
  box-shadow: 0 10px 20px -10px rgba(212,168,95,0.2);
}

/* ===== WHITE CENTER CARD ===== */
.propdt-card-2026 {
  background: #ffffff;
  padding: 50px 50px;
  border-radius: 20px;
  box-shadow: 0 20px 40px -20px rgba(0,0,0,0.1);
  border: 1px solid #e5e5e5;
}

/* ===== TITLE ===== */
.propdt-title-2026 {
  text-align: center;
  font-family: 'Playfair Display', serif;
  font-size: 36px;
  font-weight: 600;
  margin-bottom: 40px;
  color: #1e2a2e;
  position: relative;
  padding-bottom: 15px;
}

.propdt-title-2026::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 3px;
  background: #d4a85f;
}

.propdt-title-2026 span {
  color: #d4a85f;
}

/* ===== ROW STYLE ===== */
.propdt-row-2026 {
  padding: 20px 0;
  border-bottom: 1px solid #e5e5e5;
  align-items: flex-start;
  transition: all 0.3s ease;
}

.propdt-row-2026:hover {
  background: #fbf9f7;
  padding-left: 15px;
  padding-right: 15px;
  border-radius: 8px;
}

.propdt-label-2026 {
  font-size: 14px;
  font-weight: 600;
  color: #1e2a2e;
  text-transform: uppercase;
  letter-spacing: 1px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.propdt-label-2026 i {
  color: #d4a85f;
  font-size: 18px;
}

.propdt-value-2026 {
  font-size: 15px;
  color: #546e7a;
  line-height: 1.7;
}

/* ===== AMENITIES ===== */
.propdt-amenities-2026 ul {
  margin: 0;
  padding: 0;
  list-style: none;
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
}

.propdt-amenities-2026 li {
  margin-bottom: 0;
  display: flex;
  align-items: center;
  gap: 8px;
  background: #f8f6f4;
  padding: 8px 16px;
  border-radius: 30px;
  font-size: 14px;
  color: #1e2a2e;
  border: 1px solid #e5e5e5;
  transition: all 0.3s ease;
}

.propdt-amenities-2026 li:hover {
  background: #d4a85f;
  color: #ffffff;
  border-color: #d4a85f;
}

.propdt-amenities-2026 li i {
  color: #d4a85f;
  transition: color 0.3s ease;
}

.propdt-amenities-2026 li:hover i {
  color: #ffffff;
}

/* ===== PRICE ROW ===== */
.propdt-price-row-2026 {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 20px;
}

.propdt-price-box {
  background: #f8f6f4;
  padding: 15px 25px;
  border-radius: 12px;
  border-left: 4px solid #d4a85f;
  border: 1px solid #e5e5e5;
  border-left: 4px solid #d4a85f;
}

.propdt-price-2026 {
  font-weight: 700;
  color: #1e2a2e;
  font-size: 24px;
  margin-bottom: 5px;
}

.propdt-install-2026 {
  color: #d4a85f;
  font-size: 14px;
  font-weight: 500;
}

.propdt-btn-2026 {
  background: #1e2f3c;
  color: #ffffff;
  border: none;
  padding: 12px 35px;
  font-size: 14px;
  font-weight: 600;
  letter-spacing: 1px;
  border-radius: 50px;
  transition: all 0.3s ease;
  border: 2px solid #1e2f3c;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.propdt-btn-2026:hover {
  background: transparent;
  color: #1e2f3c;
  transform: translateY(-2px);
  box-shadow: 0 10px 20px -10px rgba(0,0,0,0.1);
}

/* ===== TABS ===== */
.propdt-tabs-2026 {
  margin-top: 40px;
  display: flex;
  gap: 10px;
  border-bottom: 1px solid #e5e5e5;
  padding-bottom: 10px;
  flex-wrap: wrap;
}

.propdt-tabs-2026 button {
  background: none;
  border: none;
  padding: 10px 25px;
  font-size: 14px;
  font-weight: 500;
  color: #546e7a;
  border-radius: 30px;
  transition: all 0.3s ease;
  position: relative;
  cursor: pointer;
  border: 1px solid transparent;
}

.propdt-tabs-2026 button i {
  margin-right: 8px;
  color: #d4a85f;
}

.propdt-tabs-2026 button.active {
  color: #d4a85f;
  background: #f8f6f4;
  font-weight: 600;
  border-color: #e5e5e5;
}

.propdt-tabs-2026 button:hover {
  color: #d4a85f;
  background: #f8f6f4;
}

/* ===== MAP & VIDEO ===== */
.propdt-map-2026,
.propdt-video-2026,
.propdt-arch-2026 {
  margin-top: 30px;
  display: none;
  animation: fadeIn 0.5s ease;
}

.propdt-map-2026.active,
.propdt-video-2026.active,
.propdt-arch-2026.active {
  display: block;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.propdt-map-2026 iframe {
  width: 100%;
  height: 450px;
  border: 0;
  border-radius: 15px;
  box-shadow: 0 20px 40px -20px rgba(0,0,0,0.1);
}

.propdt-video-2026 video {
  width: 100%;
  height: 450px;
  object-fit: cover;
  border-radius: 15px;
  box-shadow: 0 20px 40px -20px rgba(0,0,0,0.1);
}

.propdt-arch-2026 img {
  width: 100%;
  height: 450px;
  object-fit: cover;
  border-radius: 15px;
  box-shadow: 0 20px 40px -20px rgba(0,0,0,0.1);
}

/* ===== SIMILAR PROPERTIES SECTION ===== */
.similar-properties-section {
  margin-top: 80px;
}

.similar-header {
  text-align: center;
  margin-bottom: 40px;
}

.similar-header h2 {
  font-family: 'Playfair Display', serif;
  font-size: 32px;
  font-weight: 600;
  color: #1e2a2e;
  margin-bottom: 10px;
}

.similar-header h2 span {
  color: #d4a85f;
}

.similar-header p {
  color: #546e7a;
  font-size: 16px;
}

/* Similar Property Card */
.similar-card {
  background: #ffffff;
  border: 1px solid #e5e5e5;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
  height: 100%;
  display: flex;
  flex-direction: column;
}

.similar-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px -15px rgba(212,168,95,0.2);
  border-color: #d4a85f;
}

.similar-image {
  position: relative;
  height: 200px;
  overflow: hidden;
}

.similar-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.6s ease;
}

.similar-card:hover .similar-image img {
  transform: scale(1.1);
}

.similar-badge {
  position: absolute;
  top: 10px;
  right: 10px;
  background: #d4a85f;
  color: #0f1a24;
  padding: 4px 12px;
  font-size: 11px;
  font-weight: 600;
  border-radius: 30px;
  z-index: 2;
}

.similar-price {
  position: absolute;
  bottom: 10px;
  left: 10px;
  background: rgba(15,26,36,0.85);
  color: #ffffff;
  padding: 4px 12px;
  font-size: 14px;
  font-weight: 600;
  border-radius: 30px;
  border: 1px solid rgba(212,168,95,0.3);
}

.similar-content {
  padding: 20px;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
}

.similar-content h3 {
  font-family: 'Playfair Display', serif;
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 8px;
  color: #1e2a2e;
}

.similar-location {
  font-size: 13px;
  color: #546e7a;
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  gap: 5px;
}

.similar-location i {
  color: #d4a85f;
}

.similar-meta {
  display: flex;
  gap: 15px;
  font-size: 13px;
  color: #1e2a2e;
  background: #f8f6f4;
  padding: 10px;
  border-radius: 8px;
  margin-bottom: 15px;
  border-left: 3px solid #d4a85f;
}

.similar-meta div {
  display: flex;
  align-items: center;
  gap: 5px;
}

.similar-meta i {
  color: #d4a85f;
}

.similar-link {
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
  margin-top: auto;
  padding-top: 10px;
  border-top: 1px solid #e5e5e5;
}

.similar-link:hover {
  color: #d4a85f;
  gap: 12px;
}

.similar-link i {
  font-size: 14px;
  transition: transform 0.3s ease;
}

.similar-link:hover i {
  transform: translateX(5px);
}

/* View All Button */
.view-all-btn {
  text-align: center;
  margin-top: 50px;
}

.view-all-btn .btn-view {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: transparent;
  color: #1e2a2e;
  border: 2px solid #1e2a2e;
  padding: 12px 35px;
  font-size: 14px;
  font-weight: 600;
  letter-spacing: 1px;
  text-decoration: none;
  transition: all 0.3s ease;
  border-radius: 50px;
}

.view-all-btn .btn-view:hover {
  background: #1e2a2e;
  color: #ffffff;
  transform: translateY(-3px);
  box-shadow: 0 10px 20px -10px rgba(0,0,0,0.2);
}

/* ===== RESPONSIVE ===== */
@media(max-width: 992px){
  .propdt-main-img-2026 {
    height: 350px;
  }
}

@media(max-width:768px){
  .propdt-card-2026 {
    padding: 30px;
  }
  
  .propdt-main-img-2026 {
    height: 280px;
  }
  
  .propdt-thumbs-2026 img {
    width: 70px;
    height: 60px;
  }
  
  .propdt-title-2026 {
    font-size: 30px;
  }
  
  .propdt-price-row-2026 {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .propdt-btn-2026 {
    width: 100%;
    justify-content: center;
  }
  
  .propdt-tabs-2026 {
    justify-content: center;
  }
  
  .similar-header h2 {
    font-size: 28px;
  }
}

@media(max-width:576px){
  .propdt-main-img-2026 {
    height: 220px;
  }
  
  .propdt-thumbs-2026 {
    justify-content: center;
  }
  
  .propdt-amenities-2026 ul {
    justify-content: center;
  }
  
  .propdt-map-2026 iframe,
  .propdt-video-2026 video,
  .propdt-arch-2026 img {
    height: 300px;
  }
  
  .similar-meta {
    flex-wrap: wrap;
    gap: 10px;
  }
}
</style>
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

<div class="row justify-content-center">
<div class="col-lg-10">

<!-- ===== IMAGE GALLERY ===== -->
<div class="propdt-gallery-2026" data-aos="fade-up">
  <img src="https://images.unsplash.com/photo-1507089947368-19c1da9775ae?q=80&w=1200&auto=format&fit=crop" class="propdt-main-img-2026" id="mainImage">

  <div class="propdt-thumbs-2026">
    <img src="https://images.unsplash.com/photo-1507089947368-19c1da9775ae?q=80&w=200&auto=format&fit=crop" onclick="changeImage(this)" data-aos="fade-up" data-aos-delay="100">
    <img src="https://images.unsplash.com/photo-1560185007-cde436f6a4d0?q=80&w=200&auto=format&fit=crop" onclick="changeImage(this)" data-aos="fade-up" data-aos-delay="150">
    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=200&auto=format&fit=crop" onclick="changeImage(this)" data-aos="fade-up" data-aos-delay="200">
    <img src="https://images.unsplash.com/photo-1605276374104-dee2a0ed3cd6?q=80&w=200&auto=format&fit=crop" onclick="changeImage(this)" data-aos="fade-up" data-aos-delay="250">
    <img src="https://images.unsplash.com/photo-1560448204-603b3fc33ddc?q=80&w=200&auto=format&fit=crop" onclick="changeImage(this)" data-aos="fade-up" data-aos-delay="300">
  </div>
</div>

<!-- ===== DETAILS CARD ===== -->
<div class="propdt-card-2026" data-aos="fade-up" data-aos-delay="200">

  <div class="propdt-title-2026">
    Property <span>Details</span>
  </div>

  <!-- Description -->
  <div class="row propdt-row-2026">
    <div class="col-md-3 propdt-label-2026">
      <i class="bi bi-house-door"></i> Description
    </div>
    <div class="col-md-9 propdt-value-2026">
      Experience the serenity of Barizi Gardens, perfect for holiday homes and investment.
      Located just 35 minutes from Malindi town. This stunning property offers breathtaking views
      and modern amenities for ultimate comfort.
    </div>
  </div>

  <!-- Location -->
  <div class="row propdt-row-2026">
    <div class="col-md-3 propdt-label-2026">
      <i class="bi bi-geo-alt"></i> Location
    </div>
    <div class="col-md-9 propdt-value-2026">
      <i class="bi bi-pin-map-fill me-2" style="color: #d4a85f;"></i> Malindi, Kenya
    </div>
  </div>

  <!-- Amenities -->
  <div class="row propdt-row-2026">
    <div class="col-md-3 propdt-label-2026">
      <i class="bi bi-grid"></i> Amenities
    </div>
    <div class="col-md-9 propdt-value-2026 propdt-amenities-2026">
      <ul>
        <li><i class="bi bi-droplet"></i> Water Connection</li>
        <li><i class="bi bi-lightning"></i> Electricity</li>
        <li><i class="bi bi-shield"></i> Fully Fenced</li>
        <li><i class="bi bi-door-closed"></i> Gated Access</li>
        <li><i class="bi bi-wifi"></i> High-Speed WiFi</li>
        <li><i class="bi bi-car-front"></i> Parking</li>
      </ul>
    </div>
  </div>

  <!-- Size -->
  <div class="row propdt-row-2026">
    <div class="col-md-3 propdt-label-2026">
      <i class="bi bi-rulers"></i> Size
    </div>
    <div class="col-md-9 propdt-value-2026">1/8 Acre (Approx. 505 sq.m)</div>
  </div>

  <!-- Price -->
  <div class="row propdt-row-2026">
    <div class="col-md-3 propdt-label-2026">
      <i class="bi bi-cash-stack"></i> Price
    </div>
    <div class="col-md-9 propdt-price-row-2026">
      <div class="propdt-price-box">
        <div class="propdt-price-2026">KES 150,000</div>
        <div class="propdt-install-2026">Installments: KES 170,000 (12 months)</div>
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
          <a href="#" class="similar-link">View Details <i class="bi bi-arrow-right"></i></a>
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
          <a href="#" class="similar-link">View Details <i class="bi bi-arrow-right"></i></a>
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
          <a href="#" class="similar-link">View Details <i class="bi bi-arrow-right"></i></a>
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
</div>
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