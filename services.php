<?php
require_once('includes/config.php');

$services = $boj->getQuery("SELECT * FROM tbl_services ORDER BY id DESC");


// Fallback services if database table is empty
$fallback_services = [
    [
        'title' => 'Project Management',
        'desc' => 'We manage construction projects efficiently to deliver on time, within budget, and as per client requirements.',
        'image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=1200&auto=format&fit=crop',
        'icon' => 'bi-building',
        'link' => 'projects.php#project-management',
        'link_text' => 'View Projects'
    ],
    [
        'title' => 'Property Management',
        'desc' => 'Full-service management: tenant sourcing, maintenance coordination, rent collection and transparent reporting designed for Kenyan landlords.',
        'image' => 'https://images.unsplash.com/photo-1556912172-45b7abe8b7e1?q=80&w=1200&auto=format&fit=crop',
        'icon' => 'bi-key',
        'link' => '',
        'link_text' => ''
    ],
    [
        'title' => 'Investment Advisory',
        'desc' => 'Market analysis, suburb reports and financial modelling to identify high-return opportunities and long-term growth corridors.',
        'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=1200&auto=format&fit=crop',
        'icon' => 'bi-graph-up',
        'link' => '',
        'link_text' => ''
    ],
    [
        'title' => 'Short & Long-Term Rentals',
        'desc' => 'Managed rental programmes for corporate clients, expatriates and local tenants — with marketing, bookings and property care included.',
        'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1200&auto=format&fit=crop',
        'icon' => 'bi-house',
        'link' => 'for-rent.php',
        'link_text' => 'View Rentals'
    ],
    [
        'title' => 'Valuation & Appraisal',
        'desc' => 'Accredited valuation reports for sales, mortgages and investment planning — prepared by experienced Kenyan valuers.',
        'image' => 'https://images.unsplash.com/photo-1580048915913-4f8f5cb481c4?q=80&w=1200&auto=format&fit=crop',
        'icon' => 'bi-calculator',
        'link' => '',
        'link_text' => ''
    ],
    [
        'title' => 'Legal & Conveyancing',
        'desc' => 'Comprehensive conveyancing and transaction support including title searches, contracts and regulatory compliance for Kenyan property transfers.',
        'image' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?q=80&w=1200&auto=format&fit=crop',
        'icon' => 'bi-briefcase',
        'link' => 'contact.php',
        'link_text' => 'Contact Legal Team'
    ]
];

$services_to_show = [];

if (!empty($services)) {
    // If database has services, format them
    foreach ($services as $service) {
        
        // Check if image exists
        $imagePath = 'uploads/' . $service->image;
        if (!empty($service->image) && file_exists(__DIR__ . '/' . $imagePath)) {
            $finalImage = $imagePath;
        } else {
            // Default image if missing
            $finalImage = 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=1200&auto=format&fit=crop';
        }

        // Match with fallback to get predefined icons and links if they match title
        $icon = 'bi-building';
        $link = '';
        $link_text = '';

        foreach ($fallback_services as $fb) {
            if (strcasecmp(trim($fb['title']), trim($service->heading)) === 0) {
                $icon = !empty($fb['icon']) ? $fb['icon'] : $icon;
                $link = !empty($fb['link']) ? $fb['link'] : '';
                $link_text = !empty($fb['link_text']) ? $fb['link_text'] : '';
                break;
            }
        }

        // Add to the list
        $services_to_show[] = [
            'title' => $service->heading,
            'desc' => $service->short_description,
            'image' => $finalImage,
            'icon' => $icon,
            'link' => $link,
            'link_text' => $link_text
        ];
    }
}

// Append fallback services if database has fewer items, avoiding duplicates
$count_needed = count($fallback_services) - count($services_to_show);
if ($count_needed > 0 || empty($services)) {
    foreach ($fallback_services as $fb) {
        $exists = false;
        foreach ($services_to_show as $s) {
            if (strcasecmp(trim($s['title']), trim($fb['title'])) === 0) {
                $exists = true;
                break;
            }
        }
        if (!$exists) {
            $services_to_show[] = [
                'title' => $fb['title'],
                'desc' => $fb['desc'],
                'image' => $fb['image'],
                'icon' => $fb['icon'],
                'link' => $fb['link'],
                'link_text' => $fb['link_text']
            ];
        }
    }
}

