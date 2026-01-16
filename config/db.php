<?php
// config/db.php

// Database credentials (Not used in Mock Mode but kept for reference)
define('DB_HOST', 'localhost');
define('DB_NAME', 'chemistry_maker');
define('DB_USER', 'root');
define('DB_PASS', '');

class MockStatement {
    private $data;
    private $index = 0;
    
    public function __construct($data = []) {
        $this->data = $data;
    }
    
    public function execute($params = []) {
        return true;
    }
    
    public function fetchAll() {
        return $this->data;
    }
    
    public function fetch() {
        if (isset($this->data[$this->index])) {
            return $this->data[$this->index++]; 
        }
        return false;
    }
    
    public function fetchColumn() {
        return count($this->data);
    }
    
    public function rowCount() {
        return count($this->data);
    }
}

class MockPDO {
    public function prepare($sql) {
        return $this->handleQuery($sql);
    }
    
    public function query($sql) {
        return $this->handleQuery($sql);
    }
    
    public function lastInsertId() {
        return 999;
    }
    
    public function beginTransaction() {}
    public function commit() {}
    public function rollBack() {}
    
    private function handleQuery($sql) {
        $sql = strtolower($sql);
        
        // Mock Data: Courses (Added certificate_template)
        $courses = [
            [
                'id' => 1, 'title' => 'Organic Chemistry Masterclass', 'description' => 'Master the basics of Organic Chemistry.', 'price' => 49.99, 
                'thumbnail' => 'course1.jpg', 'status' => 'published', 'created_at' => date('Y-m-d H:i:s'),
                'certificate_template' => 'template_1.jpg', // Default Template
                'learning_outcomes' => [
                    'Master Organic Chemistry concepts from scratch',
                    'Solve complex reaction mechanisms easily',
                    'Prepare effectively for JEE/NEET exams',
                    'Understand IUPAC naming and Isomerism'
                ],
                'instructor' => [
                    'name' => 'Dr. Sharma',
                    'bio' => 'PhD in Organic Chemistry with 15+ years of teaching experience. Helped 500+ students crack IIT JEE.'
                ],
                'features' => [
                    'duration' => '40 Hours On-Demand Video',
                    'resources' => '15 Downloadable Resources',
                    'tests' => '10 Mock Tests',
                    'access' => 'Full Lifetime Access',
                    'certificate' => 'Certificate of Completion'
                ],
                'faq' => [
                    ['q' => 'Is this course suitable for beginners?', 'a' => 'Yes, we start from the very basics and move to advanced topics.'],
                    ['q' => 'What is the refund policy?', 'a' => 'We offer a 7-day no-questions-asked refund policy if you are unsatisfied.'],
                    ['q' => 'How long do I have access?', 'a' => 'You get lifetime access to all course materials and future updates.']
                ],
            ],
            [
                'id' => 2,
                'title' => 'Physical Chemistry Mastery',
                'description' => 'Detailed course on Physical Chemistry covering Thermodynamics, Kinetics, and Equilibrium.',
                'price' => 599.00,
                'created_at' => '2023-09-15 14:30:00',
                'status' => 'published',
                'thumbnail' => 'course2.jpg',
                'certificate_template' => 'template_2.jpg',
                'learning_outcomes' => [
                    'Master Thermodynamics and Kinetics',
                    'Solve numerical problems with speed',
                    'Understand Chemical Equilibrium deeply',
                    'Ace Physical Chemistry for Board Exams'
                ],
                'instructor' => [
                    'name' => 'Prof. Verma',
                    'bio' => 'Ex-IIT Professor with a passion for simplifying physical chemistry.'
                ],
                'features' => [
                    'duration' => '35 Hours On-Demand Video',
                    'resources' => '20 Downloadable Resources',
                    'tests' => '8 Mock Tests',
                     'access' => 'Full Lifetime Access',
                    'certificate' => 'Certificate of Completion'
                ],
                 'faq' => [
                    ['q' => 'Do I need a calculator?', 'a' => 'Basic calculations are taught without calculators as per exam patterns.'],
                     ['q' => 'What is the refund policy?', 'a' => 'We offer a 7-day no-questions-asked refund policy if you are unsatisfied.']
                ],
            ],
       // Course 3
    [
        'id' => 3, 'title' => 'Inorganic Chemistry Essentials', 'description' => 'Master the periodic table, coordination compounds, and d-block elements.', 'price' => 1499, 'thumbnail' => 'inorganic.jpg', 'instructor_id' => 1, 'rating' => 4.7, 'students' => 850, 'status' => 'published', 'created_at' => '2023-11-20',
        'learning_outcomes' => ['Periodic Trends', 'Coordination Chemistry', 'Metallurgy'], 'instructor' => ['name' => 'Dr. Sarah Lee', 'bio' => 'Inorganic specialist.'], 'features' => ['duration' => '35h', 'lectures' => 40, 'tests' => 6, 'certificate' => true],
        'faq' => [['q' => 'Is NCERT covered?', 'a' => 'Yes, fully.']]
    ]
        ];
        
        // ... (Modules, Videos, Resources, Tests, Users, Admins, Purchases remain same) ...

        // Mock Data: Certificates
        $certificates = [
            ['id' => 1, 'user_id' => 1, 'course_id' => 1, 'certificate_code' => 'CERT-DEMO-1234', 'student_name' => 'Demo Student', 'issued_at' => date('Y-m-d')]
        ];


        
        // Mock Data: Modules (Folders)
        $modules = [
            ['id' => 101, 'course_id' => 1, 'title' => 'Chapter 1: Intro to Carbon', 'position' => 1],
            ['id' => 102, 'course_id' => 1, 'title' => 'Chapter 2: Alkanes', 'position' => 2]
        ];

        // Mock Data: Videos (Added module_id)
        $videos = [
             ['id' => 1, 'module_id' => 101, 'course_id' => 1, 'title' => 'Introduction Lecture', 'video_type' => 'youtube', 'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'duration' => '10:00', 'position' => 1, 'is_free' => 1],
             ['id' => 2, 'module_id' => 102, 'course_id' => 1, 'title' => 'Alkane Structures', 'video_type' => 'upload', 'video_url' => 'sample.mp4', 'duration' => '15:30', 'position' => 1, 'is_free' => 0]
        ];

        // Mock Data: Resources (PDFs)
        $resources = [
            ['id' => 501, 'module_id' => 101, 'title' => 'Carbon Notes.pdf', 'file_path' => 'notes1.pdf'],
            ['id' => 502, 'module_id' => 102, 'title' => 'Alkane Formula Sheet.pdf', 'file_path' => 'notes2.pdf']
        ];

        // Mock Data: Tests
        $tests = [
            ['id' => 601, 'module_id' => 101, 'title' => 'Carbon Quiz', 'form_url' => 'https://docs.google.com/forms/d/e/example/viewform'],
        ];

        $users = [['id' => 1, 'full_name' => 'Demo Student', 'email' => 'student@example.com', 'password_hash' => password_hash('password', PASSWORD_DEFAULT)]];
        $admins = [['id' => 1, 'username' => 'admin', 'password_hash' => password_hash('admin123', PASSWORD_DEFAULT)]];
        
        // Mock Data: Purchases (Enrollment)
        $purchases = [
            ['id' => 1, 'user_id' => 1, 'course_id' => 1, 'access_status' => 'active', 'payment_id' => 999, 'created_at' => date('Y-m-d H:i:s')]
        ];

        // Mock Data: Quizzes (Paid Test Series)
        $quizzes = [
            ['id' => 1, 'title' => 'All India Chemistry Mock Test', 'description' => 'Full syllabus test with 50 questions.', 'price' => 10.00, 'time_limit' => 60, 'status' => 'published'],
            ['id' => 2, 'title' => 'Organic Chemistry Special Quiz', 'description' => 'Specialized quiz for Organic concepts.', 'price' => 5.00, 'time_limit' => 30, 'status' => 'published']
        ];
        
        // Mock Data: Quiz Questions
        $quiz_questions = [
            ['id' => 1, 'quiz_id' => 1, 'question' => 'What is the atomic number of Carbon?', 'image' => null, 'type' => 'mcq', 'opt_a' => '6', 'opt_b' => '12', 'opt_c' => '8', 'opt_d' => '14', 'correct' => 'opt_a', 'position' => 1],
            ['id' => 2, 'quiz_id' => 1, 'question' => 'Which is a noble gas?', 'image' => null, 'type' => 'mcq', 'opt_a' => 'Oxygen', 'opt_b' => 'Nitrogen', 'opt_c' => 'Helium', 'opt_d' => 'Chlorine', 'correct' => 'opt_c', 'position' => 2]
        ];
        
        // Mock Data: Video Progress
        $video_progress = [
            // Example: User 1 has completed Video 1
            ['user_id' => 1, 'video_id' => 1, 'completed' => 1, 'updated_at' => date('Y-m-d H:i:s')]
        ];

        // Mock Data: Coupons
        $coupons = [
            ['code' => 'WELCOME50', 'discount' => 50, 'valid_until' => '2030-12-31'],
            ['code' => 'CHEM10', 'discount' => 10, 'valid_until' => '2030-12-31']
        ];

        // Mock Data: Announcements
        $announcements = [
            ['id' => 1, 'title' => 'New Course Added!', 'message' => 'We have just launched Physical Chemistry Pro. Check it out!', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'title' => 'Live Session on Sunday', 'message' => 'Join us for a doubt clearing session this Sunday at 10 AM.', 'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))]
        ];

        // Mock Data: Doubts
        $doubts = [
            ['id' => 1, 'user_id' => 1, 'video_id' => 1, 'course_id' => 1, 'question' => 'I did not understand the bond angle part. Can you explain?', 'status' => 'resolved', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))],
            ['id' => 2, 'user_id' => 1, 'video_id' => 1, 'course_id' => 1, 'question' => 'Is this applicable for noble gases too?', 'status' => 'pending', 'created_at' => date('Y-m-d H:i:s')]
        ];

        // Mock Data: Doubt Replies
        $doubt_replies = [
            ['id' => 1, 'doubt_id' => 1, 'user_id' => 999, 'user_type' => 'admin', 'message' => 'Sure! In methane, the bond angle is 109.5 degrees due to sp3 hybridization.', 'created_at' => date('Y-m-d H:i:s')]
        ];

        // Simple Routing
        if (strpos($sql, 'from courses') !== false) return new MockStatement($courses);
        if (strpos($sql, 'from modules') !== false) return new MockStatement($modules);
        if (strpos($sql, 'from videos') !== false) return new MockStatement($videos);
        if (strpos($sql, 'from resources') !== false) return new MockStatement($resources);
        if (strpos($sql, 'from tests') !== false) return new MockStatement($tests); // Course Links
        if (strpos($sql, 'from quizzes') !== false) return new MockStatement($quizzes); // Paid Quizzes
        if (strpos($sql, 'from quiz_questions') !== false) return new MockStatement($quiz_questions);
        
        if (strpos($sql, 'from users') !== false) return new MockStatement($users);
        if (strpos($sql, 'from admins') !== false) return new MockStatement($admins);
        if (strpos($sql, 'from purchases') !== false) return new MockStatement($purchases);
        if (strpos($sql, 'from certificates') !== false) return new MockStatement($certificates);
        if (strpos($sql, 'from video_progress') !== false) return new MockStatement($video_progress);
        if (strpos($sql, 'from coupons') !== false) return new MockStatement($coupons);
        if (strpos($sql, 'from announcements') !== false) return new MockStatement($announcements);
        
        if (strpos($sql, 'from doubts') !== false) return new MockStatement($doubts);
        if (strpos($sql, 'from doubt_replies') !== false) return new MockStatement($doubt_replies);
        
        return new MockStatement([]);
    }
}

try {
    if (!in_array('mysql', PDO::getAvailableDrivers()) && !in_array('sqlite', PDO::getAvailableDrivers())) {
        throw new Exception("No drivers");
    }
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
} catch (Exception $e) {
    $pdo = new MockPDO();
    if (session_status() === PHP_SESSION_NONE) session_start();
}
