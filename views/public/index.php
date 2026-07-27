<?php
session_start();
if (!isset($_SESSION['course_id'])) {
    header("Location: select_course.php");
    exit();
}
$courseCode = $_SESSION['course_code'] ?? "unknown course";
$courseId = $_SESSION['course_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Matric Number</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .form-card {
            max-width: 420px;
            width: 100%;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .form-header {
            background: #f8f9fa;
            padding: 2rem 2rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        .form-header h2 {
            margin: 0;
            font-weight: 600;
            color: #212529;
            font-size: 1.5rem;
        }

        .form-body {
            padding: 2rem;
        }

        .matric-input {
            font-size: 1.1rem;
            font-family: monospace;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .btn-submit {
            background-color: #212529;
            color: white;
            border: none;
            padding: 12px 32px;
            font-weight: 500;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #343a40;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(33, 37, 41, 0.2);
        }

        /* Modern Alert */
        .modern-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 1.25rem;
            font-size: 0.9rem;
            font-weight: 500;
            animation: slideIn 0.35s ease;
            position: relative;
        }

        .modern-alert.error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .modern-alert .alert-icon {
            flex-shrink: 0;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: white;
            background: #ef4444;
            margin-top: 1px;
        }

        .modern-alert .alert-text {
            flex: 1;
            line-height: 1.4;
        }

        .modern-alert .alert-close {
            background: none;
            border: none;
            font-size: 1.1rem;
            line-height: 1;
            color: inherit;
            opacity: 0.5;
            cursor: pointer;
            padding: 0;
        }

        .modern-alert.success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .modern-alert.success .alert-icon {
            background: #10b981;
        }

        .modern-alert .alert-close:hover {
            opacity: 1;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="form-card">
        <!-- Header -->
        <div class="form-header">
            <h2>Student attendance</h2>
            <p class="text-muted mt-2 mb-0">Welcome to <?= $courseCode ?> lecture </p>
            <p class="text-muted mt-2 mb-0">Enter your matriculation number</p>
        </div>

        <!-- Form Body -->
        <div class="form-body">
            <form id="matricForm" method="POST" action="../../controllers/public/attendance.php">
                <!-- Display error message if exists -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="modern-alert error" id="searchAlert">
                        <div class="alert-icon">!</div>
                        <div class="alert-text"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
                        <button type="button" class="alert-close" onclick="document.getElementById('searchAlert').remove()">&times;</button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Display success message if exists -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="modern-alert success" id="searchSuccessAlert">
                        <div class="alert-icon">✓</div>
                        <div class="alert-text"><?php echo htmlspecialchars($_SESSION['success']); ?></div>
                        <button type="button" class="alert-close" onclick="document.getElementById('searchSuccessAlert').remove()">&times;</button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                <div class="mb-4">
                    <label for="matricNumber" class="form-label fw-medium text-dark">
                        Matriculation Number
                    </label>
                    <input
                        type="text"
                        class="form-control form-control-lg matric-input"
                        id="matricNumber"
                        name="matric_number"
                        placeholder="U20123456"
                        autofocus
                        required
                        pattern="[A-Za-z0-9]+"
                        maxlength="15">
                    <input type="hidden" name="course_id" value="<?= htmlspecialchars($courseId) ?>">
                </div>

                <button type="submit" class="btn btn-submit w-100">
                    Submit
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('matricForm').addEventListener('submit', function(e) {
            const matric = document.getElementById('matricNumber').value.trim().toUpperCase();

            if (matric) {
                // Simple success feedback
                const btn = this.querySelector('button');
                const originalText = btn.textContent;

                btn.disabled = true;
                btn.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                    Verifying...
                `;


            }
        });

        // Auto uppercase input
        document.getElementById('matricNumber').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    </script>
</body>

</html>