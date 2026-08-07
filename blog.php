<?php 
require_once('includes/config.php');

// ── Pagination settings ──────────────────────────────────────────────────────
$posts_per_page = 6;
$current_page   = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
$offset         = ($current_page - 1) * $posts_per_page;

// ── Search / Category filter ─────────────────────────────────────────────────
$search   = isset($_GET['search'])   ? trim($_GET['search'])   : '';
$cat      = isset($_GET['category']) ? trim($_GET['category']) : '';

$where_clauses = [];
$search_safe   = '';
$cat_safe      = '';

if ($search !== '') {
    $search_safe = addslashes($search);
    $where_clauses[] = "(blog_title LIKE '%{$search_safe}%' OR blog LIKE '%{$search_safe}%')";
}
if ($cat !== '') {
    $cat_safe = addslashes($cat);
    $where_clauses[] = "post = '{$cat_safe}'";
}

$where_sql = count($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

// ── Total count for pagination ────────────────────────────────────────────────
$total_result = $boj->getQuery("SELECT COUNT(*) as cnt FROM tbl_blog {$where_sql}");
$total_posts  = ($total_result && isset($total_result[0]->cnt)) ? (int)$total_result[0]->cnt : 0;
$total_pages  = $total_posts > 0 ? ceil($total_posts / $posts_per_page) : 1;
if ($current_page > $total_pages) $current_page = $total_pages;

// ── Main blog posts ───────────────────────────────────────────────────────────
$blogs = $boj->getQuery("SELECT * FROM tbl_blog {$where_sql} ORDER BY id DESC LIMIT {$posts_per_page} OFFSET {$offset}");

// ── Dynamic categories with counts ───────────────────────────────────────────
$categories = $boj->getQuery("SELECT post, COUNT(*) as cnt FROM tbl_blog WHERE post IS NOT NULL AND post != '' GROUP BY post ORDER BY cnt DESC");

// ── Popular posts (top 3 by id) ───────────────────────────────────────────────
$popular_posts = $boj->getQuery("SELECT id, blog_title, pro_image, timestamps FROM tbl_blog ORDER BY id DESC LIMIT 3");

// ── Build query-string helper (keeps existing params, swaps one key) ──────────
function build_qs($key, $value, $exclude = []) {
    $params = $_GET;
    $params[$key] = $value;
    foreach ($exclude as $k) unset($params[$k]);
    return '?' . http_build_query($params);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'layout/link.php'; ?>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <title>Blog – Villacare Kenya | Market Insights &amp; Property Guidance</title>
    <meta name="description" content="Villacare Kenya's latest analysis on property trends, investing and coastal &amp; city living. Expert insights for real estate in Kenya.">
</head>
<body>

    <!-- Header -->
    <?php include 'layout/header.php'; ?>

    <!-- Blog Banner -->
    <section class="vc-blog-banner">
        <img src="https://images.unsplash.com/photo-1499750310107-5fef28a66643?q=80&w=1600&auto=format&fit=crop" alt="Blog Banner">
        <div class="vc-banner-overlay"></div>
        <div class="vc-banner-content">
            <h1 class="vc-banner-title">Villacare <span>Blog</span></h1>
            <div class="vc-banner-breadcrumb">
                <a href="index.php">Home</a> <i class="bi bi-chevron-right" style="font-size: 12px; margin: 0 5px;"></i> Blog
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="vc-blog-section">
        <div class="vc-container">

            <!-- Section Header -->
            <div class="vc-section-header" data-aos="fade-up">
                <div class="vc-section-tag">
                    <i class="bi bi-pencil"></i>
                    <span>VILLACARE INSIGHTS</span>
                </div>
                <h2>Market <span>Insights</span> &amp; Guidance</h2>
                <p>Villacare Kenya's latest analysis on property trends, investing and coastal &amp; city living.</p>
            </div>

            <!-- Active filters notice -->
            <?php if ($search !== '' || $cat !== ''): ?>
            <div class="vc-filter-notice" data-aos="fade-up">
                <i class="bi bi-funnel-fill"></i>
                <?php if ($search !== ''): ?>
                    Showing results for: <strong>"<?= htmlspecialchars($search) ?>"</strong>
                <?php endif; ?>
                <?php if ($cat !== ''): ?>
                    <?= $search !== '' ? ' in ' : 'Category: ' ?><strong><?= htmlspecialchars($cat) ?></strong>
                <?php endif; ?>
                &nbsp;&nbsp;<a href="blog.php" class="vc-filter-clear"><i class="bi bi-x-circle"></i> Clear filters</a>
            </div>
            <?php endif; ?>

            <!-- Blog Grid -->
            <div class="vc-blog-grid">

                <?php if ($blogs && count($blogs) > 0):
                    foreach ($blogs as $i => $b):
                        $image     = !empty($b->pro_image) && file_exists(__DIR__ . '/uploads/' . $b->pro_image)
                                        ? DOMAIN . 'uploads/' . $b->pro_image
                                        : DOMAIN . 'assets/images/default.jpg';
                        $author    = !empty($b->name)  ? $b->name  : 'Admin';
                        $category  = !empty($b->post)  ? $b->post  : 'Blog';
                        $date      = !empty($b->timestamps) ? date('d M Y', strtotime($b->timestamps)) : '';
                        $excerpt   = substr(strip_tags(html_entity_decode($b->blog, ENT_QUOTES | ENT_HTML5, 'UTF-8')), 0, 130);
                        $delay     = ($i % $posts_per_page) * 100;
                        $is_first  = ($i === 0 && $current_page === 1 && $search === '' && $cat === '');
                ?>
                <div class="vc-blog-card<?= $is_first ? ' featured' : '' ?>" data-aos="fade-up" data-aos-delay="<?= $delay ?>">

                    <div class="vc-blog-image">
                        <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($b->blog_title) ?>">
                        <div class="vc-blog-category"><?= htmlspecialchars($category) ?></div>
                        <?php if ($date): ?>
                        <div class="vc-blog-date"><i class="bi bi-calendar3"></i> <?= $date ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="vc-blog-content">
                        <div class="vc-blog-meta">
                            <span><i class="bi bi-person"></i> By <?= htmlspecialchars($author) ?></span>
                        </div>

                        <h3 class="vc-blog-title">
                            <?= htmlspecialchars($b->blog_title) ?>
                        </h3>

                        <p class="vc-blog-excerpt">
                            <?= htmlspecialchars($excerpt) ?><?= strlen(strip_tags($b->blog)) > 130 ? '…' : '' ?>
                        </p>

                        <a href="blog-details.php?id=<?= (int)$b->id ?>" class="vc-blog-link">
                            Read More <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                </div>
                <?php
                    endforeach;
                else: ?>
                <div class="vc-no-posts" data-aos="fade-up">
                    <i class="bi bi-journal-x"></i>
                    <h3>No Posts Found</h3>
                    <p>
                        <?php if ($search !== '' || $cat !== ''): ?>
                            No blog posts match your current filter. <a href="blog.php">View all posts</a>
                        <?php else: ?>
                            No blog posts have been published yet. Check back soon!
                        <?php endif; ?>
                    </p>
                </div>
                <?php endif; ?>

            </div><!-- /.vc-blog-grid -->

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="vc-pagination" data-aos="fade-up">

                <!-- Previous -->
                <?php if ($current_page > 1): ?>
                <a href="<?= build_qs('page', $current_page - 1) ?>" class="vc-page-link vc-page-prev">
                    <i class="bi bi-chevron-left"></i>
                </a>
                <?php else: ?>
                <span class="vc-page-link vc-page-prev disabled"><i class="bi bi-chevron-left"></i></span>
                <?php endif; ?>

                <!-- Page numbers -->
                <?php
                    $window = 2;
                    $start  = max(1, $current_page - $window);
                    $end    = min($total_pages, $current_page + $window);

                    if ($start > 1) {
                        echo '<a href="' . build_qs('page', 1) . '" class="vc-page-link">1</a>';
                        if ($start > 2) echo '<span class="vc-page-link disabled">…</span>';
                    }
                    for ($p = $start; $p <= $end; $p++) {
                        $active = ($p === $current_page) ? ' active' : '';
                        echo '<a href="' . build_qs('page', $p) . '" class="vc-page-link' . $active . '">' . $p . '</a>';
                    }
                    if ($end < $total_pages) {
                        if ($end < $total_pages - 1) echo '<span class="vc-page-link disabled">…</span>';
                        echo '<a href="' . build_qs('page', $total_pages) . '" class="vc-page-link">' . $total_pages . '</a>';
                    }
                ?>

                <!-- Next -->
                <?php if ($current_page < $total_pages): ?>
                <a href="<?= build_qs('page', $current_page + 1) ?>" class="vc-page-link vc-page-next">
                    <i class="bi bi-chevron-right"></i>
                </a>
                <?php else: ?>
                <span class="vc-page-link vc-page-next disabled"><i class="bi bi-chevron-right"></i></span>
                <?php endif; ?>

            </div>
            <?php endif; ?>

            <!-- Sidebar Widgets -->
            <div class="vc-blog-sidebar">
                <div class="row g-4">

                    <!-- Search Widget -->
                    <div class="col-md-6 col-lg-3">
                        <div class="vc-sidebar-widget" data-aos="fade-up">
                            <h4 class="vc-widget-title">Search</h4>
                            <form class="vc-search-form" action="blog.php" method="GET">
                                <?php if ($cat !== ''): ?>
                                <input type="hidden" name="category" value="<?= htmlspecialchars($cat) ?>">
                                <?php endif; ?>
                                <input type="text" name="search" class="vc-search-input"
                                       placeholder="Search articles…"
                                       value="<?= htmlspecialchars($search) ?>">
                                <button type="submit" class="vc-search-btn"><i class="bi bi-search"></i></button>
                            </form>
                        </div>
                    </div>

                    <!-- Categories Widget -->
                    <div class="col-md-6 col-lg-3">
                        <div class="vc-sidebar-widget" data-aos="fade-up" data-aos-delay="100">
                            <h4 class="vc-widget-title">Categories</h4>
                            <ul class="vc-categories-list">
                                <?php if ($categories && count($categories) > 0):
                                    foreach ($categories as $c):
                                        $active_cat = ($cat === $c->post) ? ' class="active"' : '';
                                ?>
                                <li<?= $active_cat ?>>
                                    <a href="blog.php?category=<?= urlencode($c->post) ?>">
                                        <?= htmlspecialchars($c->post) ?>
                                        <span><?= (int)$c->cnt ?></span>
                                    </a>
                                </li>
                                <?php
                                    endforeach;
                                else: ?>
                                <li><span style="color:#999;font-size:14px;">No categories yet.</span></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Popular Posts Widget -->
                    <div class="col-md-6 col-lg-3">
                        <div class="vc-sidebar-widget" data-aos="fade-up" data-aos-delay="200">
                            <h4 class="vc-widget-title">Popular Posts</h4>

                            <?php if ($popular_posts && count($popular_posts) > 0):
                                foreach ($popular_posts as $pp):
                                    $pp_img  = !empty($pp->pro_image) && file_exists(__DIR__ . '/uploads/' . $pp->pro_image)
                                                    ? DOMAIN . 'uploads/' . $pp->pro_image
                                                    : DOMAIN . 'assets/images/default.jpg';
                                    $pp_date = !empty($pp->timestamps) ? date('d M Y', strtotime($pp->timestamps)) : '';
                            ?>
                            <div class="vc-popular-post">
                                <div class="vc-popular-img">
                                    <img src="<?= htmlspecialchars($pp_img) ?>" alt="<?= htmlspecialchars($pp->blog_title) ?>">
                                </div>
                                <div class="vc-popular-info">
                                    <h4><a href="blog-details.php?id=<?= (int)$pp->id ?>"><?= htmlspecialchars($pp->blog_title) ?></a></h4>
                                    <?php if ($pp_date): ?>
                                    <span><i class="bi bi-calendar3"></i> <?= $pp_date ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php
                                endforeach;
                            else: ?>
                            <p style="color:#999;font-size:14px;">No posts yet.</p>
                            <?php endif; ?>

                        </div>
                    </div>

                    <!-- Tags Widget -->
                    <div class="col-md-6 col-lg-3">
                        <div class="vc-sidebar-widget" data-aos="fade-up" data-aos-delay="300">
                            <h4 class="vc-widget-title">Popular Tags</h4>
                            <div class="vc-tags-cloud">
                                <?php if ($categories && count($categories) > 0):
                                    foreach ($categories as $tc): ?>
                                <a href="blog.php?category=<?= urlencode($tc->post) ?>"
                                   class="vc-tag<?= ($cat === $tc->post) ? ' active' : '' ?>">
                                    <?= htmlspecialchars($tc->post) ?>
                                </a>
                                <?php
                                    endforeach;
                                else: ?>
                                <span class="vc-tag">Real Estate</span>
                                <span class="vc-tag">Kenya</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- /.vc-blog-sidebar -->

        </div><!-- /.vc-container -->
    </section>

    <!-- Footer -->
    <?php include 'layout/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 50,
            easing: 'ease-out-cubic'
        });
    </script>

    <style>
        /* ── Filter notice ────────────────────────────── */
        .vc-filter-notice {
            background: rgba(212,168,95,0.1);
            border: 1px solid rgba(212,168,95,0.3);
            border-radius: 10px;
            padding: 14px 20px;
            margin-bottom: 30px;
            font-size: 14px;
            color: #546e7a;
        }
        .vc-filter-notice strong { color: #1e2a2e; }
        .vc-filter-clear {
            color: #d4a85f;
            text-decoration: none;
            font-weight: 600;
        }
        .vc-filter-clear:hover { text-decoration: underline; }

        /* ── No posts state ───────────────────────────── */
        .vc-no-posts {
            grid-column: 1 / -1;
            text-align: center;
            padding: 80px 20px;
            color: #546e7a;
        }
        .vc-no-posts i {
            font-size: 60px;
            color: #d4a85f;
            display: block;
            margin-bottom: 20px;
        }
        .vc-no-posts h3 { color: #1e2a2e; margin-bottom: 10px; }
        .vc-no-posts a  { color: #d4a85f; font-weight: 600; }

        /* ── Disabled pagination item ─────────────────── */
        .vc-page-link.disabled {
            pointer-events: none;
            opacity: 0.4;
            cursor: default;
        }

        /* ── Active category in sidebar ───────────────── */
        .vc-categories-list li.active > a,
        .vc-tag.active {
            color: #d4a85f !important;
            font-weight: 700;
        }
    </style>

</body>
</html>