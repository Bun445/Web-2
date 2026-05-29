<?php
require_once __DIR__ . '/../templates/header.php';

if ($isLoggedIn) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $fullName = trim($_POST['full_name'] ?? '');
    
    if (empty($username) || empty($email) || empty($password) || empty($fullName)) {
        $error = 'Please fill in all required fields.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        require_once __DIR__ . '/../../backend/models/User.php';
        $userModel = new User();
        
        if ($userModel->findByUsername($username)) {
            $error = 'Username already exists.';
        } elseif ($userModel->findByEmail($email)) {
            $error = 'Email is already in use.';
        } else {
            $userId = $userModel->create([
                'username' => $username,
                'email' => $email,
                'password' => $userModel->hashPassword($password),
                'full_name' => $fullName,
                'phone' => '',
                'address' => '',
                'role' => 'user'
            ]);
            
            if ($userId) {
                $success = 'Registration successful! Please log in.';
            } else {
                $error = 'Đăng ký thất bại. Vui lòng thử lại.';
            }
        }
    }
}
?>

<section class="auth-page">
    <div class="auth-card">
        <div class="auth-icon">
            <i class="fas fa-user-plus"></i>
        </div>
        
        <div class="auth-header">
            <h2>Create an Account</h2>
            <p>Join BookRent today</p>
        </div>
        
        <?php if ($error): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo htmlspecialchars($success); ?>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <a href="login.php" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Đăng Nhập Ngay
            </a>
        </div>
        <?php else: ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="full_name" class="form-control" placeholder="John Doe" required>
            </div>
            
            <div class="form-group">
                <label>Username *</label>
                <input type="text" name="username" class="form-control" placeholder="johndoe" required>
            </div>
            
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" class="form-control" placeholder="nguyenvana@email.com" required>
            </div>
            
            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" class="form-control" placeholder="At least 6 characters" required>
            </div>
            
            <div class="form-group">
                <label>Confirm Password *</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Re-enter password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                <i class="fas fa-user-plus"></i> Register
            </button>
        </form>
        
        <?php endif; ?>
        
        <div class="auth-footer">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
