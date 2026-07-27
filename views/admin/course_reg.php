<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Registration</title>
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
            max-width: 460px;
            width: 100%;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }
        .form-header {
            text-align: center;
            padding: 2rem 2rem 1.5rem;
            border-bottom: 1px solid #eee;
        }
        .btn-register {
            background-color: #212529;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
        }
        .btn-register:hover {
            background-color: #343a40;
        }
    </style>
</head>
<body>
    <div class="form-card">
        <div class="form-header">
            <h4 class="mb-1">Course Registration</h4>
            <p class="text-muted mb-0">Fill in the details below</p>
        </div>
        
        <div class="p-4 p-md-5">
            <form id="courseForm" action="../../controllers/admin/course_reg.php" method="POST">
                
                <!-- Course Name -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['error']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['success']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Course Name</label>
                    <input type="text" class="form-control form-control-lg" 
                           id="courseName" name="course_title" placeholder="e.g. Introduction to Programming" required>
                </div>

                <!-- Course Code -->
                <div class="mb-3">
                    <label class="form-label">Course Code</label>
                    <input type="text" class="form-control form-control-lg text-uppercase" 
                           id="courseCode" name="course_code" placeholder="e.g. CSC101" maxlength="10" required>
                </div>


                <button type="submit" class="btn btn-register w-100 mt-3">
                    Register Course
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('courseForm').addEventListener('submit', function(e) {
            const courseName = document.getElementById('courseName').value.trim();
            const courseCode = document.getElementById('courseCode').value.trim().toUpperCase();

            if (courseName && courseCode) {
                const btn = this.querySelector('button');
                btn.disabled = true;
                btn.textContent = "Processing...";
            }
        });

        // Auto uppercase course code
        document.getElementById('courseCode').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    </script>
</body>
</html>