<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Villacare Kenya · Properties For Sale</title>

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

/* ===== BANNER SECTION - FIXED ===== */
.vc-rent-banner {
    position: relative;
    height: 400px;
    overflow: hidden;
    margin: 0;
    width: 100%;
}

.vc-rent-banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(45%);
    transition: transform 8s ease;
}

.vc-rent-banner:hover img {
    transform: scale(1.1);
}

.vc-banner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(15,26,36,0.8) 0%, rgba(30,47,60,0.6) 100%);
    z-index: 1;
}

.vc-banner-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: #fff;
    width: 90%;
    max-width: 900px;
    z-index: 2;
    animation: fadeInContent 1s ease forwards;
    opacity: 1;
}

@keyframes fadeInContent {
    0% {
        opacity: 0;
        transform: translate(-50%, -30%);
    }
    100% {
        opacity: 1;
        transform: translate(-50%, -50%);
    }
}

.vc-banner-title {
    font-family: 'Playfair Display', serif;
    font-size: 52px;
    font-weight: 700;
    margin-bottom: 15px;
    text-shadow: 2px 2px 20px rgba(0,0,0,0.3);
    line-height: 1.2;
}

.vc-banner-title span {
    color: #d4a85f;
    position: relative;
    display: inline-block;
}

.vc-banner-title span::after {
    content: '';
    position: absolute;
    bottom: 8px;
    left: 0;
    width: 100%;
    height: 12px;
    background: rgba(212,168,95,0.3);
    z-index: -1;
    border-radius: 4px;
}

.vc-banner-text {
    font-size: 18px;
    color: #e0e0e0;
    margin-bottom: 25px;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.6;
}

.vc-banner-badge {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: rgba(212,168,95,0.15);
    backdrop-filter: blur(5px);
    padding: 10px 25px;
    border-radius: 50px;
    border: 1px solid rgba(212,168,95,0.3);
    font-size: 14px;
    letter-spacing: 1px;
    margin: 0 auto;
}

.vc-banner-badge i {
    color: #d4a85f;
    font-size: 18px;
}

.vc-banner-badge span {
    color: #ffffff;
    font-weight: 500;
}

.vc-banner-badge span strong {
    color: #d4a85f;
    font-weight: 700;
}

/* ===== SECTION BACKGROUND ===== */
.prc2-wrapper-2026 {
  padding: 80px 0;
  font-family: 'Inter', sans-serif;
}

/* ===== CARD STYLE ===== */
.prc2-card-2026 {
  background: #ffffff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 15px 30px -15px rgba(0,0,0,0.1);
  transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
  border: 1px solid rgba(212, 168, 95, 0.1);
  height: 100%;
  display: flex;
  flex-direction: column;
}

.prc2-card-2026:hover {
  transform: translateY(-10px);
  box-shadow: 0 30px 50px -20px rgba(212, 168, 95, 0.3);
  border-color: #d4a85f;
}

/* ===== IMAGE AREA ===== */
.prc2-image-box-2026 {
  position: relative;
  height: 240px;
  overflow: hidden;
}

.prc2-image-box-2026 img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.6s ease;
}

.prc2-card-2026:hover .prc2-image-box-2026 img {
  transform: scale(1.1);
}

