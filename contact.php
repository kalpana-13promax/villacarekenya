<?php
@session_start();
$captcha_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lead_add'])) {
    if (!isset($_POST['captcha']) || $_POST['captcha'] != $_SESSION['captcha_code']) {
        $captcha_error = "Captcha verification failed. Please enter the correct 4-digit code.";
        // Unset the POST variable so lead.php doesn't process this request
        unset($_POST['lead_add']);
    }
}

// Generate a new 4-digit code for the page load
$captcha_code = rand(1000, 9999);
$_SESSION['captcha_code'] = $captcha_code;

require_once('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villacare Kenya · Contact Us</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- AOS CSS -->
    <!-- <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"> -->

    <?php include 'layout/link.php'; ?>
  
</head>
<body>

<?php include 'layout/header.php'; ?>


<!-- CONTACT HERO SECTION -->
<section class="contact-hero position-relative d-flex align-items-center text-white text-center">

  <div class="container position-relative z-2">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-center mb-3">
        <li class="breadcrumb-item"><a href="#" class="text-white text-decoration-none">Home</a></li>
        <li class="breadcrumb-item active text-white" aria-current="page">Contact</li>
      </ol>
    </nav>

    <h1 class="hero-title fw-bold">Reach <span style="color: #d4a85f;">Villacare</span></h1>
    <p class="hero-subtitle mt-3">
      Get in touch with our property consultants — we're here to guide you through every step of your real estate journey in Kenya.
    </p>

    <a href="contact.php#contact-form" class="btn hero-btn mt-4">
      Send Message
    </a>
  </div>

</section>


<style>
.contact-hero {
  height: 65vh;
  background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)),
              url('https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=1600&auto=format&fit=crop') center/cover no-repeat;
  overflow: hidden;
  position: relative;
}

/* Background zoom animation */
.contact-hero::before {
  content: "";
  position: absolute;
  inset: 0;
  background: inherit;
  background-size: cover;
  background-position: center;
  animation: zoomBg 15s infinite alternate ease-in-out;
  z-index: 0;
}

@keyframes zoomBg {
  0% { transform: scale(1); }
  100% { transform: scale(1.1); }
}

/* Content above overlay */
.contact-hero::after {
  content: "";
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.55);
  z-index: 1;
}

.z-2 {
  z-index: 2;
}

/* Text animation */
.hero-title {
  font-size: 48px;
  animation: fadeUp 1.2s ease forwards;
}

.hero-subtitle {
  font-size: 18px;
  max-width: 600px;
  margin: auto;
  animation: fadeUp 1.6s ease forwards;
}

@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Glass Button */
.hero-btn {
  background: rgba(255,255,255,0.2);
  color: #fff;
  padding: 10px 30px;
  border-radius: 50px;
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255,255,255,0.4);
  transition: 0.3s;
}

.hero-btn:hover {
  background: #fff;
  color: #000;
}

