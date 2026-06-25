<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Instantly send a success headers back to the browser so the user isn't waiting
ignore_user_abort(true);
if (function_exists('fastcgi_finish_request')) {
    echo "✅ Telemetry queued.";
    fastcgi_finish_request(); // The browser disconnects HERE, making it instant for the user
}

// 2. Everything below this line executes in the background on the server
$host = getenv('DB_HOST') ?: 'postgres';
$name = getenv('DB_NAME') ?: 'postgres';
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

try {
    $db = new PDO("pgsql:host=$host;dbname=$name", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (\PDOException $e) {
    exit; // Fail silently in background
}

// Extract inbound POST payload values safely
$session_id  = $_POST['session_id']  ?? null;
$event_type  = $_POST['event_type']  ?? 'unknown';
$target_name = $_POST['target_name'] ?? 'unknown';
$referrer    = $_POST['referrer']    ?? null;
$screen_w    = isset($_POST['screen_w']) ? intval($_POST['screen_w']) : null;
$screen_h    = isset($_POST['screen_h']) ? intval($_POST['screen_h']) : null;

if (!$session_id) {
    die("❌ Context missing: No session ID provided.");
}

// Check if this specific session token has already been registered
$stmt = $db->prepare("SELECT 1 FROM web_sessions WHERE session_id = ?");
$stmt->execute([$session_id]);
$sessionExists = $stmt->fetch();

if (!$sessionExists) {
    // 1. Unpack server network environment information
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

    // Fast heuristic parser for the User Agent string
    $browser = 'Unknown';
    if (strpos($user_agent, 'Firefox') !== false) $browser = 'Firefox';
    elseif (strpos($user_agent, 'Chrome') !== false) $browser = 'Chrome';
    elseif (strpos($user_agent, 'Safari') !== false) $browser = 'Safari';

    $os = 'Unknown';
    if (strpos($user_agent, 'Windows') !== false) $os = 'Windows';
    elseif (strpos($user_agent, 'Macintosh') !== false) $os = 'MacOS';
    elseif (strpos($user_agent, 'Linux') !== false) $os = 'Linux';
    elseif (strpos($user_agent, 'iPhone') !== false) $os = 'iOS';

    $device = (strpos($user_agent, 'Mobile') !== false) ? 'Mobile' : 'Desktop';

    // 2. Lookup geographical telemetry (only once per unique session lifetime)
    $geo = json_decode(@file_get_contents("http://ip-api.com/json/{$ip}"), true);
    $country = $geo['country'] ?? 'Unknown';
    $city    = $geo['city'] ?? 'Unknown';
    $isp     = $geo['isp'] ?? 'Unknown';

    // 3. Write immutable master session metadata
    $insSession = $db->prepare("
        INSERT INTO web_sessions (session_id, ip_address, user_agent, device_type, os, browser, country, city, isp, referrer_url)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $insSession->execute([$session_id, $ip, $user_agent, $device, $os, $browser, $country, $city, $isp, $referrer]);
}

// 4. Log the atomic granular interaction event
$insEvent = $db->prepare("
    INSERT INTO traffic_events (session_id, event_type, target_name, screen_width, screen_height)
    VALUES (?, ?, ?, ?, ?)
");
$insEvent->execute([$session_id, $event_type, $target_name, $screen_w, $screen_h]);

echo "✅ Telemetry recorded.";