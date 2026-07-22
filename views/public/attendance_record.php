<?php
  session_start();
  $studentData = $_SESSION['student_data'] ?? null;
  if (!$studentData) {
      header('Location: ../../views/public/search.php');
      exit;
  }

  require_once __DIR__ . "/../../vendor/autoload.php";
  $pdo = require_once __DIR__ . "/../../config/config.php";

  use Models\Public\attendance_record;

  // NOTE: adjust these two keys if your $_SESSION['student_data'] uses different names
  $studentId = $studentData['student_id'] ?? '';
  $session   = $studentData['session'] ?? '2025/2026'; // fallback until session is passed in properly

  $attendance = new attendance_record($pdo);
  $attendance->studentId = $studentId;
  $attendance->session   = $session;

  $courses        = $attendance->getAttendanceRecordsByStudentId();
  $overallRate    = $attendance->getOverallAttendanceRate();

  // Maps a status label to the Bootstrap classes it needs (badge, progress bar, percentage text)
  $statusStyles = [
      'Excellent' => ['badge' => 'bg-success-subtle text-success', 'bar' => 'bg-success', 'pct' => 'fw-semibold'],
      'On Track'  => ['badge' => 'bg-info-subtle text-info',       'bar' => 'bg-info',    'pct' => 'fw-semibold text-muted'],
      'Warning'   => ['badge' => 'bg-warning-subtle text-warning', 'bar' => 'bg-warning', 'pct' => 'fw-semibold text-muted'],
      'Critical'  => ['badge' => 'bg-danger-subtle text-danger',   'bar' => 'bg-danger',  'pct' => 'fw-semibold text-muted'],
  ];

  // Overall attendance number color follows the same bands as row status
  $overallColorClass = 'text-success';
  if ($overallRate < 60) {
      $overallColorClass = 'text-danger';
  } elseif ($overallRate < 80) {
      $overallColorClass = 'text-warning';
  } elseif ($overallRate < 90) {
      $overallColorClass = 'text-info';
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Attendance Dashboard</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      color: #333;
    }
    .table-container {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
      padding: 1.5rem;
    }
    .table th {
      font-weight: 600;
      font-size: 0.85rem;
      letter-spacing: 0.5px;
      color: #6c757d;
      border-bottom-width: 1px !important;
    }
    .table td {
      vertical-align: middle;
      font-size: 0.9rem;
      padding: 1rem 0.75rem;
    }
    .badge-status {
      font-size: 0.75rem;
      font-weight: 500;
      padding: 0.35em 0.8em;
      border-radius: 50rem;
    }
    .progress-sm {
      height: 6px;
      border-radius: 10px;
    }
  </style>
</head>
<body>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">

      <div class="table-container">

        <!-- Student Header Info -->
        <div class="d-flex align-items-center justify-content-between mb-4">
          <div>
            <h4 class="fw-bold mb-1"><?= htmlspecialchars($studentData['student_fname'] ?? '') ?> <?= htmlspecialchars($studentData['student_lname'] ?? '') ?></h4>
            <span class="text-muted small">Student ID: #STU-<?= htmlspecialchars((string) $studentId) ?></span>
          </div>
          <div class="text-end">
            <span class="text-muted d-block small">Overall Attendance</span>
            <strong class="fs-5 <?= $overallColorClass ?>"><?= htmlspecialchars((string) $overallRate) ?>%</strong>
          </div>
        </div>

        <!-- Sleek Table -->
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr class="text-uppercase">
                <th scope="col" class="ps-3">Course Code</th>
                <th scope="col">Course Title</th>
                <th scope="col">Classes Attended</th>
                <th scope="col">Attendance Rate</th>
                <th scope="col" class="text-end pe-3">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($courses)): ?>
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">No attendance records found for this session.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($courses as $course): ?>
                  <?php
                    $status = $course['status'];
                    $styles = $statusStyles[$status] ?? $statusStyles['Critical'];
                    $rate   = $course['attendance_rate'];
                  ?>
                  <tr>
                    <td class="fw-semibold ps-3 text-secondary"><?= htmlspecialchars($course['course_code']) ?></td>
                    <td>
                      <span class="fw-semibold d-block"><?= htmlspecialchars($course['course_title']) ?></span>
                    </td>
                    <td><?= (int) $course['total_attended'] ?> / <?= (int) $course['total_classes_held'] ?></td>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <span class="<?= $styles['pct'] ?>"><?= htmlspecialchars((string) $rate) ?>%</span>
                        <div class="progress progress-sm flex-grow-1" style="width: 70px;">
                          <div class="progress-bar <?= $styles['bar'] ?>" role="progressbar" style="width: <?= $rate ?>%" aria-valuenow="<?= $rate ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                      </div>
                    </td>
                    <td class="text-end pe-3">
                      <span class="badge badge-status <?= $styles['badge'] ?>"><?= htmlspecialchars($status) ?></span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>