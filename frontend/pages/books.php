<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../../backend/controllers/BookController.php';

$bookController = new BookController();
$categories = $bookController->categories();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

if (!empty($search)) {
    $books = $bookController->search($search);
    $pageTitle = 'Search: ' . htmlspecialchars($search);
} elseif (!empty($category)) {
    $books = $bookController->category($category);
    $pageTitle = 'Books: ' . htmlspecialchars($category);
} else {
    $books = $bookController->index();
    $pageTitle = 'All Books';
}

function getBookCover($title) {
    return "https://covers.openlibrary.org/b/title/" . urlencode($title) . "-M.jpg";
}

// New function using local covers
function getBookCoverLocal($book) {
    return getBookCoverImage($book);
}
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="section-header section-header--no-margin">
            <div class="section-header-left">
                <div class="section-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <div>
                    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
                    <p class="page-subtitle"><?php echo count($books); ?> books found</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filter & Search -->
<section class="filter-section">
    <div class="container">
        <form action="books.php" method="GET" class="filter-bar">
            <input type="text" name="search" placeholder="Search for title or author..." 
                   value="<?php echo htmlspecialchars($search); ?>"
                   class="filter-input">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Search
            </button>
            <?php if (!empty($search) || !empty($category)): ?>
            <a href="books.php" class="btn btn-outline">
                <i class="fas fa-times"></i> Clear
            </a>
            <?php endif; ?>
        </form>
        
        <div class="categories-filter">
            <a href="books.php" class="category-pill <?php echo empty($category) ? 'active' : ''; ?>">
                <i class="fas fa-border-all"></i> Tất Cả
            </a>
            <?php foreach ($categories as $cat): ?>
            <a href="books.php?category=<?php echo urlencode($cat); ?>" 
               class="category-pill <?php echo $category === $cat ? 'active' : ''; ?>">
                <?php echo htmlspecialchars($cat); ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Books Grid -->
<section class="section">
    <div class="container">
        <?php if (count($books) > 0): ?>
        <div class="books-grid">
            <?php foreach ($books as $book): ?>
            <div class="book-card">
                <?php if ($book['quantity'] > 0): ?>
                <span class="book-discount available">
                    <i class="fas fa-check"></i> In stock
                </span>
                <?php else: ?>
                <span class="book-discount unavailable">
                    <i class="fas fa-times"></i> Out of stock
                </span>
                <?php endif; ?>
                
                <button class="book-wishlist" data-book-id="<?php echo $book['id']; ?>" title="Favorite">
                    <i class="far fa-heart"></i>
                </button>
                
                <div class="book-image">
                    <img src="<?php echo getBookCoverLocal($book); ?>"
                         alt="<?php echo htmlspecialchars($book['title']); ?>"
                         loading="lazy">
                </div>
                
                <div class="book-info">
                    <span class="book-category"><?php echo htmlspecialchars((string)($book['category'] ?? 'Books')); ?></span>
                    <h3 class="book-title"><?php echo htmlspecialchars((string)($book['title'] ?? '')); ?></h3>
                    <p class="book-author">
                        <i class="fas fa-user-edit"></i> <?php echo htmlspecialchars((string)($book['author'] ?? 'Author')); ?>
                    </p>
                    
                    <div class="book-footer">
                        <div class="book-price">
                            <span class="book-price-current"><?php echo number_format($book['price_per_day'], 0); ?>đ</span>
                            <span class="book-price-original">/ngày</span>
                        </div>
                        <?php if ($book['quantity'] > 0): ?>
                        <a href="book-detail.php?id=<?php echo $book['id']; ?>" class="btn btn-sm">Rent</a>
                        <?php else: ?>
                        <span class="text-muted small">Hết Hàng</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-search"></i>
            </div>
            <h3>No Books Found</h3>
            <p class="section-subtitle">Try adjusting the search terms or filters.</p>
            <a href="books.php" class="btn btn-primary">
                <i class="fas fa-book"></i> View All Books
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Related Categories -->
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <div class="section-header-left">
                <div class="section-icon">
                    <i class="fas fa-compass"></i>
                </div>
                <div>
                    <h2 class="section-title">Discover More</h2>
                    <p class="section-subtitle">Browse books by genre</p>
                </div>
            </div>
        </div>
        
        <div class="categories-scroll">
            <?php 
            $categoryIcons = [
                'Tiểu thuyết' => 'fa-book-open',
                'Self-help' => 'fa-heart',
                'Khoa học' => 'fa-atom',
                'Kỹ năng' => 'fa-lightbulb',
                'Truyện ngắn' => 'fa-pen-fancy',
                'Triết học' => 'fa-brain',
                'Phi hư cấu' => 'fa-hat-wizard',
            ];
            foreach ($categories as $category): 
            ?>
            <a href="books.php?category=<?php echo urlencode($category); ?>" class="category-card">
                <div class="category-icon">
                    <i class="fas <?php echo $categoryIcons[$category] ?? 'fa-book'; ?>"></i>
                </div>
                <h4><?php echo htmlspecialchars($category); ?></h4>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
