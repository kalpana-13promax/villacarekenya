<?php
require_once ('includes/config.php');
include 'config/properties.php';

// Fetch properties from the database instead of config/properties.php
$f_type = isset($_GET['type']) ? trim($_GET['type']) : '';
$f_location = isset($_GET['location']) ? trim($_GET['location']) : '';

// Base query for RENT properties
// Assuming 'Rent' or '1' represents Rent in available_for, and status != '7' means active
$where = "WHERE (pl.available_for LIKE '%Rent%' OR pl.available_for = '1' OR pl.available_for = 'Rent') AND pl.status != '7'";

if ($f_type !== '') {
    $f_type_esc = $boj->sanitize($f_type);
    if (stripos($f_type, 'apartment') !== false || stripos($f_type, 'flat') !== false || stripos($f_type, 'studio') !== false) {
        $where .= " AND (pt.type LIKE '%Apartment%' OR pt.type LIKE '%Flat%' OR pt.type LIKE '%Studio%')";
    } elseif (stripos($f_type, 'town') !== false) {
        $where .= " AND pt.type LIKE '%Town%'";
    } elseif (stripos($f_type, 'villa') !== false) {
        $where .= " AND pt.type LIKE '%Villa%'";
    } elseif (stripos($f_type, 'house') !== false) {
        $where .= " AND pt.type LIKE '%House%'";
    } elseif (stripos($f_type, 'land') !== false) {
        $where .= " AND pt.type LIKE '%Land%'";
    } elseif (stripos($f_type, 'office') !== false || stripos($f_type, 'shop') !== false || stripos($f_type, 'commercial') !== false) {
        $where .= " AND (pt.type LIKE '%Office%' OR pt.type LIKE '%Shop%' OR pt.type LIKE '%Commercial%')";
    } else {
        $where .= " AND pt.type LIKE '%$f_type_esc%'";
    }
}

if ($f_location !== '') {
    $f_loc_esc = $boj->sanitize($f_location);
    $where .= " AND (pl.address LIKE '%$f_loc_esc%' OR pl.location LIKE '%$f_loc_esc%')";
}

$sql = "SELECT pl.*, pt.type as type_name, s.slug 
        FROM property_listing pl 
        LEFT JOIN property_type pt ON pl.category = pt.id 
        LEFT JOIN seo_data s ON pl.id = s.related_id AND s.type = 'property'
        $where ORDER BY pl.id DESC";

$db_properties = $boj->getQuery($sql);

$rent_properties = [];
if (!empty($db_properties)) {
    foreach ($db_properties as $row) {
        
        // Parse property attributes for area
        $size_val = '';
        $measurement_val = '';
        if (!empty($row->property_attribute)) {
            $attrs = json_decode($row->property_attribute, true);
            if (is_array($attrs)) {
                foreach ($attrs as $attr) {
                    $fid = strtolower(trim($attr['field_id'] ?? ''));
                    if (strpos($fid, 'size') !== false) {
                        $size_val = trim($attr['field_type_value']);
                    }
                    if (strpos($fid, 'measurement') !== false) {
                        $measurement_val = trim($attr['field_type_value']);
                    }
                }
            }
        }
        
        if (!empty($size_val)) {
            $area_display = '<i class="bi bi-arrows-fullscreen"></i> ' . htmlspecialchars(trim($size_val . ' ' . $measurement_val));
        } else {
            $prop_type = !empty($row->property_type) ? $row->property_type : 'Property';
            $area_display = '<i class="bi bi-building"></i> ' . htmlspecialchars($prop_type);
        }

        // Construct Image URL
        if (!empty($row->property_image)) {
            $img_url = 'https://crm.villacarekenya.com/crm/uploads/' . $row->property_image;
        } else {
            $img_url = DOMAIN . 'assets/images/default.jpg';
        }

        // Map Status
        $status_map = ['1' => 'Available', '2' => 'Booked', '3' => 'Hold', '4' => 'Pending', '6' => 'Deactive'];
        $status_text = isset($status_map[$row->status]) ? $status_map[$row->status] : 'Unknown';
        $posted_date = !empty($row->create_date) ? date('M d, Y', strtotime($row->create_date)) : 'Recently';

        $rent_properties[] = [
            'id' => $row->id,
            'slug' => !empty($row->slug) ? $row->slug : $row->id,
            'title' => $row->property_title,
            'price' => $row->property_price,
            'price_display' => 'KES. ' . number_format((float)$row->property_price) . ' /Month',
            'area_display' => $area_display,
            'location' => !empty($row->address) ? $row->address : $row->location,
            'type' => !empty($row->type_name) ? $row->type_name : 'Property',
            'status_text' => $status_text,
            'badge' => 'FOR RENT',
            'featured_image' => $img_url,
            'description' => $row->property_description,
            'agent' => 'Villacare',
            'posted' => $posted_date
        ];
    }
}

