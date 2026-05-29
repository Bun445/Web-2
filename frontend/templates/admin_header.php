<?php
ob_start();
require_once __DIR__ . '/../../config/config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../backend/config/database.php';
require_once __DIR__ . '/../../frontend/functions/helpers.php';
require_once __DIR__ . '/../../backend/controllers/AuthController.php';
require_once __DIR__ . '/../../backend/controllers/MessageController.php';

$auth = new AuthController();
$isLoggedIn = $auth->isLoggedIn();
$isAdmin = $auth->isAdmin();
$user = $auth->getCurrentUser();

// Remember Me: Auto-login from cookie if no session
if (!$isLoggedIn && isset($_COOKIE['remember_token'])) {
    require_once __DIR__ . '/../../backend/models/User.php';
    $userModel = new User();
    $rememberUser = $userModel->findByRememberToken($_COOKIE['remember_token']);
    
    if ($rememberUser) {
        $_SESSION['user_id'] = $rememberUser['id'];
        $_SESSION['username'] = $rememberUser['username'];
        $_SESSION['role'] = $rememberUser['role'];
        $_SESSION['full_name'] = $rememberUser['full_name'];
        
        $isLoggedIn = true;
        $isAdmin = ($rememberUser['role'] === 'admin');
        $user = $rememberUser;
    }
}

if (!$isLoggedIn || !$isAdmin) {
    if (!$isLoggedIn) {
        header('Location: ../login.php');
    } else {
        header('Location: ../index.php');
    }
    exit;
}

// Get message notifications for admin
$messageController = new MessageController();
$messageData = $messageController->getNotificationsForAdmin(5);
$unreadMessagesList = $messageData['messages'];
$unreadCount = $messageData['unread_count'];

// Get overdue rentals count for notifications
require_once __DIR__ . '/../../backend/controllers/RentalController.php';
$rentalController = new RentalController();
$overdueRentals = $rentalController->getOverdue();
$overdueCount = count($overdueRentals);

