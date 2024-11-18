<?php
include 'config.php';

// Handle Add Reservation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_reservation'])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $date = $_POST['date'];
  $time = $_POST['time'];
  $guests = $_POST['guests'];
  $message = $_POST['message'];

  $stmt = $conn->prepare("INSERT INTO reservation (name, email, phone_number, date, time, number_of_guests, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssss", $name, $email, $phone, $date, $time, $guests, $message);

  if ($stmt->execute()) {
    header("Location: tablereservasi.php?status=added");
    exit;
  } else {
    echo "Error: " . $stmt->error;
  }
}

// Handle Update Reservation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_reservation'])) {
  $id_reservation = $_POST['id_reservation'];
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $date = $_POST['date'];
  $time = $_POST['time'];
  $guests = $_POST['guests'];
  $message = $_POST['message'];

  $stmt = $conn->prepare("UPDATE reservation SET name = ?, email = ?, phone_number = ?, date = ?, time = ?, number_of_guests = ?, message = ? WHERE id_reservation = ?");
  $stmt->bind_param("sssssssi", $name, $email, $phone, $date, $time, $guests, $message, $id_reservation);

  if ($stmt->execute()) {
    header("Location: tablereservasi.php?status=updated");
    exit;
  } else {
    echo "Error: " . $stmt->error;
  }
}

