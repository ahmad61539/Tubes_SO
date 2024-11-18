<?php
include 'config.php';

$sql = "SELECT name_food, description, price, image FROM menu";
$result = $conn->query($sql);

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
  <title>Menu</title>
  <style>
    @import url("https://fonts.googleapis.com/css?family=Big+Shoulders+Text:100,300,400,500,600,700,800,900&display=swap");

    body {
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
      color: #333;
    }

    .container {
      max-width: 800px;
      margin: 50px auto;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }

    h1 {
      text-align: center;
    }

    .menu-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 0;
      border-bottom: 1px solid #ddd;
    }

    .menu-item img {
      max-width: 100px;
      height: auto;
      margin-right: 20px;
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
      height: 980px;
      background-image: url(img/petr-sevcovic-qE1jxYXiwOA-unsplash.jpg);
      background-size: cover;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
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
  <div class="section-intro">
    <div class="container mt-5">
      <h1>Menu</h1>

      <?php
      if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
      ?>
          <div class="menu-item d-flex mb-4">
            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name_food']); ?>" class="img-thumbnail" width="150" height="150" />
            <div class="ml-3">
              <h3><?php echo htmlspecialchars($row['name_food']); ?></h3>
              <p style="text-align: justify;"><?php echo htmlspecialchars($row['description']); ?></p>
              <p>Price: Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
            </div>
          </div>
      <?php
        endwhile;
      else:
        echo "<p>No menu items available.</p>";
      endif;

      $conn->close();
      ?>
    </div>
  </div>
</body>

</html>