<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>VillaCare Kenya · Project Locations</title>

<!-- Bootstrap 5 CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<?php include 'layout/link.php'; ?>

<style>

/* body {
    background: #f8f6f4;
    font-family: 'Inter', sans-serif;
} */

/* Section Title */
.section-title {
    font-family: 'Playfair Display', serif;
    font-size: 36px;
    font-weight: 700;
    color: #1e2a2e;
    margin-bottom: 8px;
}

.section-title span {
    color: #d4a85f;
    position: relative;
    display: inline-block;
}

.section-title span::after {
    content: '';
    position: absolute;
    bottom: 6px;
    left: 0;
    width: 100%;
    height: 8px;
    background: rgba(212,168,95,0.2);
    z-index: -1;
    border-radius: 4px;
}

.section-subtitle {
    color: #546e7a;
    font-size: 14px;
    max-width: 500px;
    margin: 0 auto;
}

/* Project Card */
.project-card {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 10px 20px -10px rgba(0,0,0,0.15);
    border: 1px solid rgba(212,168,95,0.1);
    transition: all 0.3s ease;
    aspect-ratio: 4/3;
}

.project-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 30px -15px rgba(212,168,95,0.3);
    border-color: #d4a85f;
}

.project-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.project-card:hover img {
    transform: scale(1.08);
}

/* Overlay */
.project-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to top, rgba(15,26,36,0.85), rgba(15,26,36,0.2));
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 20px;
    transition: background 0.3s ease;
}

.project-card:hover .project-overlay {
    background: linear-gradient(to top, rgba(212,168,95,0.85), rgba(212,168,95,0.2));
}

/* Project Title */
.project-title {
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    font-size: 20px;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
    transform: translateY(0);
    transition: transform 0.3s ease;
}

.project-card:hover .project-title {
    transform: translateY(-3px);
}

/* Project Count */
.project-count {
    font-weight: 500;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(255,255,255,0.15);
    padding: 4px 12px;
    border-radius: 40px;
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255,255,255,0.2);
    width: fit-content;
    transition: all 0.3s ease;
}

.project-card:hover .project-count {
    background: #1e2f3c;
    color: #ffffff;
    border-color: #1e2f3c;
}

.project-count i {
    font-size: 12px;
}

/* View All Button - Smaller */
.btn-view {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1.5px solid #d4a85f;
    color: #1e2a2e;
    padding: 10px 28px;
    border-radius: 40px;
    text-decoration: none;
    font-weight: 500;
    font-size: 13px;
    transition: all 0.3s ease;
    background: transparent;
}

.btn-view:hover {
    background: #d4a85f;
    color: #ffffff;
    border-color: #d4a85f;
}

.btn-view i {
    font-size: 14px;
}

/* Responsive */
@media (max-width: 992px) {
    .section-title {
        font-size: 32px;
    }
    
    .project-title {
        font-size: 18px;
    }
}

@media (max-width: 768px) {
    .section-title {
        font-size: 28px;
    }
    
    .section-subtitle {
        font-size: 13px;
    }
    
    .project-card {
        aspect-ratio: 16/9;
    }
    
    .project-title {
        font-size: 18px;
    }
}

@media (max-width: 576px) {
    .section-title {
        font-size: 26px;
    }
    
    .project-title {
        font-size: 16px;
    }
    
    .project-count {
        font-size: 12px;
        padding: 3px 10px;
    }
    
    .project-overlay {
        padding: 15px;
    }
    
    .btn-view {
        padding: 8px 22px;
        font-size: 12px;
    }
}

</style>
</head>
<body>

<?php include 'layout/header.php'; ?>

<div class="container py-5">

    <!-- Section Header -->
    <div class="text-center mb-4" data-aos="fade-up">
        <h2 class="section-title">Project <span>Locations</span></h2>
        <p class="section-subtitle">Explore our premium developments across Kenya</p>
    </div>

    <!-- Project Grid -->
    <div class="row g-3">
        
        <!-- Diani Projects -->
        <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-delay="100">
            <div class="project-card">
                <img src="assets/images/kitisuru.jpg" class="img-fluid" alt="Diani">
                <div class="project-overlay">
                    <div class="project-title">Kitisuru Terraces</div>
                    <!-- <div class="project-count"><i class="bi bi-building"></i> 4 Projects</div> -->
                </div>
            </div>
        </div>

        <!-- Juja Projects -->
        <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-delay="200">
            <div class="project-card">
                <img src="assets/images/lenanan.jpg" class="img-fluid" alt="Juja">
                <div class="project-overlay">
                    <div class="project-title">Lenanan Gardens</div>
                    <!-- <div class="project-count"><i class="bi bi-building"></i> 11 Projects</div> -->
                </div>
            </div>
        </div>

        <!-- Konza Projects -->
        <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-delay="300">
            <div class="project-card">
                <img src="assets/images/oyster.jpg" class="img-fluid" alt="Konza">
                <div class="project-overlay">
                    <div class="project-title">Oyster Paradise</div>
                    <!-- <div class="project-count"><i class="bi bi-building"></i> 6 Projects</div> -->
                </div>
            </div>
        </div>

        <!-- Nairobi Projects -->
        <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-delay="400">
            <div class="project-card">
                <img src="assets/images/belcrest.jpg" class="img-fluid" alt="Nairobi">
                <div class="project-overlay">
                    <div class="project-title">Belcrest Gardens</div>
                    <!-- <div class="project-count"><i class="bi bi-building"></i> 9 Projects</div> -->
                </div>
            </div>
        </div>

        <!-- Kilimani Projects -->
        <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-delay="500">
            <div class="project-card">
                <img src="assets/images/malibu.jpg" class="img-fluid" alt="Kilimani">
                <div class="project-overlay">
                    <div class="project-title">Malibu Court</div>
                    <!-- <div class="project-count"><i class="bi bi-building"></i> 7 Projects</div> -->
                </div>
            </div>
        </div>

        <!-- Westlands Projects -->
        <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-delay="600">
            <div class="project-card">
                <img src="assets/images/villa.jpg" class="img-fluid" alt="Westlands">
                <div class="project-overlay">
                    <div class="project-title">Villa Maria</div>
                    <!-- <div class="project-count"><i class="bi bi-building"></i> 12 Projects</div> -->
                </div>
            </div>
        </div>

        <!-- Karen Projects -->
        <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-delay="700">
            <div class="project-card">
                <img src="assets/images/brooklyn.jpg" class="img-fluid" alt="Karen">
                <div class="project-overlay">
                    <div class="project-title">Brooklyn Springs</div>
                    <!-- <div class="project-count"><i class="bi bi-building"></i> 8 Projects</div> -->
                </div>
            </div>
        </div>

        <!-- Runda Projects -->
        <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-delay="800">
            <div class="project-card">
                <img src="assets/images/grand.jpg" class="img-fluid" alt="Runda">
                <div class="project-overlay">
                    <div class="project-title">The Grand Palace</div>
                    <!-- <div class="project-count"><i class="bi bi-building"></i> 5 Projects</div> -->
                </div>
            </div>
        </div>

    </div>

    <!-- View All Button - Smaller -->
    <!-- <div class="text-center mt-4" data-aos="fade-up">
        <a href="#" class="btn-view">
            View All Locations <i class="bi bi-arrow-right"></i>
        </a>
    </div> -->

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<?php include 'layout/footer.php'; ?>
<script>
    AOS.init({
        duration: 700,
        once: true,
        offset: 30
    });
</script>

</body>
</html>