// Handle Delete Reservation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_reservation'])) {
  $id_reservation = $_POST['delete_reservation'];

  $stmt = $conn->prepare("DELETE FROM reservation WHERE id_reservation = ?");
  $stmt->bind_param("i", $id_reservation);

  if ($stmt->execute()) {
    header("Location: tablereservasi.php?status=deleted");
    exit;
  } else {
    echo "Error: " . $stmt->error;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reservation Table</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
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
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2 sidebar">
        <h2>Dashboard</h2>
        <div class="profile-image">
          <img src="img/logo.jpg" alt="Foto Profil" class="img-fluid rounded-circle" />
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

      <div class="col-md-10 content">
        <h1>Reservation Table</h1>

        <?php
        if (isset($_GET['status'])) {
          if ($_GET['status'] == 'added') {
            echo "<div class='alert alert-success'>Reservation added successfully!</div>";
          } elseif ($_GET['status'] == 'updated') {
            echo "<div class='alert alert-success'>Reservation updated successfully!</div>";
          } elseif ($_GET['status'] == 'deleted') {
            echo "<div class='alert alert-danger'>Reservation deleted successfully!</div>";
          }
        }
        ?>

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Reservation List</h5>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Telephone</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Guests</th>
                  <th>Message</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // Fetch reservations from the database
                $stmt = $conn->prepare("SELECT id_reservation, name, email, phone_number, date, time, number_of_guests, message FROM reservation");
                if ($stmt->execute()) {
                  $result = $stmt->get_result();
                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      echo "<tr>";
                      echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['number_of_guests']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                      echo "<td>";
                      // Edit button with data attributes for modal
                      echo "<button 
                                    class='btn btn-sm btn-warning edit-btn mr-2'
                                    data-toggle='modal' 
                                    data-target='#editReservationModal' 
                                    data-reservation-id='" . htmlspecialchars($row['id_reservation']) . "' 
                                    data-name='" . htmlspecialchars($row['name']) . "' 
                                    data-email='" . htmlspecialchars($row['email']) . "' 
                                    data-phone='" . htmlspecialchars($row['phone_number']) . "' 
                                    data-date='" . htmlspecialchars($row['date']) . "' 
                                    data-time='" . htmlspecialchars($row['time']) . "' 
                                    data-guests='" . htmlspecialchars($row['number_of_guests']) . "' 
                                    data-message='" . htmlspecialchars($row['message']) . "'>
                                    Edit
                                  </button>";


                      // Delete button
                      echo "<button class='btn btn-danger btn-sm delete-btn' data-toggle='modal' 
                                    data-target='#deleteReservationModal' 
                                    data-id='" . htmlspecialchars($row['id_reservation']) . "'>
                                    Delete
                                  </button>";

                      echo "</td>";
                      echo "</tr>";
                    }
                  } else {
                    echo "<tr><td colspan='8' class='text-center'>No reservations found.</td></tr>";
                  }
                }
                ?>
              </tbody>
            </table>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addReservationModal">Add Reservation</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Reservation Modal -->
  <div class="modal fade" id="addReservationModal" tabindex="-1" role="dialog" aria-labelledby="addReservationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Reservation</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="tablereservasi.php" method="POST">
            <div class="form-group">
              <label for="name">Name:</label>
              <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
              <label for="phone">Phone:</label>
              <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="form-group">
              <label for="date">Date:</label>
              <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="form-group">
              <label for="time">Time:</label>
              <input type="time" class="form-control" id="time" name="time" required>
            </div>
            <div class="form-group">
              <label for="guests">Guests:</label>
              <input type="number" class="form-control" id="guests" name="guests" required>
            </div>
            <div class="form-group">
              <label for="message">Message:</label>
              <textarea class="form-control" id="message" name="message"></textarea>
            </div>
            <button type="submit" name="add_reservation" class="btn btn-primary">Save Reservation</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Reservation Modal -->
  <div class="modal fade" id="editReservationModal" tabindex="-1" role="dialog" aria-labelledby="editReservationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Reservation</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="tablereservasi.php" method="POST">
            <input type="hidden" name="id_reservation" id="edit_id_reservation">
            <div class="form-group">
              <label for="edit_name">Name:</label>
              <input type="text" class="form-control" id="edit_name" name="name" required>
            </div>
            <div class="form-group">
              <label for="edit_email">Email:</label>
              <input type="email" class="form-control" id="edit_email" name="email" required>
            </div>
            <div class="form-group">
              <label for="edit_phone">Phone:</label>
              <input type="text" class="form-control" id="edit_phone" name="phone" required>
            </div>
            <div class="form-group">
              <label for="edit_date">Date:</label>
              <input type="date" class="form-control" id="edit_date" name="date" required>
            </div>
            <div class="form-group">
              <label for="edit_time">Time:</label>
              <input type="time" class="form-control" id="edit_time" name="time" required>
            </div>
            <div class="form-group">
              <label for="edit_guests">Guests:</label>
              <input type="number" class="form-control" id="edit_guests" name="guests" required>
            </div>
            <div class="form-group">
              <label for="edit_message">Message:</label>
              <textarea class="form-control" id="edit_message" name="message"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Reservation Modal -->
  <div class="modal fade" id="deleteReservationModal" tabindex="-1" role="dialog" aria-labelledby="deleteReservationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Delete Reservation</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this reservation?</p>
        </div>
        <div class="modal-footer">
          <form action="tablereservasi.php" method="POST">
            <input type="hidden" name="delete_reservation" id="delete_reservation">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
    // Populate the Edit Reservation Modal with data from the "Edit" button
    $('#editReservationModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget); // Button that triggered the modal
      var id_reservation = button.data('reservation-id');
      var name = button.data('name');
      var email = button.data('email');
      var phone = button.data('phone');
      var date = button.data('date');
      var time = button.data('time');
      var guests = button.data('guests');
      var message = button.data('message');

      var modal = $(this);
      modal.find('#edit_id_reservation').val(id_reservation);
      modal.find('#edit_name').val(name);
      modal.find('#edit_email').val(email);
      modal.find('#edit_phone').val(phone);
      modal.find('#edit_date').val(date);
      modal.find('#edit_time').val(time);
      modal.find('#edit_guests').val(guests);
      modal.find('#edit_message').val(message);
    });

    // Set the reservation ID for deletion in the Delete Modal
    $('#deleteReservationModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget);
      var id_reservation = button.data('id');

      var modal = $(this);
      modal.find('#delete_reservation').val(id_reservation);
    });
  </script>
</body>

</html>