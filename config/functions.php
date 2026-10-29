<?php
function redirect(string $url): never {
    header("Location: $url");
    exit;
}

function flash(string $key, ?string $message = null): ?string {
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }
    $msg = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $msg;
}

function upload_lampiran(array $file): ?string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
    if ($file['error'] !== UPLOAD_ERR_OK) return null;
    if ($file['size'] > 5 * 1024 * 1024) return null;

    $allowed = ['pdf','jpg','jpeg','png','doc','docx'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) return null;

    $name = date('YmdHis') . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
    $destination = __DIR__ . '/../uploads/' . $name;
    if (move_uploaded_file($file['tmp_name'], $destination)) return $name;
    return null;
}
?>