// ---------------------------------------------------------
// Page Content Configuration (Easy to edit dynamic content)
// ---------------------------------------------------------
$page_content = [
    'banner' => [
        'image' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=1600&auto=format&fit=crop',
        'title' => 'Our <span>Services</span>'
    ],
    'intro' => [
        'tag' => 'VILLACARE KENYA',
        'title' => 'Full-Service <span>Property</span> Solutions',
        'text' => 'Villacare Kenya specialises in property sales, rentals, management and advisory services across Nairobi and the Coast — built for homeowners and investors.'
    ],
    'featured' => [
        'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=1200&auto=format&fit=crop',
        'tag' => 'FEATURED SERVICE',
        'title' => 'Villacare <span>Property Solutions</span>',
        'desc' => 'Villacare Real Estate delivers trusted property services designed to help clients buy, sell, rent and invest with confidence and transparency.',
        'list' => [
            'Residential & Commercial Property Sales',
            'Rental & Leasing Management Services',
            'Professional Market Guidance & Investment Support'
        ],
        'btn_text' => 'Discover Our Services',
        'btn_link' => 'services/'
    ],
    'process' => [
        'title' => 'How It <span>Works</span>',
        'desc' => 'A simple, transparent process from consultation to closing',
        'steps' => [
            ['title' => 'Consultation', 'desc' => 'We meet to understand your needs, budget, and timeline'],
            ['title' => 'Property Search', 'desc' => 'We curate properties that match your criteria'],
            ['title' => 'Viewings', 'desc' => 'Accompanied visits to shortlisted properties'],
            ['title' => 'Closing', 'desc' => 'We guide you through negotiations and paperwork']
        ]
    ],
    'cta' => [
        'title' => 'Ready to Get <span>Started?</span>',
        'desc' => 'Contact our team today for a free consultation and let us help you achieve your real estate goals.',
        'btn1_text' => 'Call Us Now',
        'btn1_link' => 'contact/',
        'btn2_text' => 'Schedule Consultation',
        'btn2_link' => 'contact/'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villacare Kenya · Our Services</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- AOS CSS -->
    <?php include 'layout/link.php'; ?>
    
</head>
<body>

    <!-- Header -->
    <?php include 'layout/header.php'; ?>

    <!-- Page Banner -->
    <section class="vc-page-banner">
        <img src="<?php echo $page_content['banner']['image']; ?>" alt="Services">
        <div class="vc-banner-overlay"></div>
        <div class="vc-banner-content">
            <h1 class="vc-banner-title"><?php echo $page_content['banner']['title']; ?></h1>
            <div class="vc-banner-breadcrumb">
                <a href="<?php echo DOMAIN; ?>">Home</a> <i class="bi bi-chevron-right"></i> Services
            </div>
        </div>
    </section>

    <!-- Services Intro -->
    <section class="vc-services-intro">
        <div class="vc-container">
            <div class="vc-intro-tag"><?php echo $page_content['intro']['tag']; ?></div>
            <h2 class="vc-intro-title"><?php echo $page_content['intro']['title']; ?></h2>
            <p class="vc-intro-text"><?php echo $page_content['intro']['text']; ?></p>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="vc-services-grid">
        <div class="vc-container">
            <div class="vc-grid">
                
                <?php foreach ($services_to_show as $idx => $s): ?>
                <!-- Service <?php echo $idx + 1; ?> -->
                <div class="vc-service-item" data-aos="fade-up" data-aos-delay="<?php echo ($idx % 3) * 100; ?>">
                    <div class="vc-service-image">
                        <img src="<?php echo htmlspecialchars($s['image']); ?>" alt="<?php echo htmlspecialchars($s['title']); ?>">
                        <div class="vc-service-overlay"></div>
                        <div class="vc-service-icon">
                            <i class="bi <?php echo htmlspecialchars($s['icon']); ?>"></i>
                        </div>
                    </div>
                    <div class="vc-service-content">
                        <h3><?php echo htmlspecialchars($s['title']); ?></h3>
                        <p><?php echo htmlspecialchars($s['desc']); ?></p>
                        <?php if (!empty($s['link'])): ?>
                        <a href="<?php echo htmlspecialchars($s['link']); ?>" class="vc-service-link"><?php echo htmlspecialchars(!empty($s['link_text']) ? $s['link_text'] : 'Learn More'); ?> <i class="bi bi-arrow-right"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <!-- Featured Service -->
    <section class="vc-featured-section">
    <div class="vc-container">
        <div class="vc-featured-row">
            <div class="vc-featured-content">
                <div class="vc-featured-tag"><?php echo $page_content['featured']['tag']; ?></div>
                
                <h2><?php echo $page_content['featured']['title']; ?></h2>
                
                <p><?php echo $page_content['featured']['desc']; ?></p>
                
                <ul class="vc-featured-list">
                    <?php foreach($page_content['featured']['list'] as $listItem): ?>
                    <li><i class="bi bi-check-circle-fill"></i> <?php echo $listItem; ?></li>
                    <?php endforeach; ?>
                </ul>
                
                <a href="<?php echo DOMAIN . $page_content['featured']['btn_link']; ?>" class="vc-featured-btn">
                    <?php echo $page_content['featured']['btn_text']; ?> <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="vc-featured-image">
                <img src="<?php echo $page_content['featured']['image']; ?>" alt="Villacare Real Estate">
            </div>
        </div>
    </div>
</section>


    <!-- Process Section -->
    <section class="vc-process-section">
        <div class="vc-container">
            <div class="vc-process-header">
                <h2><?php echo $page_content['process']['title']; ?></h2>
                <p><?php echo $page_content['process']['desc']; ?></p>
            </div>
            <div class="vc-process-steps">
                <?php foreach($page_content['process']['steps'] as $index => $step): ?>
                <div class="vc-step">
                    <div class="vc-step-number"><?php echo $index + 1; ?></div>
                    <h4><?php echo $step['title']; ?></h4>
                    <p><?php echo $step['desc']; ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="vc-cta-block">
        <div class="vc-container">
            <h2><?php echo $page_content['cta']['title']; ?></h2>
            <p><?php echo $page_content['cta']['desc']; ?></p>
            <div class="vc-cta-group">
                <a href="<?php echo DOMAIN . $page_content['cta']['btn1_link']; ?>" class="vc-cta-primary"><i class="bi bi-telephone me-2"></i><?php echo $page_content['cta']['btn1_text']; ?></a>
                <a href="<?php echo DOMAIN . $page_content['cta']['btn2_link']; ?>" class="vc-cta-secondary"><i class="bi bi-calendar me-2"></i><?php echo $page_content['cta']['btn2_text']; ?></a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'layout/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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