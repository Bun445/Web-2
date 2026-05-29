<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../../backend/controllers/BookController.php';
require_once __DIR__ . '/../../backend/models/Review.php';

$bookController = new BookController();
$reviewModel = new Review();
$bookId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$book = $bookController->show($bookId);

if (!$book) {
    header('Location: books.php');
    exit;
}

$pageTitle = $book['title'];
$isAvailable = $book['quantity'] > 0;
$message = '';
$messageType = '';
$userCanReview = false;
$userReview = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!$isLoggedIn) {
        header('Location: login.php?redirect=book-detail.php?id=' . $bookId);
        exit;
    }
    
    if ($_POST['action'] === 'rent_now') {
        $rentalDays = intval($_POST['rental_days']);
        require_once __DIR__ . '/../../backend/controllers/RentalController.php';
        $rentalController = new RentalController();
        $result = $rentalController->create($_SESSION['user_id'], $bookId, $rentalDays);
        
        if ($result['success']) {
            $message = 'Book rented successfully! You can view it in your rentals.';
            $messageType = 'success';
        } else {
            $message = $result['message'];
            $messageType = 'danger';
        }
    } elseif ($_POST['action'] === 'add_to_cart') {
        require_once __DIR__ . '/../../backend/models/Cart.php';
        $cartModel = new Cart();
        $rentalDays = intval($_POST['rental_days']);
        
        if (isset($_SESSION['user_id'])) {
            $cartModel->addItem($bookId, 1, $rentalDays, $_SESSION['user_id']);
        } else {
            if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
            $_SESSION['cart'][] = ['book_id' => $bookId, 'rental_days' => $rentalDays, 'quantity' => 1];
        }
        
        $message = 'Added to cart!';
        $messageType = 'success';
    } elseif ($_POST['action'] === 'submit_review') {
        $userId = intval($_SESSION['user_id']);
        $rating = intval($_POST['rating'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');

        if (!$reviewModel->hasUserRentedBook($userId, $bookId)) {
            $message = 'You need to rent this book before leaving a review.';
            $messageType = 'danger';
        } elseif ($rating < 1 || $rating > 5) {
            $message = 'Please choose a valid star rating (1-5).';
            $messageType = 'danger';
        } elseif ($comment === '') {
            $message = 'Please enter a review comment.';
            $messageType = 'danger';
        } else {
            $saved = $reviewModel->saveUserReview($userId, $bookId, $rating, $comment);
            if ($saved) {
                $message = 'Review submitted successfully.';
                $messageType = 'success';
            } else {
                $message = 'Unable to submit review. Please try again.';
                $messageType = 'danger';
            }
        }
    }
}

$defaultDays = 7;
$totalPrice = $book['price_per_day'] * $defaultDays;
$reviewSummary = $reviewModel->getBookReviewSummary($bookId);
$bookReviews = $reviewModel->getBookReviews($bookId, 20);
$rating = $reviewSummary['avg_rating'] ?? 0;
$reviewCount = $reviewSummary['review_count'] ?? 0;

if ($isLoggedIn) {
    $userId = intval($_SESSION['user_id']);
    $userCanReview = $reviewModel->hasUserRentedBook($userId, $bookId);
    $userReview = $reviewModel->getUserReview($userId, $bookId);
}

// Related books
$relatedBooks = [];
$allBooks = $bookController->category($book['category']);
foreach ($allBooks as $rb) {
    if ($rb['id'] != $bookId && count($relatedBooks) < 4) {
        $relatedBooks[] = $rb;
    }
}

function getBookCover($title) {
    return "https://covers.openlibrary.org/b/title/" . urlencode($title) . "-M.jpg";
}

// New function using local covers
function getBookCoverLocal($book) {
    return getBookCoverImage($book);
}
?>

<!-- Breadcrumb -->
<section class="breadcrumb-section">
    <div class="container">
        <nav class="breadcrumb-nav">
            <a href="index.php">Trang Chủ</a>
            <i class="fas fa-chevron-right breadcrumb-sep"></i>
            <a href="books.php">Books</a>
            <i class="fas fa-chevron-right breadcrumb-sep"></i>
            <a href="books.php?category=<?php echo urlencode((string)($book['category'] ?? '')); ?>"><?php echo htmlspecialchars((string)($book['category'] ?? 'Books')); ?></a>
            <i class="fas fa-chevron-right breadcrumb-sep"></i>
            <span class="breadcrumb-current"><?php echo htmlspecialchars((string)($book['title'] ?? '')); ?></span>
        </nav>
    </div>
</section>

<?php if ($message): ?>
<div class="container" style="margin-top: 24px;">
    <div class="alert alert-<?php echo $messageType; ?>">
        <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
        <?php echo htmlspecialchars($message); ?>
    </div>
</div>
<?php endif; ?>

<!-- Book Detail -->
<section class="book-detail-section">
    <div class="container">
        <div class="book-detail-grid">
            <!-- Book Image -->
            <div class="book-detail-image-wrapper">
                 <?php if ($isAvailable): ?>
                 <span class="book-status available">In Stock</span>
                 <?php else: ?>
                 <span class="book-status unavailable">Out of Stock</span>
                 <?php endif; ?>
                
                 <img src="<?php echo getBookCoverLocal($book); ?>"
                     alt="<?php echo htmlspecialchars($book['title']); ?>"
                     class="book-detail-image">
            </div>
            
            <!-- Book Info -->
            <div class="book-detail-info">
                <span class="book-detail-category">
                    <i class="fas fa-folder"></i>
                    <?php echo htmlspecialchars((string)($book['category'] ?? 'Books')); ?>
                </span>
                
                <h1><?php echo htmlspecialchars((string)($book['title'] ?? '')); ?></h1>
                
                <p class="book-detail-author">
                    <i class="fas fa-user-edit"></i>
                    Author: <?php echo htmlspecialchars((string)($book['author'] ?? 'Author')); ?>
                </p>
                
                <!-- Rating -->
                <div class="book-detail-rating">
                    <div class="stars">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star<?php if($i > floor($rating)) echo '-half-alt'; ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <span class="rating-text"><?php echo $reviewCount > 0 ? number_format($rating, 1) : 'No reviews yet'; ?></span>
                    <span class="review-count">(<?php echo number_format($reviewCount); ?> reviews)</span>
                </div>
                
                <!-- Price -->
                <div class="book-detail-price">
                    <?php echo number_format($book['price_per_day'], 0); ?>đ<span> / ngày</span>
                </div>
                
                <!-- Description -->
                <div class="book-detail-desc">
                    <p><?php echo nl2br(htmlspecialchars($book['description'] ?: 'No description available for this book yet. Rent it now to discover what it offers.')); ?></p>
                </div>
                
                <!-- Meta Info -->
                <div class="book-detail-meta">
                    <div class="book-meta-item">
                        <i class="fas fa-box"></i>
                        <div>
                            <span class="meta-text">Số lượng</span>
                            <span class="meta-value"><?php echo $book['quantity']; ?> cuốn</span>
                        </div>
                    </div>
                    <div class="book-meta-item">
                        <i class="fas fa-layer-group"></i>
                        <div>
                            <span class="meta-text">Thể loại</span>
                            <span class="meta-value"><?php echo htmlspecialchars((string)($book['category'] ?? 'Books')); ?></span>
                        </div>
                    </div>
                    <div class="book-meta-item">
                        <i class="fas fa-pen-fancy"></i>
                        <div>
                            <span class="meta-text">Author</span>
                            <span class="meta-value"><?php echo htmlspecialchars((string)($book['author'] ?? 'Tác giả')); ?></span>
                        </div>
                    </div>
                    <div class="book-meta-item">
                        <i class="fas fa-calendar-check"></i>
                        <div>
                            <span class="meta-text">Rental period</span>
                            <span class="meta-value">7-30 ngày</span>
                        </div>
                    </div>
                </div>
                
                <?php if ($isAvailable): ?>
                <!-- Rental Form -->
                <div class="rental-form-box">
                    <form method="POST" id="rentalForm">
                        <input type="hidden" name="action" value="rent_now">
                        <input type="hidden" name="rental_days" id="rental_days" value="7">
                        
                        <label>
                            <i class="fas fa-calendar-alt"></i>
                            Rental Period
                        </label>
                        <select name="rental_days_select" onchange="updatePrice(this.value)">
                            <option value="7">7 days - <?php echo number_format($book['price_per_day'] * 7, 0); ?>đ</option>
                            <option value="14">14 days - <?php echo number_format($book['price_per_day'] * 14, 0); ?>đ</option>
                            <option value="30">30 days - <?php echo number_format($book['price_per_day'] * 30, 0); ?>đ</option>
                        </select>
                        
                        <div class="rental-total-row">
                            <span style="font-size: 1rem; color: var(--text-secondary);">
                                <strong>Total:</strong> (<?php echo number_format($book['price_per_day'], 0); ?>/day x <span id="daysDisplay">7</span> days)
                            </span>
                            <span class="rental-total-price" id="total-price"><?php echo number_format($totalPrice, 0); ?>đ</span>
                        </div>
                        
                        <div class="rental-actions">
                            <button type="submit" class="btn btn-primary btn-lg w-full">
                                <i class="fas fa-book"></i> Rent Now
                            </button>
                        </div>
                    </form>
                    
                    <form method="POST" class="mt-12">
                        <input type="hidden" name="action" value="add_to_cart">
                        <input type="hidden" name="rental_days" id="rental_days_cart" value="7">
                        <button type="submit" class="btn btn-outline btn-lg w-full">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    </form>
                </div>
                <?php else: ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    This book is currently out of stock. Please check back later or choose another title.
                </div>
                <?php endif; ?>
                
                <!-- Back Button -->
                <div style="margin-top: 24px;">
                    <a href="books.php" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Quay Lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Reviews -->
<section class="section section-alt">
    <div class="container container-narrow">
        <div class="section-header section-header--spaced">
            <div class="section-header-left">
                <div class="section-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <h2 class="section-title">Reviews & Comments</h2>
                    <p class="section-subtitle">See what readers are saying about this book</p>
                </div>
            </div>
        </div>

        <?php if ($isLoggedIn && $userCanReview): ?>
        <div class="rental-form-box" style="margin-bottom: 24px;">
            <h3 style="margin-bottom: 14px;">
                <?php echo $userReview ? 'Update your review' : 'Write your review'; ?>
            </h3>
            <form method="POST">
                <input type="hidden" name="action" value="submit_review">

                <div class="form-group">
                    <label for="rating">Số sao</label>
                    <select id="rating" name="rating" class="form-control" required>
                        <?php $selectedRating = intval($userReview['rating'] ?? 5); ?>
                        <option value="5" <?php echo $selectedRating === 5 ? 'selected' : ''; ?>>5 sao - Rất tốt</option>
                        <option value="4" <?php echo $selectedRating === 4 ? 'selected' : ''; ?>>4 sao - Tốt</option>
                        <option value="3" <?php echo $selectedRating === 3 ? 'selected' : ''; ?>>3 sao - Ổn</option>
                        <option value="2" <?php echo $selectedRating === 2 ? 'selected' : ''; ?>>2 sao - Chưa tốt</option>
                        <option value="1" <?php echo $selectedRating === 1 ? 'selected' : ''; ?>>1 star - Poor</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="comment">Comment</label>
                    <textarea id="comment" name="comment" rows="4" class="form-control" placeholder="Chia sẻ cảm nhận của bạn..." required><?php echo htmlspecialchars($userReview['comment'] ?? ''); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    <?php echo $userReview ? 'Update review' : 'Submit review'; ?>
                </button>
            </form>
        </div>
        <?php elseif ($isLoggedIn): ?>
        <div class="alert alert-danger" style="margin-bottom: 24px;">
            <i class="fas fa-info-circle"></i>
            You need to rent this book before you can leave a review and comment.
        </div>
        <?php else: ?>
        <div class="alert alert-danger" style="margin-bottom: 24px;">
            <i class="fas fa-sign-in-alt"></i>
            Please log in and rent the book to leave a review.
        </div>
        <?php endif; ?>

        <?php if (!empty($bookReviews)): ?>
        <div style="display: flex; flex-direction: column; gap: 14px;">
            <?php foreach ($bookReviews as $review): ?>
            <div style="background: white; border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 18px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; gap: 12px;">
                    <strong><?php echo htmlspecialchars($review['full_name'] ?: $review['username']); ?></strong>
                    <span style="font-size: 0.85rem; color: var(--text-muted);"><?php echo date('d/m/Y H:i', strtotime($review['created_at'])); ?></span>
                </div>
                <div class="book-rating" style="margin-bottom: 8px;">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star<?php echo $i <= intval($review['rating']) ? '' : '-half-alt'; ?>"></i>
                    <?php endfor; ?>
                    <span><?php echo intval($review['rating']); ?>/5</span>
                </div>
                <p style="margin: 0; color: var(--text-secondary); line-height: 1.7;"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state" style="padding: 36px 20px;">
            <div class="empty-state-icon" style="width: 72px; height: 72px; font-size: 1.8rem;">
                <i class="fas fa-comment-dots"></i>
            </div>
            <h3>No Reviews Yet</h3>
            <p>Be the first to share your experience with this book.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Related Books -->
<?php if (count($relatedBooks) > 0): ?>
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <div class="section-header-left">
                <div class="section-icon">
                    <i class="fas fa-books"></i>
                </div>
                <div>
                    <h2 class="section-title">Related Books</h2>
                    <p class="section-subtitle">More books in the <?php echo htmlspecialchars($book['category']); ?> genre</p>
                </div>
            </div>
            <a href="books.php?category=<?php echo urlencode($book['category']); ?>" class="btn btn-outline btn-sm">View More <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="books-scroll">
            <?php foreach ($relatedBooks as $related): ?>
            <div class="book-card">
                <?php if ($related['quantity'] > 0): ?>
                <span class="book-status available">In Stock</span>
                <?php else: ?>
                <span class="book-status unavailable">Out of Stock</span>
                <?php endif; ?>
                
                <div class="book-image">
                    <img src="<?php echo getBookCoverLocal($related); ?>"
                         alt="<?php echo htmlspecialchars($related['title']); ?>"
                         loading="lazy">
                </div>
                
                <div class="book-info">
                    <span class="book-category"><?php echo htmlspecialchars($related['category']); ?></span>
                    <h3 class="book-title"><?php echo htmlspecialchars($related['title']); ?></h3>
                    <p class="book-author"><?php echo htmlspecialchars($related['author']); ?></p>
                    
                    <div class="book-footer">
                        <div class="book-price">
                            <?php echo number_format($related['price_per_day'], 0); ?>đ<span>/ngày</span>
                        </div>
                        <?php if ($related['quantity'] > 0): ?>
                        <a href="book-detail.php?id=<?php echo $related['id']; ?>" class="btn btn-sm">Thuê</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
const pricePerDay = <?php echo $book['price_per_day']; ?>;

function updatePrice(days) {
    const total = pricePerDay * parseInt(days);
    document.getElementById('total-price').textContent = total.toLocaleString('vi-VN') + 'đ';
    document.getElementById('daysDisplay').textContent = days;
    document.getElementById('rental_days').value = days;
    document.getElementById('rental_days_cart').value = days;
}
</script>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
