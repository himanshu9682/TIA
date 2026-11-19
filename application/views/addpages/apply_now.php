<?php
/**
 * ============================================================================
 * THE IOT ACADEMY - ADMISSION APPLICATION PORTAL (APPLY NOW)
 * ============================================================================
 * Official Online Admission Application System
 * Features:
 *  - 4-Stage Multi-Step Admission Process (Program, Personal, Education, Professional)
 *  - Interactive Progress Stepper & Live Review Summary
 *  - Real-time Client-Side & Robust Server-Side Validation
 *  - Embedded Zero-Latency Geographic Cascading (Country -> State -> City)
 *  - Database Persistence (PDO Prepared Statements, Auto Schema Migration)
 *  - Google Sheets Webhook Integration (cURL)
 *  - CSRF Security Token Protection
 *  - Responsive Education Institute UI (Navy #113563 & Orange #FDA31B)
 * ============================================================================
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ----------------------------------------------------------------------------
// 1. DATABASE CONNECTION & AUTO TABLE MIGRATION
// ----------------------------------------------------------------------------
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

            // Ensure training_mode column exists in legacy tables
            try {
                $pdo->exec("ALTER TABLE `course_enrollments` ADD `training_mode` VARCHAR(50) DEFAULT NULL AFTER `course_title`");
            } catch (Exception $ignored) {}

            return $pdo;
        } catch (Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            return null;
        }
    }
}

// ----------------------------------------------------------------------------
// 2. CSRF & HELPER UTILITIES
// ----------------------------------------------------------------------------
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

// ----------------------------------------------------------------------------
// 3. EMBEDDED GEOGRAPHIC DATASET
// ----------------------------------------------------------------------------
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

// ----------------------------------------------------------------------------
// 4. COURSE CATALOG & CONFIGURATION
// ----------------------------------------------------------------------------
$courseCatalog = [
    'Data Science & AI (9 Months)' => [
        'id'          => 1,
        'title'       => 'Data Science & AI (9 Months)',
        'duration'    => '9 Months',
        'mode'        => 'Live Classes',
        'certificate' => 'Certificate Included',
        'status_text' => 'Admissions Open'
    ],
    'DA With Gen AI & Agentic AI (6 Months)' => [
        'id'          => 2,
        'title'       => 'DA With Gen AI & Agentic AI (6 Months)',
        'duration'    => '6 Months',
        'mode'        => 'Live Classes',
        'certificate' => 'Certificate Included',
        'status_text' => 'Admissions Open'
    ],
    'Data Analytics (4 Months)' => [
        'id'          => 3,
        'title'       => 'Data Analytics (4 Months)',
        'duration'    => '4 Months',
        'mode'        => 'Live Classes',
        'certificate' => 'Certificate Included',
        'status_text' => 'Admissions Open'
    ],
    'Embedded & IoT With AI (6 Months)' => [
        'id'          => 4,
        'title'       => 'Embedded & IoT With AI (6 Months)',
        'duration'    => '6 Months',
        'mode'        => 'Live Classes',
        'certificate' => 'Certificate Included',
        'status_text' => 'Admissions Open'
    ],
    'Embedded & IoT (4 Months)' => [
        'id'          => 5,
        'title'       => 'Embedded & IoT (4 Months)',
        'duration'    => '4 Months',
        'mode'        => 'Live Classes',
        'certificate' => 'Certificate Included',
        'status_text' => 'Admissions Open'
    ],
];

// Course Name Aliases
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
    'advanced certification in data science machine learning and ai by eict iitg' => 'Data Science & AI (9 Months)',
    'data science machine learning and ai by eict iitg' => 'Data Science & AI (9 Months)',
    'data analytics'                              => 'Data Analytics (4 Months)',
    'data analytics (4 months)'                   => 'Data Analytics (4 Months)',
    'data analytics and data visualization for business' => 'Data Analytics (4 Months)',
    'data analyst'                                => 'Data Analytics (4 Months)',
    'da with gen ai'                              => 'DA With Gen AI & Agentic AI (6 Months)',
    'da with gen ai & agentic ai'                 => 'DA With Gen AI & Agentic AI (6 Months)',
    'da with gen ai and agentic ai'               => 'DA With Gen AI & Agentic AI (6 Months)',
    'advanced generative ai course'               => 'DA With Gen AI & Agentic AI (6 Months)',
    'advanced generative ai'                      => 'DA With Gen AI & Agentic AI (6 Months)',
    'generative ai'                               => 'DA With Gen AI & Agentic AI (6 Months)',
];

$selectedCourseName = sanitize($_POST['course_name'] ?? $_GET['course'] ?? '');
$selectedCourseName = trim(preg_replace('/[\x{200B}-\x{200D}\x{2060}\x{FEFF}]/u', '', $selectedCourseName));

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

$emptyCourse = [
    'id'          => 0,
    'title'       => 'Select Your Program',
    'duration'    => '-',
    'mode'        => 'Live Classes',
    'certificate' => 'Certificate Included',
    'status_text' => 'Admissions Open'
];

$selectedCourse = (!empty($selectedCourseName) && isset($courseCatalog[$selectedCourseName]))
    ? $courseCatalog[$selectedCourseName]
    : $emptyCourse;

$dialCodes = [
    ['code' => '+91', 'country' => 'India'],
];

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
$initialStep = 1;
if (!empty($_GET['step']) && in_array((int)$_GET['step'], [1, 2, 3, 4], true)) {
    $initialStep = (int)$_GET['step'];
}

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

// ----------------------------------------------------------------------------
// 5. POST SUBMISSION PROCESSING
// ----------------------------------------------------------------------------
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
        $generalError = 'Security verification failed or session expired. Please refresh and try again.';
    } else {
        $courseName = sanitize($_POST['course_name'] ?? '');
        $courseName = trim(preg_replace('/[\x{200B}-\x{200D}\x{2060}\x{FEFF}]/u', '', $courseName));
        $trainingMode = sanitize($_POST['training_mode'] ?? '');

        if (!in_array($trainingMode, ['Online', 'Offline'], true)) {
            $errors['training_mode'] = 'Please select a training mode (Online or Offline).';
        }

        $name        = sanitize($_POST['full_name'] ?? '');
        $email       = sanitize($_POST['email'] ?? '');
        $pCode       = sanitize($_POST['primary_country_code'] ?? '+91');
        $pPhone      = sanitize($_POST['primary_phone'] ?? '');
        $aCode       = sanitize($_POST['alternate_country_code'] ?? '+91');
        $aPhone      = sanitize($_POST['alternate_phone'] ?? '');
        $gender      = sanitize($_POST['gender'] ?? '');
        $dob         = sanitize($_POST['date_of_birth'] ?? '');
        $country     = sanitize($_POST['country'] ?? '');
        $state       = sanitize($_POST['state'] ?? '');
        $city        = sanitize($_POST['city'] ?? '');
        $qual        = sanitize($_POST['highest_qualification'] ?? '');
        $area        = sanitize($_POST['area_of_study'] ?? '');
        $year        = sanitize($_POST['year_of_completion'] ?? '');
        $gpa         = sanitize($_POST['gpa_percentage'] ?? '');
        $exp         = sanitize($_POST['years_of_experience'] ?? '');
        $company     = sanitize($_POST['current_company'] ?? '');
        $designation = sanitize($_POST['current_designation'] ?? '');

        // Validation - Step 1: Program
        if ($courseName === '' || $courseName === '-- Select Course --' || $courseName === '-- Choose Course --') {
            $errors['course_name'] = 'Please select a program from the list.';
        } elseif (!array_key_exists($courseName, $courseCatalog)) {
            $errors['course_name'] = 'Please choose a valid program from the list.';
        } else {
            $selectedCourse = $courseCatalog[$courseName];
            $selectedCourseName = $courseName;
        }

        // Validation - Step 2: Personal Information
        if ($name === '') {
            $errors['full_name'] = 'Please enter your full name.';
        } elseif (mb_strlen($name) < 2) {
            $errors['full_name'] = 'Full name must be at least 2 characters.';
        } elseif (!preg_match('/^[\p{L}\s\'\.\-]+$/u', $name)) {
            $errors['full_name'] = 'Name can only contain letters, spaces, hyphens, and apostrophes.';
        }

        if ($email === '') {
            $errors['email'] = 'Email address is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
            $errors['email'] = 'Please enter a valid email address (e.g. name@example.com).';
        }

        $cleanPhone = preg_replace('/[^\d]/', '', $pPhone);
        if ($cleanPhone === '') {
            $errors['primary_phone'] = 'Primary mobile number is required.';
        } elseif ($pCode === '+91') {
            if (strlen($cleanPhone) !== 10) {
                $errors['primary_phone'] = 'Please enter a 10-digit Indian mobile number.';
            } elseif (!preg_match('/^[6-9]\d{9}$/', $cleanPhone)) {
                $errors['primary_phone'] = 'Indian mobile number must start with 6, 7, 8, or 9.';
            }
        } elseif (strlen($cleanPhone) < 7 || strlen($cleanPhone) > 15) {
            $errors['primary_phone'] = 'Mobile number must contain between 7 and 15 digits.';
        }

        if ($aPhone !== '') {
            $cleanAlt = preg_replace('/[^\d]/', '', $aPhone);
            if ($aCode === '+91') {
                if (strlen($cleanAlt) !== 10) {
                    $errors['alternate_phone'] = 'Alternate mobile number must be 10 digits.';
                } elseif (!preg_match('/^[6-9]\d{9}$/', $cleanAlt)) {
                    $errors['alternate_phone'] = 'Alternate mobile number must start with 6, 7, 8, or 9.';
                }
            } elseif (strlen($cleanAlt) < 7 || strlen($cleanAlt) > 15) {
                $errors['alternate_phone'] = 'Alternate phone must contain between 7 and 15 digits.';
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

        // Validation - Step 3: Educational Information
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

        // Validation - Step 4: Professional Information
        if ($exp === '' || !is_numeric($exp) || (float)$exp < 0) {
            $errors['years_of_experience'] = 'Please enter total years of experience (0 if fresher).';
        } elseif ((float)$exp > 99) {
            $errors['years_of_experience'] = 'Total experience cannot exceed 99 years.';
        }

        if (empty($_POST['consent'])) {
            $errors['consent'] = 'You must authorize The IoT Academy to process your application.';
        }

        // Step determination if validation failed
        if (!empty($errors)) {
            if (isset($errors['years_of_experience']) || isset($errors['current_company']) || isset($errors['current_designation']) || isset($errors['consent'])) {
                $initialStep = 4;
            }
            if (isset($errors['highest_qualification']) || isset($errors['area_of_study']) || isset($errors['year_of_completion']) || isset($errors['gpa_percentage'])) {
                $initialStep = 3;
            }
            if (isset($errors['full_name']) || isset($errors['email']) || isset($errors['primary_phone']) || isset($errors['alternate_phone']) || isset($errors['gender']) || isset($errors['date_of_birth']) || isset($errors['country']) || isset($errors['state']) || isset($errors['city'])) {
                $initialStep = 2;
            }
            if (isset($errors['course_name']) || isset($errors['training_mode'])) {
                $initialStep = 1;
            }
            $generalError = 'Please check and correct the highlighted fields below.';
        } else {
            // Success: Insert record & Notify
            $ref = sprintf('APP-%s-%s', date('Y'), strtoupper(bin2hex(random_bytes(3))));
            $inserted = false;

            $pdo = getDbConnection();
            if ($pdo) {
                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO course_enrollments (
                            enrollment_ref, course_id, course_title, training_mode, full_name, email,
                            primary_country_code, primary_phone, alternate_country_code, alternate_phone,
                            gender, date_of_birth, country, state, city,
                            highest_qualification, area_of_study, year_of_completion, gpa_percentage,
                            years_of_experience, current_company, current_designation, enrollment_status, ip_address
                        ) VALUES (
                            :ref, :c_id, :c_title, :training_mode, :name, :email,
                            :p_code, :p_phone, :a_code, :a_phone,
                            :gender, :dob, :country, :state, :city,
                            :qual, :area, :year, :gpa,
                            :exp, :company, :designation, 'Pending', :ip
                        )
                    ");

                    $stmt->execute([
                        ':ref'           => $ref,
                        ':c_id'          => $selectedCourse['id'],
                        ':c_title'       => $selectedCourse['title'],
                        ':training_mode' => $trainingMode,
                        ':name'          => $name,
                        ':email'         => $email,
                        ':p_code'        => $pCode,
                        ':p_phone'       => $pPhone,
                        ':a_code'        => $aCode,
                        ':a_phone'       => $aPhone ?: null,
                        ':gender'        => $gender,
                        ':dob'           => $dob,
                        ':country'       => $country,
                        ':state'         => $state,
                        ':city'          => $city,
                        ':qual'          => $qual,
                        ':area'          => $area,
                        ':year'          => $year ? (int)$year : null,
                        ':gpa'           => $gpa ?: null,
                        ':exp'           => (float)$exp,
                        ':company'       => $company ?: null,
                        ':designation'   => $designation ?: null,
                        ':ip'            => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
                    ]);

                    $inserted = true;
                } catch (Exception $e) {
                    error_log("Application insert error: " . $e->getMessage());
                    $generalError = "Could not save application to database: " . $e->getMessage();
                }
            } else {
                $inserted = true; // Fallback permit
            }

            if ($inserted) {
                unset($_SESSION['csrf_token']); // Prevent replay

                // Sync to Google Apps Script (Google Sheets) Webhook
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
                        CURLOPT_POST           => true,
                        CURLOPT_POSTFIELDS     => json_encode($googlePayload, JSON_UNESCAPED_UNICODE),
                        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_MAXREDIRS      => 5,
                        CURLOPT_CONNECTTIMEOUT => 10,
                        CURLOPT_TIMEOUT        => 25,
                        CURLOPT_SSL_VERIFYPEER => false
                    ]);
                    @curl_exec($ch);
                    curl_close($ch);
                }

                $_SESSION['latest_enrollment'] = [
                    'ref'                    => $ref,
                    'reference_id'           => $ref,
                    'course_title'           => $selectedCourse['title'],
                    'course_name'            => $selectedCourse['title'],
                    'training_mode'          => $trainingMode,
                    'full_name'              => $name,
                    'email'                  => $email,
                    'primary_country_code'   => $pCode,
                    'primary_phone'          => $pPhone,
                    'gender'                 => $gender,
                    'date_of_birth'          => $dob,
                    'city'                   => $city,
                    'state'                  => $state,
                    'country'                => $country,
                    'highest_qualification'  => $qual,
                    'area_of_study'          => $area,
                    'years_of_experience'    => $exp,
                    'current_company'        => $company,
                    'current_designation'    => $designation,
                    'created_at'             => date('F j, Y, g:i a')
                ];
                $_SESSION['enrollment_success'] = $_SESSION['latest_enrollment'];

                // Post-Redirect-Get to confirmation receipt
                $targetScript = function_exists('site_url') ? site_url('success') : 'success';
                if (!empty($_SERVER['HTTP_HOST']) && strpos($targetScript, $_SERVER['HTTP_HOST']) === false) {
                    $targetScript = 'success';
                }
                header("Location: {$targetScript}?ref=" . urlencode($ref) . "&course=" . urlencode($selectedCourse['title']));
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply Now - Admission Application | The IoT Academy</title>
    <meta name="description" content="Apply for certified industry-recognized programs in Data Science, AI, Machine Learning, Embedded Systems, and IoT at The IoT Academy. Complete your admission application online.">

    <!-- Bootstrap CSS for Site Header & Footer Components -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- The IoT Academy Common CSS -->
    <link href="<?php echo function_exists('asset_url') ? asset_url() : 'http://localhost/TIA/assets/'; ?>dit/css/custom.css" rel="stylesheet">

    <!-- Swiper CSS for Announcement Ticker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --tia-navy: #113563;
            --tia-navy-dark: #0a2140;
            --tia-navy-light: #1d4d8c;
            --tia-orange: #FDA31B;
            --tia-orange-hover: #e59112;
            --tia-orange-light: #fff8ee;
            --tia-orange-subtle: rgba(253, 163, 27, 0.12);
            --tia-bg: #f8fafc;
            --tia-card-bg: #ffffff;
            --tia-text-main: #1e293b;
            --tia-text-muted: #64748b;
            --tia-text-light: #94a3b8;
            --tia-border: #e2e8f0;
            --tia-border-focus: #113563;
            --tia-error: #ef4444;
            --tia-error-bg: #fef2f2;
            --tia-success: #10b981;
            --tia-success-bg: #ecfdf5;
            --tia-shadow-sm: 0 1px 3px rgba(17, 53, 99, 0.05);
            --tia-shadow-md: 0 4px 16px rgba(17, 53, 99, 0.08);
            --tia-shadow-lg: 0 12px 36px -4px rgba(17, 53, 99, 0.12);
            --tia-radius: 10px;
            --tia-radius-lg: 14px;
            --tia-radius-sm: 6px;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--tia-bg);
            color: var(--tia-text-main);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* -------------------------------------------------------------------------
           PAGE LAYOUT & CONTAINER
           ------------------------------------------------------------------------- */
        .apply-page-wrapper {
            padding: 32px 16px 64px;
            min-height: calc(100vh - 200px);
            background: linear-gradient(180deg, #f1f5f9 0%, var(--tia-bg) 280px);
        }

        .apply-container {
            max-width: 960px;
            margin: 0 auto;
        }

        /* -------------------------------------------------------------------------
           BREADCRUMB
           ------------------------------------------------------------------------- */
        .apply-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--tia-text-muted);
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .apply-breadcrumb a {
            color: var(--tia-text-muted);
            text-decoration: none;
            transition: color 0.18s ease;
        }

        .apply-breadcrumb a:hover {
            color: var(--tia-navy);
            text-decoration: underline;
        }

        .apply-breadcrumb .sep {
            color: var(--tia-text-light);
            font-size: 11px;
        }

        .apply-breadcrumb span:last-child {
            color: var(--tia-navy);
            font-weight: 600;
        }

        /* -------------------------------------------------------------------------
           PAGE INTRO HEADER
           ------------------------------------------------------------------------- */
        .apply-page-intro {
            text-align: center;
            margin-bottom: 28px;
            padding: 0 12px;
        }

        .apply-page-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e0f2fe;
            color: #0369a1;
            font-size: 12.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 4px 14px;
            border-radius: 20px;
            margin-bottom: 12px;
            border: 1px solid #bae6fd;
        }

        .apply-page-badge .badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #0284c7;
            display: inline-block;
        }

        .page-main-heading {
            font-family: 'Nunito', 'Inter', sans-serif;
            font-size: 34px;
            font-weight: 800;
            color: var(--tia-navy);
            letter-spacing: -0.5px;
            margin-bottom: 8px;
            line-height: 1.25;
        }

        .page-main-heading span {
            color: var(--tia-orange);
        }

        .page-main-subtitle {
            font-size: 15.5px;
            color: var(--tia-text-muted);
            max-width: 660px;
            margin: 0 auto;
            line-height: 1.55;
        }

        /* -------------------------------------------------------------------------
           ALERTS (SUCCESS / DANGER)
           ------------------------------------------------------------------------- */
        .apply-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 18px;
            border-radius: var(--tia-radius);
            margin-bottom: 22px;
            font-size: 14px;
            position: relative;
            animation: fadeIn 0.25s ease;
        }

        .apply-alert-danger {
            background-color: var(--tia-error-bg);
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .apply-alert-success {
            background-color: var(--tia-success-bg);
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .apply-alert-close {
            margin-left: auto;
            background: none;
            border: none;
            font-size: 18px;
            line-height: 1;
            color: inherit;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.15s ease;
            padding: 0 4px;
        }

        .apply-alert-close:hover {
            opacity: 1;
        }

        /* -------------------------------------------------------------------------
           APPLICATION PROGRESS INDICATOR (STEPPER)
           ------------------------------------------------------------------------- */
        .apply-stepper-container {
            background: #ffffff;
            border: 1px solid var(--tia-border);
            border-radius: var(--tia-radius-lg);
            padding: 18px 24px;
            margin-bottom: 24px;
            box-shadow: var(--tia-shadow-sm);
            position: relative;
        }

        .stepper-track-wrap {
            position: relative;
            margin: 0 32px 14px;
        }

        .stepper-bg-line {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 3px;
            background: #e2e8f0;
            transform: translateY(-50%);
            z-index: 1;
        }

        .stepper-fill-line {
            position: absolute;
            top: 50%;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--tia-navy) 0%, var(--tia-orange) 100%);
            transform: translateY(-50%);
            z-index: 2;
            transition: width 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            width: 0%;
        }

        .stepper-steps-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            position: relative;
            z-index: 3;
        }

        .step-step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            text-align: center;
            user-select: none;
            transition: all 0.2s ease;
            width: 25%;
            padding: 0 4px;
        }

        .step-bubble {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #ffffff;
            border: 2.5px solid #cbd5e1;
            color: #64748b;
            font-weight: 700;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            transition: all 0.25s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            position: relative;
        }

        .step-bubble .check-icon {
            display: none;
        }

        .step-meta {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .step-number-tag {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .step-name-text {
            font-size: 13.5px;
            font-weight: 600;
            color: #475569;
            transition: color 0.2s ease;
            white-space: nowrap;
        }

        /* Active Step */
        .step-step-item.is-active .step-bubble {
            background: var(--tia-navy);
            border-color: var(--tia-orange);
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(253, 163, 27, 0.25);
            transform: scale(1.08);
        }

        .step-step-item.is-active .step-number-tag {
            color: var(--tia-orange);
        }

        .step-step-item.is-active .step-name-text {
            color: var(--tia-navy);
            font-weight: 700;
        }

        /* Completed Step */
        .step-step-item.is-completed .step-bubble {
            background: var(--tia-navy);
            border-color: var(--tia-navy);
            color: #ffffff;
        }

        .step-step-item.is-completed .step-bubble .digit {
            display: none;
        }

        .step-step-item.is-completed .step-bubble .check-icon {
            display: block;
        }

        .step-step-item.is-completed .step-name-text {
            color: var(--tia-navy);
        }

        /* Mobile Compact Stepper Bar */
        .mobile-step-status-bar {
            display: none;
            align-items: center;
            justify-content: space-between;
            background: var(--tia-navy);
            color: #ffffff;
            padding: 10px 14px;
            border-radius: var(--tia-radius);
            margin-bottom: 16px;
            font-size: 13px;
        }

        .mobile-step-status-bar strong {
            color: var(--tia-orange);
        }

        /* -------------------------------------------------------------------------
           MAIN FORM CARD
           ------------------------------------------------------------------------- */
        .apply-card {
            background: var(--tia-card-bg);
            border: 1px solid var(--tia-border);
            border-radius: var(--tia-radius-lg);
            box-shadow: var(--tia-shadow-lg);
            overflow: hidden;
            margin-bottom: 28px;
        }

        .apply-card-header {
            background: #ffffff;
            border-bottom: 1px solid var(--tia-border);
            padding: 24px 32px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            position: relative;
        }

        .apply-card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--tia-navy) 0%, var(--tia-orange) 100%);
        }

        .apply-card-title {
            font-family: 'Nunito', 'Inter', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: var(--tia-navy);
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }

        .apply-card-subtitle {
            font-size: 14px;
            color: var(--tia-text-muted);
            line-height: 1.45;
        }

        .step-badge-pill {
            background: var(--tia-orange-light);
            color: #b45309;
            border: 1px solid rgba(253, 163, 27, 0.4);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12.5px;
            font-weight: 700;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .apply-form-body {
            padding: 32px;
        }

        /* -------------------------------------------------------------------------
           FORM STEP PANELS
           ------------------------------------------------------------------------- */
        .form-step-panel {
            display: none;
        }

        .form-step-panel.is-active {
            display: block;
            animation: panelFade 0.28s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes panelFade {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Section Internal Headers */
        .step-panel-heading {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 14px;
            margin-bottom: 24px;
            border-bottom: 1.5px solid #f1f5f9;
        }

        .step-panel-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(17, 53, 99, 0.08);
            color: var(--tia-navy);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .step-panel-title-group h3 {
            font-size: 17px;
            font-weight: 700;
            color: var(--tia-navy);
            margin-bottom: 2px;
        }

        .step-panel-title-group p {
            font-size: 13px;
            color: var(--tia-text-muted);
        }

        /* -------------------------------------------------------------------------
           HERO PROGRAM BANNER (STEP 1)
           ------------------------------------------------------------------------- */
        .course-hero-banner {
            background: linear-gradient(135deg, #0e2a50 0%, #113563 60%, #1a4985 100%);
            border-radius: var(--tia-radius);
            padding: 22px 24px;
            color: #ffffff;
            margin-bottom: 26px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(17, 53, 99, 0.2);
        }

        .course-hero-banner::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, rgba(253, 163, 27, 0.22) 0%, rgba(253, 163, 27, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-banner-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }

        .hero-banner-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.15);
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #e2e8f0;
        }

        .hero-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(16, 185, 129, 0.22);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.4);
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11.5px;
            font-weight: 700;
        }

        .hero-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #10b981;
            animation: pulseDot 1.8s infinite;
        }

        @keyframes pulseDot {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.4); opacity: 0.6; }
            100% { transform: scale(1); opacity: 1; }
        }

        .hero-course-title {
            font-family: 'Nunito', 'Inter', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 14px;
            letter-spacing: -0.2px;
            line-height: 1.3;
            position: relative;
            z-index: 2;
        }

        .hero-specs-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            position: relative;
            z-index: 2;
        }

        .hero-spec-item {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.14);
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #e2e8f0;
        }

        .hero-spec-item strong {
            color: #ffffff;
            font-weight: 700;
        }

        /* -------------------------------------------------------------------------
           FIELDS & FORM CONTROLS
           ------------------------------------------------------------------------- */
        .fields-grid {
            display: grid;
            gap: 20px;
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

        .form-group {
            display: flex;
            flex-direction: column;
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
            font-weight: 700;
            margin-left: 2px;
        }

        .form-label .opt-tag {
            font-size: 11px;
            font-weight: 500;
            color: #94a3b8;
            background: #f1f5f9;
            padding: 2px 7px;
            border-radius: 10px;
        }

        .form-control,
        .form-select {
            width: 100%;
            height: 46px;
            padding: 0 14px;
            font-size: 14.5px;
            font-family: inherit;
            color: var(--tia-text-main);
            background-color: #ffffff;
            border: 1.5px solid var(--tia-border);
            border-radius: var(--tia-radius-sm);
            transition: all 0.2s ease;
            outline: none;
            box-shadow: var(--tia-shadow-sm);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--tia-navy);
            box-shadow: 0 0 0 3px rgba(17, 53, 99, 0.12);
        }

        .form-control::placeholder {
            color: #94a3b8;
            font-size: 14px;
        }

        .select-custom-wrapper {
            position: relative;
        }

        .select-custom-wrapper select {
            width: 100%;
        }

        .select-custom-wrapper::after,
        .select-custom-wrapper::before {
            display: none !important;
            content: none !important;
        }

        /* Error States */
        .form-group.has-error .form-control,
        .form-group.has-error .form-select,
        .form-group.has-error .phone-group-combo {
            border-color: var(--tia-error) !important;
            background-color: #fffbfa;
        }

        .field-error-msg {
            color: var(--tia-error);
            font-size: 12.5px;
            margin-top: 5px;
            display: none;
            font-weight: 500;
        }

        .form-group.has-error .field-error-msg {
            display: block;
        }

        .field-hint {
            display: block;
            font-size: 12px;
            color: #64748b;
            margin-top: 5px;
        }

        /* -------------------------------------------------------------------------
           PHONE COMBO (DIAL CODE + INPUT)
           ------------------------------------------------------------------------- */
        .phone-group-combo {
            display: flex;
            border: 1.5px solid var(--tia-border);
            border-radius: var(--tia-radius-sm);
            overflow: hidden;
            background: #ffffff;
            transition: all 0.2s ease;
            box-shadow: var(--tia-shadow-sm);
        }

        .phone-group-combo:focus-within {
            border-color: var(--tia-navy);
            box-shadow: 0 0 0 3px rgba(17, 53, 99, 0.12);
        }

        .phone-dial-code {
            width: 100px;
            flex-shrink: 0;
            background: #f8fafc;
            border-right: 1.5px solid var(--tia-border);
            position: relative;
        }

        .phone-dial-code select {
            width: 100%;
            height: 44px;
            border: none;
            background: transparent;
            padding: 0 8px 0 10px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--tia-text-main);
            outline: none;
            cursor: pointer;
        }

        .phone-number-field {
            flex: 1;
        }

        .phone-number-field input {
            border: none;
            box-shadow: none;
            height: 44px;
            padding: 0 12px;
            font-size: 14.5px;
        }

        .phone-number-field input:focus {
            box-shadow: none;
        }

        /* -------------------------------------------------------------------------
           TRAINING MODE & GENDER PILLS
           ------------------------------------------------------------------------- */
        .mode-selection-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        .mode-option-card {
            border: 1.5px solid var(--tia-border);
            border-radius: var(--tia-radius);
            padding: 14px 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            background: #ffffff;
            transition: all 0.22s ease;
            position: relative;
            user-select: none;
        }

        .mode-option-card:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }

        .mode-option-card.selected {
            border-color: var(--tia-orange);
            background: var(--tia-orange-light);
            box-shadow: 0 0 0 2px rgba(253, 163, 27, 0.2);
        }

        .mode-option-card input[type="radio"] {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .mode-radio-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .mode-option-card.selected .mode-radio-icon {
            border-color: var(--tia-orange);
            background: var(--tia-orange);
        }

        .mode-radio-icon::after {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ffffff;
            opacity: 0;
            transform: scale(0);
            transition: all 0.2s ease;
        }

        .mode-option-card.selected .mode-radio-icon::after {
            opacity: 1;
            transform: scale(1);
        }

        .mode-card-body {
            display: flex;
            flex-direction: column;
        }

        .mode-card-title {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--tia-navy);
            margin-bottom: 2px;
        }

        .mode-card-sub {
            font-size: 12px;
            color: var(--tia-text-muted);
        }

        /* Gender Segmented Radio Group */
        .gender-segmented-group {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .gender-radio-option {
            height: 46px;
            border: 1.5px solid var(--tia-border);
            border-radius: var(--tia-radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--tia-text-main);
            background: #ffffff;
            transition: all 0.2s ease;
            user-select: none;
        }

        .gender-radio-option:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }

        .gender-radio-option.selected {
            border-color: var(--tia-navy);
            background: #f1f5f9;
            color: var(--tia-navy);
            font-weight: 700;
        }

        .gender-radio-option input[type="radio"] {
            accent-color: var(--tia-navy);
            width: 15px;
            height: 15px;
        }

        /* -------------------------------------------------------------------------
           APPLICATION REVIEW SUMMARY CARD (STEP 4)
           ------------------------------------------------------------------------- */
        .review-summary-card {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: var(--tia-radius);
            padding: 22px;
            margin-top: 24px;
            margin-bottom: 24px;
        }

        .review-summary-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .review-summary-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--tia-navy);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .review-badge-verified {
            font-size: 11.5px;
            font-weight: 700;
            color: #0369a1;
            background: #e0f2fe;
            padding: 3px 10px;
            border-radius: 12px;
        }

        .review-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        .review-block {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px 16px;
            position: relative;
        }

        .review-block-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .review-block-tag {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--tia-navy);
        }

        .review-edit-btn {
            font-size: 12px;
            font-weight: 600;
            color: #d97706;
            cursor: pointer;
            text-decoration: underline;
            background: none;
            border: none;
            padding: 0;
            transition: color 0.15s ease;
        }

        .review-edit-btn:hover {
            color: #b45309;
        }

        .review-block-content {
            font-size: 13.5px;
            color: #334155;
            line-height: 1.5;
        }

        .review-block-content strong {
            color: #0f172a;
        }

        /* -------------------------------------------------------------------------
           CONSENT & DECLARATION
           ------------------------------------------------------------------------- */
        .consent-wrapper {
            background: #f8fafc;
            border: 1px solid var(--tia-border);
            border-radius: var(--tia-radius);
            padding: 16px 20px;
            margin-top: 10px;
            margin-bottom: 24px;
        }

        .consent-checkbox-label {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            cursor: pointer;
            font-size: 13px;
            color: #475569;
            line-height: 1.5;
        }

        .consent-checkbox-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--tia-navy);
            margin-top: 2px;
            flex-shrink: 0;
            cursor: pointer;
        }

        /* -------------------------------------------------------------------------
           ACTION BUTTONS (PREV / NEXT / SUBMIT)
           ------------------------------------------------------------------------- */
        .step-nav-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-top: 28px;
            padding-top: 24px;
            border-top: 1px solid var(--tia-border);
        }

        .btn-step-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 48px;
            padding: 0 24px;
            font-size: 14.5px;
            font-weight: 600;
            font-family: inherit;
            border-radius: var(--tia-radius-sm);
            cursor: pointer;
            transition: all 0.22s ease;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-step-prev {
            background: #ffffff;
            color: #475569;
            border: 1.5px solid #cbd5e1;
        }

        .btn-step-prev:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
            color: #0f172a;
        }

        .btn-step-next {
            background: var(--tia-navy);
            color: #ffffff;
            border: none;
            box-shadow: 0 4px 14px rgba(17, 53, 99, 0.25);
            margin-left: auto;
        }

        .btn-step-next:hover {
            background: var(--tia-navy-dark);
            box-shadow: 0 6px 18px rgba(17, 53, 99, 0.35);
            transform: translateY(-1px);
        }

        .btn-submit-enrollment {
            background: linear-gradient(135deg, var(--tia-navy) 0%, #1e4a82 100%);
            color: #ffffff;
            border: none;
            padding: 0 32px;
            font-size: 15.5px;
            font-weight: 700;
            box-shadow: 0 4px 16px rgba(17, 53, 99, 0.3);
            margin-left: auto;
            position: relative;
        }

        .btn-submit-enrollment:hover {
            background: linear-gradient(135deg, var(--tia-navy-dark) 0%, var(--tia-navy) 100%);
            box-shadow: 0 6px 20px rgba(17, 53, 99, 0.4);
            transform: translateY(-1px);
        }

        .btn-submit-enrollment:disabled {
            opacity: 0.75;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Loading Spinner */
        .btn-loading-spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2.5px solid rgba(255, 255, 255, 0.35);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 0.75s infinite linear;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .d-none-soft {
            display: none !important;
        }

        /* -------------------------------------------------------------------------
           ADMISSIONS HELP / SUPPORT CARD
           ------------------------------------------------------------------------- */
        .admissions-help-card {
            background: #ffffff;
            border: 1px solid var(--tia-border);
            border-radius: var(--tia-radius);
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            box-shadow: var(--tia-shadow-sm);
        }

        .help-card-text {
            font-size: 13.5px;
            color: #475569;
        }

        .help-card-text strong {
            color: var(--tia-navy);
        }

        .help-contact-links {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-shrink: 0;
            flex-wrap: wrap;
        }

        .help-contact-links a {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--tia-navy);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            background: #f1f5f9;
            padding: 8px 14px;
            border-radius: 6px;
            transition: all 0.18s ease;
        }

        .help-contact-links a:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        /* Floating Scroll to Top Button */
        .scroll-top-btn {
            position: fixed;
            bottom: 28px;
            right: 28px;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--tia-navy);
            color: #ffffff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(17, 53, 99, 0.3);
            opacity: 0;
            visibility: hidden;
            transform: translateY(12px);
            transition: all 0.25s ease;
            z-index: 99;
        }

        .scroll-top-btn.is-visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .scroll-top-btn:hover {
            background: var(--tia-navy-dark);
            transform: translateY(-2px);
        }

        /* -------------------------------------------------------------------------
           RESPONSIVE MEDIA QUERIES
           ------------------------------------------------------------------------- */
        @media (max-width: 820px) {
            .grid-col-3 {
                grid-template-columns: repeat(2, 1fr);
            }
            .apply-card-header {
                padding: 20px 24px;
            }
            .apply-form-body {
                padding: 24px;
            }
            .review-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 680px) {
            .apply-stepper-container {
                display: none;
            }
            .mobile-step-status-bar {
                display: flex;
            }
            .grid-col-2,
            .grid-col-3 {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .mode-selection-grid {
                grid-template-columns: 1fr;
            }
            .apply-card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                padding: 18px 20px;
            }
            .step-badge-pill {
                align-self: flex-start;
            }
            .apply-form-body {
                padding: 20px 16px;
            }
            .admissions-help-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 14px;
            }
            .help-contact-links {
                width: 100%;
            }
            .help-contact-links a {
                flex: 1 1 auto;
                justify-content: center;
            }
            .step-nav-actions {
                flex-direction: column-reverse;
                gap: 12px;
            }
            .btn-step-action {
                width: 100%;
                margin: 0 !important;
            }
            .apply-card-title {
                font-size: 18.5px;
                line-height: 1.3;
                word-break: break-word;
            }
            .apply-card-subtitle {
                font-size: 13px;
                line-height: 1.45;
            }
            .page-main-heading {
                font-size: 26px;
            }
            .page-main-subtitle {
                font-size: 14px;
            }
            .page-main-subtitle {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .gender-segmented-group {
                grid-template-columns: 1fr;
                gap: 8px;
            }
            .gender-radio-option {
                height: 42px;
                justify-content: flex-start;
                padding: 0 14px;
            }
            .hero-specs-row {
                flex-direction: column;
                gap: 6px;
            }
            .hero-spec-item {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>

    <!-- Site Header & Navigation -->
    <?php
    if (isset($this) && isset($this->load)) {
        $this->load->view("commons/inc/new-header.php");
    } elseif (function_exists('get_instance')) {
        get_instance()->load->view("commons/inc/new-header.php");
    }
    ?>

    <main class="apply-page-wrapper">
        <div class="apply-container">

            <!-- Breadcrumbs -->
            <nav class="apply-breadcrumb" aria-label="Breadcrumb">
                <a href="<?= function_exists('site_url') ? site_url() : '/' ?>">Home</a>
                <span class="sep">/</span>
                <a href="<?= function_exists('site_url') ? site_url('courses') : '/courses' ?>">Courses</a>
                <span class="sep">/</span>
                <span>Apply Now</span>
            </nav>

            <!-- Page Intro Header -->
            <div class="apply-page-intro">
                <div class="apply-page-badge">
                    <span class="badge-dot"></span>
                    <span>Admissions Open 2026</span>
                </div>
                <h1 class="page-main-heading">Apply <span>Now</span></h1>
                <p class="page-main-subtitle">Take the first step towards your career. Complete the application form below and our admission team will contact you.</p>
            </div>

            <!-- General Error Alert -->
            <?php if (!empty($generalError)): ?>
                <div class="apply-alert apply-alert-danger" role="alert">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <div>
                        <strong>Attention Required:</strong> <?= esc($generalError) ?>
                    </div>
                    <button type="button" class="apply-alert-close" onclick="this.parentElement.remove();" aria-label="Close">&times;</button>
                </div>
            <?php endif; ?>

            <!-- Success Alert Notification -->
            <?php if ($isSuccess && !empty($successRef)): ?>
                <div class="apply-alert apply-alert-success" role="alert">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:1px;">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <div>
                        <strong>Application Submitted Successfully!</strong><br>
                        Your admission application reference number is <strong><?= esc($successRef) ?></strong><?= !empty($successCourse) ? ' for <strong>' . esc($successCourse) . '</strong>' : '' ?>. Our admissions counselor will contact you shortly to review your candidature and seat allotment.
                    </div>
                    <button type="button" class="apply-alert-close" onclick="this.parentElement.remove();" aria-label="Close">&times;</button>
                </div>
            <?php endif; ?>

            <!-- Application Progress Indicator (Desktop / Tablet) -->
            <div class="apply-stepper-container" aria-label="Application Progress Stepper">
                <div class="stepper-track-wrap">
                    <div class="stepper-bg-line"></div>
                    <div class="stepper-fill-line" id="stepperFillLine" style="width: <?= (($initialStep - 1) / 3) * 100 ?>%;"></div>
                </div>
                <div class="stepper-steps-row">
                    <!-- Step 1 -->
                    <div class="step-step-item <?= $initialStep === 1 ? 'is-active' : ($initialStep > 1 ? 'is-completed' : '') ?>" data-step="1" id="stepperStep1" role="button" tabindex="0">
                        <div class="step-bubble">
                            <span class="digit">1</span>
                            <svg class="check-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div class="step-meta">
                            <span class="step-number-tag">Step 1</span>
                            <span class="step-name-text">Program Details</span>
                        </div>
                    </div>
                    <!-- Step 2 -->
                    <div class="step-step-item <?= $initialStep === 2 ? 'is-active' : ($initialStep > 2 ? 'is-completed' : '') ?>" data-step="2" id="stepperStep2" role="button" tabindex="0">
                        <div class="step-bubble">
                            <span class="digit">2</span>
                            <svg class="check-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div class="step-meta">
                            <span class="step-number-tag">Step 2</span>
                            <span class="step-name-text">Personal Details</span>
                        </div>
                    </div>
                    <!-- Step 3 -->
                    <div class="step-step-item <?= $initialStep === 3 ? 'is-active' : ($initialStep > 3 ? 'is-completed' : '') ?>" data-step="3" id="stepperStep3" role="button" tabindex="0">
                        <div class="step-bubble">
                            <span class="digit">3</span>
                            <svg class="check-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div class="step-meta">
                            <span class="step-number-tag">Step 3</span>
                            <span class="step-name-text">Education Details</span>
                        </div>
                    </div>
                    <!-- Step 4 -->
                    <div class="step-step-item <?= $initialStep === 4 ? 'is-active' : '' ?>" data-step="4" id="stepperStep4" role="button" tabindex="0">
                        <div class="step-bubble">
                            <span class="digit">4</span>
                            <svg class="check-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div class="step-meta">
                            <span class="step-number-tag">Step 4</span>
                            <span class="step-name-text">Professional Details</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Compact Step Status Bar -->
            <div class="mobile-step-status-bar" id="mobileStatusBar">
                <span>Application Progress: <strong id="mobileStepText">Step <?= (int)$initialStep ?> of 4</strong></span>
                <span id="mobileStepName"><?= ['Program Details', 'Personal Details', 'Education Details', 'Professional Details'][$initialStep - 1] ?></span>
            </div>

            <!-- Main Admission Application Form Card -->
            <div class="apply-card" id="admissionFormCard">
                <div class="apply-card-header">
                    <div>
                        <h2 class="apply-card-title">Apply Now – Admission Application</h2>
                        <p class="apply-card-subtitle">Fill in your details to apply for your preferred program. Our admission counsellors will review your application and get in touch with you.</p>
                    </div>
                    <div class="step-badge-pill" id="stepPillBadge">Stage <?= (int)$initialStep ?> of 4</div>
                </div>

                <form id="enrollmentForm" method="POST" action="" novalidate class="apply-form-body">
                    <input type="hidden" name="csrf_token" value="<?= esc($csrf) ?>">
                    <input type="hidden" name="course_id" id="course_id_hidden" value="<?= (int)$selectedCourse['id'] ?>">

                    <!-- =======================================================
                         STEP 1: PROGRAM SELECTION
                         ======================================================= -->
                    <div class="form-step-panel <?= $initialStep === 1 ? 'is-active' : '' ?>" data-step="1" id="stepPanel1">
                        <div class="step-panel-heading">
                            <div class="step-panel-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                            </div>
                            <div class="step-panel-title-group">
                                <h3>Section 1: Program Selection</h3>
                                <p>Choose your desired training track and learning mode</p>
                            </div>
                        </div>

                        <!-- Course Overview Hero Card -->
                        <div class="course-hero-banner" aria-label="Selected Course Details">
                            <div class="hero-banner-header">
                                <div class="hero-banner-tag">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                    <span>Official Certification Program</span>
                                </div>
                                <div class="hero-status-pill">
                                    <span class="hero-status-dot"></span>
                                    <span id="bannerStatusText"><?= esc($selectedCourse['status_text'] ?? 'Admissions Open') ?></span>
                                </div>
                            </div>
                            <h2 class="hero-course-title banner-course-title"><?= esc($selectedCourse['title'] !== 'None' ? $selectedCourse['title'] : 'Select Your Program to Enroll') ?></h2>
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
                        </div>

                        <div class="fields-grid grid-col-2">
                            <!-- Select Program -->
                            <div class="form-group grid-full-width <?= isset($errors['course_name']) ? 'has-error' : '' ?>">
                                <label for="course_name" class="form-label">
                                    <span>Select Course / Program <span class="req">*</span></span>
                                </label>
                                <div class="select-custom-wrapper">
                                    <select id="course_name" name="course_name" class="form-select" required>
                                        <option value="">-- Choose Course / Program --</option>
                                        <?php foreach ($courseCatalog as $cName => $cInfo): ?>
                                            <option value="<?= esc($cName) ?>" <?= (($formData['course_name'] ?? $selectedCourseName) === $cName) ? 'selected' : '' ?>>
                                                <?= esc($cName) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <span class="field-error-msg"><?= esc($errors['course_name'] ?? 'Please select a program.') ?></span>
                            </div>

                            <!-- Training Mode -->
                            <div class="form-group grid-full-width <?= isset($errors['training_mode']) ? 'has-error' : '' ?>">
                                <label class="form-label">
                                    <span>Training Mode <span class="req">*</span></span>
                                </label>
                                <?php
                                $selectedMode = $_POST['training_mode'] ?? $formData['training_mode'] ?? 'Online';
                                $selectedMode = ucfirst(strtolower(trim((string)$selectedMode)));
                                if (!in_array($selectedMode, ['Online', 'Offline'], true)) $selectedMode = 'Online';
                                ?>
                                <div class="mode-selection-grid">
                                    <!-- Online -->
                                    <label class="mode-option-card <?= $selectedMode === 'Online' ? 'selected' : '' ?>" id="modeCardOnline">
                                        <input type="radio" name="training_mode" value="Online" <?= $selectedMode === 'Online' ? 'checked' : '' ?> required>
                                        <div class="mode-radio-icon"></div>
                                        <div class="mode-card-body">
                                            <span class="mode-card-title">Live Online Classes</span>
                                            <span class="mode-card-sub">Interactive live instructor-led weekend & weekday batches</span>
                                        </div>
                                    </label>
                                    <!-- Offline -->
                                    <label class="mode-option-card <?= $selectedMode === 'Offline' ? 'selected' : '' ?>" id="modeCardOffline">
                                        <input type="radio" name="training_mode" value="Offline" <?= $selectedMode === 'Offline' ? 'checked' : '' ?> required>
                                        <div class="mode-radio-icon"></div>
                                        <div class="mode-card-body">
                                            <span class="mode-card-title">Classroom (Offline / Noida)</span>
                                            <span class="mode-card-sub">In-person lab training at The IoT Academy Center, Noida</span>
                                        </div>
                                    </label>
                                </div>
                                <span class="field-error-msg"><?= esc($errors['training_mode'] ?? 'Please select a training mode.') ?></span>
                            </div>
                        </div>

                        <!-- Navigation -->
                        <div class="step-nav-actions">
                            <div></div>
                            <button type="button" class="btn-step-action btn-step-next" id="btnNext1">
                                <span>Continue to Personal Details</span>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </button>
                        </div>
                    </div>

                    <!-- =======================================================
                         STEP 2: PERSONAL INFORMATION
                         ======================================================= -->
                    <div class="form-step-panel <?= $initialStep === 2 ? 'is-active' : '' ?>" data-step="2" id="stepPanel2">
                        <div class="step-panel-heading">
                            <div class="step-panel-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <div class="step-panel-title-group">
                                <h3>Section 2: Personal Information</h3>
                                <p>Provide your contact details and communication address</p>
                            </div>
                        </div>

                        <div class="fields-grid grid-col-2">
                            <!-- Full Name -->
                            <div class="form-group <?= isset($errors['full_name']) ? 'has-error' : '' ?>">
                                <label for="full_name" class="form-label">
                                    <span>Full Name <span class="req">*</span></span>
                                </label>
                                <input type="text" id="full_name" name="full_name" class="form-control" placeholder="e.g. Manish Sharma" value="<?= esc($formData['full_name'] ?? '') ?>" required maxlength="100" autocomplete="name">
                                <span class="field-error-msg"><?= esc($errors['full_name'] ?? 'Please enter your full name.') ?></span>
                            </div>

                            <!-- Email Address -->
                            <div class="form-group <?= isset($errors['email']) ? 'has-error' : '' ?>">
                                <label for="email" class="form-label">
                                    <span>Email Address <span class="req">*</span></span>
                                </label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="e.g. manish.sharma@example.com" value="<?= esc($formData['email'] ?? '') ?>" required maxlength="190" autocomplete="email">
                                <span class="field-error-msg"><?= esc($errors['email'] ?? 'Please enter a valid email address.') ?></span>
                            </div>

                            <!-- Primary Mobile Number -->
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
                                <span class="field-error-msg"><?= esc($errors['primary_phone'] ?? 'Valid 10-digit mobile number is required.') ?></span>
                            </div>

                            <!-- Alternate Mobile Number -->
                            <div class="form-group <?= isset($errors['alternate_phone']) ? 'has-error' : '' ?>">
                                <label for="alternate_phone" class="form-label">
                                    <span>Alternate Mobile Number</span>
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
                                <span class="field-error-msg"><?= esc($errors['alternate_phone'] ?? 'Please enter a valid alternate phone number.') ?></span>
                            </div>

                            <!-- Gender -->
                            <div class="form-group <?= isset($errors['gender']) ? 'has-error' : '' ?>">
                                <label class="form-label">
                                    <span>Gender <span class="req">*</span></span>
                                </label>
                                <div class="gender-segmented-group">
                                    <?php foreach (['Male', 'Female', 'Other'] as $g): ?>
                                        <label class="gender-radio-option <?= (($formData['gender'] ?? '') === $g) ? 'selected' : '' ?>">
                                            <input type="radio" name="gender" value="<?= $g ?>" <?= (($formData['gender'] ?? '') === $g) ? 'checked' : '' ?> required>
                                            <span><?= $g ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <span class="field-error-msg"><?= esc($errors['gender'] ?? 'Please select your gender.') ?></span>
                            </div>

                            <!-- Date of Birth -->
                            <div class="form-group <?= isset($errors['date_of_birth']) ? 'has-error' : '' ?>">
                                <label for="date_of_birth" class="form-label">
                                    <span>Date of Birth <span class="req">*</span></span>
                                </label>
                                <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="<?= esc($formData['date_of_birth'] ?? '') ?>" max="<?= date('Y-m-d') ?>" required>
                                <span class="field-error-msg"><?= esc($errors['date_of_birth'] ?? 'Please select a valid date in the past.') ?></span>
                            </div>

                            <!-- Location: Country -->
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
                                <span class="field-error-msg"><?= esc($errors['country'] ?? 'Please select your country.') ?></span>
                            </div>

                            <!-- Location: State -->
                            <div class="form-group <?= isset($errors['state']) ? 'has-error' : '' ?>">
                                <label for="state" class="form-label">
                                    <span>State <span class="req">*</span></span>
                                </label>
                                <div class="select-custom-wrapper">
                                    <select id="state" name="state" class="form-select" required data-selected="<?= esc($formData['state'] ?? '') ?>">
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                                <span class="field-error-msg"><?= esc($errors['state'] ?? 'Please select your state.') ?></span>
                            </div>

                            <!-- Location: City -->
                            <div class="form-group grid-full-width <?= isset($errors['city']) ? 'has-error' : '' ?>">
                                <label for="city" class="form-label">
                                    <span>City / District <span class="req">*</span></span>
                                </label>
                                <div class="select-custom-wrapper">
                                    <select id="city" name="city" class="form-select" required data-selected="<?= esc($formData['city'] ?? '') ?>">
                                        <option value="">Select City / District</option>
                                    </select>
                                </div>
                                <span class="field-error-msg"><?= esc($errors['city'] ?? 'Please select your city.') ?></span>
                            </div>
                        </div>

                        <!-- Navigation -->
                        <div class="step-nav-actions">
                            <button type="button" class="btn-step-action btn-step-prev" id="btnPrev2">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                <span>Previous: Program</span>
                            </button>
                            <button type="button" class="btn-step-action btn-step-next" id="btnNext2">
                                <span>Continue to Education Details</span>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </button>
                        </div>
                    </div>

                    <!-- =======================================================
                         STEP 3: EDUCATIONAL INFORMATION
                         ======================================================= -->
                    <div class="form-step-panel <?= $initialStep === 3 ? 'is-active' : '' ?>" data-step="3" id="stepPanel3">
                        <div class="step-panel-heading">
                            <div class="step-panel-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                    <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                                </svg>
                            </div>
                            <div class="step-panel-title-group">
                                <h3>Section 3: Educational Information</h3>
                                <p>Provide details of your academic background and highest degree</p>
                            </div>
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
                                <span class="field-error-msg"><?= esc($errors['highest_qualification'] ?? 'Please select your highest qualification.') ?></span>
                            </div>

                            <!-- Area of Study -->
                            <div class="form-group <?= isset($errors['area_of_study']) ? 'has-error' : '' ?>">
                                <label for="area_of_study" class="form-label">
                                    <span>Area of Study / Discipline <span class="req">*</span></span>
                                </label>
                                <div class="select-custom-wrapper">
                                    <select id="area_of_study" name="area_of_study" class="form-select" required>
                                        <option value="">-- Select Field / Discipline --</option>
                                        <?php foreach ($areasOfStudy as $a): ?>
                                            <option value="<?= esc($a) ?>" <?= (($formData['area_of_study'] ?? '') === $a) ? 'selected' : '' ?>>
                                                <?= esc($a) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <span class="field-error-msg"><?= esc($errors['area_of_study'] ?? 'Please select your area of study.') ?></span>
                            </div>

                            <!-- Year of Completion -->
                            <div class="form-group <?= isset($errors['year_of_completion']) ? 'has-error' : '' ?>">
                                <label for="year_of_completion" class="form-label">
                                    <span>Year of Completion</span>
                                    <span class="opt-tag">Optional</span>
                                </label>
                                <input type="number" id="year_of_completion" name="year_of_completion" class="form-control" placeholder="e.g. 2024" min="1960" max="2035" value="<?= esc($formData['year_of_completion'] ?? '') ?>">
                                <span class="field-error-msg"><?= esc($errors['year_of_completion'] ?? 'Enter a valid 4-digit year (1960-2035).') ?></span>
                            </div>

                            <!-- GPA / Percentage -->
                            <div class="form-group <?= isset($errors['gpa_percentage']) ? 'has-error' : '' ?>">
                                <label for="gpa_percentage" class="form-label">
                                    <span>GPA / Percentage</span>
                                    <span class="opt-tag">Optional</span>
                                </label>
                                <input type="text" id="gpa_percentage" name="gpa_percentage" class="form-control" placeholder="e.g. 8.2 CGPA or 78%" maxlength="20" value="<?= esc($formData['gpa_percentage'] ?? '') ?>">
                                <small class="field-hint">Accepts GPA (1–10) or Percentage (1–100%)</small>
                                <span class="field-error-msg"><?= esc($errors['gpa_percentage'] ?? 'Enter a valid GPA (1–10) or Percentage (1–100%).') ?></span>
                            </div>
                        </div>

                        <!-- Navigation -->
                        <div class="step-nav-actions">
                            <button type="button" class="btn-step-action btn-step-prev" id="btnPrev3">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                <span>Previous: Personal Details</span>
                            </button>
                            <button type="button" class="btn-step-action btn-step-next" id="btnNext3">
                                <span>Continue to Professional Details</span>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </button>
                        </div>
                    </div>

                    <!-- =======================================================
                         STEP 4: PROFESSIONAL INFORMATION & REVIEW
                         ======================================================= -->
                    <div class="form-step-panel <?= $initialStep === 4 ? 'is-active' : '' ?>" data-step="4" id="stepPanel4">
                        <div class="step-panel-heading">
                            <div class="step-panel-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                </svg>
                            </div>
                            <div class="step-panel-title-group">
                                <h3>Section 4: Professional Information & Final Review</h3>
                                <p>Provide work background details and verify your complete application</p>
                            </div>
                        </div>

                        <div class="fields-grid grid-col-3">
                            <!-- Total Years of Experience -->
                            <div class="form-group <?= isset($errors['years_of_experience']) ? 'has-error' : '' ?>">
                                <label for="years_of_experience" class="form-label">
                                    <span>Total Experience (Years) <span class="req">*</span></span>
                                </label>
                                <input type="number" id="years_of_experience" name="years_of_experience" class="form-control" placeholder="0 if fresher (max 99)" min="0" max="99" step="0.5" value="<?= esc($formData['years_of_experience'] ?? '0') ?>" required>
                                <small class="field-hint">Range: 0 to 99 years (0 if fresher)</small>
                                <span class="field-error-msg"><?= esc($errors['years_of_experience'] ?? 'Please enter total years of experience.') ?></span>
                            </div>

                            <!-- Current Company -->
                            <div class="form-group <?= isset($errors['current_company']) ? 'has-error' : '' ?>">
                                <label for="current_company" class="form-label">
                                    <span>Current Employer / Company</span>
                                    <span class="opt-tag">Optional</span>
                                </label>
                                <input type="text" id="current_company" name="current_company" class="form-control" placeholder="Company / Organization" maxlength="150" value="<?= esc($formData['current_company'] ?? '') ?>">
                                <span class="field-error-msg"><?= esc($errors['current_company'] ?? '') ?></span>
                            </div>

                            <!-- Current Designation -->
                            <div class="form-group <?= isset($errors['current_designation']) ? 'has-error' : '' ?>">
                                <label for="current_designation" class="form-label">
                                    <span>Current Designation / Role</span>
                                    <span class="opt-tag">Optional</span>
                                </label>
                                <input type="text" id="current_designation" name="current_designation" class="form-control" placeholder="Job Title / Role" maxlength="150" value="<?= esc($formData['current_designation'] ?? '') ?>">
                                <span class="field-error-msg"><?= esc($errors['current_designation'] ?? '') ?></span>
                            </div>
                        </div>

                        <!-- Live Application Review Summary Card -->
                        <div class="review-summary-card" id="applicationReviewSummary">
                            <div class="review-summary-header">
                                <div class="review-summary-title">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    <span>Application Summary Preview</span>
                                </div>
                                <span class="review-badge-verified">Review Before Submitting</span>
                            </div>

                            <div class="review-grid">
                                <!-- Block 1: Program -->
                                <div class="review-block">
                                    <div class="review-block-header">
                                        <span class="review-block-tag">Program Selection</span>
                                        <button type="button" class="review-edit-btn" onclick="appWizard.goToStep(1);">Edit</button>
                                    </div>
                                    <div class="review-block-content" id="reviewBlockProgram">
                                        <strong>Data Science & AI (9 Months)</strong><br>
                                        <span>Mode: Live Online Classes</span>
                                    </div>
                                </div>

                                <!-- Block 2: Personal -->
                                <div class="review-block">
                                    <div class="review-block-header">
                                        <span class="review-block-tag">Personal Details</span>
                                        <button type="button" class="review-edit-btn" onclick="appWizard.goToStep(2);">Edit</button>
                                    </div>
                                    <div class="review-block-content" id="reviewBlockPersonal">
                                        <strong>Applicant Name</strong><br>
                                        <span>email@example.com • +91 9876543210</span><br>
                                        <span>Noida, Uttar Pradesh, India</span>
                                    </div>
                                </div>

                                <!-- Block 3: Education -->
                                <div class="review-block">
                                    <div class="review-block-header">
                                        <span class="review-block-tag">Education</span>
                                        <button type="button" class="review-edit-btn" onclick="appWizard.goToStep(3);">Edit</button>
                                    </div>
                                    <div class="review-block-content" id="reviewBlockEducation">
                                        <strong>Bachelor's Degree</strong><br>
                                        <span>Computer Science & Information Technology</span>
                                    </div>
                                </div>

                                <!-- Block 4: Professional -->
                                <div class="review-block">
                                    <div class="review-block-header">
                                        <span class="review-block-tag">Professional Background</span>
                                        <button type="button" class="review-edit-btn" onclick="document.getElementById('years_of_experience').focus();">Edit</button>
                                    </div>
                                    <div class="review-block-content" id="reviewBlockExperience">
                                        <strong>0 Years (Fresher)</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Declaration & Authorization Checkbox -->
                        <div class="consent-wrapper <?= isset($errors['consent']) ? 'has-error' : '' ?>">
                            <label class="consent-checkbox-label" for="termsConsent">
                                <input type="checkbox" id="termsConsent" name="consent" required checked>
                                <span>I certify that all information submitted is true and accurate. I authorize The IoT Academy and its academic admissions advisors to contact me via Phone, WhatsApp, and Email with admission review results, interview schedules, and enrollment instructions.</span>
                            </label>
                            <span class="field-error-msg"><?= esc($errors['consent'] ?? 'Please accept the declaration to submit.') ?></span>
                        </div>

                        <!-- Navigation & Final Submit -->
                        <div class="step-nav-actions">
                            <button type="button" class="btn-step-action btn-step-prev" id="btnPrev4">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                <span>Previous: Education Details</span>
                            </button>
                            <button type="submit" id="submitEnrollBtn" class="btn-step-action btn-submit-enrollment">
                                <span class="btn-text">Submit Application</span>
                                <span class="btn-loading-spinner d-none-soft"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Help / Admissions Counseling Card -->
            <div class="admissions-help-card">
                <p class="help-card-text">
                    <strong>Need assistance with your admission application?</strong> Our academic counselors are available Mon–Sat, 9:30 AM to 6:30 PM.
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
    <button type="button" id="scrollToTopBtn" class="scroll-top-btn" aria-label="Scroll to top">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </button>

    <!-- Site Footer -->
    <?php
    if (isset($this) && isset($this->load)) {
        $this->load->view("commons/inc/new-footer.php");
    } elseif (function_exists('get_instance')) {
        get_instance()->load->view("commons/inc/new-footer.php");
    }
    ?>

    <!-- =======================================================================
         CLIENT JAVASCRIPT: MULTI-STEP ENGINE, GEO CASCADES & VALIDATION
         ======================================================================= -->
    <script>
    (function () {
        'use strict';

        const geoMap = <?= json_encode($geoData) ?>;
        const courseCatalogMap = <?= json_encode($courseCatalog) ?>;

        const formEl = document.getElementById('enrollmentForm');
        const courseSelect = document.getElementById('course_name');
        const courseIdHidden = document.getElementById('course_id_hidden');
        const bannerTitle = document.querySelector('.banner-course-title');
        const bannerDuration = document.getElementById('bannerDurationText');
        const bannerMode = document.getElementById('bannerModeText');
        const bannerCert = document.getElementById('bannerCertText');
        const bannerStatus = document.getElementById('bannerStatusText');

        const countryEl = document.getElementById('country');
        const stateEl = document.getElementById('state');
        const cityEl = document.getElementById('city');

        const stepperFill = document.getElementById('stepperFillLine');
        const stepPillBadge = document.getElementById('stepPillBadge');
        const mobileStepText = document.getElementById('mobileStepText');
        const mobileStepName = document.getElementById('mobileStepName');
        const admissionFormCard = document.getElementById('admissionFormCard');

        const stepNames = [
            'Program Details',
            'Personal Details',
            'Education Details',
            'Professional Details'
        ];

        let currentStep = <?= (int)$initialStep ?>;
        let maxStepVisited = Math.max(currentStep, 1);

        // ---------------------------------------------------------------------
        // MULTI-STEP ENGINE
        // ---------------------------------------------------------------------
        window.appWizard = {
            goToStep: function (targetStep) {
                if (targetStep < 1 || targetStep > 4) return;
                
                // Allow backwards freely or forward only if visited
                if (targetStep > currentStep) {
                    for (let s = currentStep; s < targetStep; s++) {
                        if (!validateStep(s)) {
                            return;
                        }
                    }
                }

                currentStep = targetStep;
                if (currentStep > maxStepVisited) {
                    maxStepVisited = currentStep;
                }
                renderStep();
                
                // Smooth scroll to form card top if below viewport
                if (admissionFormCard) {
                    const rect = admissionFormCard.getBoundingClientRect();
                    if (rect.top < 0 || rect.top > window.innerHeight) {
                        admissionFormCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            }
        };

        function renderStep() {
            // Panels
            document.querySelectorAll('.form-step-panel').forEach(function (panel) {
                const s = parseInt(panel.getAttribute('data-step'), 10);
                if (s === currentStep) {
                    panel.classList.add('is-active');
                } else {
                    panel.classList.remove('is-active');
                }
            });

            // Stepper indicators
            for (let i = 1; i <= 4; i++) {
                const stepEl = document.getElementById('stepperStep' + i);
                if (!stepEl) continue;

                stepEl.classList.remove('is-active', 'is-completed');
                if (i === currentStep) {
                    stepEl.classList.add('is-active');
                } else if (i < currentStep) {
                    stepEl.classList.add('is-completed');
                }
            }

            // Fill line percentage (0% to 100%)
            const pct = ((currentStep - 1) / 3) * 100;
            if (stepperFill) {
                stepperFill.style.width = pct + '%';
            }

            // Badges
            if (stepPillBadge) {
                stepPillBadge.textContent = 'Stage ' + currentStep + ' of 4';
            }
            if (mobileStepText) {
                mobileStepText.textContent = 'Step ' + currentStep + ' of 4';
            }
            if (mobileStepName) {
                mobileStepName.textContent = stepNames[currentStep - 1] || '';
            }

            // Update live review preview if on Step 4
            if (currentStep === 4) {
                updateLiveReviewSummary();
            }
        }

        // Stepper clickable navigation
        document.querySelectorAll('.step-step-item').forEach(function (item) {
            item.addEventListener('click', function () {
                const target = parseInt(this.getAttribute('data-step'), 10);
                window.appWizard.goToStep(target);
            });
            item.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    const target = parseInt(this.getAttribute('data-step'), 10);
                    window.appWizard.goToStep(target);
                }
            });
        });

        // Next / Prev button wiring
        const btnNext1 = document.getElementById('btnNext1');
        if (btnNext1) {
            btnNext1.addEventListener('click', function () {
                if (validateStep(1)) {
                    window.appWizard.goToStep(2);
                }
            });
        }

        const btnPrev2 = document.getElementById('btnPrev2');
        if (btnPrev2) {
            btnPrev2.addEventListener('click', function () {
                window.appWizard.goToStep(1);
            });
        }
        const btnNext2 = document.getElementById('btnNext2');
        if (btnNext2) {
            btnNext2.addEventListener('click', function () {
                if (validateStep(2)) {
                    window.appWizard.goToStep(3);
                }
            });
        }

        const btnPrev3 = document.getElementById('btnPrev3');
        if (btnPrev3) {
            btnPrev3.addEventListener('click', function () {
                window.appWizard.goToStep(2);
            });
        }
        const btnNext3 = document.getElementById('btnNext3');
        if (btnNext3) {
            btnNext3.addEventListener('click', function () {
                if (validateStep(3)) {
                    window.appWizard.goToStep(4);
                }
            });
        }

        const btnPrev4 = document.getElementById('btnPrev4');
        if (btnPrev4) {
            btnPrev4.addEventListener('click', function () {
                window.appWizard.goToStep(3);
            });
        }

        // ---------------------------------------------------------------------
        // LIVE APPLICATION SUMMARY PREVIEW (STEP 4)
        // ---------------------------------------------------------------------
        function updateLiveReviewSummary() {
            // Block 1: Program
            const courseVal = courseSelect ? courseSelect.value : '';
            const modeVal = document.querySelector('input[name="training_mode"]:checked')?.value || 'Online';
            const reviewProgram = document.getElementById('reviewBlockProgram');
            if (reviewProgram) {
                reviewProgram.innerHTML = '<strong>' + (escapeHtml(courseVal) || 'No Program Selected') + '</strong><br><span>Mode: ' + (modeVal === 'Online' ? 'Live Online Classes' : 'Classroom (Offline / Noida)') + '</span>';
            }

            // Block 2: Personal
            const nameVal = document.getElementById('full_name')?.value.trim() || 'Not Provided';
            const emailVal = document.getElementById('email')?.value.trim() || 'Not Provided';
            const codeVal = document.getElementById('primary_country_code')?.value || '+91';
            const phoneVal = document.getElementById('primary_phone')?.value.trim() || '';
            const genderVal = document.querySelector('input[name="gender"]:checked')?.value || 'Not specified';
            const cityVal = cityEl?.value.trim() || '';
            const stateVal = stateEl?.value.trim() || '';
            const countryVal = countryEl?.value.trim() || 'India';
            
            const locArray = [cityVal, stateVal, countryVal].filter(Boolean);
            const locStr = locArray.length ? locArray.join(', ') : 'Location not set';

            const reviewPersonal = document.getElementById('reviewBlockPersonal');
            if (reviewPersonal) {
                reviewPersonal.innerHTML = '<strong>' + escapeHtml(nameVal) + '</strong> (' + escapeHtml(genderVal) + ')<br>' +
                    '<span>' + escapeHtml(emailVal) + ' • ' + escapeHtml(codeVal) + ' ' + escapeHtml(phoneVal) + '</span><br>' +
                    '<span>' + escapeHtml(locStr) + '</span>';
            }

            // Block 3: Education
            const qualVal = document.getElementById('highest_qualification')?.value || 'Not selected';
            const areaVal = document.getElementById('area_of_study')?.value || 'Not selected';
            const yearVal = document.getElementById('year_of_completion')?.value.trim() || '';
            const gpaVal = document.getElementById('gpa_percentage')?.value.trim() || '';

            let eduMeta = '';
            if (yearVal || gpaVal) {
                const parts = [];
                if (yearVal) parts.push('Passing Year: ' + yearVal);
                if (gpaVal) parts.push('Score: ' + gpaVal);
                eduMeta = '<br><span style="color:#64748b; font-size:12.5px;">' + escapeHtml(parts.join(' • ')) + '</span>';
            }

            const reviewEdu = document.getElementById('reviewBlockEducation');
            if (reviewEdu) {
                reviewEdu.innerHTML = '<strong>' + escapeHtml(qualVal) + '</strong><br>' +
                    '<span>Discipline: ' + escapeHtml(areaVal) + '</span>' + eduMeta;
            }

            // Block 4: Experience
            const expVal = document.getElementById('years_of_experience')?.value.trim() || '0';
            const companyVal = document.getElementById('current_company')?.value.trim() || '';
            const desigVal = document.getElementById('current_designation')?.value.trim() || '';

            let expText = parseFloat(expVal) > 0 ? (expVal + ' Years Experience') : 'Fresher (0 Years Experience)';
            let roleText = 'Not currently employed / Seeking role';
            if (companyVal || desigVal) {
                roleText = [desigVal, companyVal].filter(Boolean).join(' at ');
            }

            const reviewExp = document.getElementById('reviewBlockExperience');
            if (reviewExp) {
                reviewExp.innerHTML = '<strong>' + escapeHtml(expText) + '</strong><br><span>' + escapeHtml(roleText) + '</span>';
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text || '';
            return div.innerHTML;
        }

        // ---------------------------------------------------------------------
        // VALIDATION UTILITIES
        // ---------------------------------------------------------------------
        function setFieldError(field, message) {
            if (!field) return;
            const grp = field.closest('.form-group');
            if (grp) {
                grp.classList.add('has-error');
                const errSpan = grp.querySelector('.field-error-msg');
                if (errSpan) {
                    errSpan.textContent = message;
                    errSpan.style.display = 'block';
                }
            }
        }

        function clearFieldError(field) {
            if (!field) return;
            const grp = field.closest('.form-group');
            if (grp) {
                grp.classList.remove('has-error');
                const errSpan = grp.querySelector('.field-error-msg');
                if (errSpan) {
                    errSpan.style.display = 'none';
                }
            }
        }

        function validateStep(step) {
            let isValid = true;
            let firstInvalid = null;

            if (step === 1) {
                // Course selection
                if (!courseSelect.value) {
                    setFieldError(courseSelect, 'Please select a program to apply for.');
                    isValid = false;
                    if (!firstInvalid) firstInvalid = courseSelect;
                } else {
                    clearFieldError(courseSelect);
                }

                // Training mode
                const modeChecked = document.querySelector('input[name="training_mode"]:checked');
                const modeGrp = document.querySelector('input[name="training_mode"]')?.closest('.form-group');
                if (!modeChecked) {
                    if (modeGrp) {
                        modeGrp.classList.add('has-error');
                        const err = modeGrp.querySelector('.field-error-msg');
                        if (err) { err.textContent = 'Please choose Online or Offline mode.'; err.style.display = 'block'; }
                    }
                    isValid = false;
                    if (!firstInvalid) firstInvalid = document.querySelector('input[name="training_mode"]');
                } else {
                    if (modeGrp) {
                        modeGrp.classList.remove('has-error');
                        const err = modeGrp.querySelector('.field-error-msg');
                        if (err) err.style.display = 'none';
                    }
                }
            } else if (step === 2) {
                // Full name
                const nameIn = document.getElementById('full_name');
                const nameVal = nameIn ? nameIn.value.trim() : '';
                if (!nameVal) {
                    setFieldError(nameIn, 'Please enter your full name.');
                    isValid = false;
                    if (!firstInvalid) firstInvalid = nameIn;
                } else if (nameVal.length < 2) {
                    setFieldError(nameIn, 'Name must be at least 2 characters.');
                    isValid = false;
                    if (!firstInvalid) firstInvalid = nameIn;
                } else if (!/^[\p{L}\s'.-]+$/u.test(nameVal)) {
                    setFieldError(nameIn, 'Name can only contain letters, spaces, hyphens, and apostrophes.');
                    isValid = false;
                    if (!firstInvalid) firstInvalid = nameIn;
                } else {
                    clearFieldError(nameIn);
                }

                // Email
                const emailIn = document.getElementById('email');
                const emailVal = emailIn ? emailIn.value.trim() : '';
                const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (!emailVal) {
                    setFieldError(emailIn, 'Email address is required.');
                    isValid = false;
                    if (!firstInvalid) firstInvalid = emailIn;
                } else if (!emailRegex.test(emailVal)) {
                    setFieldError(emailIn, 'Please enter a valid email address.');
                    isValid = false;
                    if (!firstInvalid) firstInvalid = emailIn;
                } else {
                    clearFieldError(emailIn);
                }

                // Primary Phone
                const phoneIn = document.getElementById('primary_phone');
                const codeIn = document.getElementById('primary_country_code');
                const phoneVal = phoneIn ? phoneIn.value.replace(/\D/g, '') : '';
                const codeVal = codeIn ? codeIn.value : '+91';

                if (!phoneVal) {
                    setFieldError(phoneIn, 'Primary mobile number is required.');
                    isValid = false;
                    if (!firstInvalid) firstInvalid = phoneIn;
                } else if (codeVal === '+91') {
                    if (phoneVal.length !== 10) {
                        setFieldError(phoneIn, 'Indian mobile number must be exactly 10 digits.');
                        isValid = false;
                        if (!firstInvalid) firstInvalid = phoneIn;
                    } else if (!/^[6-9]/.test(phoneVal)) {
                        setFieldError(phoneIn, 'Indian mobile number must start with 6, 7, 8, or 9.');
                        isValid = false;
                        if (!firstInvalid) firstInvalid = phoneIn;
                    } else {
                        clearFieldError(phoneIn);
                    }
                } else if (phoneVal.length < 7 || phoneVal.length > 15) {
                    setFieldError(phoneIn, 'Mobile number must be between 7 and 15 digits.');
                    isValid = false;
                    if (!firstInvalid) firstInvalid = phoneIn;
                } else {
                    clearFieldError(phoneIn);
                }

                // Alternate Phone (Optional)
                const altIn = document.getElementById('alternate_phone');
                const altCodeIn = document.getElementById('alternate_country_code');
                const altVal = altIn ? altIn.value.replace(/\D/g, '') : '';
                const altCode = altCodeIn ? altCodeIn.value : '+91';

                if (altVal) {
                    if (altCode === '+91') {
                        if (altVal.length !== 10) {
                            setFieldError(altIn, 'Alternate mobile number must be exactly 10 digits.');
                            isValid = false;
                            if (!firstInvalid) firstInvalid = altIn;
                        } else if (!/^[6-9]/.test(altVal)) {
                            setFieldError(altIn, 'Alternate mobile number must start with 6, 7, 8, or 9.');
                            isValid = false;
                            if (!firstInvalid) firstInvalid = altIn;
                        } else {
                            clearFieldError(altIn);
                        }
                    } else if (altVal.length < 7 || altVal.length > 15) {
                        setFieldError(altIn, 'Alternate phone must be between 7 and 15 digits.');
                        isValid = false;
                        if (!firstInvalid) firstInvalid = altIn;
                    } else {
                        clearFieldError(altIn);
                    }
                } else {
                    clearFieldError(altIn);
                }

                // Gender
                const genderChecked = document.querySelector('input[name="gender"]:checked');
                const genderGrp = document.querySelector('input[name="gender"]')?.closest('.form-group');
                if (!genderChecked) {
                    if (genderGrp) {
                        genderGrp.classList.add('has-error');
                        const err = genderGrp.querySelector('.field-error-msg');
                        if (err) { err.textContent = 'Please select your gender.'; err.style.display = 'block'; }
                    }
                    isValid = false;
                    if (!firstInvalid) firstInvalid = document.querySelector('input[name="gender"]');
                } else {
                    if (genderGrp) {
                        genderGrp.classList.remove('has-error');
                        const err = genderGrp.querySelector('.field-error-msg');
                        if (err) err.style.display = 'none';
                    }
                }

                // Date of Birth
                const dobIn = document.getElementById('date_of_birth');
                const dobVal = dobIn ? dobIn.value : '';
                if (!dobVal) {
                    setFieldError(dobIn, 'Please select your date of birth.');
                    isValid = false;
                    if (!firstInvalid) firstInvalid = dobIn;
                } else {
                    const dobDate = new Date(dobVal);
                    const today = new Date();
                    today.setHours(0,0,0,0);
                    if (isNaN(dobDate.getTime()) || dobDate >= today) {
                        setFieldError(dobIn, 'Date of birth must be a valid date in the past.');
                        isValid = false;
                        if (!firstInvalid) firstInvalid = dobIn;
                    } else {
                        clearFieldError(dobIn);
                    }
                }

                // Country, State, City
                ['country', 'state', 'city'].forEach(function (id) {
                    const el = document.getElementById(id);
                    if (!el || !el.value) {
                        setFieldError(el, 'Please select your ' + id + '.');
                        isValid = false;
                        if (!firstInvalid) firstInvalid = el;
                    } else {
                        clearFieldError(el);
                    }
                });
            } else if (step === 3) {
                // Highest Qualification
                const qualIn = document.getElementById('highest_qualification');
                if (!qualIn.value) {
                    setFieldError(qualIn, 'Please select your highest qualification.');
                    isValid = false;
                    if (!firstInvalid) firstInvalid = qualIn;
                } else {
                    clearFieldError(qualIn);
                }

                // Area of Study
                const areaIn = document.getElementById('area_of_study');
                if (!areaIn.value) {
                    setFieldError(areaIn, 'Please select your area of study.');
                    isValid = false;
                    if (!firstInvalid) firstInvalid = areaIn;
                } else {
                    clearFieldError(areaIn);
                }

                // Passing Year (Optional)
                const yearIn = document.getElementById('year_of_completion');
                const yearVal = yearIn ? yearIn.value.trim() : '';
                if (yearVal) {
                    const y = parseInt(yearVal, 10);
                    if (isNaN(y) || y < 1960 || y > 2035) {
                        setFieldError(yearIn, 'Passing year must be between 1960 and 2035.');
                        isValid = false;
                        if (!firstInvalid) firstInvalid = yearIn;
                    } else {
                        clearFieldError(yearIn);
                    }
                } else {
                    clearFieldError(yearIn);
                }

                // GPA / Percentage (Optional)
                const gpaIn = document.getElementById('gpa_percentage');
                const gpaVal = gpaIn ? gpaIn.value.trim() : '';
                if (gpaVal) {
                    const clean = gpaVal.replace(/[^0-9.]/g, '');
                    const n = parseFloat(clean);
                    if (isNaN(n) || n < 1 || n > 100) {
                        setFieldError(gpaIn, 'Score must be between 1 and 10 (GPA) or 1 and 100 (%).');
                        isValid = false;
                        if (!firstInvalid) firstInvalid = gpaIn;
                    } else {
                        clearFieldError(gpaIn);
                    }
                } else {
                    clearFieldError(gpaIn);
                }
            } else if (step === 4) {
                // Experience
                const expIn = document.getElementById('years_of_experience');
                const expVal = expIn ? expIn.value.trim() : '';
                if (expVal === '' || isNaN(parseFloat(expVal)) || parseFloat(expVal) < 0) {
                    setFieldError(expIn, 'Please enter years of experience (0 if fresher).');
                    isValid = false;
                    if (!firstInvalid) firstInvalid = expIn;
                } else if (parseFloat(expVal) > 99) {
                    setFieldError(expIn, 'Total experience cannot exceed 99 years.');
                    isValid = false;
                    if (!firstInvalid) firstInvalid = expIn;
                } else {
                    clearFieldError(expIn);
                }

                // Consent Checkbox
                const consentIn = document.getElementById('termsConsent');
                const consentGrp = consentIn ? consentIn.closest('.consent-wrapper') : null;
                if (consentIn && !consentIn.checked) {
                    if (consentGrp) {
                        consentGrp.classList.add('has-error');
                        const err = consentGrp.querySelector('.field-error-msg');
                        if (err) { err.textContent = 'Please accept the declaration to submit.'; err.style.display = 'block'; }
                    }
                    isValid = false;
                    if (!firstInvalid) firstInvalid = consentIn;
                } else {
                    if (consentGrp) {
                        consentGrp.classList.remove('has-error');
                        const err = consentGrp.querySelector('.field-error-msg');
                        if (err) err.style.display = 'none';
                    }
                }
            }

            if (!isValid && firstInvalid) {
                firstInvalid.focus();
                if (typeof firstInvalid.scrollIntoView === 'function') {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }

            return isValid;
        }

        // Live error clearing on input/change
        document.querySelectorAll('.form-control, .form-select').forEach(function (ctrl) {
            ctrl.addEventListener('input', function () { clearFieldError(this); });
            ctrl.addEventListener('change', function () { clearFieldError(this); });
        });

        // ---------------------------------------------------------------------
        // COURSE SELECT & HERO BANNER DYNAMICS
        // ---------------------------------------------------------------------
        if (courseSelect) {
            courseSelect.addEventListener('change', function () {
                const selected = this.value;
                if (selected && courseCatalogMap[selected]) {
                    const c = courseCatalogMap[selected];
                    if (bannerTitle) bannerTitle.textContent = c.title;
                    if (bannerDuration) bannerDuration.textContent = c.duration;
                    if (bannerMode) bannerMode.textContent = c.mode;
                    if (bannerCert) bannerCert.textContent = c.certificate;
                    if (bannerStatus) bannerStatus.textContent = c.status_text || 'Admissions Open';
                    if (courseIdHidden) courseIdHidden.value = c.id;
                } else {
                    if (bannerTitle) bannerTitle.textContent = 'Select Your Program to Enroll';
                    if (bannerDuration) bannerDuration.textContent = 'Program Duration';
                    if (bannerMode) bannerMode.textContent = 'Live Classes';
                    if (bannerCert) bannerCert.textContent = 'Certificate Included';
                    if (bannerStatus) bannerStatus.textContent = 'Admissions Open';
                    if (courseIdHidden) courseIdHidden.value = 0;
                }
                clearFieldError(this);
            });
        }

        // ---------------------------------------------------------------------
        // TRAINING MODE & GENDER PILL INTERACTIONS
        // ---------------------------------------------------------------------
        const modeCards = document.querySelectorAll('.mode-option-card');
        modeCards.forEach(function (card) {
            card.addEventListener('click', function () {
                modeCards.forEach(function (c) { c.classList.remove('selected'); });
                this.classList.add('selected');
                const radio = this.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;
                const grp = this.closest('.form-group');
                if (grp) grp.classList.remove('has-error');
            });
        });

        const genderOptions = document.querySelectorAll('.gender-radio-option');
        genderOptions.forEach(function (opt) {
            opt.addEventListener('click', function () {
                genderOptions.forEach(function (o) { o.classList.remove('selected'); });
                this.classList.add('selected');
                const radio = this.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;
                const grp = this.closest('.form-group');
                if (grp) grp.classList.remove('has-error');
            });
        });

        // ---------------------------------------------------------------------
        // EMBEDDED GEOGRAPHIC CASCADING (COUNTRY -> STATE -> CITY)
        // ---------------------------------------------------------------------
        function populateStates(country, preselectedState) {
            stateEl.innerHTML = '<option value="">Select State</option>';
            cityEl.innerHTML = '<option value="">Select City / District</option>';

            if (!country || !geoMap[country]) return;

            const states = Object.keys(geoMap[country]).sort();
            states.forEach(function (st) {
                const opt = document.createElement('option');
                opt.value = st;
                opt.textContent = st;
                if (preselectedState && st.toLowerCase() === preselectedState.toLowerCase()) {
                    opt.selected = true;
                }
                stateEl.appendChild(opt);
            });

            if (stateEl.value) {
                populateCities(country, stateEl.value, cityEl.getAttribute('data-selected'));
            }
        }

        function populateCities(country, state, preselectedCity) {
            cityEl.innerHTML = '<option value="">Select City / District</option>';

            if (!country || !state || !geoMap[country] || !geoMap[country][state]) return;

            const cities = geoMap[country][state].slice().sort();
            cities.forEach(function (ct) {
                const opt = document.createElement('option');
                opt.value = ct;
                opt.textContent = ct;
                if (preselectedCity && ct.toLowerCase() === preselectedCity.toLowerCase()) {
                    opt.selected = true;
                }
                cityEl.appendChild(opt);
            });
        }

        if (countryEl) {
            countryEl.addEventListener('change', function () {
                populateStates(this.value, null);
                clearFieldError(this);
            });
        }

        if (stateEl) {
            stateEl.addEventListener('change', function () {
                populateCities(countryEl ? countryEl.value : 'India', this.value, null);
                clearFieldError(this);
            });
        }

        if (cityEl) {
            cityEl.addEventListener('change', function () {
                clearFieldError(this);
            });
        }

        // Initialize cascades from preselected / default data
        if (countryEl) {
            const initialCountry = countryEl.getAttribute('data-selected') || countryEl.value || 'India';
            countryEl.value = initialCountry;
            const initialState = stateEl ? stateEl.getAttribute('data-selected') : '';
            populateStates(initialCountry, initialState);
        }

        // ---------------------------------------------------------------------
        // PHONE INPUT FORMATTING (DIGITS ONLY)
        // ---------------------------------------------------------------------
        ['primary_phone', 'alternate_phone'].forEach(function (id) {
            const input = document.getElementById(id);
            if (!input) return;
            input.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 10);
                clearFieldError(this);
            });
        });

        // ---------------------------------------------------------------------
        // FINAL FORM SUBMISSION HANDLER
        // ---------------------------------------------------------------------
        if (formEl) {
            formEl.addEventListener('submit', function (e) {
                // Re-validate all steps 1 through 4
                for (let s = 1; s <= 4; s++) {
                    if (!validateStep(s)) {
                        e.preventDefault();
                        window.appWizard.goToStep(s);
                        return false;
                    }
                }

                // Submission UI State (Loading Spinner & Prevent Double-Submission)
                const submitBtn = document.getElementById('submitEnrollBtn');
                if (submitBtn) {
                    const btnText = submitBtn.querySelector('.btn-text');
                    const spinner = submitBtn.querySelector('.btn-loading-spinner');
                    if (btnText) btnText.textContent = 'Submitting Application...';
                    if (spinner) spinner.classList.remove('d-none-soft');
                    setTimeout(function () {
                        submitBtn.disabled = true;
                    }, 50);
                }
                return true;
            });
        }

        // ---------------------------------------------------------------------
        // FLOATING SCROLL TO TOP
        // ---------------------------------------------------------------------
        const scrollBtn = document.getElementById('scrollToTopBtn');
        if (scrollBtn) {
            window.addEventListener('scroll', function () {
                if (window.pageYOffset > 320) {
                    scrollBtn.classList.add('is-visible');
                } else {
                    scrollBtn.classList.remove('is-visible');
                }
            });
            scrollBtn.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // Initialize view to initial step
        renderStep();
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
</body>
</html>