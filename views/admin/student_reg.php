<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
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
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .form-header {
            background: #f8f9fa;
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        .form-header h2 {
            margin: 0;
            font-weight: 600;
            color: #212529;
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

        .modern-alert .alert-close:hover {
            opacity: 1;
        }

        .modern-alert.success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .modern-alert.success .alert-icon {
            background: #10b981;
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
        <div class="form-header">
            <h2>Student Registration</h2>
            <p class="text-muted mb-0">Please fill in your details</p>
        </div>

        <div class="p-4 p-md-5">
            <form id="studentForm" method="POST" action="../../controllers/admin/student_reg.php">
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

                <div class="row">
                    <!-- First Name -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">First Name</label>
                        <input type="text" class="form-control form-control-lg" id="firstName" name="first_name"
                            placeholder="John" required>
                    </div>

                    <!-- Last Name -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Last Name</label>
                        <input type="text" class="form-control form-control-lg" id="lastName" name="last_name"
                            placeholder="Doe" required>
                    </div>
                </div>

                <!-- Matric Number -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Matriculation Number</label>
                    <input type="text" class="form-control form-control-lg matric-input" name="matric_number"
                        id="matricNumber" placeholder="U20123456"
                        maxlength="15" required>
                </div>

                <!-- Department -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Department</label>
                    <select class="form-select form-select-lg" name="department" id="department" required>
                        <option value="">Select Department</option>
                        <option value="1">Computer Science</option>
                        <option value="2">Computer Science Education</option>
                    </select>
                </div>

                <button type="submit" name="submit" class="btn btn-dark btn-lg w-100">
                    Submit Registration
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto uppercase for matric number
        document.getElementById('matricNumber').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Form submission
        document.getElementById('studentForm').addEventListener('submit', function(e) {
            // e.preventDefault();

            const firstName = document.getElementById('firstName').value.trim();
            const lastName = document.getElementById('lastName').value.trim();
            const matric = document.getElementById('matricNumber').value.trim();
            const department = document.getElementById('department').value;

            if (firstName && lastName && matric && department) {
                const btn = this.querySelector('button');
                const originalText = btn.textContent;

                btn.disabled = true;
                btn.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Processing...
                `;
            }
        });
    </script>
</body>

</html>