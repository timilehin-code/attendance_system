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
                    <input type="text" class="form-control form-control-lg matric-input"  name="matric_number"
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