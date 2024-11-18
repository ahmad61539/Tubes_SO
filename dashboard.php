<?php
include 'config.php';

// Fungsi untuk menghitung total data
function getTotal($table)
{
  global $conn;
  $result = $conn->query("SELECT COUNT(*) as total FROM $table");
  $row = $result->fetch_assoc();
  return $row['total'];
}

$totalReservations = getTotal('reservation');
$totalMenus = getTotal('menu');
$totalBookings = getTotal('booking');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <link
    rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <style>
    body {
      font-family: sans-serif;
      background-color: #f8f9fa;
    }

    .sidebar {
      background-color: #343a40;
      color: #fff;
      height: 270vh;
      padding: 20px;
    }

    .sidebar a {
      color: #fff;
      display: block;
      padding: 10px 0;
    }

    .content {
      padding: 40px;
    }

    .card {
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .featured-number {
      font-size: 3rem;
      font-weight: bold;
    }

    .profile-image {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      overflow: hidden;
      margin: 20px auto;
    }

    .profile-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    img {
      max-width: 150px;
      height: auto;
    }
  </style>
</head>

<body>
  <?php
  session_start();

  ?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2 sidebar">
        <h2>Dashboard</h2>
        <div class="profile-image">
          <img src="img/logo.jpg" alt="Foto Profil" />
        </div>
        <div class="text-center">
          <h6>User Name</h6>
          <p>Admin</p>
        </div>
        <a href="dashboard.php">Dashboard</a>
        <a href="tablereservasi.php">Reservasi</a>
        <a href="tablebooking.php">Booking</a>
        <a href="tablemenu.php">Menu</a>
        <a href="index.php"><i class="fas fa-sign-out-alt"></i> Exit</a>
      </div>
      <div class="col-md-9 content">
        <h1>Welcome to the Dashboard</h1>
        <div class="row">
          <div class="col-md-4">
            <div class="card mb-4">
              <div class="card-body">
                <h5 class="card-title">Total Reservation</h5>
                <p class="featured-number"><?php echo $totalReservations; ?></p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card mb-4">
              <div class="card-body">
                <h5 class="card-title">Total Menu</h5>
                <p class="featured-number"><?php echo $totalMenus; ?></p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card mb-4">
              <div class="card-body">
                <h5 class="card-title">Total Booking</h5>
                <p class="featured-number"><?php echo $totalBookings; ?></p>
              </div>
            </div>
          </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>