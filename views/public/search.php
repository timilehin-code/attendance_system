<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Record Search</title>
  
  <!-- Bootstrap 5 CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  
  <style>
    /* Sleek & Modern Global Overrides */
    body {
      background-color: #f4f6f9;
      color: #333333;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }
    
    /* Search Card Container styling */
    .custom-card {
      background: #ffffff;
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
    }
  </style>
</head>
<body>

<div class="container my-5" style="max-width: 950px;">
  
  <!-- Section Title -->
  <div class="mb-4 text-center text-sm-start">
    <h3 class="fw-bold text-dark mb-1">Attendance Portal</h3>
    <p class="text-muted small">Search and manage individual academic records</p>
  </div>

  <!-- MODERN SEARCH FORM CARD ONLY -->
  <div class="card custom-card p-4 mb-4">
    <div class="row align-items-center">
      
      <!-- Left side: Prompt text -->
      <div class="col-md-4 mb-3 mb-md-0">
        <h5 class="fw-bold mb-1 text-dark">Find Student Record</h5>
        <p class="text-muted small mb-0">Enter a matriculation number to fetch the record.</p>
      </div>
      
      <!-- Right side: Input Form -->
      <div class="col-md-8">
        <form action="../../controllers/public/search.php" method="POST">
            <!-- Display error message if exists -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['error']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
          <div class="input-group">
            <input 
              type="text" 
              name="matric_no"
              class="form-control form-control-lg border-end-0 bg-light" 
              placeholder="e.g. 23120101072" 
              style="font-size: 0.95rem; border-color: #e2e8f0; border-radius: 10px 0 0 10px; outline: none; box-shadow: none;"
              required
            >
            <button 
              class="btn btn-dark btn-lg px-4 d-flex align-items-center justify-content-center" 
              type="submit"
              style="border-radius: 0 10px 10px 0; font-size: 0.95rem;"
            >
              <!-- Minimal Search Glass SVG Icon -->
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search me-2" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
              </svg>
              Search
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>

</div>

<!-- Bootstrap 5 JS Bundle CDN (Includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>