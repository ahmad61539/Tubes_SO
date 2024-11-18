<?php
include 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $date = $_POST['date'];
  $time = $_POST['time'];
  $guests = (int)$_POST['guests'];
  $messageContent = trim($_POST['message']);


  if (empty($name) || empty($email) || empty($phone) || empty($date) || empty($time) || $guests < 1) {
    $message = "<div class='alert alert-danger text-center'>Semua field harus diisi dengan benar!</div>";
  } else {

    $sql = "INSERT INTO reservation (name, email, phone_number, date, time, number_of_guests, message)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssis", $name, $email, $phone, $date, $time, $guests, $messageContent);

    if ($stmt->execute()) {
      $message = "<div class='alert alert-success text-center'>Reservasi berhasil dikirim! Kami akan menghubungi Anda segera.</div>";
    } else {
      $message = "<div class='alert alert-danger text-center'>Terjadi kesalahan: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link
    rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
    crossorigin="anonymous" />
  <title>Reservation</title>
  <style>
    @import url("https://fonts.googleapis.com/css?family=Big+Shoulders+Text:100,300,400,500,600,700,800,900&display=swap");

    body {
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
      color: #333;
    }

    .container {
      max-width: 500px;
      margin: 50px auto;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }

    h1 {
      text-align: center;
    }

    form {
      display: flex;
      flex-direction: column;
      width: 60%;
      margin: 0 auto;
    }

    label {
      margin-top: 10px;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"],
    textarea {
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ddd;
      border-radius: 3px;
    }

    button {
      background-color: #4caf50;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 3px;
      cursor: pointer;
      margin-top: 15px;
    }

    .nav-flex-row {
      display: flex;
      flex-direction: row;
      justify-content: center;
      position: absolute;
      z-index: 100;
      left: 0;
      width: 100%;
      padding: 0;
    }

    .nav-flex-row li {
      text-decoration: none;
      list-style-type: none;
      padding: 20px 15px;
    }

    .nav-flex-row li a {
      font-family: "Big Shoulders Text", cursive;
      color: #ffffff;
      font-size: 1.5em;
      text-transform: uppercase;
      font-weight: 300;
    }

    .section-intro {
      height: 1200px;
      background-image: url(img/petr-sevcovic-qE1jxYXiwOA-unsplash.jpg);
      background-size: cover;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .alert-container {
      position: fixed;
      top: 10%;
      left: 50%;
      transform: translateX(-50%);
      width: 100%;
      max-width: 500px;
      z-index: 1000;
    }
  </style>
</head>

<body>
  <nav>
    <ul class="nav-flex-row">
      <li class="nav-item">
        <a href="index.php">Home</a>
      </li>
      <li class="nav-item">
        <a href="reservation.php">Reservation</a>
      </li>
      <li class="nav-item">
        <a href="menu.php">Menu</a>
      </li>
      <li class="nav-item"><a href="login.php">Log in</a></li>
    </ul>
  </nav>

  <section class="section-intro">
    <div class="container mt-5">
      <?php if ($message): ?>
        <div class="alert-container">
          <?php echo $message; ?>
        </div>
        <script>
          setTimeout(function() {
            document.querySelector('.alert-container').style.display = 'none';
          }, 5000);
        </script>
      <?php endif; ?>

      <h1 class="text-center mb-4">Reservation</h1>
      <form action="reservation.php" method="post" class="needs-validation" novalidate>
        <div class="form-group">
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="phone">Telephone:</label>
          <input type="tel" id="phone" name="phone" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="date">Date:</label>
          <input type="date" id="date" name="date" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="time">Time:</label>
          <input type="time" id="time" name="time" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="guests">Number of Guests:</label>
          <input type="number" id="guests" name="guests" class="form-control" min="1" required>
        </div>
        <div class="form-group">
          <label for="message">Message:</label>
          <textarea id="message" name="message" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Send Reservation</button>
      </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </section>
</body>

</html>