<?php
// Dynamic meta: set these variables on a page before including this file:
// $meta_title, $meta_description, $meta_keywords, $meta_author, $og_title, $og_description, $og_image

$meta_title = isset($meta_title) ? $meta_title : 'Villacare | Premium Real Estate Since 1991';
$meta_description = isset($meta_description) ? $meta_description : 'Villacare Real Estate - Premium Developments & Investment Solutions Since 1991';
$meta_keywords = isset($meta_keywords) ? $meta_keywords : 'Real Estate, Property, Buy, Rent, Luxury Homes, Investment';
$meta_author = isset($meta_author) ? $meta_author : 'Villacare';
$og_title = isset($og_title) ? $og_title : $meta_title;
$og_description = isset($og_description) ? $og_description : $meta_description;
$og_image = isset($og_image) ? $og_image : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80';
?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?php echo htmlspecialchars($meta_description); ?>">
<meta name="keywords" content="<?php echo htmlspecialchars($meta_keywords); ?>">
<meta name="author" content="<?php echo htmlspecialchars($meta_author); ?>">

<!-- Open Graph / Social Media -->
<meta property="og:title" content="<?php echo htmlspecialchars($og_title); ?>">
<meta property="og:description" content="<?php echo htmlspecialchars($og_description); ?>">
<meta property="og:image" content="<?php echo htmlspecialchars($og_image); ?>">

<title><?php echo htmlspecialchars($meta_title); ?></title>

    <!-- Google Fonts - Improved -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">