<?php
require_once('includes/config.php');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$blog_post = null;

try {
    $database = new db();
    if ($id > 0) {
        $res = $database->getQuery("SELECT * FROM tbl_blog WHERE id = $id");
        if ($res) {
            $blog_post = $res[0];
        }
    }
    
    // Fallback if not found or no ID
    if (!$blog_post) {
        $res = $database->getQuery("SELECT * FROM tbl_blog ORDER BY id DESC LIMIT 1");
        if ($res) {
            $blog_post = $res[0];
        }
    }
} catch (Exception $e) {
    // db failed
}

// Default image path
$default_img = DOMAIN . 'assets/images/default.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VillaCare Kenya · <?php echo htmlspecialchars($blog_post ? $blog_post->blog_title : 'Blog Details'); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- AOS CSS -->
    <?php include 'layout/link.php'; ?>
    
    <style>
        .vc-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        /* ===== BREADCRUMB ===== */
        .vc-breadcrumb {
            margin-bottom: 30px;
            font-size: 14px;
            color: #546e7a;
        }

        .vc-breadcrumb a {
            color: #d4a85f;
            text-decoration: none;
        }

        .vc-breadcrumb i {
            font-size: 12px;
            margin: 0 8px;
            color: #d4a85f;
        }

        /* ===== BLOG CARD ===== */
        .vc-blog-card {
            background: #ffffff;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid #e5e5e5;
        }

        /* Blog Meta */
        .vc-blog-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #546e7a;
            flex-wrap: wrap;
        }

        .vc-blog-meta i {
            color: #d4a85f;
            margin-right: 5px;
        }

        .vc-blog-meta span {
            color: #1e2a2e;
            font-weight: 600;
            margin-right: 5px;
        }

        .vc-blog-category {
            display: inline-block;
            background: rgba(212,168,95,0.1);
            color: #d4a85f;
            padding: 5px 15px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            margin-right: 15px;
        }

        /* Blog Title */
        .vc-blog-title {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #1e2a2e;
            line-height: 1.2;
        }

        /* Featured Image */
        .vc-blog-image {
            margin-bottom: 30px;
            border-radius: 10px;
            overflow: hidden;
        }

        .vc-blog-image img {
            max-width: 100%;
            /* height: auto; */
            object-fit: cover;
            display: block;
            margin: 0 auto;
        }

        /* Blog Content */
        .vc-blog-content {
            font-size: 16px;
            color: #546e7a;
        }

        .vc-blog-content h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 600;
            margin: 30px 0 15px;
            color: #1e2a2e;
        }

        .vc-blog-content p {
            margin-bottom: 20px;
        }

        .vc-blog-content ul {
            margin-bottom: 20px;
            padding-left: 20px;
        }

        .vc-blog-content li {
            margin-bottom: 8px;
        }

        /* Tags */
        .vc-blog-tags {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e5e5e5;
        }

        .vc-tags-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #1e2a2e;
        }

        .vc-tags-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .vc-tag {
            background: #f8f6f4;
            color: #546e7a;
            padding: 6px 15px;
            border-radius: 30px;
            font-size: 13px;
            text-decoration: none;
            transition: 0.3s;
            border: 1px solid #e5e5e5;
        }

        .vc-tag:hover {
            background: #d4a85f;
            color: #ffffff;
        }

        /* Share */
        .vc-blog-share {
            margin-top: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .vc-share-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e2a2e;
        }

        .vc-share-links {
            display: flex;
            gap: 10px;
        }

        .vc-share-link {
            width: 35px;
            height: 35px;
            background: #f8f6f4;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1e2a2e;
            text-decoration: none;
            transition: 0.3s;
            border: 1px solid #e5e5e5;
        }

        .vc-share-link:hover {
            background: #d4a85f;
            color: #ffffff;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .vc-container {
                padding: 40px 20px;
            }
            
            .vc-blog-card {
                padding: 30px;
            }
            
            .vc-blog-title {
                font-size: 30px;
            }
            
            .vc-blog-meta {
                gap: 15px;
            }
        }

        @media (max-width: 576px) {
            .vc-blog-card {
                padding: 20px;
            }
            
            .vc-blog-title {
                font-size: 26px;
            }
            
            .vc-blog-meta {
                flex-direction: column;
                gap: 8px;
            }
            
            .vc-blog-share {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

    <?php include 'layout/header.php'; ?>

    <div class="vc-container">
        
        <!-- Breadcrumb -->
        <div class="vc-breadcrumb">
            <a href="#">Home</a> <i class="bi bi-chevron-right"></i>
            <a href="#">Blog</a> <i class="bi bi-chevron-right"></i>
            <span>Blog Details</span>
        </div>

        <!-- Blog Card -->
        <?php if ($blog_post): 
            $blog_img = '';
            if (!empty($blog_post->pro_image)) {
                $blog_img = 'https://crm.villacarekenya.com/crm/uploads/' . $blog_post->pro_image;
            } else {
                $blog_img = $default_img;
            }
            $date_str = date('F d, Y', strtotime($blog_post->timestamps));
            $author = !empty($blog_post->name) ? $blog_post->name : 'Sarah Johnson';
            $category = !empty($blog_post->post) ? $blog_post->post : 'Insights';
        ?>
        <div class="vc-blog-card" data-aos="fade-up">
            
            <!-- Meta Information -->
            <div class="vc-blog-meta">
                <span><i class="bi bi-folder"></i> <span>Category:</span> <?php echo htmlspecialchars($category); ?></span>
                <span><i class="bi bi-calendar3"></i> <span>Date:</span> <?php echo htmlspecialchars($date_str); ?></span>
                <span><i class="bi bi-person"></i> <span>By:</span> <?php echo htmlspecialchars($author); ?></span>
                <span><i class="bi bi-chat"></i> <span>Comments:</span> 0</span>
            </div>

            <!-- Title -->
            <h1 class="vc-blog-title"><?php echo htmlspecialchars($blog_post->blog_title); ?></h1>

            <!-- Featured Image -->
            <div class="vc-blog-image">
                <img src="<?php echo htmlspecialchars($blog_img); ?>" alt="<?php echo htmlspecialchars($blog_post->blog_title); ?>">
            </div>

            <!-- Content -->
            <div class="vc-blog-content">
                <?php echo html_entity_decode($blog_post->blog, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>
            </div>

            <!-- Tags -->
            <div class="vc-blog-tags">
                <div class="vc-tags-title">Tags:</div>
                <div class="vc-tags-list">
                    <a href="#" class="vc-tag"><?php echo htmlspecialchars($category); ?></a>
                    <a href="#" class="vc-tag">Villacare Kenya</a>
                    <a href="#" class="vc-tag">Real Estate</a>
                    <a href="#" class="vc-tag">Property Development</a>
                </div>
            </div>

            <!-- Share -->
            <div class="vc-blog-share">
                <span class="vc-share-title">Share:</span>
                <div class="vc-share-links">
                    <a href="#" class="vc-share-link"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="vc-share-link"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="vc-share-link"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="vc-share-link"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>

        </div>
        <?php else: ?>
        <div class="alert alert-warning text-center my-5" data-aos="fade-up">
            <h4>No Blog Post Found</h4>
            <p>Sorry, the blog article you are looking for could not be found.</p>
            <a href="blog.php" class="btn btn-outline-primary mt-3">Back to Blog</a>
        </div>
        <?php endif; ?>

    </div>

    <?php include 'layout/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>
</html>