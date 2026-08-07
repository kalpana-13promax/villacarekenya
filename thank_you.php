<?php require_once('includes/config.php'); ?>
<!doctype html>
<html lang="en" data-bs-theme="light">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Primary SEO -->
  <title>Thank You – Villacare Kenya | Enquiry Received</title>
  <meta name="description"
    content="Thank you for reaching out to Villacare Kenya. We have received your enquiry and will get back to you shortly.">
  <meta name="robots" content="noindex, nofollow">

  <!-- Assets -->
  <?php include 'layout/link.php'; ?>
  
  <style>
    .site-main {
      background-color: #f8f9fa;
      padding: 80px 20px;
      min-height: 60vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .tq-wrapper {
      width: 100%;
      max-width: 600px;
    }
    .tq-card {
      background: #ffffff;
      padding: 50px 40px;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.05);
      text-align: center;
      position: relative;
    }
    .tq-icon {
      width: 80px;
      height: 80px;
      background: #e8f5e9;
      color: #2e7d32;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 36px;
      margin: 0 auto 25px;
      box-shadow: 0 10px 20px rgba(46, 125, 50, 0.15);
    }
    .tq-eyebrow {
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 2px;
      color: #d4a85f;
      font-weight: 600;
      margin-bottom: 10px;
    }
    .tq-title {
      font-family: 'Playfair Display', serif;
      font-size: 36px;
      color: #1a1a1a;
      font-weight: 700;
      margin-bottom: 20px;
    }
    .tq-lead {
      font-size: 16px;
      color: #666;
      line-height: 1.6;
      margin-bottom: 35px;
    }
    .tq-actions {
      display: flex;
      gap: 15px;
      justify-content: center;
      margin-bottom: 40px;
      flex-wrap: wrap;
    }
    .btn-pill {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 28px;
      background: #d4a85f;
      color: #fff;
      border-radius: 50px;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    .btn-pill:hover {
      background: #b88a44;
      color: #fff;
      transform: translateY(-2px);
      box-shadow: 0 8px 15px rgba(212, 168, 95, 0.3);
    }
    .btn-quiet {
      background: #f1f1f1;
      color: #333;
    }
    .btn-quiet:hover {
      background: #e2e2e2;
      color: #111;
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.05);
    }
    .tq-info-row {
      display: flex;
      justify-content: center;
      gap: 25px;
      border-top: 1px solid #eee;
      padding-top: 25px;
      flex-wrap: wrap;
    }
    .tq-info-item {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      color: #555;
    }
    .tq-info-item i {
      color: #d4a85f;
      font-size: 16px;
    }
    .tq-info-item a {
      color: #555;
      text-decoration: none;
      transition: color 0.2s;
    }
    .tq-info-item a:hover {
      color: #d4a85f;
    }
  </style>

</head>

<body>
  <div class="site-shell">
    <?php include 'layout/header.php'; ?>

    <main class="site-main">
      <div class="tq-wrapper">
        <div class="tq-card">

          <!-- Animated Check Icon -->
          <div class="tq-icon">
            <i class="fa-solid fa-check"></i>
          </div>

          <!-- Eyebrow -->
          <p class="tq-eyebrow">Enquiry Received</p>

          <!-- Heading -->
          <h1 class="tq-title">Thank you for reaching out.</h1>

          <!-- Lead Text -->
          <p class="tq-lead">
            We have received your enquiry and our team will get back to you within
            <strong>24 hours</strong>. We look forward to helping you find the right property.
          </p>

          <!-- Action Buttons -->
          <div class="tq-actions">
            <a class="btn-pill" href="property.php">
              Browse Properties <i class="fa-solid fa-arrow-right"></i>
            </a>
            <a class="btn-pill btn-quiet" href="./">
              Back to Home
            </a>
          </div>

          <!-- Quick Contact Info -->
          <div class="tq-info-row">
            <span class="tq-info-item">
              <i class="fa-solid fa-phone"></i>
              <a href="tel:<?php echo CONTACT; ?>"><?php echo CONTACT; ?></a>
            </span>
            <span class="tq-info-item">
              <i class="fa-solid fa-envelope"></i>
              <a href="mailto:<?php echo EMAIL; ?>"><?php echo EMAIL; ?></a>
            </span>
            <span class="tq-info-item">
              <i class="fa-brands fa-whatsapp"></i>
              <a href="https://wa.me/254<?php echo preg_replace('/\D/', '', CONTACT); ?>" target="_blank"
                rel="noopener">WhatsApp Us</a>
            </span>
          </div>

        </div>
      </div>
    </main>

    <?php include "layout/footer.php" ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js?v=12"></script>
</body>

</html>