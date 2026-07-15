<?php
use Models\Public\Select_course;
require_once __DIR__ . "/../../vendor/autoload.php";
$pdo = require_once __DIR__ . "/../../config/config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-card {
            max-width: 420px;
            width: 100%;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }
        .form-header {
            padding: 2rem 2rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        .btn-select {
            background-color: #212529;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="form-card">
        <div class="form-header">
            <h4 class="mb-1">Select Course</h4>
            <p class="text-muted mb-0">Choose a course to attend</p>
        </div>
        
        <div class="p-5">
            <form id="courseForm" action="../../controllers/public/select_course.php" method="POST">
                <div class="mb-4">
                    <label class="form-label fw-medium">Course</label>
                    <select class="form-select form-select-lg" id="course" name="course_id" required>
                        <option value="">-- Select a Course --</option>
                        <?php
                        $selectCourse = new Select_course($pdo);
                        $courses = $selectCourse->SelectAllCourses();
                        if ($courses) {
                            foreach ($courses as $course) {
                                echo '<option value="' . htmlspecialchars($course['course_id']) . '">' . htmlspecialchars($course['course_code']) . ' - ' . htmlspecialchars($course['course_title']) . '</option>';
                            }
                        } else {
                            echo '<option value="">No courses available</option>';
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-select w-100">
                    Select Course
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('courseForm').addEventListener('submit', function(e) {

            const selectedCourse = document.getElementById('course').value;
            
            if (selectedCourse) {
                const btn = this.querySelector('button');
                btn.disabled = true;
                btn.textContent = "Processing...";
            }
        });
    </script>
</body>
</html>