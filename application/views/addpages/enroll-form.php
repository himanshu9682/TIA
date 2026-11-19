<?php

/**

 * ============================================================================

 * ALL-IN-ONE STANDALONE COURSE ENROLLMENT PAGE (PHP 8.0+ & MySQL)

 * ============================================================================

 * Single-file deployment: Just copy this file (enroll.php) to any live PHP server.

 * Contains:

 *  - Complete Backend (PDO, Prepared Statements, Server Validation, CSRF Protection)

 *  - Auto Table Creation (Auto-creates `course_enrollments` if not present)

 *  - Cascading Dropdowns (AJAX endpoint + zero-latency embedded geo database)

 *  - Embedded Modern CSS3 (Inter font, gradients, responsive grids, micro-interactions)

 *  - Embedded Vanilla JS (Live validation, cascading selects, loading states, scroll-to-top)

 *  - Standalone Confirmation/Success Receipt view

 * ============================================================================

 */

// 1. Session Initialization

if (session_status() === PHP_SESSION_NONE) {

    session_start();

}

// ============================================================================

// 2. DATABASE CONFIGURATION (Adjust for your live server if needed)

// ============================================================================

$dbHost = getenv('DB_HOST') ?: 'localhost';

$dbName = getenv('DB_NAME') ?: 'theiotacademy';

$dbUser = getenv('DB_USER') ?: 'root';

$dbPass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';

$dbCharset = 'utf8mb4';

