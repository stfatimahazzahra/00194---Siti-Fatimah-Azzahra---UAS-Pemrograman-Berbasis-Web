<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';

if (isLoggedIn()) {
    redirect(isAdmin() ? 'admin/index.php' : 'dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $db = Database::connect();

    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        setFlash('error', 'Semua field wajib diisi.');
        redirect('register.php');
    }
    if ($password !== $confirm) {
        setFlash('error', 'Konfirmasi password tidak cocok.');
        redirect('register.php');
    }
    if (strlen($password) < 6) {
        setFlash('error', 'Password minimal 6 karakter.');
        redirect('register.php');
    }

    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        setFlash('error', 'Email sudah terdaftar. Silakan login.');
        redirect('register.php');
    }

    $hashed = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $db->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, 'customer')");
    $stmt->execute([$name, $email, $phone, $hashed]);

    setFlash('success', 'Registrasi berhasil! Silakan login.');
    redirect('login.php');
}

$pageTitle = 'Daftar Akun';
require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm border-0 p-4">
            <h4 class="fw-bold text-center mb-3"><i class="fa-solid fa-user-plus"></i> Daftar Akun</h4>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100 fw-bold">Daftar</button>
            </form>
            <p class="text-center mt-3 mb-0 small">Sudah punya akun? <a href="login.php">Login</a></p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