/* BADGE - Gold Style */
.prc2-badge-2026 {
  position: absolute;
  top: 15px;
  right: 15px;
  background: #d4a85f;
  color: #0f1a24;
  padding: 6px 16px;
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 1px;
  border-radius: 30px;
  z-index: 2;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* PRICE - Gold Style */
.prc2-price-2026 {
  position: absolute;
  bottom: 15px;
  left: 15px;
  color: #ffffff;
  font-weight: 700;
  font-size: 16px;
  background: rgba(15, 26, 36, 0.85);
  backdrop-filter: blur(5px);
  padding: 8px 16px;
  border-radius: 30px;
  border: 1px solid rgba(212, 168, 95, 0.3);
  z-index: 2;
}

/* BODY */
.prc2-body-2026 { 
  padding: 25px 22px;
  flex-grow: 1;
}

.prc2-title-2026 { 
  font-family: 'Playfair Display', serif;
  font-size: 20px; 
  font-weight: 600; 
  margin-bottom: 8px; 
  color: #1e2a2e; 
  transition: color 0.3s ease;
}

.prc2-card-2026:hover .prc2-title-2026 {
  color: #d4a85f;
}

.prc2-location-2026 { 
  font-size: 14px; 
  color: #546e7a; 
  margin-bottom: 15px; 
  display: flex;
  align-items: center;
  gap: 5px;
}

.prc2-location-2026 i {
  color: #d4a85f;
  font-size: 14px;
}

.prc2-meta-2026 { 
  display: flex; 
  gap: 25px; 
  font-size: 14px; 
  margin-bottom: 15px; 
  color: #1e2a2e; 
  background: #f8f6f4;
  padding: 12px 15px;
  border-radius: 8px;
  border-left: 3px solid #d4a85f;
}

.prc2-meta-2026 div {
  display: flex;
  align-items: center;
  gap: 5px;
}

.prc2-meta-2026 i {
  color: #d4a85f;
}

.prc2-category-2026 { 
  font-size: 12px; 
  font-weight: 600; 
  letter-spacing: 1px; 
  color: #d4a85f;
  text-transform: uppercase;
  display: inline-block;
  background: rgba(212, 168, 95, 0.1);
  padding: 5px 12px;
  border-radius: 30px;
}

/* FOOTER */
.prc2-footer-2026 {
  border-top: 1px solid rgba(212, 168, 95, 0.1);
  padding: 15px 22px;
  font-size: 13px;
  color: #546e7a;
  display: flex;
  justify-content: space-between;
  background: #fbf9f7;
}

.prc2-footer-2026 span:first-child {
  font-weight: 600;
  color: #1e2a2e;
}

.prc2-footer-2026 span:first-child i {
  color: #d4a85f;
  margin-right: 5px;
}

.prc2-footer-2026 span:last-child {
  display: flex;
  align-items: center;
  gap: 5px;
}

.prc2-footer-2026 i {
  color: #d4a85f;
}

/* PAGINATION - Gold Style */
.pagination {
  gap: 8px;
}

.page-link {
  border: 1px solid rgba(212, 168, 95, 0.2);
  color: #1e2a2e;
  font-weight: 500;
  border-radius: 8px !important;
  padding: 10px 16px;
  transition: all 0.3s ease;
  background: #ffffff;
}

.page-link:hover {
  background: #d4a85f;
  color: #ffffff;
  border-color: #d4a85f;
}

.page-item.active .page-link {
  background: #d4a85f;
  border-color: #d4a85f;
  color: #0f1a24;
  font-weight: 600;
}

/* PAGE TRANSITION */
.property-page { 
  display: none; 
  animation: fadeIn 0.5s ease;
}

.property-page.active { 
  display: flex; 
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* RESPONSIVE */
@media (max-width: 992px) {
    .vc-banner-title {
        font-size: 42px;
    }
    
    .vc-banner-text {
        font-size: 16px;
    }
}

@media (max-width: 768px) {
    .vc-rent-banner {
        height: 350px;
    }
    
    .vc-banner-title {
        font-size: 36px;
    }
    
    .prc2-wrapper-2026 {
        padding: 60px 0;
    }
    
    .prc2-meta-2026 {
        gap: 15px;
        flex-wrap: wrap;
    }
}

@media (max-width: 576px) {
    .vc-rent-banner {
        height: 300px;
    }
    
    .vc-banner-title {
        font-size: 28px;
    }
    
    .vc-banner-text {
        font-size: 14px;
    }
    
    .vc-banner-badge {
        padding: 8px 20px;
        font-size: 12px;
    }
    
    .prc2-image-box-2026 {
        height: 200px;
    }
    
    .prc2-title-2026 {
        font-size: 18px;
    }
    
    .prc2-footer-2026 {
        flex-direction: column;
        gap: 5px;
        text-align: center;
    }
}
</style>
</head>

<body>

<?php include 'layout/header.php'; ?>

<!-- RENT BANNER SECTION - FIXED -->
<section class="vc-rent-banner">
    <img src="https://images.unsplash.com/photo-1560448204-603b3fc33ddc?q=80&w=1600&auto=format&fit=crop" alt="Rent Properties">
    <div class="vc-banner-overlay"></div>
    <div class="vc-banner-content">
        <h1 class="vc-banner-title">Properties for <span>Sale</span></h1>
        <p class="vc-banner-text">Discover the finest properties for sale in Nairobi's most sought-after neighborhoods</p>
        <div class="vc-banner-badge">
            <i class="bi bi-house-door"></i>
            <span><strong>50+</strong> Properties Available</span>
        </div>
    </div>
</section>

<section class="prc2-wrapper-2026">
<div class="container">

<!-- PAGE 1 -->
<div class="row g-4 property-page active" id="page1">

  <!-- CARD 1 -->
  <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
    <div class="prc2-card-2026">
      <div class="prc2-image-box-2026">
        <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1200&auto=format&fit=crop">
        <div class="prc2-badge-2026">FOR RENT</div>
        <div class="prc2-price-2026">Ksh.90,000/Month</div>
      </div>
      <div class="prc2-body-2026">
        <div class="prc2-title-2026">2 Bedroom Apartment</div>
        <div class="prc2-location-2026"><i class="bi bi-geo-alt-fill"></i> Kilimani, Nairobi</div>
        <div class="prc2-meta-2026">
          <div><i class="bi bi-door-open"></i> 2 Beds</div>
          <div><i class="bi bi-droplet"></i> 2 Baths</div>
        </div>
        <div class="prc2-category-2026">APARTMENT</div>
      </div>
      <div class="prc2-footer-2026">
        <span><i class="bi bi-building"></i> VillaCare Kenya</span>
        <span><i class="bi bi-clock"></i> 3 months ago</span>
      </div>
    </div>
  </div>

  <!-- CARD 2 -->
  <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
    <div class="prc2-card-2026">
      <div class="prc2-image-box-2026">
        <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?q=80&w=1200&auto=format&fit=crop">
        <div class="prc2-badge-2026">FOR RENT</div>
        <div class="prc2-price-2026">Ksh.280,000/Month</div>
      </div>
      <div class="prc2-body-2026">
        <div class="prc2-title-2026">3 Bedroom Apartment</div>
        <div class="prc2-location-2026"><i class="bi bi-geo-alt-fill"></i> Kileleshwa, Nairobi</div>
        <div class="prc2-meta-2026">
          <div><i class="bi bi-door-open"></i> 3 Beds</div>
          <div><i class="bi bi-droplet"></i> 4 Baths</div>
        </div>
        <div class="prc2-category-2026">FURNISHED</div>
      </div>
      <div class="prc2-footer-2026">
        <span><i class="bi bi-building"></i> Admin</span>
        <span><i class="bi bi-clock"></i> 5 months ago</span>
      </div>
    </div>
  </div>

  <!-- CARD 3 -->
  <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
    <div class="prc2-card-2026">
      <div class="prc2-image-box-2026">
        <img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?q=80&w=1200&auto=format&fit=crop">
        <div class="prc2-badge-2026">FOR RENT</div>
        <div class="prc2-price-2026">Ksh.150,000/Month</div>
      </div>
      <div class="prc2-body-2026">
        <div class="prc2-title-2026">Modern Apartment</div>
        <div class="prc2-location-2026"><i class="bi bi-geo-alt-fill"></i> Kilimani, Nairobi</div>
        <div class="prc2-meta-2026">
          <div><i class="bi bi-door-open"></i> 2 Beds</div>
          <div><i class="bi bi-droplet"></i> 2 Baths</div>
        </div>
        <div class="prc2-category-2026">APARTMENT</div>
      </div>
      <div class="prc2-footer-2026">
        <span><i class="bi bi-building"></i> VillaCare Kenya</span>
        <span><i class="bi bi-clock"></i> 5 months ago</span>
      </div>
    </div>
  </div>

</div>


<!-- PAGE 2 -->
<div class="row g-4 property-page" id="page2">

  <!-- CARD 4 -->
  <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
    <div class="prc2-card-2026">
      <div class="prc2-image-box-2026">
        <img src="https://images.unsplash.com/photo-1605276374104-dee2a0ed3cd6?q=80&w=1200&auto=format&fit=crop">
        <div class="prc2-badge-2026">FOR RENT</div>
        <div class="prc2-price-2026">Ksh.120,000/Month</div>
      </div>
      <div class="prc2-body-2026">
        <div class="prc2-title-2026">Luxury Flat</div>
        <div class="prc2-location-2026"><i class="bi bi-geo-alt-fill"></i> Westlands, Nairobi</div>
        <div class="prc2-meta-2026">
          <div><i class="bi bi-door-open"></i> 2 Beds</div>
          <div><i class="bi bi-droplet"></i> 2 Baths</div>
        </div>
        <div class="prc2-category-2026">APARTMENT</div>
      </div>
      <div class="prc2-footer-2026">
        <span><i class="bi bi-building"></i> Admin</span>
        <span><i class="bi bi-clock"></i> 1 month ago</span>
      </div>
    </div>
  </div>

  <!-- CARD 5 -->
  <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
    <div class="prc2-card-2026">
      <div class="prc2-image-box-2026">
        <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=1200&auto=format&fit=crop">
        <div class="prc2-badge-2026">FOR SALE</div>
        <div class="prc2-price-2026">Ksh.12.5M</div>
      </div>
      <div class="prc2-body-2026">
        <div class="prc2-title-2026">4 Bedroom Villa</div>
        <div class="prc2-location-2026"><i class="bi bi-geo-alt-fill"></i> Karen, Nairobi</div>
        <div class="prc2-meta-2026">
          <div><i class="bi bi-door-open"></i> 4 Beds</div>
          <div><i class="bi bi-droplet"></i> 3 Baths</div>
        </div>
        <div class="prc2-category-2026">VILLA</div>
      </div>
      <div class="prc2-footer-2026">
        <span><i class="bi bi-building"></i> VillaCare Kenya</span>
        <span><i class="bi bi-clock"></i> 2 weeks ago</span>
      </div>
    </div>
  </div>

  <!-- CARD 6 -->
  <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
    <div class="prc2-card-2026">
      <div class="prc2-image-box-2026">
        <img src="https://images.unsplash.com/photo-1560448204-603b3fc33ddc?q=80&w=1200&auto=format&fit=crop">
        <div class="prc2-badge-2026">FOR RENT</div>
        <div class="prc2-price-2026">Ksh.180,000/Month</div>
      </div>
      <div class="prc2-body-2026">
        <div class="prc2-title-2026">Penthouse</div>
        <div class="prc2-location-2026"><i class="bi bi-geo-alt-fill"></i> Westlands, Nairobi</div>
        <div class="prc2-meta-2026">
          <div><i class="bi bi-door-open"></i> 3 Beds</div>
          <div><i class="bi bi-droplet"></i> 3 Baths</div>
        </div>
        <div class="prc2-category-2026">PENTHOUSE</div>
      </div>
      <div class="prc2-footer-2026">
        <span><i class="bi bi-building"></i> Admin</span>
        <span><i class="bi bi-clock"></i> 1 week ago</span>
      </div>
    </div>
  </div>

</div>

<!-- PAGINATION -->
<nav class="mt-5">
  <ul class="pagination justify-content-center">
    <li class="page-item active"><a class="page-link" href="#" onclick="showPage(1)">1</a></li>
    <li class="page-item"><a class="page-link" href="#" onclick="showPage(2)">2</a></li>
    <li class="page-item"><a class="page-link" href="#" onclick="showPage(3)">3</a></li>
  </ul>
</nav>

</div>
</section>

<script>
function showPage(page) {
  document.querySelectorAll('.property-page').forEach(p => p.classList.remove('active'));
  document.getElementById('page'+page).classList.add('active');

  document.querySelectorAll('.pagination .page-item').forEach(li => li.classList.remove('active'));
  document.querySelectorAll('.pagination .page-item')[page-1].classList.add('active');
}
</script>

<?php include 'layout/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 40,
        easing: 'ease-out'
    });
</script>

</body>
</html>