/* Responsive */
@media (max-width: 768px) {
  .contact-hero {
    height: 50vh;
  }
  .hero-title {
    font-size: 32px;
  }
}
</style>



    <div class="container pt-5" id="contactContainer">
        <!-- LEFT SIDE - Enhanced with Animations -->
        <div class="left" data-aos="fade-right" data-aos-duration="1000">
            <div class="image-wrapper">
                <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=1400&auto=format&fit=crop" alt="Luxury Property">
            </div>
            
            <div class="testimonial-card" data-aos="fade-up" data-aos-delay="300">
                <div class="quote-icon">
                    <i class="bi bi-quote"></i>
                </div>
                <p>"Villacare Kenya made our property purchase seamless. Their expert guidance and transparent process gave us confidence every step of the way."</p>
                <div class="client-info">
                    <div class="client-img">
                        <img src="https://images.unsplash.com/photo-1494790108777-466d853b773d?q=80&w=200&auto=format&fit=crop" alt="Sarah Njeri">
                    </div>
                    <div class="client-details">
                        <h4>Sarah Njeri</h4>
                        <p>Property Owner, Nairobi
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE - Enhanced Form -->
        <div class="right" data-aos="fade-left" data-aos-duration="1000">
            <div class="right-content">
                <!-- <div class="section-tag" data-aos="fade-up" data-aos-delay="100">
                    <i class="bi bi-envelope"></i>
                    <span>GET IN TOUCH</span>
                </div> -->

                <h1 data-aos="fade-up" data-aos-delay="200">
                    Contact <span>Us</span>
                </h1>

                <?php $boj->message(); ?>
                <form action="" method="post">
                    <div class="form-group" data-aos="fade-up" data-aos-delay="250">
                        <div class="input-wrapper">
                            <i class="bi bi-person input-icon"></i>
                            <input type="text" name="lead_name" placeholder="Full Name" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-wrapper" data-aos="fade-up" data-aos-delay="350">
                            <i class="bi bi-envelope input-icon"></i>
                            <input type="email" name="lead_mail" placeholder="Email Address">
                        </div>
                        <div class="input-wrapper" data-aos="fade-up" data-aos-delay="400">
                            <i class="bi bi-telephone input-icon"></i>
                            <input type="tel" name="lead_contact" placeholder="Phone Number">
                        </div>
                    </div>

                    <div class="form-group" data-aos="fade-up" data-aos-delay="450">
                        <div class="input-wrapper">
                            <i class="bi bi-tag input-icon"></i>
                            <select name="property_type">
                                <option>Select Inquiry Type</option>
                                <option>General Inquiry</option>
                                <option>Property Viewing</option>
                                <option>Investment Consultation</option>
                                <option>Support</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" data-aos="fade-up" data-aos-delay="500">
                        <div class="input-wrapper">
                            <i class="bi bi-chat-dots input-icon"></i>
                            <textarea placeholder="Your Message" name="lead_message"></textarea>
                        </div>
                    </div>

                     <!-- CSRF -->
                <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">

                <!-- Hidden CRM Fields required by lead_add -->
                <input type="hidden" name="source_id" value="Website">
                <input type="hidden" name="lead_type" value="Website Form">
                <input type="hidden" name="user" value="System">
                <input type="hidden" name="user_id" value="crm">
                <input type="hidden" name="is_frontend" value="1">

                <div class="form-group" data-aos="fade-up" data-aos-delay="520">
                    <?php if(!empty($captcha_error)): ?>
                        <div style="color: #dc3545; background: #f8d7da; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 8px; border: 1px solid #f5c6cb;">
                            <i class="bi bi-exclamation-circle-fill"></i> <?php echo $captcha_error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div style="display: flex; gap: 15px; align-items: center; justify-content: space-between;">
                        <!-- Premium Code Display -->
                        <div style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #d4a85f; font-size: 22px; font-weight: 700; letter-spacing: 8px; padding: 12px 25px; border-radius: 8px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.5); font-family: monospace; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; user-select: none;">
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0.15; background-image: repeating-linear-gradient(45deg, transparent, transparent 2px, #ffffff 2px, #ffffff 4px);"></div>
                            <span style="position: relative; z-index: 1; text-decoration: line-through; text-decoration-color: rgba(212, 168, 95, 0.4); text-decoration-thickness: 3px;"><?php echo $captcha_code; ?></span>
                        </div>
                        
                        <!-- Native Input Field -->
                        <div class="input-wrapper" style="flex: 1; margin: 0;">
                            <i class="bi bi-shield-lock input-icon"></i>
                            <input type="number" name="captcha" placeholder="Enter 4-digit code" required>
                        </div>
                    </div>
                    <small style="display: block; color: #888; font-size: 12px; margin-top: 8px; margin-left: 5px;"><i class="bi bi-info-circle"></i> Please enter the code shown to verify you are human.</small>
                </div>

                    <button type="submit" class="btn-submit" name="lead_add" data-aos="fade-up" data-aos-delay="550">
                        SEND MESSAGE <i class="bi bi-send"></i>
                    </button>
                </form>

                <!-- Contact Info Cards -->
                <div class="contact-info-cards" data-aos="fade-up" data-aos-delay="600">
                    <div class="info-card">
                        <i class="bi bi-telephone"></i>
                        <p>+254 712 345 678</p>
                    </div>
                    <div class="info-card">
                        <i class="bi bi-envelope"></i>
                        <p>hello@villacarekenya.com</p>
                    </div>
                    <div class="info-card">
                        <i class="bi bi-geo-alt"></i>
                        <p>Diani, Kwale County, Kenya</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br><br>
    <div class="container">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.8434206298016!2d36.7966682744829!3d-1.2666303356039335!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f1741750c152f%3A0x4fc80912de6f9998!2sVilla%20Care%20Ltd!5e0!3m2!1sen!2sin!4v1771146329356!5m2!1sen!2sin" width="100%" height="450" style="border:1px solid #ccc; border-radius: 10px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
<br>

    <?php include 'layout/footer.php'; ?>
    
     <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 50
        });

        // Loading Animation
        // window.addEventListener('load', function() {
        //     setTimeout(function() {
        //         document.getElementById('loading').classList.add('fade-out');
        //     }, 500);
        // });

        // Form Submit Animation
        document.querySelector('form').addEventListener('submit', function(e) {
            const btn = document.querySelector('.btn-submit');
            btn.innerHTML = 'SENDING <i class="bi bi-hourglass"></i>';
            btn.style.background = '#0f1a24';
            // Form will submit naturally to the server now
        });
    </script>
</body>
</html>