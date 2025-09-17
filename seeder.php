<?php
// ... inside seeder.php
require_once 'api/config/db.php';

$careers = [
    [
        'title' => 'Software Developer',
        'description' => 'Designs, develops, and maintains software applications...',
        'averageSalary' => 7055000,
        'requiredEducation' => "Bachelor's Degree in Computer Science",
        'skills' => json_encode(['JavaScript', 'Python', 'React', 'Node.js', 'SQL']),
    ],
    [
        'title' => 'Data Scientist',
        'description' => 'Analyzes complex data sets to identify trends and make predictions...',
        'averageSalary' => 9130000,
        'requiredEducation' => "Master's or PhD in Statistics or Math",
        'skills' => json_encode(['Python', 'R', 'Machine Learning', 'Statistics', 'Data Visualization']),
    ],
    [
        'title' => 'UX/UI Designer',
        'description' => 'Focuses on creating user-friendly and visually appealing interfaces...',
        'averageSalary' => 6225000,
        'requiredEducation' => "Bachelor's Degree in Design or HCI",
        'skills' => json_encode(['Figma', 'Sketch', 'Adobe XD', 'User Research', 'Prototyping']),
    ],
    [
        'title' => 'Digital Marketer',
        'description' => 'Promotes brands and products online using various digital channels...',
        'averageSalary' => 4980000,
        'requiredEducation' => "Bachelor's Degree in Marketing",
        'skills' => json_encode(['SEO', 'Social Media Marketing', 'Content Creation', 'Google Analytics']),
    ],
    [
        'title' => 'Financial Analyst',
        'description' => 'Examines financial data to help companies make business decisions...',
        'averageSalary' => 6806000,
        'requiredEducation' => "Bachelor's Degree in Finance or Economics",
        'skills' => json_encode(['Financial Modeling', 'Excel', 'Data Analysis', 'Accounting']),
    ],
    [
        'title' => 'Product Manager',
        'description' => 'Guides the success of a product and leads the cross-functional team that is responsible for improving it.',
        'averageSalary' => 9960000,
        'requiredEducation' => "Bachelor's Degree in Business or a related field",
        'skills' => json_encode(['Roadmapping', 'Agile Methodologies', 'Market Research', 'Leadership']),
    ],
    [
        'title' => 'Civil Engineer',
        'description' => 'Designs, builds, and maintains public works such as roads, bridges, canals, and dams.',
        'averageSalary' => 7304000,
        'requiredEducation' => "Bachelor's Degree in Civil Engineering",
        'skills' => json_encode(['AutoCAD', 'Project Management', 'Structural Analysis', 'Mathematics']),
    ]
];

// ... rest of the seeder.php file
?>