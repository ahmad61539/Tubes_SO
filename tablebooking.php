<?php
include 'config.php';

// Handle Add Booking
if (isset($_POST['add_booking'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO booking (name, phone_number) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $phone);
    if ($stmt->execute()) {
        header("Location: tablebooking.php?status=added");
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Handle Edit Booking
if (isset($_POST['edit_booking'])) {
    $id_booking = $_POST['id_booking'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE booking SET name = ?, phone_number = ? WHERE id_booking = ?");
    $stmt->bind_param("ssi", $name, $phone, $id_booking);

    if ($stmt->execute()) {
        header("Location: tablebooking.php?status=updated");
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Handle Delete Booking
if (isset($_POST['delete_booking'])) {
    $id_booking = $_POST['id_booking'];

    $stmt = $conn->prepare("DELETE FROM booking WHERE id_booking = ?");
    $stmt->bind_param("i", $id_booking);

    if ($stmt->execute()) {
        header("Location: tablebooking.php?status=deleted");
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
    <title>Booking Table</title>
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
                <h1>Booking Table</h1>

                <?php
                if (isset($_GET['status'])) {
                    if ($_GET['status'] == 'added') {
                        echo "<div class='alert alert-success'>Booking added successfully!</div>";
                    } elseif ($_GET['status'] == 'deleted') {
                        echo "<div class='alert alert-danger'>Booking deleted successfully!</div>";
                    } elseif ($_GET['status'] == 'updated') {
                        echo "<div class='alert alert-warning'>Booking updated successfully!</div>";
                    }
                }
                ?>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Booking List</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch bookings from the database
                                $stmt = $conn->prepare("SELECT id_booking, name, phone_number FROM booking");
                                if ($stmt->execute()) {
                                    $result = $stmt->get_result();
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["phone_number"]) . "</td>";
                                            echo "<td>";

                                            // Edit button
                                            echo "<button class='btn btn-sm btn-warning edit-btn mr-2' data-toggle='modal' 
                                  data-target='#editBookingModal' 
                                  data-id='" . htmlspecialchars($row['id_booking']) . "' 
                                  data-name='" . htmlspecialchars($row['name']) . "' 
                                  data-phone='" . htmlspecialchars($row['phone_number']) . "'>
                                  Edit
                                </button>";

                                            // Delete button
                                            echo "<button class='btn btn-danger btn-sm delete-btn' data-toggle='modal' 
                                    data-target='#deleteBookingModal' 
                                    data-id='" . htmlspecialchars($row['id_booking']) . "'>
                                    Delete
                                  </button>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='3' class='text-center'>No bookings found.</td></tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addBookingModal">Add Booking</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Booking Modal -->
    <div class="modal fade" id="addBookingModal" tabindex="-1" role="dialog" aria-labelledby="addBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Booking</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="tablebooking.php" method="POST">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone:</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <button type="submit" name="add_booking" class="btn btn-primary">Save Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Booking Modal -->
    <div class="modal fade" id="editBookingModal" tabindex="-1" role="dialog" aria-labelledby="editBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Booking</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="tablebooking.php" method="POST">
                        <input type="hidden" name="id_booking" id="edit_booking_id">
                        <div class="form-group">
                            <label for="edit_name">Name:</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_phone">Phone:</label>
                            <input type="text" class="form-control" id="edit_phone" name="phone" required>
                        </div>
                        <button type="submit" name="edit_booking" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Booking Modal -->
    <div class="modal fade" id="deleteBookingModal" tabindex="-1" role="dialog" aria-labelledby="deleteBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Booking</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="tablebooking.php" method="POST">
                        <input type="hidden" name="id_booking" id="delete_booking_id">
                        <p>Are you sure you want to delete this booking?</p>
                        <button type="submit" name="delete_booking" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#editBookingModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var name = button.data('name');
            var phone = button.data('phone');

            // Populate the modal form with the booking data
            $('#edit_booking_id').val(id);
            $('#edit_name').val(name);
            $('#edit_phone').val(phone);
        });

        $('#deleteBookingModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');

            // Populate the modal form with the booking ID
            $('#delete_booking_id').val(id);
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>