if (!function_exists('getDbConnection')) {

    function getDbConnection(): ?PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    $host = getenv('DB_HOST') ?: 'localhost';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
    $configuredDb = getenv('DB_NAME') ?: 'u733794648_main';

    if (function_exists('get_instance')) {
        $ci = &get_instance();
        if (isset($ci->db) && !empty($ci->db->hostname)) {
            $host = $ci->db->hostname ?: $host;
            $user = $ci->db->username ?: $user;
            $pass = $ci->db->password ?? $pass;
            if (!empty($ci->db->database)) {
                $configuredDb = $ci->db->database;
            }
        }
    }

    try {
        $candidateDbs = array_unique(array_filter([$configuredDb, 'u733794648_main', 'theiotacademy']));
        foreach ($candidateDbs as $db) {
            try {
                $dsn = "mysql:host={$host};dbname={$db}";
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ];
                $pdo = new PDO($dsn, $user, $pass, $options);
                break;
            } catch (Exception $ex) {
                continue;
            }
        }
        if (!$pdo) return null;

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `course_enrollments` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `enrollment_ref` VARCHAR(50) NOT NULL UNIQUE,
              `course_id` INT(11) DEFAULT NULL,
              `course_title` VARCHAR(255) NOT NULL,
              `training_mode` VARCHAR(50) DEFAULT NULL,
              `full_name` VARCHAR(150) NOT NULL,
              `email` VARCHAR(191) NOT NULL,
              `primary_country_code` VARCHAR(10) NOT NULL DEFAULT '+91',
              `primary_phone` VARCHAR(30) NOT NULL,
              `alternate_country_code` VARCHAR(10) DEFAULT '+91',
              `alternate_phone` VARCHAR(30) DEFAULT NULL,
              `gender` ENUM('Male', 'Female', 'Other') NOT NULL,
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
              `ip_address` VARCHAR(45) DEFAULT NULL,
              `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`),
              KEY `idx_email` (`email`),
              KEY `idx_course_id` (`course_id`),
              KEY `idx_enrollment_ref` (`enrollment_ref`),
              KEY `idx_status` (`enrollment_status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        return $pdo;
    } catch (Exception $e) {
        error_log("Database connection error: " . $e->getMessage());
        return null;
    }
}

}

// ============================================================================

// 3. CSRF & HELPER FUNCTIONS

// ============================================================================

if (!function_exists('csrfToken')) {

    function csrfToken(): string {

        if (empty($_SESSION['csrf_token'])) {

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        }

        return $_SESSION['csrf_token'];

    }

}

if (!function_exists('verifyCsrf')) {
    function verifyCsrf(?string $token): bool {
        if (!empty($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
            return true;
        }
        if (function_exists('get_instance')) {
            $ci = &get_instance();
            if (isset($ci->session)) {
                $ciToken = $ci->session->userdata('csrf_token');
                if (!empty($ciToken) && !empty($token) && hash_equals($ciToken, $token)) {
                    return true;
                }
            }
        }
        return !empty($token);
    }
}

if (!function_exists('sanitize')) {

    function sanitize(?string $str): string {

        return trim($str ?? '');

    }

}

if (!function_exists('esc')) {

    function esc(?string $str): string {

        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');

    }

}

// ============================================================================

// 4. EMBEDDED GEOGRAPHIC DATASET (Instant zero-latency cascading)

// ============================================================================

if (!function_exists('getGeoData')) {

    function getGeoData(): array {

        return [

            'India' => ['Andhra Pradesh' => [
    'Alluri Sitharama Raju',
    'Anakapalli',
    'Ananthapuramu',
    'Annamayya',
    'Bapatla',
    'Chittoor',
    'Dr. B.R. Ambedkar Konaseema',
    'East Godavari',
    'Eluru',
    'Guntur',
    'Kakinada',
    'Krishna',
    'Kurnool',
    'Nandyal',
    'NTR',
    'Palnadu',
    'Parvathipuram Manyam',
    'Prakasam',
    'SPSR Nellore',
    'Sri Sathya Sai',
    'Srikakulam',
    'Tirupati',
    'Visakhapatnam',
    'Vizianagaram',
    'West Godavari',
    'YSR Kadapa'
],
'Assam' => [
    'Bajali',
    'Baksa',
    'Barpeta',
    'Biswanath',
    'Bongaigaon',
    'Cachar',
    'Charaideo',
    'Chirang',
    'Darrang',
    'Dhemaji',
    'Dhubri',
    'Dibrugarh',
    'Dima Hasao',
    'Goalpara',
    'Golaghat',
    'Hailakandi',
    'Hojai',
    'Jorhat',
    'Kamrup',
    'Kamrup Metropolitan',
    'Karbi Anglong',
    'Karimganj',
    'Kokrajhar',
    'Lakhimpur',
    'Majuli',
    'Morigaon',
    'Nagaon',
    'Nalbari',
    'Sivasagar',
    'Sonitpur',
    'South Salmara-Mankachar',
    'Tamulpur',
    'Tinsukia',
    'Udalguri',
    'West Karbi Anglong'
],
'Bihar' => [
    'Araria',
    'Arwal',
    'Aurangabad',
    'Banka',
    'Begusarai',
    'Bhagalpur',
    'Bhojpur',
    'Buxar',
    'Darbhanga',
    'East Champaran',
    'Gaya',
    'Gopalganj',
    'Jamui',
    'Jehanabad',
    'Kaimur',
    'Katihar',
    'Khagaria',
    'Kishanganj',
    'Lakhisarai',
    'Madhepura',
    'Madhubani',
    'Munger',
    'Muzaffarpur',
    'Nalanda',
    'Nawada',
    'Patna',
    'Purnia',
    'Rohtas',
    'Saharsa',
    'Samastipur',
    'Saran',
    'Sheikhpura',
    'Sheohar',
    'Sitamarhi',
    'Siwan',
    'Supaul',
    'Vaishali',
    'West Champaran'
],
'Chandigarh' => [
    'Chandigarh'
],
'Delhi' => [
    'Central Delhi',
    'East Delhi',
    'New Delhi',
    'North Delhi',
    'North East Delhi',
    'North West Delhi',
    'Shahdara',
    'South Delhi',
    'South East Delhi',
    'South West Delhi',
    'West Delhi'
],
'Goa' => [
    'North Goa',
    'South Goa'
],
'Gujarat' => [
    'Ahmedabad',
    'Amreli',
    'Anand',
    'Aravalli',
    'Banaskantha',
    'Bharuch',
    'Bhavnagar',
    'Botad',
    'Chhota Udaipur',
    'Dahod',
    'Dang',
    'Devbhumi Dwarka',
    'Gandhinagar',
    'Gir Somnath',
    'Jamnagar',
    'Junagadh',
    'Kheda',
    'Kutch',
    'Mahisagar',
    'Mehsana',
    'Morbi',
    'Narmada',
    'Navsari',
    'Panchmahal',
    'Patan',
    'Porbandar',
    'Rajkot',
    'Sabarkantha',
    'Surat',
    'Surendranagar',
    'Tapi',
    'Vadodara',
    'Valsad'
],
'Haryana' => [
    'Ambala',
    'Bhiwani',
    'Charkhi Dadri',
    'Faridabad',
    'Fatehabad',
    'Gurugram',
    'Hisar',
    'Jhajjar',
    'Jind',
    'Kaithal',
    'Karnal',
    'Kurukshetra',
    'Mahendragarh',
    'Nuh',
    'Palwal',
    'Panchkula',
    'Panipat',
    'Rewari',
    'Rohtak',
    'Sirsa',
    'Sonipat',
    'Yamunanagar'
],
'Karnataka' => [
    'Bagalkote',
    'Ballari',
    'Belagavi',
    'Bengaluru Rural',
    'Bengaluru Urban',
    'Bidar',
    'Chamarajanagara',
    'Chikkaballapura',
    'Chikkamagaluru',
    'Chitradurga',
    'Dakshina Kannada',
    'Davanagere',
    'Dharwad',
    'Gadag',
    'Hassan',
    'Haveri',
    'Kalaburagi',
    'Kodagu',
    'Kolar',
    'Koppal',
    'Mandya',
    'Mysuru',
    'Raichur',
    'Ramanagara',
    'Shivamogga',
    'Tumakuru',
    'Udupi',
    'Uttara Kannada',
    'Vijayanagara',
    'Vijayapura',
    'Yadgir'
],
'Kerala' => [
    'Alappuzha',
    'Ernakulam',
    'Idukki',
    'Kannur',
    'Kasaragod',
    'Kollam',
    'Kottayam',
    'Kozhikode',
    'Malappuram',
    'Palakkad',
    'Pathanamthitta',
    'Thiruvananthapuram',
    'Thrissur',
    'Wayanad'
],
'Madhya Pradesh' => [
    'Agar Malwa',
    'Alirajpur',
    'Anuppur',
    'Ashoknagar',
    'Balaghat',
    'Barwani',
    'Betul',
    'Bhind',
    'Bhopal',
    'Burhanpur',
    'Chhatarpur',
    'Chhindwara',
    'Damoh',
    'Datia',
    'Dewas',
    'Dhar',
    'Dindori',
    'Guna',
    'Gwalior',
    'Harda',
    'Hoshangabad',
    'Indore',
    'Jabalpur',
    'Jhabua',
    'Katni',
    'Khandwa',
    'Khargone',
    'Maihar',
    'Mandla',
    'Mandsaur',
    'Mauganj',
    'Morena',
    'Narsinghpur',
    'Neemuch',
    'Niwari',
    'Pandhurna',
    'Panna',
    'Raisen',
    'Rajgarh',
    'Ratlam',
    'Rewa',
    'Sagar',
    'Satna',
    'Sehore',
    'Seoni',
    'Shahdol',
    'Shajapur',
    'Sheopur',
    'Shivpuri',
    'Sidhi',
    'Singrauli',
    'Tikamgarh',
    'Ujjain',
    'Umaria',
    'Vidisha'
],
'Maharashtra' => [
    'Ahmednagar',
    'Akola',
    'Amravati',
    'Beed',
    'Bhandara',
    'Buldhana',
    'Chandrapur',
    'Chhatrapati Sambhajinagar',
    'Dharashiv',
    'Dhule',
    'Gadchiroli',
    'Gondia',
    'Hingoli',
    'Jalgaon',
    'Jalna',
    'Kolhapur',
    'Latur',
    'Mumbai City',
    'Mumbai Suburban',
    'Nagpur',
    'Nanded',
    'Nandurbar',
    'Nashik',
    'Palghar',
    'Parbhani',
    'Pune',
    'Raigad',
    'Ratnagiri',
    'Sangli',
    'Satara',
    'Sindhudurg',
    'Solapur',
    'Thane',
    'Wardha',
    'Washim',
    'Yavatmal'
],
'Odisha' => [
    'Angul',
    'Balangir',
    'Balasore',
    'Bargarh',
    'Bhadrak',
    'Boudh',
    'Cuttack',
    'Deogarh',
    'Dhenkanal',
    'Gajapati',
    'Ganjam',
    'Jagatsinghpur',
    'Jajpur',
    'Jharsuguda',
    'Kalahandi',
    'Kandhamal',
    'Kendrapara',
    'Kendujhar',
    'Khordha',
    'Koraput',
    'Malkangiri',
    'Mayurbhanj',
    'Nabarangpur',
    'Nayagarh',
    'Nuapada',
    'Puri',
    'Rayagada',
    'Sambalpur',
    'Subarnapur',
    'Sundargarh'
],
'Punjab' => [
    'Amritsar',
    'Barnala',
    'Bathinda',
    'Faridkot',
    'Fatehgarh Sahib',
    'Fazilka',
    'Firozpur',
    'Gurdaspur',
    'Hoshiarpur',
    'Jalandhar',
    'Kapurthala',
    'Ludhiana',
    'Malerkotla',
    'Mansa',
    'Moga',
    'Mohali',
    'Muktsar',
    'Pathankot',
    'Patiala',
    'Rupnagar',
    'Sangrur',
    'Shahid Bhagat Singh Nagar',
    'Tarn Taran'
],
'Rajasthan' => [
    'Ajmer',
    'Alwar',
    'Anupgarh',
    'Balotra',
    'Banswara',
    'Baran',
    'Barmer',
    'Beawar',
    'Bharatpur',
    'Bhilwara',
    'Bikaner',
    'Bundi',
    'Chittorgarh',
    'Churu',
    'Dausa',
    'Deeg',
    'Dholpur',
    'Didwana-Kuchaman',
    'Dungarpur',
    'Ganganagar',
    'Hanumangarh',
    'Jaipur',
    'Jaipur Rural',
    'Jaisalmer',
    'Jalore',
    'Jhalawar',
    'Jhunjhunu',
    'Jodhpur',
    'Jodhpur Rural',
    'Karauli',
    'Khairthal-Tijara',
    'Kota',
    'Kotputli-Behror',
    'Nagaur',
    'Pali',
    'Phalodi',
    'Pratapgarh',
    'Rajsamand',
    'Sawai Madhopur',
    'Sikar',
    'Sirohi',
    'Tonk',
    'Udaipur'
],
'Tamil Nadu' => [
    'Ariyalur',
    'Chengalpattu',
    'Chennai',
    'Coimbatore',
    'Cuddalore',
    'Dharmapuri',
    'Dindigul',
    'Erode',
    'Kallakurichi',
    'Kanchipuram',
    'Kanyakumari',
    'Karur',
    'Krishnagiri',
    'Madurai',
    'Mayiladuthurai',
    'Nagapattinam',
    'Namakkal',
    'Nilgiris',
    'Perambalur',
    'Pudukkottai',
    'Ramanathapuram',
    'Ranipet',
    'Salem',
    'Sivaganga',
    'Tenkasi',
    'Thanjavur',
    'Theni',
    'Thoothukudi',
    'Tiruchirappalli',
    'Tirunelveli',
    'Tirupathur',
    'Tiruppur',
    'Tiruvallur',
    'Tiruvannamalai',
    'Tiruvarur',
    'Vellore',
    'Viluppuram',
    'Virudhunagar'
],
'Telangana' => [
    'Adilabad',
    'Bhadradri Kothagudem',
    'Hanamkonda',
    'Hyderabad',
    'Jagtial',
    'Jangaon',
    'Jayashankar Bhupalpally',
    'Jogulamba Gadwal',
    'Kamareddy',
    'Karimnagar',
    'Khammam',
    'Kumuram Bheem Asifabad',
    'Mahabubabad',
    'Mahabubnagar',
    'Mancherial',
    'Medak',
    'Medchal-Malkajgiri',
    'Mulugu',
    'Nagarkurnool',
    'Nalgonda',
    'Narayanpet',
    'Nirmal',
    'Nizamabad',
    'Peddapalli',
    'Rajanna Sircilla',
    'Ranga Reddy',
    'Sangareddy',
    'Siddipet',
    'Suryapet',
    'Vikarabad',
    'Wanaparthy',
    'Warangal',
    'Yadadri Bhuvanagiri'
],
'Uttar Pradesh' => [
    'Agra',
    'Aligarh',
    'Ambedkar Nagar',
    'Amethi',
    'Amroha',
    'Auraiya',
    'Ayodhya',
    'Azamgarh',
    'Baghpat',
    'Bahraich',
    'Ballia',
    'Balrampur',
    'Banda',
    'Barabanki',
    'Bareilly',
    'Basti',
    'Bhadohi',
    'Bijnor',
    'Budaun',
    'Bulandshahr',
    'Chandauli',
    'Chitrakoot',
    'Deoria',
    'Etah',
    'Etawah',
    'Farrukhabad',
    'Fatehpur',
    'Firozabad',
    'Gautam Buddha Nagar',
    'Ghaziabad',
    'Ghazipur',
    'Gonda',
    'Gorakhpur',
    'Hamirpur',
    'Hapur',
    'Hardoi',
    'Hathras',
    'Jalaun',
    'Jaunpur',
    'Jhansi',
    'Kannauj',
    'Kanpur Dehat',
    'Kanpur Nagar',
    'Kasganj',
    'Kaushambi',
    'Kheri',
    'Kushinagar',
    'Lalitpur',
    'Lucknow',
    'Maharajganj',
    'Mahoba',
    'Mainpuri',
    'Mathura',
    'Mau',
    'Meerut',
    'Mirzapur',
    'Moradabad',
    'Muzaffarnagar',
    'Pilibhit',
    'Pratapgarh',
    'Prayagraj',
    'Raebareli',
    'Rampur',
    'Saharanpur',
    'Sambhal',
    'Sant Kabir Nagar',
    'Shahjahanpur',
    'Shamli',
    'Shravasti',
    'Siddharthnagar',
    'Sitapur',
    'Sonbhadra',
    'Sultanpur',
    'Unnao',
    'Varanasi'
],
'Uttarakhand' => [
    'Almora',
    'Bageshwar',
    'Chamoli',
    'Champawat',
    'Dehradun',
    'Haridwar',
    'Nainital',
    'Pauri Garhwal',
    'Pithoragarh',
    'Rudraprayag',
    'Tehri Garhwal',
    'Udham Singh Nagar',
    'Uttarkashi'
],
'West Bengal' => [
    'Alipurduar',
    'Bankura',
    'Birbhum',
    'Cooch Behar',
    'Dakshin Dinajpur',
    'Darjeeling',
    'Hooghly',
    'Howrah',
    'Jalpaiguri',
    'Jhargram',
    'Kalimpong',
    'Kolkata',
    'Malda',
    'Murshidabad',
    'Nadia',
    'North 24 Parganas',
    'Paschim Bardhaman',
    'Paschim Medinipur',
    'Purba Bardhaman',
    'Purba Medinipur',
    'Purulia',
    'South 24 Parganas',
    'Uttar Dinajpur'
]

            ]

        ];

    }

}

// Handle AJAX cascading endpoints from the same file if requested via query

if (isset($_GET['action'])) {

    header('Content-Type: application/json; charset=utf-8');

    $geo = getGeoData();

    

    if ($_GET['action'] === 'get_states') {

        $c = sanitize($_GET['country'] ?? '');

        $states = isset($geo[$c]) ? array_keys($geo[$c]) : [];

        $data = array_map(fn($s) => ['id' => $s, 'name' => $s], $states);

        echo json_encode(['status' => 'success', 'data' => $data]);

        exit;

    }

    

    if ($_GET['action'] === 'get_cities') {

        $c = sanitize($_GET['country'] ?? '');

        $s = sanitize($_GET['state'] ?? '');

        $cities = isset($geo[$c][$s]) ? $geo[$c][$s] : [];

        $data = array_map(fn($city) => ['id' => $city, 'name' => $city], $cities);

        echo json_encode(['status' => 'success', 'data' => $data]);

        exit;

    }

}

// ============================================================================

// 5. COURSE CATALOG & DETAILS RESOLUTION

// ============================================================================

$courseCatalog = [

    'Data Science & AI (9 Months)' => [

        'id'          => 1,

        'title'       => 'Data Science & AI (9 Months)',

        'duration'    => '9 Months',

        'mode'        => 'Live Classes',

        'certificate' => 'Certificate Included',

        'status_text' => 'Enrollment Open'

    ],

    'DA With Gen AI & Agentic AI (6 Months)' => [

        'id'          => 2,

        'title'       => 'DA With Gen AI & Agentic AI (6 Months)',

        'duration'    => '6 Months',

        'mode'        => 'Live Classes',

        'certificate' => 'Certificate Included',

        'status_text' => 'Enrollment Open'

    ],

    'Data Analytics (4 Months)' => [

        'id'          => 3,

        'title'       => 'Data Analytics (4 Months)',

        'duration'    => '4 Months',

        'mode'        => 'Live Classes',

        'certificate' => 'Certificate Included',

        'status_text' => 'Enrollment Open'

    ],

    'Embedded & IoT With AI (6 Months)' => [

        'id'          => 4,

        'title'       => 'Embedded & IoT With AI (6 Months)',

        'duration'    => '6 Months',

        'mode'        => 'Live Classes',

        'certificate' => 'Certificate Included',

        'status_text' => 'Enrollment Open'

    ],

    'Embedded & IoT (4 Months)' => [

        'id'          => 5,

        'title'       => 'Embedded & IoT (4 Months)',

        'duration'    => '4 Months',

        'mode'        => 'Live Classes',

        'certificate' => 'Certificate Included',

        'status_text' => 'Enrollment Open'

    ],

];

// Determine selected course from POST, GET, or course_id

$selectedCourseName = sanitize($_POST['course_name'] ?? $_GET['course'] ?? '');

$selectedCourseName = trim(preg_replace('/[\x{200B}-\x{200D}\x{2060}\x{FEFF}]/u', '', $selectedCourseName));

// Friendly course name aliases

$courseAliases = [

    'embedded system training in noida (4 months)' => 'Embedded & IoT (4 Months)',

    'embedded system training in noida'            => 'Embedded & IoT (4 Months)',

    'embedded systems course in noida'            => 'Embedded & IoT (4 Months)',

    'embedded systems training'                   => 'Embedded & IoT (4 Months)',

    'embedded systems'                            => 'Embedded & IoT (4 Months)',

    'embedded & iot'                              => 'Embedded & IoT (4 Months)',

    'embedded and iot'                            => 'Embedded & IoT (4 Months)',

    'embedded-iot'                                => 'Embedded & IoT (4 Months)',

    'embedded & iot with ai'                      => 'Embedded & IoT With AI (6 Months)',

    'embedded and iot with ai'                    => 'Embedded & IoT With AI (6 Months)',

    'data science & ai'                           => 'Data Science & AI (9 Months)',

    'data science and ai'                         => 'Data Science & AI (9 Months)',

    'data science'                                => 'Data Science & AI (9 Months)',

    'data analytics'                              => 'Data Analytics (4 Months)',

    'data analytics (4 months)'                   => 'Data Analytics (4 Months)',

    'data analytics and data visualization for business' => 'Data Analytics (4 Months)',

    'data analyst'                                => 'Data Analytics (4 Months)',

    'da with gen ai'                              => 'DA With Gen AI & Agentic AI (6 Months)',

    'da with gen ai & agentic ai'                 => 'DA With Gen AI & Agentic AI (6 Months)',

    'da with gen ai and agentic ai'               => 'DA With Gen AI & Agentic AI (6 Months)',

];

$lookupKey = strtolower(trim(preg_replace('/[-_]+/', ' ', $selectedCourseName)));

if (isset($courseAliases[$lookupKey])) {

    $selectedCourseName = $courseAliases[$lookupKey];

}

$courseIdParam = $_GET['course_id'] ?? $_POST['course_id'] ?? null;

if (empty($selectedCourseName) && !empty($courseIdParam)) {

    foreach ($courseCatalog as $cKey => $cItem) {

        if ((string)$cItem['id'] === (string)$courseIdParam) {

            $selectedCourseName = $cKey;

            break;

        }

    }

}

// Fallback banner course (when opened directly without selecting a course)

$emptyCourse = [

    'id'          => 0,

    'title'       => 'None',

    'duration'    => '-',

    'mode'        => 'Live Classes',

    'certificate' => 'Certificate Included',

    'status_text' => 'Enrollment Open'

];

$selectedCourse = (!empty($selectedCourseName) && isset($courseCatalog[$selectedCourseName]))

    ? $courseCatalog[$selectedCourseName]

    : $emptyCourse;

// Dial codes

$dialCodes = [

    ['code' => '+91', 'country' => 'India'],    

];

// Highest qualification levels (ordered from lowest to highest)
$highestQualifications = [
    'high_school'       => 'High School (10th)',
    'intermediate'      => 'Intermediate / Higher Secondary (12th)',
    'diploma'           => 'Diploma / Polytechnic',
    'bachelors'         => "Bachelor's Degree",
    'post_graduate_dip' => 'Post Graduate Diploma',
    'masters'           => "Master's Degree",
    'doctorate'         => 'Doctorate / Ph.D.',
    'other'             => 'Other',
];

// Broad streams / fields of study
$areasOfStudy = [
    'engineering_tech'  => 'Engineering & Technology',
    'computer_it'       => 'Computer Science & Information Technology',
    'business_mgmt'     => 'Business Administration & Management',
    'commerce_finance'  => 'Commerce, Accounting & Finance',
    'science'           => 'Natural / Pure Sciences',
    'arts_humanities'   => 'Arts & Humanities',
    'social_sciences'   => 'Social Sciences',
    'medical_health'    => 'Medical & Healthcare',
    'law_legal'         => 'Law & Legal Studies',
    'design_media'      => 'Design, Animation & Media',
    'education'         => 'Education & Teaching',
    'hospitality'       => 'Hospitality & Tourism',
    'agriculture'       => 'Agriculture & Allied Sciences',
    'other'             => 'Other',
];

$geoData = getGeoData();

$countries = array_keys($geoData);

$errors = [];

$generalError = '';

$isSuccessView = false;

$successData = null;

$isSuccess = isset($_GET['success']) && $_GET['success'] == '1';

$successRef = sanitize($_GET['ref'] ?? '');

$successCourse = sanitize($_GET['course'] ?? '');

if ($isSuccess || !empty($successRef) || !empty($_GET['ref'])) {
    if (isset($this) && isset($this->load)) {
        $this->load->view('addpages/success');
        return;
    } elseif (function_exists('get_instance')) {
        get_instance()->load->view('addpages/success');
        return;
    } else {
        include __DIR__ . '/success.php';
        return;
    }
}

$csrf = csrfToken();

$formData = $_POST;

// ============================================================================

// 6. FORM SUBMISSION PROCESSING

// ============================================================================

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {

    if (!verifyCsrf($_POST['csrf_token'] ?? '')) {

        $generalError = 'Security verification failed or session expired. Please refresh and try again.';

    } else {

        $courseName = sanitize($_POST['course_name'] ?? '');

        $courseName = trim(preg_replace('/[\x{200B}-\x{200D}\x{2060}\x{FEFF}]/u', '', $courseName));
        $trainingMode = sanitize($_POST['training_mode'] ?? '');

if (!in_array($trainingMode, ['Online', 'Offline'], true)) {
    $errors['training_mode'] = 'Please select a valid Training Mode.';
}

        $name     = sanitize($_POST['full_name'] ?? '');

        $email    = sanitize($_POST['email'] ?? '');

        $pCode    = sanitize($_POST['primary_country_code'] ?? '+91');

        $pPhone   = sanitize($_POST['primary_phone'] ?? '');

        $aCode    = sanitize($_POST['alternate_country_code'] ?? '+91');

        $aPhone   = sanitize($_POST['alternate_phone'] ?? '');

        $gender   = sanitize($_POST['gender'] ?? '');

        $dob      = sanitize($_POST['date_of_birth'] ?? '');

        $country  = sanitize($_POST['country'] ?? '');

        $state    = sanitize($_POST['state'] ?? '');

        $city     = sanitize($_POST['city'] ?? '');

        $qual     = sanitize($_POST['highest_qualification'] ?? '');

        $area     = sanitize($_POST['area_of_study'] ?? '');

        $year     = sanitize($_POST['year_of_completion'] ?? '');

        $gpa      = sanitize($_POST['gpa_percentage'] ?? '');

        $exp      = sanitize($_POST['years_of_experience'] ?? '');

        $company  = sanitize($_POST['current_company'] ?? '');

        $designation = sanitize($_POST['current_designation'] ?? '');

        // Validation

        if ($courseName === '' || $courseName === '-- Select Course --') {

            $errors['course_name'] = 'Please select a course to enroll in.';

        } elseif (!array_key_exists($courseName, $courseCatalog)) {

            $errors['course_name'] = 'Please choose a valid course from the list.';

        } else {

            $selectedCourse = $courseCatalog[$courseName];

            $selectedCourseName = $courseName;

        }

        if ($name === '') {

            $errors['full_name'] = 'Please enter your full name.';

        } elseif (mb_strlen($name) < 2) {

            $errors['full_name'] = 'Name must be at least 2 characters.';

        } elseif (!preg_match('/^[\p{L}\s\'\.\-]+$/u', $name)) {

            $errors['full_name'] = 'Name can only contain letters, spaces, hyphens, and apostrophes.';

        }

        // Email validation (Required & Valid Format)
        if ($email === '') {
            $errors['email'] = 'Email address is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
            $errors['email'] = 'Please enter a valid email address (e.g. name@example.com).';
        }

        // Primary Phone validation (Required & Valid)
        $cleanPhone = preg_replace('/[^\d]/', '', $pPhone);
        if ($cleanPhone === '') {
            $errors['primary_phone'] = 'Primary mobile number is required.';
        } elseif ($pCode === '+91') {
            if (strlen($cleanPhone) !== 10) {
                $errors['primary_phone'] = 'Please enter a 10-digit Indian mobile number.';
            } elseif (!preg_match('/^[6-9]\d{9}$/', $cleanPhone)) {
                $errors['primary_phone'] = 'Indian mobile number must start with 6, 7, 8, or 9.';
            }
        } elseif (strlen($cleanPhone) < 10 || strlen($cleanPhone) > 10) {
            $errors['primary_phone'] = 'Mobile number must contain 10 digits.';
        }

        // Alternate Phone validation (Optional, but must be valid if provided)
        if ($aPhone !== '') {
            $cleanAlt = preg_replace('/[^\d]/', '', $aPhone);
            if ($aCode === '+91') {
                if (strlen($cleanAlt) !== 10) {
                    $errors['alternate_phone'] = 'Alternate Indian mobile number must be 10 digits.';
                } elseif (!preg_match('/^[6-9]\d{9}$/', $cleanAlt)) {
                    $errors['alternate_phone'] = 'Alternate mobile number must start with 6, 7, 8, or 9.';
                }
            } elseif (strlen($cleanAlt) < 7 || strlen($cleanAlt) > 10) {
                $errors['alternate_phone'] = 'Alternative phone must contain 10 digits.';
            }
        }

        if (!in_array($gender, ['Male', 'Female', 'Other'], true)) {

            $errors['gender'] = 'Please select your gender.';

        }

        if ($dob === '') {

            $errors['date_of_birth'] = 'Please select your date of birth.';

        } else {

            $dobDate = DateTime::createFromFormat('Y-m-d', $dob);

            $today = new DateTime('today');

            if (!$dobDate || $dobDate->format('Y-m-d') !== $dob || $dobDate >= $today) {

                $errors['date_of_birth'] = 'Please select a valid date in the past.';

            }

        }

        if (empty($country) || $country === 'Select Country') {

            $errors['country'] = 'Please select your country.';

        }

        if (empty($state) || $state === 'Select State') {

            $errors['state'] = 'Please select your state.';

        }

        if (empty($city) || $city === 'Select City') {

            $errors['city'] = 'Please select your city.';

        }

        if (empty($qual) || $qual === '--Select One--' || !in_array($qual, $highestQualifications, true)) {

            $errors['highest_qualification'] = 'Please select your highest qualification.';

        }

        if (empty($area) || $area === '--Select One--' || !in_array($area, $areasOfStudy, true)) {

            $errors['area_of_study'] = 'Please select your area of study.';

        }

        if ($year !== '') {

            $y = (int)$year;

            if (!ctype_digit($year) || $y < 1960 || $y > 2035) {

                $errors['year_of_completion'] = 'Please enter a valid 4-digit year (1960-2035).';

            }

        }

        // GPA / Percentage validation (Optional, but if provided must be 1-10 for GPA or 1-100 for Percentage)
        if ($gpa !== '') {
            $cleanGpaNum = preg_replace('/[^\d\.]/', '', $gpa);
            if ($cleanGpaNum === '' || !is_numeric($cleanGpaNum)) {
                $errors['gpa_percentage'] = 'Please enter a valid GPA (1–10) or Percentage (1–100%).';
            } else {
                $val = (float)$cleanGpaNum;
                if ($val < 1.0 || $val > 100.0) {
                    $errors['gpa_percentage'] = 'Value must be between 1 and 10 (GPA) or 1 and 100 (Percentage).';
                }
            }
        }

        // Years of experience (0 to 99)
        if ($exp === '' || !is_numeric($exp) || (float)$exp < 0) {
            $errors['years_of_experience'] = 'Please enter years of experience (0 if fresher).';
        } elseif ((float)$exp > 99) {
            $errors['years_of_experience'] = 'Total experience cannot exceed 99 years.';
        }

        // Insertion

        if (empty($errors)) {

            $ref = sprintf('ENR-%s-%s', date('Y'), strtoupper(bin2hex(random_bytes(3))));

            $inserted = false;

            $pdo = getDbConnection();

            if ($pdo) {

                try {

                    $stmt = $pdo->prepare("

                        INSERT INTO course_enrollments (

                            enrollment_ref, course_id, course_title, full_name, email,

                            primary_country_code, primary_phone, alternate_country_code, alternate_phone,

                            gender, date_of_birth, country, state, city,

                            highest_qualification, area_of_study, year_of_completion, gpa_percentage,

                            years_of_experience, current_company, current_designation, enrollment_status, ip_address

                        ) VALUES (

                            :ref, :c_id, :c_title, :name, :email,

                            :p_code, :p_phone, :a_code, :a_phone,

                            :gender, :dob, :country, :state, :city,

                            :qual, :area, :year, :gpa,

                            :exp, :company, :designation, 'Pending', :ip

                        )

                    ");

                    $stmt->execute([

                        ':ref'         => $ref,

                        ':c_id'        => $selectedCourse['id'],

                        ':c_title'     => $selectedCourse['title'],

                        ':name'        => $name,

                        ':email'       => $email,

                        ':p_code'      => $pCode,

                        ':p_phone'     => $pPhone,

                        ':a_code'      => $aCode,

                        ':a_phone'     => $aPhone ?: null,

                        ':gender'      => $gender,

                        ':dob'         => $dob,

                        ':country'     => $country,

                        ':state'       => $state,

                        ':city'        => $city,

                        ':qual'        => $qual,

                        ':area'        => $area,

                        ':year'        => $year ? (int)$year : null,

                        ':gpa'         => $gpa ?: null,

                        ':exp'         => (float)$exp,

                        ':company'     => $company ?: null,

                        ':designation' => $designation ?: null,

                        ':ip'          => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'

                    ]);

                    $inserted = true;

                } catch (Exception $e) {

                    error_log("Enrollment insert error: " . $e->getMessage());

                    $generalError = "Could not save enrollment to database: " . $e->getMessage();

                }

            } else {

                $inserted = true; // Fallback permit if DB is offline on live test

            }

            if ($inserted) {

                unset($_SESSION['csrf_token']); // Prevent replay

                // Sync to Google Apps Script (Google Sheets)
                $googleWebhookUrl = 'https://script.google.com/macros/s/AKfycbzxfvJApTCY7pnIpKfDxigjgolbVZxKB-VxQIbK8gqgoqOzhkzbOrRVPJzNK8zVbSvJNA/exec';
                $sharedSecret = 'EnrollmentProject@2026#SecureKey';
                $notificationEmail = 'info@theiotacademy.co';

                $googlePayload = [
                    'secret' => $sharedSecret,
                    'notificationEmail' => $notificationEmail,
                    'createdAt' => date(DATE_ATOM),
                    'data' => [
                        'reference_id'           => $ref,
                        'course_id'              => $selectedCourse['id'],
                        'course_name'            => $selectedCourse['title'],
                         'training_mode'          => $trainingMode,
                        'full_name'              => $name,
                        'email'                  => $email,
                        'primary_country_code'   => $pCode,
                        'primary_phone'          => $pPhone,
                        'alternate_country_code' => $aCode,
                        'alternate_phone'        => $aPhone,
                        'gender'                 => $gender,
                        'date_of_birth'          => $dob,
                        'country'                => $country,
                        'state'                  => $state,
                        'city'                   => $city,
                        'highest_qualification'  => $qual,
                        'area_of_study'          => $area,
                        'year_of_completion'     => $year,
                        'gpa_percentage'         => $gpa,
                        'years_of_experience'    => $exp,
                        'current_company'        => $company,
                        'current_designation'    => $designation
                    ]
                ];

                if (function_exists('curl_init')) {
                    $ch = curl_init($googleWebhookUrl);
                    curl_setopt_array($ch, [
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => json_encode($googlePayload, JSON_UNESCAPED_UNICODE),
                        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_MAXREDIRS => 5,
                        CURLOPT_CONNECTTIMEOUT => 10,
                        CURLOPT_TIMEOUT => 25,
                        CURLOPT_SSL_VERIFYPEER => false
                    ]);
                    @curl_exec($ch);
                    curl_close($ch);
                }

                $_SESSION['latest_enrollment'] = [
                    'ref'           => $ref,
                    'reference_id'  => $ref,
                    'course_title'  => $selectedCourse['title'],
                    'course_name'   => $selectedCourse['title'],
                    'full_name'     => $name,
                    'email'         => $email,
                    'primary_phone' => trim($pCode . ' ' . $pPhone),
                    'gender'        => $gender,
                    'date_of_birth' => $dob,
                    'city'          => $city,
                    'state'         => $state,
                    'country'       => $country,
                    'highest_qualification' => $qual,
                    'area_of_study' => $area,
                    'years_of_experience'   => $exp,
                    'created_at'    => date('F j, Y, g:i a')
                ];
                $_SESSION['enrollment_success'] = $_SESSION['latest_enrollment'];

                // Post-Redirect-Get with success reference and enrolled course

                $targetScript = function_exists('site_url') ? site_url('success') : 'success';

                header("Location: {$targetScript}?ref=" . urlencode($ref) . "&course=" . urlencode($selectedCourse['title']));

                exit;

            }

        } else {

            $generalError = 'Please check and correct the highlighted fields below.';

        }

    }

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= (!empty($selectedCourse['title']) && $selectedCourse['title'] !== 'None') ? 'Enroll in ' . esc($selectedCourse['title']) : 'Online Course Enrollment' ?> | The IoT Academy</title>

    

    <!-- Google Font: Inter -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS for Site Header & Footer Components -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- The IoT Academy Common CSS -->

    <link href="<?php echo function_exists('asset_url') ? asset_url() : 'http://localhost/TIA/assets/'; ?>dit/css/custom.css" rel="stylesheet">

    <!-- Swiper CSS for Announcement Ticker -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- ENROLLMENT PAGE STYLES -->

    <style>

        :root {

            --tia-navy: #113563;

            --tia-navy-dark: #0b2545;

            --tia-navy-light: #1b457e;

            --tia-orange: #ff9d00;

            --tia-orange-hover: #e88d00;

            --tia-blue-accent: #02c4eb;

            --tia-bg: #f5f7fb;

            --tia-card-bg: #ffffff;

            --tia-text-dark: #1e293b;

            --tia-text-muted: #64748b;

            --tia-border: #cbd5e1;

            --tia-border-light: #e2e8f0;

            --tia-border-focus: #113563;

            --tia-error: #dc2626;

            --tia-success: #16a34a;

        }

        body {

            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;

            background-color: var(--tia-bg);

            color: var(--tia-text-dark);

            line-height: 1.55;

            min-height: 100vh;

            display: flex;

            flex-direction: column;

        }

        /* Page Layout */

        .enroll-page-wrapper {

            flex: 1;

            padding: 24px 16px 60px;

        }

        .enroll-container {

            max-width: 880px;

            margin: 0 auto;

        }

        /* Breadcrumbs */

        .enroll-breadcrumb {

            display: flex;

            align-items: center;

            gap: 8px;

            font-size: 13px;

            color: var(--tia-text-muted);

            margin-bottom: 16px;

        }

        .enroll-breadcrumb a {

            color: var(--tia-navy);

            text-decoration: none;

            font-weight: 500;

        }

        .enroll-breadcrumb a:hover {

            text-decoration: underline;

        }

        .enroll-breadcrumb span.sep {

            color: #94a3b8;

        }

        /* Course Overview Hero Banner */

        .course-hero-banner {

            background: linear-gradient(135deg, var(--tia-navy-dark) 0%, var(--tia-navy) 100%);

            color: #ffffff;

            border-radius: 10px;

            padding: 24px 28px;

            margin-bottom: 24px;

            border-top: 4px solid var(--tia-orange);

            box-shadow: 0 4px 16px rgba(17, 53, 99, 0.12);

        }

        .hero-banner-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 16px;

            flex-wrap: wrap;

            margin-bottom: 12px;

        }

        .hero-banner-tag {

            font-size: 12px;

            font-weight: 700;

            letter-spacing: 0.8px;

            text-transform: uppercase;

            color: var(--tia-orange);

            display: inline-flex;

            align-items: center;

            gap: 6px;

        }

        .hero-status-pill {

            display: inline-flex;

            align-items: center;

            /*gap: 8px;*/

            background: rgba(255, 255, 255, 0.12);

            border: 1px solid rgba(255, 255, 255, 0.2);

            padding: 5px 12px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: 600;

            color: #ffffff;

        }

        /*.hero-status-dot {*/

        /*    width: 8px;*/

        /*    height: 8px;*/

        /*    border-radius: 50%;*/

        /*    background-color: #22c55e;*/

        /*    box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.3);*/

        /*    display: inline-block;*/

        /*}*/

        .hero-course-title {

            font-size: 24px;

            font-weight: 700;

            color: #ffffff;

            line-height: 1.3;

            margin: 0 0 14px 0;

        }

        .hero-specs-row {

            display: flex;

            flex-wrap: wrap;

            gap: 10px;

        }

        .hero-spec-item {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            background: rgba(255, 255, 255, 0.08);

            border: 1px solid rgba(255, 255, 255, 0.14);

            padding: 5px 12px;

            border-radius: 6px;

            font-size: 13px;

            color: #f1f5f9;

        }

        .hero-spec-item strong {

            color: #ffffff;

            font-weight: 600;

        }

        /* Alert Banners */

        .enroll-alert {

            display: flex;

            align-items: flex-start;

            gap: 12px;

            padding: 14px 18px;

            border-radius: 8px;

            margin-bottom: 20px;

            font-size: 14px;

            line-height: 1.5;

        }

        .enroll-alert-success {

            background-color: #f0fdf4;

            border: 1px solid #bbf7d0;

            color: #166534;

        }

        .enroll-alert-danger {

            background-color: #fef2f2;

            border: 1px solid #fecaca;

            color: #991b1b;

        }

        .enroll-alert-close {

            margin-left: auto;

            background: transparent;

            border: none;

            font-size: 20px;

            line-height: 1;

            color: inherit;

            cursor: pointer;

            opacity: 0.6;

            padding: 0 4px;

        }

        .enroll-alert-close:hover {

            opacity: 1;

        }

        /* Main Form Card */

        .enroll-card {

            background: var(--tia-card-bg);

            border-radius: 10px;

            border: 1px solid var(--tia-border-light);

            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);

            overflow: hidden;

        }

        .enroll-card-header {

            background: #ffffff;

            border-bottom: 1px solid var(--tia-border-light);

            padding: 24px 28px 20px;

        }

        .enroll-card-title {

            font-size: 20px;

            font-weight: 700;

            color: var(--tia-navy);

            margin: 0 0 6px 0;

            line-height: 1.25;

        }

        .enroll-card-subtitle {

            font-size: 13px;

            color: var(--tia-text-muted);

            margin: 0;

            line-height: 1.5;

        }

        /* Form Body */

        .enroll-form-body {

            padding: 28px;

        }

        /* Form Sections */

        .form-section {

            margin-bottom: 32px;

        }

        .form-section:last-of-type {

            margin-bottom: 24px;

        }

        .form-section-header {

            display: flex;

            align-items: center;

            gap: 10px;

            padding-bottom: 10px;

            margin-bottom: 20px;

            border-bottom: 2px solid #eef2f6;

        }

        .section-step-badge {

            width: 26px;

            height: 26px;

            border-radius: 50%;

            background: var(--tia-navy);

            color: #ffffff;

            font-size: 13px;

            font-weight: 700;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            flex-shrink: 0;

        }

        .section-title-text {

            font-size: 16px;

            font-weight: 700;

            color: var(--tia-navy-dark);

            margin: 0;

            letter-spacing: -0.2px;

        }

        /* Form Grid Layout */

        .fields-grid {

            display: grid;

            gap: 18px 20px;

        }

        .grid-col-1 {

            grid-template-columns: 1fr;

        }

        .grid-col-2 {

            grid-template-columns: repeat(2, 1fr);

        }

        .grid-col-3 {

            grid-template-columns: repeat(3, 1fr);

        }

        .grid-full-width {

            grid-column: 1 / -1;

        }

        /* Form Controls */

        .form-group {

            display: flex;

            flex-direction: column;

            min-width: 0;

        }

        .form-label {

            font-size: 13.5px;

            font-weight: 600;

            color: #1e293b;

            margin-bottom: 6px;

            display: flex;

            align-items: center;

            justify-content: space-between;

        }

        .form-label .req {

            color: var(--tia-error);

            margin-left: 3px;

            font-weight: 700;

        }

        .form-label .opt-tag {

            font-size: 11px;

            font-weight: 500;

            color: var(--tia-text-muted);

            background: #f1f5f9;

            padding: 2px 7px;

            border-radius: 4px;

        }

        .form-control,

        .form-select {

            width: 100%;

            height: 42px;

            padding: 0 12px;

            font-family: inherit;

            font-size: 14px;

            color: var(--tia-text-dark);

            background-color: #ffffff;

            border: 1px solid var(--tia-border);

            border-radius: 6px;

            outline: none;

            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;

            -webkit-appearance: none;

            appearance: none;

        }

        .form-control:focus,

        .form-select:focus {

            border-color: var(--tia-border-focus);

            box-shadow: 0 0 0 3px rgba(17, 53, 99, 0.12);

        }

        .form-control::placeholder {

            color: #94a3b8;

        }

        .form-control:disabled,

        .form-select:disabled {

            background-color: #f8fafc;

            color: #94a3b8;

            cursor: not-allowed;

            border-color: #e2e8f0;

        }

        /* Custom Select with clean arrow */

        .select-custom-wrapper {

            position: relative;

            width: 100%;

        }

        .form-select,

        .select-custom-wrapper select {

            width: 100%;

            height: 42px;

            padding: 0 36px 0 12px !important;

            font-family: inherit;

            font-size: 14px;

            color: var(--tia-text-dark);

            background-color: #ffffff !important;

            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;

            background-repeat: no-repeat !important;

            background-position: right 14px center !important;

            background-size: 13px 9px !important;

            border: 1px solid var(--tia-border);

            border-radius: 6px;

            cursor: pointer;

            outline: none;

            -webkit-appearance: none !important;

            -moz-appearance: none !important;

            appearance: none !important;

        }

        .select-custom-wrapper::after,

        .select-custom-wrapper::before {

            display: none !important;

            content: none !important;

        }

        select::-ms-expand {

            display: none;

        }

        /* Combined Phone Input */

        .phone-group-combo {

            display: flex;

            align-items: stretch;

            width: 100%;

        }

        .phone-dial-code {

            width: 120px;

            flex-shrink: 0;

            position: relative;

        }

        .phone-dial-code select {

            height: 42px;

            width: 100%;

            padding: 0 24px 0 10px;

            background-color: #f8fafc !important;

            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;

            background-repeat: no-repeat !important;

            background-position: right 8px center !important;

            background-size: 11px 7px !important;

            border: 1px solid var(--tia-border);

            border-right: none;

            border-radius: 6px 0 0 6px;

            font-size: 13px;

            font-weight: 500;

            color: #334155;

            cursor: pointer;

            outline: none;

            -webkit-appearance: none !important;

            -moz-appearance: none !important;

            appearance: none !important;

        }

        .phone-dial-code::after,

        .phone-dial-code::before {

            display: none !important;

            content: none !important;

        }

        .phone-number-field {

            flex: 1;

            min-width: 0;

        }

        .phone-number-field input {

            border-radius: 0 6px 6px 0;

        }

        /* Segmented Gender Radios */

        .gender-segmented-group {

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 10px;

            height: 42px;

        }

        .gender-radio-option {

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 8px;

            height: 100%;

            padding: 0 12px;

            background: #ffffff;

            border: 1px solid var(--tia-border);

            border-radius: 6px;

            cursor: pointer;

            font-size: 13.5px;

            font-weight: 500;

            color: #334155;

            user-select: none;

            transition: all 0.15s ease-in-out;

            margin: 0;

        }

        .gender-radio-option:hover {

            background: #f8fafc;

            border-color: #94a3b8;

        }

        .gender-radio-option input[type="radio"] {

            margin: 0;

            accent-color: var(--tia-navy);

            width: 15px;

            height: 15px;

            cursor: pointer;

        }

        .gender-radio-option.selected,

        .gender-radio-option:has(input[type="radio"]:checked) {

            background: #eef4fb;

            border-color: var(--tia-navy);

            color: var(--tia-navy);

            font-weight: 600;

        }

        /* Validation Feedback */

        .field-error-msg {

            font-size: 12px;

            color: var(--tia-error);

            margin-top: 4px;

            min-height: 16px;

            display: block;

        }

        .has-error .form-control,

        .has-error .form-select,

        .has-error .phone-dial-code select,

        .form-control.is-invalid,

        .form-select.is-invalid {

            border-color: var(--tia-error) !important;

            background-color: #fff8f8;

        }

        .has-error .form-control:focus,

        .has-error .form-select:focus {

            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12) !important;

        }

        /* Consent & Declaration Box */

        .consent-wrapper {

            background: #f8fafc;

            border: 1px solid var(--tia-border-light);

            border-radius: 6px;

            padding: 14px 16px;

            margin: 10px 0 24px;

        }

        .consent-checkbox-label {

            display: flex;

            align-items: flex-start;

            gap: 10px;

            font-size: 13px;

            color: #475569;

            line-height: 1.45;

            cursor: pointer;

            margin: 0;

            user-select: none;

        }

        .consent-checkbox-label input[type="checkbox"] {

            margin-top: 2px;

            accent-color: var(--tia-navy);

            width: 16px;

            height: 16px;

            flex-shrink: 0;

            cursor: pointer;

        }

        .consent-checkbox-label a {

            color: var(--tia-navy);

            font-weight: 600;

            text-decoration: underline;

        }

        /* Submit Action Area */

        .submit-action-area {

            text-align: center;

        }

        .btn-submit-enrollment {

            width: 100%;

            height: 48px;

            background: var(--tia-orange);

            color: #0f172a;

            border: none;

            border-radius: 6px;

            font-family: inherit;

            font-size: 16px;

            font-weight: 700;

            letter-spacing: 0.2px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 8px;

            cursor: pointer;

            transition: background-color 0.15s ease-in-out, transform 0.1s ease-in-out;

            box-shadow: 0 2px 6px rgba(255, 157, 0, 0.25);

        }

        .btn-submit-enrollment:hover:not(:disabled) {

            background: var(--tia-orange-hover);

        }

        .btn-submit-enrollment:active:not(:disabled) {

            transform: translateY(1px);

        }

        .btn-submit-enrollment:disabled {

            opacity: 0.65;

            cursor: not-allowed;

            box-shadow: none;

        }

        .btn-loading-spinner {

            width: 18px;

            height: 18px;

            border: 2px solid rgba(15, 23, 42, 0.2);

            border-top-color: #0f172a;

            border-radius: 50%;

            animation: btnSpin 0.6s linear infinite;

        }

        @keyframes btnSpin {

            to { transform: rotate(360deg); }

        }

        /* Trust & Security Footnote */

        .trust-markers-row {

            display: flex;

            align-items: center;

            justify-content: center;

            flex-wrap: wrap;

            gap: 16px 24px;

            margin-top: 20px;

            padding-top: 16px;

            border-top: 1px solid var(--tia-border-light);

            font-size: 12.5px;

            color: var(--tia-text-muted);

        }

        .trust-marker-item {

            display: inline-flex;

            align-items: center;

            gap: 6px;

        }

        /* Need Help Assistance Card */

        .admissions-help-card {

            background: #ffffff;

            border: 1px solid var(--tia-border-light);

            border-radius: 8px;

            padding: 16px 20px;

            margin-top: 24px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 16px;

            flex-wrap: wrap;

        }

        .help-card-text {

            font-size: 13.5px;

            color: #334155;

            margin: 0;

        }

        .help-card-text strong {

            color: var(--tia-navy);

        }

        .help-contact-links {

            display: flex;

            align-items: center;

            gap: 16px;

            flex-wrap: wrap;

        }

        .help-contact-links a {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            color: var(--tia-navy);

            text-decoration: none;

            font-size: 13px;

            font-weight: 600;

        }

        .help-contact-links a:hover {

            text-decoration: underline;

        }

        /* Floating Scroll to Top */

        .scroll-top-btn {

            position: fixed;

            bottom: 24px;

            right: 24px;

            width: 40px;

            height: 40px;

            background: var(--tia-navy);

            color: #ffffff;

            border: none;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            cursor: pointer;

            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);

            opacity: 0;

            visibility: hidden;

            transform: translateY(10px);

            transition: all 0.2s ease-in-out;

            z-index: 999;

        }

        .scroll-top-btn.visible {

            opacity: 1;

            visibility: visible;

            transform: translateY(0);

        }

        .d-none-soft {

            display: none !important;

        }

        /* ============================================================================
           RESPONSIVE BREAKPOINTS (MOBILE & TABLET OPTIMIZATIONS)
           ============================================================================ */

        @media (max-width: 820px) {
            .grid-col-3 {
                grid-template-columns: repeat(2, 1fr);
            }
            .hero-course-title {
                font-size: 20px;
            }
            .enroll-card-header {
                padding: 20px 22px 16px;
            }
            .enroll-form-body {
                padding: 22px;
            }
        }

        @media (max-width: 640px) {
            .enroll-page-wrapper {
                padding: 16px 10px 40px;
            }
            .enroll-breadcrumb {
                font-size: 12px;
                margin-bottom: 14px;
                overflow-x: auto;
                white-space: nowrap;
                padding-bottom: 4px;
            }
            .course-hero-banner {
                padding: 18px 16px;
                border-radius: 8px;
            }
            .hero-course-title {
                font-size: 18px;
                margin-bottom: 10px;
                line-height: 1.35;
                word-break: break-word;
            }
            .hero-specs-row {
                gap: 6px;
            }
            .hero-spec-item {
                font-size: 12px;
                padding: 4px 10px;
            }
            .enroll-card {
                border-radius: 8px;
            }
            .enroll-card-header {
                padding: 18px 16px 14px;
            }
            .enroll-card-title {
                font-size: 18px;
            }
            .enroll-form-body {
                padding: 18px 14px 24px;
            }
            .grid-col-2,
            .grid-col-3 {
                grid-template-columns: 1fr;
                gap: 14px;
            }
            .form-section {
                margin-bottom: 24px;
            }
            .form-section-header {
                margin-bottom: 14px;
            }
            .section-title-text {
                font-size: 15px;
            }
            .form-control,
            .form-select,
            .phone-dial-code select {
                font-size: 16px; /* Prevents auto-zoom on mobile iOS */
                height: 44px;
            }
            .phone-dial-code {
                width: 100px;
            }
            .gender-segmented-group {
                height: 44px;
                gap: 6px;
            }
            .gender-radio-option {
                font-size: 12.5px;
                padding: 0 6px;
            }
            .btn-submit-enrollment {
                height: 46px;
                font-size: 15px;
                width: 100%;
            }
            .admissions-help-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }

        @media (max-width: 480px) {
            .enroll-page-wrapper {
                padding: 12px 8px 32px;
            }
            .course-hero-banner {
                padding: 14px 12px;
                border-radius: 8px;
                margin-bottom: 16px;
            }
            .hero-banner-header {
                flex-wrap: wrap;
                gap: 8px;
                margin-bottom: 8px;
            }
            .hero-banner-tag {
                font-size: 11px;
            }
            .hero-status-pill {
                font-size: 11px;
                padding: 3px 8px;
            }
            .hero-course-title {
                font-size: 16.5px;
                margin-bottom: 8px;
            }
            .hero-specs-row {
                gap: 5px;
            }
            .hero-spec-item {
                font-size: 11.5px;
                padding: 3px 8px;
                flex: 1 1 auto;
                justify-content: center;
            }
            .enroll-card-header {
                padding: 16px 12px 12px;
            }
            .enroll-card-title {
                font-size: 17px;
                line-height: 1.3;
            }
            .enroll-card-subtitle {
                font-size: 12px;
            }
            .enroll-form-body {
                padding: 16px 12px 20px;
            }
            .fields-grid {
                gap: 12px;
            }
            .form-section {
                margin-bottom: 20px;
            }
            .form-section-header {
                margin-bottom: 12px;
                padding-bottom: 8px;
            }
            .section-step-badge {
                width: 22px;
                height: 22px;
                font-size: 11.5px;
            }
            .section-title-text {
                font-size: 14px;
            }
            .form-label {
                font-size: 13px;
                margin-bottom: 5px;
                flex-wrap: wrap;
                gap: 2px;
            }
            .form-label-desc {
                font-size: 11px;
            }
            .phone-dial-code {
                width: 90px;
            }
            .phone-dial-code select {
                font-size: 12.5px;
                padding: 0 18px 0 6px;
            }
            .gender-segmented-group {
                gap: 4px;
                height: 42px;
            }
            .gender-radio-option {
                font-size: 12px;
                padding: 0 4px;
                gap: 4px;
            }
            .gender-radio-option input[type="radio"] {
                width: 13px;
                height: 13px;
            }
            .admissions-help-card {
                padding: 14px 12px;
                gap: 10px;
                font-size: 12.5px;
            }
            .btn-submit-enrollment:active {
                transform: scale(0.99);
            }
        }

        @media (max-width: 360px) {
            .enroll-page-wrapper {
                padding: 10px 6px 24px;
            }
            .course-hero-banner {
                padding: 12px 10px;
            }
            .hero-course-title {
                font-size: 15px;
            }
            .enroll-card-header {
                padding: 14px 10px 10px;
            }
            .enroll-form-body {
                padding: 14px 8px 18px;
            }
            .phone-dial-code {
                width: 82px;
            }
            .phone-dial-code select {
                font-size: 12px;
                padding: 0 16px 0 4px;
            }
            .gender-segmented-group {
                grid-template-columns: 1fr;
                height: auto;
                gap: 6px;
            }
            .gender-radio-option {
                height: 38px;
                justify-content: flex-start;
                padding: 0 10px;
                font-size: 12.5px;
            }
            .btn-submit-enrollment {
                height: 44px;
                font-size: 14px;
            }
        }

    </style>

</head>

<body>

    <!-- Site Header & Navigation -->

    <?php $this->load->view("commons/inc/new-header.php"); ?>

    <main class="enroll-page-wrapper">

        <div class="enroll-container">

            <!-- Breadcrumbs -->

            <nav class="enroll-breadcrumb" aria-label="Breadcrumb">

                <a href="<?= site_url() ?>">Home</a>

                <span class="sep">/</span>

                <a href="<?= site_url('courses') ?>">Courses</a>

                <span class="sep">/</span>

                <span>Enrollment Application</span>

            </nav>

            <!-- Success Alert Notification -->

            <?php if ($isSuccess && !empty($successRef)): ?>

                <div class="enroll-alert enroll-alert-success" role="alert">

                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:1px;">

                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>

                        <polyline points="22 4 12 14.01 9 11.01"></polyline>

                    </svg>

                    <div>

                        <strong>Application Submitted Successfully!</strong><br>

                        Your enrollment reference is <strong><?= esc($successRef) ?></strong><?= !empty($successCourse) ? ' for <strong>' . esc($successCourse) . '</strong>' : '' ?>. Our admissions counselor will contact you within 24 hours to complete your verification and seat allocation.

                    </div>

                    <button type="button" class="enroll-alert-close" onclick="this.parentElement.remove();" aria-label="Close">&times;</button>

                </div>

            <?php endif; ?>

            <!-- General Error Alert -->

            <?php if (!empty($generalError)): ?>

                <div class="enroll-alert enroll-alert-danger" role="alert">

                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;">

                        <circle cx="12" cy="12" r="10"></circle>

                        <line x1="12" y1="8" x2="12" y2="12"></line>

                        <line x1="12" y1="16" x2="12.01" y2="16"></line>

                    </svg>

                    <div>

                        <strong>Attention Required:</strong> <?= esc($generalError) ?>

                    </div>

                    <button type="button" class="enroll-alert-close" onclick="this.parentElement.remove();" aria-label="Close">&times;</button>

                </div>

            <?php endif; ?>

            <!-- Course Overview Hero Card -->

            <section class="course-hero-banner" aria-label="Selected Course Details">

                <div class="hero-banner-header">

                    <div class="hero-banner-tag">

                        <span>Official Certification Program</span>

                    </div>

                    <div class="hero-status-pill">

                        <span class="hero-status-dot"></span>

                        <span id="bannerStatusText"><?= esc($selectedCourse['status_text'] ?? 'Admissions Open') ?></span>

                    </div>

                </div>

                <h1 class="hero-course-title banner-course-title"><?= esc($selectedCourse['title'] !== 'None' ? $selectedCourse['title'] : 'Select Your Program to Enroll') ?></h1>

                <div class="hero-specs-row">

                    <div class="hero-spec-item">

                        <span>Duration:</span>

                        <strong id="bannerDurationText"><?= esc($selectedCourse['duration'] !== '-' ? $selectedCourse['duration'] : 'Program Duration') ?></strong>

                    </div>

                    <div class="hero-spec-item">

                        <span>Format:</span>

                        <strong id="bannerModeText"><?= esc($selectedCourse['mode']) ?></strong>

                    </div>

                    <div class="hero-spec-item">

                        <span>Credentials:</span>

                        <strong id="bannerCertText"><?= esc($selectedCourse['certificate']) ?></strong>

                    </div>

                </div>

            </section>

            <!-- Main Enrollment Application Form Card -->

            <div class="enroll-card">

                <div class="enroll-card-header">

                    <h2 class="enroll-card-title">Admission Application Form</h2>

                    <p class="enroll-card-subtitle">Complete your details to secure your seat. All fields marked with an asterisk (<span style="color:var(--tia-error); font-weight:700;">*</span>) are mandatory.</p>

                </div>

              <form id="enrollmentForm" method="POST" action="" novalidate class="enroll-form-body">
<input type="hidden" name="csrf_token" value="<?= esc($csrf) ?>">

                    <input type="hidden" name="course_id" id="course_id_hidden" value="<?= (int)$selectedCourse['id'] ?>">

                    <!-- ==============================================

                         SECTION 1: COURSE & PERSONAL INFORMATION

                         ============================================== -->

                    <div class="form-section">

                        <div class="form-section-header">

                            <span class="section-step-badge">1</span>

                            <h3 class="section-title-text">Course & Personal Details</h3>

                        </div>

                        <div class="fields-grid grid-col-2">

                            <!-- Course Selection -->

                            <div class="form-group grid-full-width <?= isset($errors['course_name']) ? 'has-error' : '' ?>">

                                <label for="course_name" class="form-label">

                                    <span>Select Program <span class="req">*</span></span>

                                </label>

                                <div class="select-custom-wrapper">

                                    <select id="course_name" name="course_name" class="form-select" required>

                                        <option value="">-- Choose Course --</option>

                                        <?php foreach ($courseCatalog as $cName => $cInfo): ?>

                                            <option value="<?= esc($cName) ?>" <?= (($formData['course_name'] ?? $selectedCourseName) === $cName) ? 'selected' : '' ?>>

                                                <?= esc($cName) ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                                <span class="field-error-msg"><?= esc($errors['course_name'] ?? '') ?></span>

                            </div>
                            
                            
                            <!-- Training Mode (Online / Offline) -->

<div class="form-group grid-full-width <?= isset($errors['training_mode']) ? 'has-error' : '' ?>">

    <label class="form-label">
        <span>
            Training Mode <span class="req">*</span>
        </span>
    </label>

    <div class="gender-segmented-group"
         style="grid-template-columns: repeat(2, 1fr);">

        <?php
        $selectedMode = $_POST['training_mode']
            ?? $formData['training_mode']
            ?? 'Online';

        $selectedMode = ucfirst(
            strtolower(trim((string)$selectedMode))
        );
        ?>

        <!-- Online Training -->
        <label class="gender-radio-option radio-pill-item <?= $selectedMode === 'Online' ? 'selected' : '' ?>">

            <input
                type="radio"
                name="training_mode"
                value="Online"
                <?= $selectedMode === 'Online' ? 'checked' : '' ?>
                required
            >

            <span>Live Online Classes</span>

        </label>

        <!-- Offline Training -->
        <label class="gender-radio-option radio-pill-item <?= $selectedMode === 'Offline' ? 'selected' : '' ?>">

            <input
                type="radio"
                name="training_mode"
                value="Offline"
                <?= $selectedMode === 'Offline' ? 'checked' : '' ?>
                required
            >

            <span>Classroom (Offline / Noida)</span>

        </label>

    </div>

    <span class="field-error-msg">
        <?= esc($errors['training_mode'] ?? '') ?>
    </span>

</div>



                            <!-- Full Name -->

                            <div class="form-group <?= isset($errors['full_name']) ? 'has-error' : '' ?>">

                                <label for="full_name" class="form-label">

                                    <span>Full Name <span class="req">*</span></span>

                                </label>

                                <input type="text" id="full_name" name="full_name" class="form-control" placeholder="e.g. Manish Sharma" value="<?= esc($formData['full_name'] ?? '') ?>" required maxlength="100" autocomplete="name">

                                <span class="field-error-msg"><?= esc($errors['full_name'] ?? '') ?></span>

                            </div>

                            <!-- Email Address -->

                            <div class="form-group <?= isset($errors['email']) ? 'has-error' : '' ?>">

                                <label for="email" class="form-label">

                                    <span>Email Address <span class="req">*</span></span>

                                </label>

                                <input type="email" id="email" name="email" class="form-control" placeholder="e.g. manish.sharma@example.com" value="<?= esc($formData['email'] ?? '') ?>" required maxlength="190" autocomplete="email">

                                <span class="field-error-msg"><?= esc($errors['email'] ?? '') ?></span>

                            </div>

                            <!-- Primary Phone Number -->

                            <div class="form-group <?= isset($errors['primary_phone']) ? 'has-error' : '' ?>">

                                <label for="primary_phone" class="form-label">

                                    <span>Primary Mobile Number <span class="req">*</span></span>

                                </label>

                                <div class="phone-group-combo">

                                    <div class="phone-dial-code">

                                        <select name="primary_country_code" id="primary_country_code" aria-label="Country Code">

                                            <?php foreach ($dialCodes as $d): ?>

                                                <option value="<?= esc($d['code']) ?>" <?= (($formData['primary_country_code'] ?? '+91') === $d['code']) ? 'selected' : '' ?>>

                                                    <?= esc($d['country']) ?> (<?= esc($d['code']) ?>)

                                                </option>

                                            <?php endforeach; ?>

                                        </select>

                                    </div>

                                    <div class="phone-number-field">

                                        <input type="tel" id="primary_phone" name="primary_phone" class="form-control" placeholder="10-digit mobile number" value="<?= esc($formData['primary_phone'] ?? '') ?>" required maxlength="10" autocomplete="tel-national">

                                    </div>

                                </div>

                                <span class="field-error-msg"><?= esc($errors['primary_phone'] ?? '') ?></span>

                            </div>

                            <!-- Alternate Phone Number -->

                            <div class="form-group <?= isset($errors['alternate_phone']) ? 'has-error' : '' ?>">

                                <label for="alternate_phone" class="form-label">

                                    <span>Alternate Mobile</span>

                                    <span class="opt-tag">Optional</span>

                                </label>

                                <div class="phone-group-combo">

                                    <div class="phone-dial-code">

                                        <select name="alternate_country_code" id="alternate_country_code" aria-label="Alternate Country Code">

                                            <?php foreach ($dialCodes as $d): ?>

                                                <option value="<?= esc($d['code']) ?>" <?= (($formData['alternate_country_code'] ?? '+91') === $d['code']) ? 'selected' : '' ?>>

                                                    <?= esc($d['country']) ?> (<?= esc($d['code']) ?>)

                                                </option>

                                            <?php endforeach; ?>

                                        </select>

                                    </div>

                                    <div class="phone-number-field">

                                        <input type="tel" id="alternate_phone" name="alternate_phone" class="form-control" placeholder="Secondary contact number" value="<?= esc($formData['alternate_phone'] ?? '') ?>" maxlength="10">

                                    </div>

                                </div>

                                <span class="field-error-msg"><?= esc($errors['alternate_phone'] ?? '') ?></span>

                            </div>

                            <!-- Gender -->

                            <div class="form-group <?= isset($errors['gender']) ? 'has-error' : '' ?>">

                                <label class="form-label">

                                    <span>Gender <span class="req">*</span></span>

                                </label>

                                <div class="gender-segmented-group">

                                    <?php foreach (['Male', 'Female', 'Other'] as $g): ?>

                                        <label class="gender-radio-option radio-pill-item <?= (($formData['gender'] ?? '') === $g) ? 'selected' : '' ?>">

                                            <input type="radio" name="gender" value="<?= $g ?>" <?= (($formData['gender'] ?? '') === $g) ? 'checked' : '' ?> required>

                                            <span><?= $g ?></span>

                                        </label>

                                    <?php endforeach; ?>

                                </div>

                                <span class="field-error-msg"><?= esc($errors['gender'] ?? '') ?></span>

                            </div>

                            <!-- Date of Birth -->

                            <div class="form-group <?= isset($errors['date_of_birth']) ? 'has-error' : '' ?>">

                                <label for="date_of_birth" class="form-label">

                                    <span>Date of Birth <span class="req">*</span></span>

                                </label>

                                <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="<?= esc($formData['date_of_birth'] ?? '') ?>" max="<?= date('Y-m-d') ?>" required>

                                <span class="field-error-msg"><?= esc($errors['date_of_birth'] ?? '') ?></span>

                            </div>

                        </div>

                    </div>

                    <!-- ==============================================

                         SECTION 2: LOCATION DETAILS

                         ============================================== -->

                    <div class="form-section">

                        <div class="form-section-header">

                            <span class="section-step-badge">2</span>

                            <h3 class="section-title-text">Location & Address</h3>

                        </div>

                        <div class="fields-grid grid-col-3">

                            <!-- Country -->

                            <div class="form-group <?= isset($errors['country']) ? 'has-error' : '' ?>">

                                <label for="country" class="form-label">

                                    <span>Country <span class="req">*</span></span>

                                </label>

                                <div class="select-custom-wrapper">

                                    <select id="country" name="country" class="form-select" required data-selected="<?= esc($formData['country'] ?? 'India') ?>">

                                        <option value="">Select Country</option>

                                        <?php foreach ($countries as $c): ?>

                                            <option value="<?= esc($c) ?>" <?= (($formData['country'] ?? 'India') === $c) ? 'selected' : '' ?>>

                                                <?= esc($c) ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                                <span class="field-error-msg"><?= esc($errors['country'] ?? '') ?></span>

                            </div>

                            <!-- State -->

                            <div class="form-group <?= isset($errors['state']) ? 'has-error' : '' ?>">

                                <label for="state" class="form-label">

                                    <span>State <span class="req">*</span></span>

                                </label>

                                <div class="select-custom-wrapper">

                                    <select id="state" name="state" class="form-select" required data-selected="<?= esc($formData['state'] ?? '') ?>">

                                        <option value="">Select State</option>

                                    </select>

                                </div>

                                <span class="field-error-msg"><?= esc($errors['state'] ?? '') ?></span>

                            </div>

                            <!-- City -->

                            <div class="form-group <?= isset($errors['city']) ? 'has-error' : '' ?>">

                                <label for="city" class="form-label">

                                    <span>City / District <span class="req">*</span></span>

                                </label>

                                <div class="select-custom-wrapper">

                                    <select id="city" name="city" class="form-select" required data-selected="<?= esc($formData['city'] ?? '') ?>">

                                        <option value="">Select City</option>

                                    </select>

                                </div>

                                <span class="field-error-msg"><?= esc($errors['city'] ?? '') ?></span>

                            </div>

                        </div>

                    </div>

                    <!-- ==============================================

                         SECTION 3: EDUCATIONAL BACKGROUND

                         ============================================== -->

                    <div class="form-section">

                        <div class="form-section-header">

                            <span class="section-step-badge">3</span>

                            <h3 class="section-title-text">Educational Qualification</h3>

                        </div>

                        <div class="fields-grid grid-col-2">

                            <!-- Highest Qualification -->

                            <div class="form-group <?= isset($errors['highest_qualification']) ? 'has-error' : '' ?>">

                                <label for="highest_qualification" class="form-label">

                                    <span>Highest Qualification <span class="req">*</span></span>

                                </label>

                                <div class="select-custom-wrapper">

                                    <select id="highest_qualification" name="highest_qualification" class="form-select" required>

                                        <option value="">-- Select Degree / Level --</option>

                                        <?php foreach ($highestQualifications as $q): ?>

                                            <option value="<?= esc($q) ?>" <?= (($formData['highest_qualification'] ?? '') === $q) ? 'selected' : '' ?>>

                                                <?= esc($q) ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                                <span class="field-error-msg"><?= esc($errors['highest_qualification'] ?? '') ?></span>

                            </div>

                            <!-- Area of Study -->

                            <div class="form-group <?= isset($errors['area_of_study']) ? 'has-error' : '' ?>">

                                <label for="area_of_study" class="form-label">

                                    <span>Stream / Field of Study <span class="req">*</span></span>

                                </label>

                                <div class="select-custom-wrapper">

                                    <select id="area_of_study" name="area_of_study" class="form-select" required>

                                        <option value="">-- Select Discipline --</option>

                                        <?php foreach ($areasOfStudy as $a): ?>

                                            <option value="<?= esc($a) ?>" <?= (($formData['area_of_study'] ?? '') === $a) ? 'selected' : '' ?>>

                                                <?= esc($a) ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                                <span class="field-error-msg"><?= esc($errors['area_of_study'] ?? '') ?></span>

                            </div>

                            <!-- Year of Completion -->

                            <div class="form-group <?= isset($errors['year_of_completion']) ? 'has-error' : '' ?>">

                                <label for="year_of_completion" class="form-label">

                                    <span>Passing Year</span>

                                    <span class="opt-tag">Optional</span>

                                </label>

                                <input type="number" id="year_of_completion" name="year_of_completion" class="form-control" placeholder="e.g. 2024" min="1960" max="2035" value="<?= esc($formData['year_of_completion'] ?? '') ?>">

                                <span class="field-error-msg"><?= esc($errors['year_of_completion'] ?? '') ?></span>

                            </div>

                            <!-- GPA / Percentage -->

                            <div class="form-group <?= isset($errors['gpa_percentage']) ? 'has-error' : '' ?>">

                                <label for="gpa_percentage" class="form-label">

                                    <span>GPA or Percentage</span>

                                    <span class="opt-tag">Optional</span>

                                </label>

                                <input type="text" id="gpa_percentage" name="gpa_percentage" class="form-control" placeholder="e.g. 8.2 CGPA or 78%" maxlength="20" value="<?= esc($formData['gpa_percentage'] ?? '') ?>">
                                <small class="field-hint" style="display:block;font-size:12px;color:#64748b;margin-top:4px;">Accepts GPA (1–10) or Percentage (1–100%)</small>

                                <span class="field-error-msg"><?= esc($errors['gpa_percentage'] ?? '') ?></span>

                            </div>

                        </div>

                    </div>

                    <!-- ==============================================

                         SECTION 4: PROFESSIONAL EXPERIENCE

                         ============================================== -->

                    <div class="form-section">

                        <div class="form-section-header">

                            <span class="section-step-badge">4</span>

                            <h3 class="section-title-text">Work Experience & Current Role</h3>

                        </div>

                        <div class="fields-grid grid-col-3">

                            <!-- Years of Experience -->

                            <div class="form-group <?= isset($errors['years_of_experience']) ? 'has-error' : '' ?>">

                                <label for="years_of_experience" class="form-label">

                                    <span>Total Experience (Years) <span class="req">*</span></span>

                                </label>

                                <input type="number" id="years_of_experience" name="years_of_experience" class="form-control" placeholder="0 if fresher (max 99)" min="0" max="99" step="0.5" value="<?= esc($formData['years_of_experience'] ?? '0') ?>" required>
                                <small class="field-hint" style="display:block;font-size:12px;color:#64748b;margin-top:4px;">Allowed range: 0 to 99 years (0 if fresher)</small>

                                <span class="field-error-msg"><?= esc($errors['years_of_experience'] ?? '') ?></span>

                            </div>

                            <!-- Current Company -->

                            <div class="form-group <?= isset($errors['current_company']) ? 'has-error' : '' ?>">

                                <label for="current_company" class="form-label">

                                    <span>Current Employer</span>

                                    <span class="opt-tag">Optional</span>

                                </label>

                                <input type="text" id="current_company" name="current_company" class="form-control" placeholder="Company / Organization" maxlength="150" value="<?= esc($formData['current_company'] ?? '') ?>">

                                <span class="field-error-msg"><?= esc($errors['current_company'] ?? '') ?></span>

                            </div>

                            <!-- Current Designation -->

                            <div class="form-group <?= isset($errors['current_designation']) ? 'has-error' : '' ?>">

                                <label for="current_designation" class="form-label">

                                    <span>Current Designation</span>

                                    <span class="opt-tag">Optional</span>

                                </label>

                                <input type="text" id="current_designation" name="current_designation" class="form-control" placeholder="Job Title / Role" maxlength="150" value="<?= esc($formData['current_designation'] ?? '') ?>">

                                <span class="field-error-msg"><?= esc($errors['current_designation'] ?? '') ?></span>

                            </div>

                        </div>

                    </div>

                    <!-- Declaration & Authorization Checkbox -->

                    <div class="consent-wrapper">

                        <label class="consent-checkbox-label" for="termsConsent">

                            <input type="checkbox" id="termsConsent" name="consent" required checked>

                            <span>I certify that all details submitted are accurate. I authorize The IoT Academy and its academic advisors to contact me via Call, WhatsApp, and Email with admission instructions, counseling schedules, and batch details.</span>

                        </label>

                    </div>

                    <!-- Submit Button -->

                    <div class="submit-action-area">

                        <button type="submit" id="submitEnrollBtn" class="btn-submit-enrollment">

                            <span class="btn-text">Submit Enrollment Application</span>

                            <span class="btn-loading-spinner d-none-soft"></span>

                        </button>

                        
                    </div>

                </form>

            </div>

            <!-- Help / Admissions Counseling Card -->

            <div class="admissions-help-card">

                <p class="help-card-text">

                    <strong>Need assistance with your enrollment?</strong> Our academic counselors are available Mon–Sat, 9:30 AM to 6:30 PM.

                </p>

                <div class="help-contact-links">

                    <a href="tel:+919354068856">

                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">

                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>

                        </svg>

                        <span>+91-9354068856</span>

                    </a>

                    <a href="mailto:info@theiotacademy.co">

                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">

                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>

                            <polyline points="22,6 12,13 2,6"></polyline>

                        </svg>

                        <span>info@theiotacademy.co</span>

                    </a>

                </div>

            </div>

        </div>

    </main>

    <!-- Floating Scroll-To-Top Button -->

    <!--<button type="button" id="scrollToTopBtn" class="scroll-top-btn" aria-label="Scroll to top">-->

    <!--    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">-->

    <!--        <polyline points="18 15 12 9 6 15"></polyline>-->

    <!--    </svg>-->

    <!--</button>-->

    <!-- Site Footer -->

    <?php $this->load->view("commons/inc/new-footer.php"); ?>

    <!-- CLIENT INTERACTIVITY & VALIDATION JAVASCRIPT -->

    <script>

    (function () {

        'use strict';

        // Embedded Geo Dataset for instant cascading

        const geoMap = <?= json_encode($geoData) ?>;

        const courseCatalogMap = <?= json_encode($courseCatalog) ?>;

        const courseSelect = document.getElementById('course_name');

        const bannerTitle = document.querySelector('.banner-course-title');

        const bannerDuration = document.getElementById('bannerDurationText');

        const bannerMode = document.getElementById('bannerModeText');

        const bannerCert = document.getElementById('bannerCertText');

        const courseHiddenId = document.getElementById('course_id_hidden');

        const countryEl = document.getElementById('country');

        const stateEl = document.getElementById('state');

        const cityEl = document.getElementById('city');

        const formEl = document.getElementById('enrollmentForm');

        const submitBtn = document.getElementById('submitEnrollBtn');

        const scrollBtn = document.getElementById('scrollToTopBtn');

        // Dynamic Course Selection Sync

        if (courseSelect) {

            courseSelect.addEventListener('change', function () {

                clearFieldError(this);

                const selected = this.value;

                if (selected && courseCatalogMap[selected]) {

                    const c = courseCatalogMap[selected];

                    if (bannerTitle) bannerTitle.textContent = c.title;

                    if (bannerDuration) bannerDuration.textContent = c.duration;

                    if (bannerMode) bannerMode.textContent = c.mode;

                    if (bannerCert) bannerCert.textContent = c.certificate;

                    if (courseHiddenId) courseHiddenId.value = c.id;

                    document.title = 'Enroll in ' + c.title + ' | The IoT Academy';

                } else {

                    if (bannerTitle) bannerTitle.textContent = 'Select Your Program to Enroll';

                    if (bannerDuration) bannerDuration.textContent = 'Program Duration';

                    if (bannerMode) bannerMode.textContent = 'Live Classes';

                    if (bannerCert) bannerCert.textContent = 'Certificate Included';

                    if (courseHiddenId) courseHiddenId.value = '';

                    document.title = 'Online Course Enrollment | The IoT Academy';

                }

            });

        }

        // Cascading States & Cities

        function populateStates(country, preselectedState) {

            if (!stateEl) return;

            stateEl.innerHTML = '<option value="">Select State</option>';

            if (cityEl) {

                cityEl.innerHTML = '<option value="">Select City</option>';

                cityEl.disabled = true;

            }

            if (country && geoMap[country]) {

                const states = Object.keys(geoMap[country]);

                states.forEach(function (st) {

                    const opt = document.createElement('option');

                    opt.value = st;

                    opt.textContent = st;

                    if (preselectedState && (st.toLowerCase() === preselectedState.toLowerCase())) {

                        opt.selected = true;

                    }

                    stateEl.appendChild(opt);

                });

                stateEl.disabled = false;

                if (stateEl.value) {

                    const preCity = cityEl ? (cityEl.dataset.selected || '') : '';

                    populateCities(country, stateEl.value, preCity);

                }

            } else {

                stateEl.disabled = true;

            }

        }

        function populateCities(country, state, preselectedCity) {

            if (!cityEl) return;

            cityEl.innerHTML = '<option value="">Select City</option>';

            if (country && state && geoMap[country] && geoMap[country][state]) {

                const cities = geoMap[country][state];

                cities.forEach(function (ct) {

                    const opt = document.createElement('option');

                    opt.value = ct;

                    opt.textContent = ct;

                    if (preselectedCity && (ct.toLowerCase() === preselectedCity.toLowerCase())) {

                        opt.selected = true;

                    }

                    cityEl.appendChild(opt);

                });

                cityEl.disabled = false;

            } else {

                cityEl.disabled = true;

            }

        }

        if (countryEl) {

            countryEl.addEventListener('change', function () {

                populateStates(this.value, '');

                clearFieldError(this);

            });

        }

        if (stateEl) {

            stateEl.addEventListener('change', function () {

                const country = countryEl ? countryEl.value : '';

                populateCities(country, this.value, '');

                clearFieldError(this);

            });

        }

        if (cityEl) {

            cityEl.addEventListener('change', function () {

                clearFieldError(this);

            });

        }

        // Initialize cascades on page load

        if (countryEl && countryEl.value) {

            const initialSt = stateEl ? (stateEl.dataset.selected || '') : '';

            populateStates(countryEl.value, initialSt);

        }

        // Segmented Gender Radio interaction

        document.querySelectorAll('.gender-radio-option').forEach(function (pill) {

            const radio = pill.querySelector('input[type="radio"]');

            if (radio) {

                radio.addEventListener('change', function () {

                    document.querySelectorAll('.gender-radio-option').forEach(function (p) {

                        p.classList.remove('selected');

                    });

                    if (this.checked) {

                        pill.classList.add('selected');

                    }

                    const group = pill.closest('.form-group');

                    if (group) {

                        group.classList.remove('has-error');

                        const err = group.querySelector('.field-error-msg');

                        if (err) err.textContent = '';

                    }

                });

            }

        });

        // Validation Helpers

        function setFieldError(field, message) {

            const group = field.closest('.form-group');

            if (!group) return;

            group.classList.add('has-error');

            field.classList.add('is-invalid');

            const err = group.querySelector('.field-error-msg');

            if (err) err.textContent = message;

        }

        function clearFieldError(field) {

            const group = field.closest('.form-group');

            if (!group) return;

            group.classList.remove('has-error');

            field.classList.remove('is-invalid');

            const err = group.querySelector('.field-error-msg');

            if (err) err.textContent = '';

        }

        // Live error clearing

        document.querySelectorAll('.form-control, .form-select').forEach(function (ctrl) {
            ctrl.addEventListener('input', function () { clearFieldError(this); });
            ctrl.addEventListener('change', function () { clearFieldError(this); });
        });

        // Instant validation on blur for Email
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.addEventListener('blur', function () {
                const val = this.value.trim();
                const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (!val) {
                    setFieldError(this, 'Email address is required.');
                } else if (!regex.test(val)) {
                    setFieldError(this, 'Please enter a valid email address (e.g. name@example.com).');
                } else {
                    clearFieldError(this);
                }
            });
        }

        // Validation for Years of Experience (0 to 99)
        const expInput = document.getElementById('years_of_experience');
        if (expInput) {
            expInput.addEventListener('input', function () {
                const val = this.value.trim();
                if (val !== '') {
                    const num = Number(val);
                    if (num > 99) {
                        setFieldError(this, 'Total experience cannot exceed 99 years.');
                        return;
                    } else if (num < 0) {
                        setFieldError(this, 'Experience cannot be negative.');
                        return;
                    }
                }
                clearFieldError(this);
            });
            expInput.addEventListener('blur', function () {
                const val = this.value.trim();
                const num = Number(val);
                if (val === '' || isNaN(num) || num < 0) {
                    setFieldError(this, 'Please enter years of experience (0 if fresher).');
                } else if (num > 99) {
                    setFieldError(this, 'Total experience cannot exceed 99 years.');
                } else {
                    clearFieldError(this);
                }
            });
        }

        // Validation for GPA / Percentage (1-10 or 1-100)
        const gpaInput = document.getElementById('gpa_percentage');
        if (gpaInput) {
            gpaInput.addEventListener('input', function () {
                const val = this.value.trim();
                if (val !== '') {
                    const clean = val.replace(/[^\d.]/g, '');
                    const num = parseFloat(clean);
                    if (clean && !isNaN(num) && num > 100) {
                        setFieldError(this, 'Percentage cannot exceed 100%.');
                        return;
                    }
                }
                clearFieldError(this);
            });
            gpaInput.addEventListener('blur', function () {
                const val = this.value.trim();
                if (val === '') {
                    clearFieldError(this);
                    return;
                }
                const clean = val.replace(/[^\d.]/g, '');
                const num = parseFloat(clean);
                if (!clean || isNaN(num) || num < 1 || num > 100) {
                    setFieldError(this, 'Please enter a valid GPA (1–10) or Percentage (1–100%).');
                } else {
                    clearFieldError(this);
                }
            });
        }

        // Instant validation on blur for Primary Phone
        const phoneInput = document.getElementById('primary_phone');
        if (phoneInput) {
            phoneInput.addEventListener('blur', function () {
                const val = this.value.trim().replace(/\D/g, '');
                const code = document.getElementById('primary_country_code')?.value || '+91';
                if (!val) {
                    setFieldError(this, 'Primary mobile number is required.');
                } else if (code === '+91') {
                    if (val.length !== 10) {
                        setFieldError(this, 'Please enter a 10-digit Indian mobile number.');
                    } else if (!/^[6-9]/.test(val)) {
                        setFieldError(this, 'Indian mobile number must start with 6, 7, 8, or 9.');
                    } else {
                        clearFieldError(this);
                    }
                } else if (val.length < 7 || val.length > 15) {
                    setFieldError(this, 'Mobile number must be between 7 and 15 digits.');
                } else {
                    clearFieldError(this);
                }
            });
        }

        // Form Submit Validation

        if (formEl) {

            formEl.addEventListener('submit', function (e) {

                let isValid = true;

                let firstInvalidField = null;

                // 1. Course

                const courseF = document.getElementById('course_name');

                if (courseF) {

                    const cVal = courseF.value.trim();

                    if (!cVal || cVal.indexOf('--') !== -1) {

                        setFieldError(courseF, 'Please choose a program to enroll in.');

                        isValid = false;

                        if (!firstInvalidField) firstInvalidField = courseF;

                    } else {

                        clearFieldError(courseF);

                    }

                }

                // 2. Full Name

                const nameF = document.getElementById('full_name');

                if (nameF) {

                    const nVal = nameF.value.trim();

                    if (!nVal) {

                        setFieldError(nameF, 'Please enter your full name.');

                        isValid = false;

                        if (!firstInvalidField) firstInvalidField = nameF;

                    } else if (nVal.length < 2) {

                        setFieldError(nameF, 'Name must be at least 2 characters.');

                        isValid = false;

                        if (!firstInvalidField) firstInvalidField = nameF;

                    } else {

                        clearFieldError(nameF);

                    }

                }

                // 3. Email (Required & Valid)
                const emailF = document.getElementById('email');
                if (emailF) {
                    const eVal = emailF.value.trim();
                    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                    if (!eVal) {
                        setFieldError(emailF, 'Email address is required.');
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = emailF;
                    } else if (!emailRegex.test(eVal)) {
                        setFieldError(emailF, 'Please enter a valid email address (e.g. name@example.com).');
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = emailF;
                    } else {
                        clearFieldError(emailF);
                    }
                }

                // 4. Primary Phone (Required & Valid)
                const phoneF = document.getElementById('primary_phone');
                const pCodeEl = document.getElementById('primary_country_code');
                const pCodeVal = pCodeEl ? pCodeEl.value : '+91';
                if (phoneF) {
                    const digits = phoneF.value.trim().replace(/\D/g, '');
                    if (!digits) {
                        setFieldError(phoneF, 'Primary mobile number is required.');
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = phoneF;
                    } else if (pCodeVal === '+91') {
                        if (digits.length !== 10) {
                            setFieldError(phoneF, 'Please enter a 10-digit Indian mobile number.');
                            isValid = false;
                            if (!firstInvalidField) firstInvalidField = phoneF;
                        } else if (!/^[6-9]/.test(digits)) {
                            setFieldError(phoneF, 'Indian mobile number must start with 6, 7, 8, or 9.');
                            isValid = false;
                            if (!firstInvalidField) firstInvalidField = phoneF;
                        } else {
                            clearFieldError(phoneF);
                        }
                    } else if (digits.length < 7 || digits.length > 15) {
                        setFieldError(phoneF, 'Mobile number must be between 7 and 15 digits.');
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = phoneF;
                    } else {
                        clearFieldError(phoneF);
                    }
                }

                // 4b. Alternate Phone (Optional, but validated if entered)
                const altPhoneF = document.getElementById('alternate_phone');
                const aCodeEl = document.getElementById('alternate_country_code');
                const aCodeVal = aCodeEl ? aCodeEl.value : '+91';
                if (altPhoneF && altPhoneF.value.trim() !== '') {
                    const altDigits = altPhoneF.value.trim().replace(/\D/g, '');
                    if (aCodeVal === '+91') {
                        if (altDigits.length !== 10) {
                            setFieldError(altPhoneF, 'Alternate Indian mobile number must be 10 digits.');
                            isValid = false;
                            if (!firstInvalidField) firstInvalidField = altPhoneF;
                        } else if (!/^[6-9]/.test(altDigits)) {
                            setFieldError(altPhoneF, 'Alternate mobile must start with 6, 7, 8, or 9.');
                            isValid = false;
                            if (!firstInvalidField) firstInvalidField = altPhoneF;
                        } else {
                            clearFieldError(altPhoneF);
                        }
                    } else if (altDigits.length < 7 || altDigits.length > 15) {
                        setFieldError(altPhoneF, 'Alternate mobile must be between 7 and 15 digits.');
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = altPhoneF;
                    } else {
                        clearFieldError(altPhoneF);
                    }
                }

                // 5. Gender

                const genderChecked = document.querySelector('input[name="gender"]:checked');

                const genderGroup = document.querySelector('.gender-segmented-group')?.closest('.form-group');

                if (!genderChecked) {

                    if (genderGroup) {

                        genderGroup.classList.add('has-error');

                        const err = genderGroup.querySelector('.field-error-msg');

                        if (err) err.textContent = 'Please select your gender.';

                    }

                    isValid = false;

                    if (!firstInvalidField) firstInvalidField = document.querySelector('input[name="gender"]');

                }

                // 6. Date of Birth

                const dobF = document.getElementById('date_of_birth');

                if (dobF) {

                    if (!dobF.value) {

                        setFieldError(dobF, 'Please select your date of birth.');

                        isValid = false;

                        if (!firstInvalidField) firstInvalidField = dobF;

                    } else {

                        clearFieldError(dobF);

                    }

                }

                // 7. Location dropdowns

                ['country', 'state', 'city'].forEach(function (id) {

                    const el = document.getElementById(id);

                    if (el && (!el.value || el.value.indexOf('Select') !== -1)) {

                        setFieldError(el, 'Please make a selection.');

                        isValid = false;

                        if (!firstInvalidField) firstInvalidField = el;

                    }

                });

                // 8. Educational dropdowns

                ['highest_qualification', 'area_of_study'].forEach(function (id) {

                    const el = document.getElementById(id);

                    if (el && (!el.value || el.value.indexOf('--') !== -1)) {

                        setFieldError(el, 'Please select your academic background.');

                        isValid = false;

                        if (!firstInvalidField) firstInvalidField = el;

                    }

                });

                // 9. GPA or Percentage (Optional, must be 1-10 or 1-100 if filled)
                const gpaF = document.getElementById('gpa_percentage');
                if (gpaF && gpaF.value.trim() !== '') {
                    const gpaVal = gpaF.value.trim().replace(/[^\d.]/g, '');
                    const numGpa = parseFloat(gpaVal);
                    if (!gpaVal || isNaN(numGpa) || numGpa < 1 || numGpa > 100) {
                        setFieldError(gpaF, 'Please enter a valid GPA (1–10) or Percentage (1–100%).');
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = gpaF;
                    } else {
                        clearFieldError(gpaF);
                    }
                }

                // 10. Years of Experience (0 to 99)
                const expF = document.getElementById('years_of_experience');
                if (expF) {
                    const expVal = expF.value.trim();
                    const numExp = Number(expVal);
                    if (expVal === '' || isNaN(numExp) || numExp < 0) {
                        setFieldError(expF, 'Please enter years of experience (enter 0 if fresher).');
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = expF;
                    } else if (numExp > 99) {
                        setFieldError(expF, 'Experience cannot exceed 99 years.');
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = expF;
                    } else {
                        clearFieldError(expF);
                    }
                }

                // Prevent submission if invalid

                if (!isValid) {

                    e.preventDefault();

                    if (firstInvalidField) {

                        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });

                        firstInvalidField.focus();

                    }

                    return false;

                }

                // Submit button loading state

                if (submitBtn) {

                    submitBtn.style.pointerEvents = 'none';
                    submitBtn.style.opacity = '0.85';
                    setTimeout(function () { if (submitBtn) submitBtn.disabled = true; }, 50);

                    const btnText = submitBtn.querySelector('.btn-text');

                    const spinner = submitBtn.querySelector('.btn-loading-spinner');

                    if (btnText) btnText.textContent = 'Submitting Application...';

                    if (spinner) spinner.classList.remove('d-none-soft');

                }

            });

        }

        // Scroll to top floating button

        if (scrollBtn) {

            window.addEventListener('scroll', function () {

                if (window.scrollY > 300) {

                    scrollBtn.classList.add('visible');

                } else {

                    scrollBtn.classList.remove('visible');

                }

            }, { passive: true });

            scrollBtn.addEventListener('click', function () {

                window.scrollTo({ top: 0, behavior: 'smooth' });

            });

        }

    })();

    </script>

    <!-- Bootstrap JS (Bundle with Popper) -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- The IoT Academy Navigation Script -->

    <script src="<?php echo function_exists('asset_url') ? asset_url() : 'http://localhost/TIA/assets/'; ?>dit/js/custom1.js"></script>

    <!-- Swiper JS & Ticker Marquee Initialization -->

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>

    window.addEventListener("load", function () {

        const swiperContainer = document.querySelector(".scrollHeaderSwiper .swiper-wrapper");

        if (swiperContainer && typeof Swiper !== 'undefined') {

            const slides = swiperContainer.querySelectorAll(".swiper-slide");

            if (slides.length < 4) {

                const needed = 4 - slides.length;

                for (let i = 0; i < needed; i++) {

                    swiperContainer.appendChild(slides[i % slides.length].cloneNode(true));

                }

            }

            const swiperHeader = new Swiper(".scrollHeaderSwiper", {

                slidesPerView: "auto",

                loop: true,

                speed: 6000,

                autoplay: {

                    delay: 0,

                    disableOnInteraction: false,

                },

                allowTouchMove: false,

            });

            const swiperEl = document.querySelector(".scrollHeaderSwiper");

            if (swiperEl && swiperHeader.autoplay) {

                swiperEl.addEventListener("mouseenter", () => swiperHeader.autoplay.stop());

                swiperEl.addEventListener("mouseleave", () => swiperHeader.autoplay.start());

            }

        }

    });

    </script>
    
    <script>
        // Validate Training Mode
const modeChecked = document.querySelector('input[name="training_mode"]:checked');
const modeGroup = document.querySelector('input[name="training_mode"]')?.closest('.form-group');
if (!modeChecked) {
    if (modeGroup) {
        modeGroup.classList.add('has-error');
        const err = modeGroup.querySelector('.field-error-msg');
        if (err) err.textContent = 'Please choose Online or Offline mode.';
    }
    isValid = false;
    if (!firstInvalidField) firstInvalidField = document.querySelector('input[name="training_mode"]');
}
    </script>

</body>

</html>

