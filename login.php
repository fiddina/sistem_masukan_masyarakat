<?php
require_once 'config/database.php';
require_once 'config/auth.php';

if (is_logged_in()) {
    header('Location: dashboard.php'); exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        header('Location: dashboard.php'); exit;
    }
    $error = 'Username atau password salah.';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Masukan DPRD Kota Medan</title>
    <style>
    /* ============ RESET & BASE ============ */
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #1a4d8f 0%, #0f3768 50%, #2c6bb8 100%);
        color: #343a40;
        line-height: 1.6;
        font-size: 14px;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        position: relative;
        overflow: hidden;
    }

    /* Dekorasi background bulat */
    body::before {
        content: '';
        position: absolute;
        width: 500px;
        height: 500px;
        background: rgba(245, 166, 35, 0.15);
        border-radius: 50%;
        top: -200px;
        right: -200px;
    }

    body::after {
        content: '';
        position: absolute;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        bottom: -150px;
        left: -150px;
    }

    /* ============ LOGIN BOX ============ */
    .login-box {
        background: #ffffff;
        padding: 45px 40px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        width: 100%;
        max-width: 420px;
        position: relative;
        z-index: 1;
        animation: slideUp 0.5s ease;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Logo bulat */
    .login-logo {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #1a4d8f 0%, #f5a623 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        letter-spacing: 1px;
    }

    .login-box h2 {
        text-align: center;
        color: #1a4d8f;
        margin-bottom: 8px;
        font-size: 22px;
        font-weight: 700;
    }

    .login-box .subtitle {
        text-align: center;
        color: #6c757d;
        font-size: 12px;
        margin-bottom: 30px;
        line-height: 1.5;
    }

    /* Form group */
    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        font-size: 13px;
        color: #343a40;
    }

    .form-group input {
        width: 100%;
        padding: 12px 15px;
        border: 1.5px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s;
        background: #ffffff;
    }

    .form-group input:focus {
        outline: none;
        border-color: #1a4d8f;
        box-shadow: 0 0 0 3px rgba(26, 77, 143, 0.1);
    }

    .form-group input::placeholder {
        color: #adb5bd;
    }

    /* Tombol login */
    .btn-login {
        width: 100%;
        padding: 13px;
        background: linear-gradient(135deg, #1a4d8f 0%, #2c6bb8 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 10px;
        font-family: inherit;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        background: linear-gradient(135deg, #0f3768 0%, #1a4d8f 100%);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    /* Alert */
    .alert {
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-size: 13px;
        border-left: 4px solid;
        animation: shake 0.4s ease;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border-color: #dc3545;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-color: #28a745;
    }

    .alert-warning {
        background: #fff3cd;
        color: #856404;
        border-color: #ffc107;
    }

    .alert-info {
        background: #d1ecf1;
        color: #0c5460;
        border-color: #17a2b8;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    /* Footer */
    .login-footer {
        text-align: center;
        margin-top: 25px;
        font-size: 11px;
        color: #6c757d;
        line-height: 1.6;
    }

    .login-footer .instansi {
        font-weight: 600;
        color: #1a4d8f;
    }

    /* Info akun demo */
    .demo-info {
        margin-top: 18px;
        padding: 12px 15px;
        background: #f8f9fa;
        border-radius: 8px;
        font-size: 11.5px;
        color: #6c757d;
        text-align: center;
        border: 1px dashed #dee2e6;
    }

    .demo-info b {
        color: #1a4d8f;
    }

    /* Responsive */
    @media (max-width: 480px) {
        .login-box {
            padding: 35px 25px;
        }
        .login-logo {
            width: 65px;
            height: 65px;
            font-size: 20px;
        }
        .login-box h2 {
            font-size: 19px;
        }
    }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login Sistem</h2>
    <p class="subtitle">
        Sistem Informasi Pengelolaan Data Masukan Masyarakat<br>
        Komisi IV Sekretariat DPRD Kota Medan
    </p>
<form method="POST" action="" autocomplete="off">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username" required autofocus>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required>
        </div>

        <button type="submit" name="login" class="btn-login"> Masuk ke Sistem</button>
    </form>

</div></body></html>
