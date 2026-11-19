<?php

require_once __DIR__ . '/config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: text/html; charset=utf-8');

// Show success or error message
function showMessage(string $message, bool $success): void
{
    $color = $success ? '#16803c' : '#dc2626';

    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Enrollment Status - The IoT Academy</title>
        <style>
            body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; background: #f8fafc; color: #1e293b; padding: 40px 16px; margin: 0; display: flex; align-items: center; justify-content: center; min-height: 100vh; box-sizing: border-box; }
            .status-card { max-width: 480px; width: 100%; background: #ffffff; border-radius: 14px; padding: 32px 24px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08); text-align: center; border: 1px solid #e2e8f0; }
            .status-icon { width: 56px; height: 56px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px; font-size: 26px; }
            .status-icon.error { background: #fee2e2; color: #dc2626; }
            .status-icon.success { background: #dcfce7; color: #16803c; }
            h2 { margin: 0 0 10px; font-size: 22px; font-weight: 700; color: ' . $color . '; }
            p { margin: 0 0 24px; color: #475569; font-size: 15px; line-height: 1.5; }
            .btn-back { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: #113563; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; transition: background 0.2s; }
            .btn-back:hover { background: #0c2747; }
        </style>
    </head>
    <body>
        <div class="status-card">
            <div class="status-icon ' . ($success ? 'success' : 'error') . '">' . ($success ? '✓' : '!') . '</div>
            <h2>' . htmlspecialchars($success ? 'Success!' : 'Submission Notice', ENT_QUOTES, 'UTF-8') . '</h2>
            <p>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>
            <a href="javascript:history.back()" class="btn-back">← Go Back & Edit Details</a>
        </div>
    </body>
    </html>';

    exit;
}

// Check request method - If visited directly via GET in the browser, automatically redirect to the enrollment form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $httpHost = $_SERVER['HTTP_HOST'] ?? '';
    $isLocal = in_array($httpHost, ['localhost', '127.0.0.1'])
               || (strpos($httpHost, 'localhost:') === 0);
    $formUrl = $isLocal ? '/theiotacademy/enroll-form' : '/enroll-form';
    header('Location: ' . $formUrl, true, 302);
    exit;
}

// Check cURL
if (!function_exists('curl_init')) {
    showMessage('PHP cURL extension is not enabled on this server.', false);
}

// All form fields
$fields = [
    'course_id',
    'course_name',
    'training_mode',
    'full_name',
    'email',
    'primary_country_code',
    'primary_phone',
    'alternate_country_code',
    'alternate_phone',
    'gender',
    'date_of_birth',
    'country',
    'state',
    'city',
    'highest_qualification',
    'area_of_study',
    'year_of_completion',
    'gpa_percentage',
    'years_of_experience',
    'current_company',
    'current_designation'
];

// Collect form data
$data = [];
foreach ($fields as $field) {
    $data[$field] = trim((string)($_POST[$field] ?? ''));
}
// DEBUG TRAINING MODE
error_log(
    'POST Training Mode: ' .
    ($_POST['training_mode'] ?? 'NOT RECEIVED')
);

error_log(
    'PHP Training Mode: ' .
    ($data['training_mode'] ?? 'NOT SET')
);

// Clean invisibles from course_name if any
$data['course_name'] = trim(preg_replace('/[\x{200B}-\x{200D}\x{2060}\x{FEFF}]/u', '', $data['course_name']));

// Required fields
$requiredFields = [
    'course_name',
    'training_mode',
    'full_name',
    'email',
    'primary_phone',
    'gender',
    'date_of_birth',
    'country',
    'state',
    'city',
    'highest_qualification',
    'area_of_study',
    'years_of_experience'
];



// Validate Training Mode

// =========================================
// VALIDATE TRAINING MODE
// =========================================

$trainingMode = trim(
    (string)($_POST['training_mode'] ?? '')
);

// Normalize mode
$trainingMode = ucfirst(
    strtolower($trainingMode)
);

// Validate
if (!in_array(
    $trainingMode,
    ['Online', 'Offline'],
    true
)) {
    showMessage(
        'Please select a valid training mode.',
        false
    );
}

// Save normalized value
$data['training_mode'] = $trainingMode;

// Debug
error_log(
    'FINAL TRAINING MODE: ' . $data['training_mode']
);

/* Convert training mode to display name */


$trainingMode = trim(
    $data['training_mode'] ?? ''
);

if (!in_array(
    $trainingMode,
    ['Online', 'Offline'],
    true
)) {
    showMessage(
        'Please select a valid training mode.',
        false
    );
}


$data['training_mode'] = $trainingMode;

// Validate required fields
foreach ($requiredFields as $field) {
    if ($data[$field] === '') {
        showMessage('Please fill in all mandatory fields (missing: ' . htmlspecialchars(str_replace('_', ' ', $field)) . ').', false);
    }
}

// Validate email
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    showMessage('Please enter a valid email address.', false);
}

// Sanitize phone numbers to digits only
$cleanPrimaryPhone = preg_replace('/[^0-9]/', '', $data['primary_phone']);
$cleanAltPhone = preg_replace('/[^0-9]/', '', $data['alternate_phone']);

// Primary mobile must be valid digits
if (strlen($cleanPrimaryPhone) < 7 || strlen($cleanPrimaryPhone) > 15) {
    showMessage('Primary mobile number must be between 7 and 15 digits.', false);
}

// Alternate mobile validation if entered
if ($data['alternate_phone'] !== '' && (strlen($cleanAltPhone) < 7 || strlen($cleanAltPhone) > 15)) {
    showMessage('Alternate mobile number must be between 7 and 15 digits.', false);
}

// Validate gender
if (!in_array($data['gender'], ['Male', 'Female', 'Other'], true)) {
    showMessage('Please select a valid gender option.', false);
}

// Validate DOB (expected format: YYYY-MM-DD)
$dob = DateTime::createFromFormat('!Y-m-d', $data['date_of_birth']);
if (!$dob || $dob->format('Y-m-d') !== $data['date_of_birth']) {
    showMessage('Please enter a valid date of birth (YYYY-MM-DD).', false);
}

// Generate unique Reference ID
$referenceId = 'ENR-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(4)));

// Add Reference ID and cleaned phones to submitted form data
$data['reference_id'] = $referenceId;
$data['primary_phone'] = $cleanPrimaryPhone;
if ($cleanAltPhone !== '') {
    $data['alternate_phone'] = $cleanAltPhone;
}

// ============================================================================
// STEP 1: Send data to Google Apps Script (Webhook for Google Sheets)
// ============================================================================
$payload = [
    'secret' => SHARED_SECRET,
    'notificationEmail' => NOTIFICATION_EMAIL,
    'createdAt' => date(DATE_ATOM),
    'data' => $data
];

$jsonPayload = json_encode($payload, JSON_UNESCAPED_UNICODE);

if ($jsonPayload !== false) {
    $ch = curl_init(GOOGLE_APPS_SCRIPT_URL);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $jsonPayload,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json'
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_TIMEOUT => 40,
        CURLOPT_SSL_VERIFYPEER => false // Helpful for local XAMPP environments
    ]);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false) {
        error_log('Google Apps Script cURL error: ' . $error);
    } else {
        $result = json_decode($response, true);
        if ($httpCode < 200 || $httpCode >= 300 || !is_array($result) || empty($result['success'])) {
            error_log('Google Apps Script response note: ' . $response);
        }
    }
}

// ============================================================================
// STEP 2: Save data into MySQL database
// ============================================================================
try {
    // 1. Ensure course_enrollments table exists (matches CodeIgniter application)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `course_enrollments` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `enrollment_ref` VARCHAR(50) NOT NULL UNIQUE,
          `course_id` INT(11) NOT NULL,
          `course_title` VARCHAR(255) NOT NULL,
          `training_mode` VARCHAR(100) NOT NULL,
          `full_name` VARCHAR(150) NOT NULL,
          `email` VARCHAR(191) NOT NULL,
          `primary_country_code` VARCHAR(10) NOT NULL DEFAULT '+91',
          `primary_phone` VARCHAR(30) NOT NULL,
          `alternate_country_code` VARCHAR(10) DEFAULT '+91',
          `alternate_phone` VARCHAR(30) DEFAULT NULL,
          `gender` ENUM('Male','Female','Other') NOT NULL,
          `date_of_birth` DATE NOT NULL,
          `country` VARCHAR(100) NOT NULL,
          `state` VARCHAR(100) NOT NULL,
          `city` VARCHAR(100) NOT NULL,
          `highest_qualification` VARCHAR(100) NOT NULL,
          `area_of_study` VARCHAR(100) NOT NULL,
          `year_of_completion` INT(4) DEFAULT NULL,
          `gpa_percentage` VARCHAR(50) DEFAULT NULL,
          `years_of_experience` DECIMAL(4,1) NOT NULL DEFAULT 0.0,
          `current_company` VARCHAR(150) DEFAULT NULL,
          `current_designation` VARCHAR(150) DEFAULT NULL,
          `enrollment_status` VARCHAR(50) NOT NULL DEFAULT 'Pending',
          `ip_address` VARCHAR(45) NOT NULL,
          `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `idx_course_id` (`course_id`),
          KEY `idx_email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    $sqlCourse = "INSERT INTO course_enrollments (
        enrollment_ref,
        course_id,
        course_title,
        training_mode,
        full_name,
        email,
        primary_country_code,
        primary_phone,
        alternate_country_code,
        alternate_phone,
        gender,
        date_of_birth,
        country,
        state,
        city,
        highest_qualification,
        area_of_study,
        year_of_completion,
        gpa_percentage,
        years_of_experience,
        current_company,
        current_designation,
        enrollment_status,
        ip_address
    ) VALUES (
        :ref,
        :course_id,
        :course_title,
        :training_mode,
        :full_name,
        :email,
        :primary_country_code,
        :primary_phone,
        :alternate_country_code,
        :alternate_phone,
        :gender,
        :date_of_birth,
        :country,
        :state,
        :city,
        :highest_qualification,
        :area_of_study,
        :year_of_completion,
        :gpa_percentage,
        :years_of_experience,
        :current_company,
        :current_designation,
        'Pending',
        :ip_address
    )";

    $stmtCourse = $pdo->prepare($sqlCourse);
    $stmtCourse->execute([
        ':ref'                    => $referenceId,
        ':course_id'               => (int)($data['course_id'] ?: 0),
        ':course_title'            => $data['course_name'],
       ':training_mode'           => $data['training_mode'],
        ':full_name'               => $data['full_name'],
        ':email'                   => $data['email'],
        ':primary_country_code'    => $data['primary_country_code'] ?: '+91',
        ':primary_phone'           => $data['primary_phone'],
        ':alternate_country_code'  => $data['alternate_country_code'] ?: '+91',
        ':alternate_phone'         => $data['alternate_phone'] ?: null,
        ':gender'                  => $data['gender'],
        ':date_of_birth'           => $data['date_of_birth'],
        ':country'                 => $data['country'],
        ':state'                   => $data['state'],
        ':city'                    => $data['city'],
        ':highest_qualification'   => $data['highest_qualification'],
        ':area_of_study'           => $data['area_of_study'],
        ':year_of_completion'      => $data['year_of_completion'] ? (int)$data['year_of_completion'] : null,
        ':gpa_percentage'          => $data['gpa_percentage'] ?: null,
        ':years_of_experience'      => (float)($data['years_of_experience'] ?: 0),
        ':current_company'         => $data['current_company'] ?: null,
        ':current_designation'     => $data['current_designation'] ?: null,
        ':ip_address'              => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
    ]);

    // Optional legacy compatibility: also write to enrollments if present
    try {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `enrollments` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `course_name` VARCHAR(255) NOT NULL,
              `training_mode` VARCHAR(100) NOT NULL DEFAULT '',
              `full_name` VARCHAR(255) NOT NULL,
              `email` VARCHAR(255) NOT NULL,
              `primary_phone` VARCHAR(20) NOT NULL,
              `alternate_phone` VARCHAR(20) DEFAULT NULL,
              `gender` VARCHAR(20) NOT NULL,
              `date_of_birth` DATE NOT NULL,
              `country` VARCHAR(100) NOT NULL,
              `state` VARCHAR(100) NOT NULL,
              `city` VARCHAR(100) NOT NULL,
              `highest_qualification` VARCHAR(100) NOT NULL,
              `area_of_study` VARCHAR(255) NOT NULL,
              `year_of_completion` VARCHAR(10) DEFAULT NULL,
              `gpa_percentage` VARCHAR(50) DEFAULT NULL,
              `years_of_experience` VARCHAR(50) NOT NULL,
              `current_company` VARCHAR(255) DEFAULT NULL,
              `current_designation` VARCHAR(255) DEFAULT NULL,
              `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        $sqlLegacy = "INSERT INTO enrollments (
            course_name,    training_mode,full_name, email, primary_phone, alternate_phone,
            gender, date_of_birth, country, state, city,
            highest_qualification, area_of_study, year_of_completion,
            gpa_percentage, years_of_experience, current_company, current_designation
        ) VALUES (
            :course_name, :training_mode, :full_name, :email, :primary_phone, :alternate_phone,
            :gender, :date_of_birth, :country, :state, :city,
            :highest_qualification, :area_of_study, :year_of_completion,
            :gpa_percentage, :years_of_experience, :current_company, :current_designation
        )";
        $stmtLegacy = $pdo->prepare($sqlLegacy);
        $stmtLegacy->execute([
            ':course_name'            => $data['course_name'],
            ':training_mode' => $data['training_mode'],
            ':full_name'              => $data['full_name'],
            ':email'                  => $data['email'],
            ':primary_phone'          => $data['primary_phone'],
            ':alternate_phone'        => $data['alternate_phone'] ?? null,
            ':gender'                 => $data['gender'],
            ':date_of_birth'          => $data['date_of_birth'],
            ':country'                => $data['country'],
            ':state'                  => $data['state'],
            ':city'                   => $data['city'],
            ':highest_qualification'  => $data['highest_qualification'],
            ':area_of_study'          => $data['area_of_study'],
            ':year_of_completion'     => $data['year_of_completion'],
            ':gpa_percentage'         => $data['gpa_percentage'],
            ':years_of_experience'     => $data['years_of_experience'],
            ':current_company'        => $data['current_company'],
            ':current_designation'    => $data['current_designation']
        ]);
    } catch (Exception $lex) {
        error_log('Legacy enrollments table insert note: ' . $lex->getMessage());
    }

} catch (PDOException $e) {
    error_log('MySQL enrollment error: ' . $e->getMessage());
    showMessage('Database saving issue: ' . $e->getMessage(), false);
}

// ============================================================================
// STEP 3: Store submission in Session for receipt display
// ============================================================================
$enrollmentRecord = [
    'ref'           => $referenceId,
    'reference_id'  => $referenceId,
    'course_title'  => $data['course_name'],
    'course_name'   => $data['course_name'],
    'training_mode' => $data['training_mode'],
    'full_name'     => $data['full_name'],
    'email'         => $data['email'],
    'primary_phone' => trim(($data['primary_country_code'] ?: '+91') . ' ' . $data['primary_phone']),
    'gender'        => $data['gender'],
    'date_of_birth' => $data['date_of_birth'],
    'country'       => $data['country'],
    'state'         => $data['state'],
    'city'          => $data['city'],
    'highest_qualification' => $data['highest_qualification'],
    'area_of_study' => $data['area_of_study'],
    'years_of_experience' => $data['years_of_experience'],
    'created_at'    => date('F j, Y, g:i a')
];

$_SESSION['latest_enrollment'] = $enrollmentRecord;
$_SESSION['enrollment_success'] = $enrollmentRecord;

// ============================================================================
// STEP 4: Redirect to the official success receipt page
// ============================================================================
$httpHost = $_SERVER['HTTP_HOST'] ?? '';
$isLocal = in_array($httpHost, ['localhost', '127.0.0.1'])
           || (strpos($httpHost, 'localhost:') === 0);

$queryParam = '?ref=' . urlencode($referenceId) . '&course=' . urlencode($data['course_name']);

if ($isLocal) {
    $redirectUrl = '/theiotacademy/success' . $queryParam;
} else {
    $redirectUrl = '/success' . $queryParam;
}

header('Location: ' . $redirectUrl);
exit;