$currentPage = basename($_SERVER['PHP_SELF']);
$currentDir = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : ''; ?>Admin - BookRent</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="index.php" class="sidebar-logo">
                <div class="sidebar-logo-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <span class="sidebar-logo-text">MÂY MƠ <span>BOOK</span></span>
            </a>
        </div>
        
        <nav class="sidebar-nav">
            <div class="sidebar-nav-section">
                <span class="sidebar-nav-label">Main Management</span>
                <a href="index.php" class="sidebar-nav-item <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
                <a href="books.php" class="sidebar-nav-item <?php echo $currentPage == 'books.php' ? 'active' : ''; ?>">
                    <i class="fas fa-book"></i>
                    <span>Book Management</span>
                </a>
                <a href="rentals.php" class="sidebar-nav-item <?php echo $currentPage == 'rentals.php' ? 'active' : ''; ?>">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Rental Management</span>
                </a>
                <a href="users.php" class="sidebar-nav-item <?php echo $currentPage == 'users.php' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    <span>Customer Management</span>
                </a>
                <a href="messages.php" class="sidebar-nav-item <?php echo $currentPage == 'messages.php' ? 'active' : ''; ?>">
                    <i class="fas fa-envelope"></i>
                    <span>Messages</span>
                    <?php if ($unreadCount > 0): ?>
                    <span class="sidebar-badge"><?php echo $unreadCount; ?></span>
                    <?php endif; ?>
                </a>
            </div>
            
            <div class="sidebar-nav-section">
                <span class="sidebar-nav-label">Settings</span>
                <a href="settings.php" class="sidebar-nav-item <?php echo $currentPage == 'settings.php' ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
                <a href="../logout.php" class="sidebar-nav-item" onclick="return confirm('Are you sure you want to log out?')">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Đăng xuất</span>
                </a>
            </div>
        </nav>
        
        <div class="sidebar-footer">
            <div class="sidebar-admin-card">
                <div class="sidebar-admin-avatar">
                    <?php echo strtoupper(substr($user['full_name'] ?? 'A', 0, 1)); ?>
                </div>
                <div class="sidebar-admin-info">
                    <div class="sidebar-admin-name"><?php echo htmlspecialchars($user['full_name'] ?? 'Admin'); ?></div>
                    <div class="sidebar-admin-role">Administrator</div>
                </div>
            </div>
        </div>
    </aside>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="topbar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="topbar-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Tìm kiếm...">
                </div>
            </div>
            
            <div class="topbar-right">
                <!-- Notifications Dropdown -->
                <div class="topbar-dropdown">
                    <button class="topbar-icon-btn" id="notificationBtn">
                        <i class="fas fa-bell"></i>
                        <?php if ($overdueCount > 0): ?>
                        <span class="topbar-badge"><?php echo $overdueCount; ?></span>
                        <?php endif; ?>
                    </button>
                    <div class="topbar-dropdown-menu" id="notificationMenu">
                        <div class="dropdown-header">
                            <span>Notifications</span>
                            <a href="#" onclick="markAllNotificationsRead(); return false;">Mark all as read</a>
                        </div>
                        <div class="dropdown-list">
                            <?php if ($overdueCount > 0): ?>
                            <a href="rentals.php?status=overdue" class="dropdown-item-notif unread">
                                <div class="notif-icon warning"><i class="fas fa-exclamation-circle"></i></div>
                                <div class="notif-content">
                                    <div class="notif-text">You have <?php echo $overdueCount; ?> overdue rentals</div>
                                    <div class="notif-time">Requires attention</div>
                                </div>
                            </a>
                            <?php endif; ?>
                            <?php if ($unreadCount > 0): ?>
                            <a href="messages.php" class="dropdown-item-notif unread">
                                <div class="notif-icon info"><i class="fas fa-envelope"></i></div>
                                <div class="notif-content">
                                    <div class="notif-text">Có <?php echo $unreadCount; ?> tin nhắn mới từ người dùng</div>
                                    <div class="notif-time">Unread</div>
                                </div>
                            </a>
                            <?php endif; ?>
                            <?php if ($overdueCount == 0 && $unreadCount == 0): ?>
                            <div class="dropdown-empty">
                                <i class="fas fa-check-circle"></i>
                                <span>No new notifications</span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="dropdown-footer">
                            <a href="rentals.php?status=overdue">View overdue rentals</a>
                        </div>
                    </div>
                </div>
                
                <!-- Messages Dropdown -->
                <div class="topbar-dropdown">
                    <button class="topbar-icon-btn" id="messageBtn">
                        <i class="fas fa-envelope"></i>
                        <?php if ($unreadCount > 0): ?>
                        <span class="topbar-badge"><?php echo $unreadCount; ?></span>
                        <?php endif; ?>
                    </button>
                    <div class="topbar-dropdown-menu" id="messageMenu">
                        <div class="dropdown-header">
                            <span>Messages</span>
                            <a href="messages.php">View all</a>
                        </div>
                        <div class="dropdown-list">
                            <?php if (count($unreadMessagesList) > 0): ?>
                                <?php foreach (array_slice($unreadMessagesList, 0, 5) as $msg): ?>
                                <a href="messages.php?view=<?php echo $msg['id']; ?>" class="dropdown-item-message <?php echo $msg['is_read'] ? '' : 'unread'; ?>">
                                    <div class="message-avatar">
                                        <?php if ($msg['type'] === 'system'): ?>
                                        <i class="fas fa-cog"></i>
                                        <?php else: ?>
                                        <?php echo strtoupper(substr($msg['sender_name'] ?? 'A', 0, 1)); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="message-content">
                                        <div class="message-sender">
                                            <?php 
                                            if ($msg['type'] === 'system') echo 'System';
                                            else echo htmlspecialchars($msg['sender_name'] ?? 'Unknown');
                                            ?>
                                        </div>
                                        <div class="message-text"><?php echo htmlspecialchars($msg['subject'] ?: mb_substr($msg['content'], 0, 30) . '...'); ?></div>
                                        <div class="message-time"><?php echo timeAgo($msg['created_at']); ?></div>
                                    </div>
                                </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="dropdown-empty">
                                    <i class="fas fa-inbox"></i>
                                    <span>No messages yet</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="dropdown-footer">
                            <a href="messages.php">Go to inbox</a>
                        </div>
                    </div>
                </div>
                
                <div class="topbar-divider"></div>
                <div class="topbar-profile">
                    <div class="topbar-avatar">
                        <?php echo strtoupper(substr($user['full_name'] ?? 'A', 0, 1)); ?>
                    </div>
                    <div class="topbar-profile-info">
                        <span class="topbar-profile-name"><?php echo htmlspecialchars($user['full_name'] ?? 'Admin'); ?></span>
                        <span class="topbar-profile-role">Admin</span>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="page-content">
