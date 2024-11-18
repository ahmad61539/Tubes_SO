<?php
include 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $conn->real_escape_string($_POST['name']);
  $phone = $conn->real_escape_string($_POST['phone_number']);

  $sql = "INSERT INTO booking (name, phone_number) VALUES ('$name', '$phone')";

  if ($conn->query($sql) === TRUE) {
    $message = "Data Has Been Saved!";
  } else {
    $message = "Error: " . $sql . "<br>" . $conn->error;
  }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="stylesheet" href="style.css" />
  <link
    rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
    crossorigin="anonymous" />
  <title>Restaurant Website</title>
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
    <header>
      <h1>FoodIT</h1>
    </header>
    <div class="link-to-book-wrapper">
      <a class="link-to-book" href="reservation.php">Book a table</a>
    </div>
  </section>

  <section class="about-section">
    <article>
      <h3>FOODIT restaurant is the best.</h3>
      <p>
        FOODIT is a modern restaurant with a relaxed yet elegant concept,
        perfect for food lovers seeking a memorable dining experience. With a
        warm and cozy interior design, FOODIT offers dishes that blend local
        and international flavors, from light appetizers to diverse and
        delicious main courses. The restaurant is also known for its aesthetic
        presentation, making each dish not only a treat for the taste buds but
        also visually appealing.
      </p>
      <p>
        In addition to excellent taste, FOODIT is committed to using fresh,
        high-quality ingredients. Friendly and professional service is a
        priority, creating a pleasant atmosphere for guests. FOODIT is
        suitable for various occasions, from lunch with friends to romantic
        dinners and special celebrations. With competitive prices and
        guaranteed quality, FOODIT is ready to be the best choice for those
        who want to enjoy exceptional dishes in a comfortable and charming
        setting.
      </p>
    </article>
  </section>

  <div
    id="carouselExampleControls"
    class="carousel slide"
    data-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img
          src="img/rachel-park-hrlvr2ZlUNk-unsplash.jpg"
          class="d-block w-100"
          alt="food" />
      </div>
      <div class="carousel-item">
        <img
          src="img/lily-banse--YHSwy6uqvk-unsplash.jpg"
          class="d-block w-100"
          alt="food" />
      </div>
      <div class="carousel-item">
        <img
          src="img/brooke-lark-aGjP08-HbYY-unsplash.jpg"
          class="d-block w-100"
          alt="food" />
      </div>
    </div>
    <a
      class="carousel-control-prev"
      href="#carouselExampleControls"
      role="button"
      data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a
      class="carousel-control-next"
      href="#carouselExampleControls"
      role="button"
      data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>

  <div class="container">
    <div class="row-flex">
      <div class="flex-column-form">
        <h3>Make a booking</h3>
        <div class="container mt-5">
          <h2>Form Reservasi</h2>

          <?php if (!empty($message)): ?>
            <div class="alert alert-success">
              <?php echo $message; ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="index.php">
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
              <label for="phone_number">Phone Number</label>
              <input type="text" class="form-control" id="phone_number" name="phone_number" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>

      </div>
      <div class="opening-time">
        <h3>Opening times</h3>
        <p>
          <span>Monday—Thursday: 08:00 — 22:00</span>
          <span>Friday—Saturday: 09:00 — 23:00 </span>
          <span>Sunday: 10:00 — 17:00</span>
        </p>
      </div>
      <div class="contact-adress">
        <h3>Contact</h3>
        <p>
          <span>0895 2961 5638</span>
          <span>Bengkulu</span>
          <span>Indonesia, 4421</span>
        </p>
      </div>
    </div>
  </div>

  <script
    src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
    crossorigin="anonymous"></script>
  <script
    src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
    crossorigin="anonymous"></script>
</body>

<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ex ipsum eveniet id at sed dolore dolorum! Soluta reiciendis nulla expedita? Voluptas consequatur iusto quasi dolores nisi commodi, cupiditate perferendis reprehenderit.</p>

</html>