// Chunk properties for pagination (6 per page)
$items_per_page = 6;
$chunks = array_chunk($rent_properties, $items_per_page);
$total_pages = count($chunks);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Villacare Kenya · Properties For Rent</title>

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

<!-- RENT BANNER SECTION - FIXED -->
<section class="vc-rent-banner">
    <img src="https://images.unsplash.com/photo-1560448204-603b3fc33ddc?q=80&w=1600&auto=format&fit=crop" alt="Rent Properties">
    <div class="vc-banner-overlay"></div>
    <div class="vc-banner-content">
        <h1 class="vc-banner-title">Properties for <span>Rent</span></h1>
        <p class="vc-banner-text">Discover the finest rental properties in Nairobi's most sought-after neighborhoods</p>
        <div class="vc-banner-badge">
            <i class="bi bi-house-door"></i>
            <span><strong><?php echo count($rent_properties); ?></strong> Properties Available</span>
        </div>
    </div>
</section>

<section class="prc2-wrapper-2026">
<div class="container">

<!-- RESULTS SUMMARY -->
<div class="vc-results-summary mb-4" data-aos="fade-up">
    <div class="d-flex justify-content-between align-items-center">
        <div class="vc-count-text">
            Showing <span class="vc-count-num"><?php echo count($rent_properties); ?></span> Properties
        </div>
        <div class="vc-view-options">
            <span class="text-muted small"><i class="bi bi-sort-down"></i> Default Sorting</span>
        </div>
    </div>
</div>

<?php
if (empty($rent_properties)):
  ?>
<div class="text-center py-5" data-aos="fade-up">
    <i class="bi bi-search" style="font-size: 4rem; color: #d4a85f; opacity: 0.3;"></i>
    <h3 class="mt-4">No rental properties found</h3>
    <p class="text-muted">Please check back later or contact us for offline listings.</p>
    <a href="contact.php" class="plx-btn mt-3">Contact Us</a>
</div>
<?php
else:
  foreach ($chunks as $page_idx => $chunk):
    $page_num = $page_idx + 1;
    $active_class = ($page_idx === 0) ? 'active' : '';
    ?>
<!-- PAGE <?php echo $page_num; ?> -->
<div class="row g-4 property-page <?php echo $active_class; ?>" id="page<?php echo $page_num; ?>">

  <?php
  foreach ($chunk as $idx => $property):
    $delay = ($idx % 3 + 1) * 100;
    ?>
  <!-- CARD <?php echo $property['id']; ?> -->
  <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
    <div class="prc2-card-2026">
      <div class="prc2-image-box-2026">
        <img src="<?php echo htmlspecialchars($property['featured_image']); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>">
        <div class="prc2-badge-2026"><?php echo htmlspecialchars($property['badge']); ?></div>
        <div class="prc2-price-2026"><?php echo htmlspecialchars($property['price_display']); ?></div>
      </div>
      <div class="prc2-body-2026">
        <a href="<?php echo DOMAIN; ?>property/<?php echo htmlspecialchars($property['slug']); ?>/" class="stretched-link" aria-label="View details for <?php echo htmlspecialchars($property['title']); ?>"></a>
        <div class="prc2-title-2026"><?php   echo htmlspecialchars($property['title']); ?></div>
        <div class="prc2-location-2026"><i class="bi bi-geo-alt-fill"></i> <?php echo htmlspecialchars($property['location']); ?></div>
        <div class="prc2-meta-2026">
          <div><?php echo $property['area_display']; ?></div>
          <div><i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($property['status_text']); ?></div>
        </div>
        <div class="prc2-category-2026"><?php echo htmlspecialchars($property['type']); ?></div>
      </div>
      <div class="prc2-footer-2026">
        <span><i class="bi bi-person-badge"></i> <?php echo htmlspecialchars($property['agent']); ?></span>
        <span><i class="bi bi-clock"></i> <?php echo htmlspecialchars($property['posted']); ?></span>
      </div>
    </div>
  </div>
  <?php endforeach; ?>

</div>
<?php
  endforeach;
endif;
?>

<!-- PAGINATION -->
<?php if ($total_pages > 1): ?>
<nav class="mt-5">
  <ul class="pagination justify-content-center">
    <?php for ($p = 1; $p <= $total_pages; $p++): ?>
    <li class="page-item <?php echo ($p === 1) ? 'active' : ''; ?>"><a class="page-link" href="#" onclick="showPage(<?php echo $p; ?>)"><?php echo $p; ?></a></li>
    <?php endfor; ?>
  </ul>
</nav>
<?php endif; ?>

</div>
</section>

<script>
function showPage(page) {
  event.preventDefault();
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