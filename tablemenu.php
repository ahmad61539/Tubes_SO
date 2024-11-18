<?php
include 'config.php';

if (isset($_POST['add_menu'])) {
    $name_food = $_POST['name_food'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Set up the image upload
    $target_dir = "img/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    $upload_ok = true;

    // Check if the file was uploaded successfully
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Prepare the SQL insert statement
        $stmt = $conn->prepare("INSERT INTO menu (name_food, price, description, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $name_food, $price, $description, $target_file);

        if ($stmt->execute()) {
            // Redirect if insertion is successful
            header("Location: tablemenu.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Handle Update Menu Item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_menu'])) {
    $id_menu = $_POST['id_menu'];
    $name_food = $_POST['name_food'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image'];
        $image_name = $image['name'];
        $image_tmp_name = $image['tmp_name'];
        $image_size = $image['size'];
        $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($image_extension, $allowed_extensions)) {
            $new_image_name = uniqid('', true) . '.' . $image_extension;
            $image_upload_path = 'uploads/' . $new_image_name;
            move_uploaded_file($image_tmp_name, $image_upload_path);
        }
    } else {
        // If no new image, keep the old one
        $image_upload_path = $_POST['current_image'];
    }

    $stmt = $conn->prepare("UPDATE menu SET name_food = ?, description = ?, price = ?, image = ? WHERE id_menu = ?");
    $stmt->bind_param("ssssi", $name_food, $description, $price, $image_upload_path, $id_menu);

    if ($stmt->execute()) {
        header("Location: tablemenu.php?status=updated");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Handle Delete Menu Item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_menu'])) {
    $id_menu = $_POST['delete_menu'];

    $stmt = $conn->prepare("DELETE FROM menu WHERE id_menu = ?");
    $stmt->bind_param("i", $id_menu);

    if ($stmt->execute()) {
        header("Location: tablemenu.php?status=deleted");
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
    <title>Menu Table</title>
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
                    <img src="img/logo.jpg" alt="Logo" class="img-fluid rounded-circle" />
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
                <h1>Menu Table</h1>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Menu List</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch menu items from the database
                                $stmt = $conn->prepare("SELECT id_menu, name_food, description, price, image FROM menu");
                                if ($stmt->execute()) {
                                    $result = $stmt->get_result();
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td><img src='" . htmlspecialchars($row['image']) . "' alt='Food Image' width='100' /></td>";
                                            echo "<td>" . htmlspecialchars($row['name_food']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                            echo "<td>Rp " . number_format($row['price'], 0, ',', '.') . "</td>";
                                            echo "<td>";
                                            echo "<button class='btn btn-sm btn-warning edit-btn mr-2'
                                     data-toggle='modal' data-target='#editMenuModal'
                                     data-menu-id='" . htmlspecialchars($row['id_menu']) . "'
                                     data-name='" . htmlspecialchars($row['name_food']) . "'
                                     data-description='" . htmlspecialchars($row['description']) . "'
                                     data-price='" . htmlspecialchars($row['price']) . "'
                                     data-image='" . htmlspecialchars($row['image']) . "'>
                                     Edit
                                    </button>";
                                            echo "<button class='btn btn-danger btn-sm delete-btn' data-toggle='modal' 
                                    data-target='#deleteMenuModal' data-id='" . htmlspecialchars($row['id_menu']) . "'>
                                    Delete
                                  </button>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>No menu items found.</td></tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addMenuModal">Add Menu</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Menu Modal -->
    <div class="modal fade" id="addMenuModal" tabindex="-1" role="dialog" aria-labelledby="addMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Menu Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="tablemenu.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name_food">Name:</label>
                            <input type="text" class="form-control" id="name_food" name="name_food" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <input type="text" class="form-control" id="description" name="description" required>
                        </div>
                        <div class="form-group">
                            <label for="price">Price:</label>
                            <input type="text" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="form-group">
                            <label for="image">Image:</label>
                            <input type="file" class="form-control" id="image" name="image" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="add_menu">Add Menu Item</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Menu Modal -->
    <div class="modal fade" id="editMenuModal" tabindex="-1" role="dialog" aria-labelledby="editMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Menu Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="tablemenu.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_menu" id="edit_id_menu">
                        <div class="form-group">
                            <label for="edit_name_food">Name:</label>
                            <input type="text" class="form-control" id="edit_name_food" name="name_food" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_description">Description:</label>
                            <input type="text" class="form-control" id="edit_description" name="description" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_price">Price:</label>
                            <input type="text" class="form-control" id="edit_price" name="price" required>
                        </div>
                        <div class="form-group">
                            <label for="editImage">Image:</label>
                            <input type="file" class="form-control" id="editImage" name="image">
                            <img id="currentImage" src="" alt="Current Image" width="100" style="margin-top:10px;">
                        </div>
                        <input type="hidden" name="current_image" id="current_image">
                        <button type="submit" class="btn btn-warning" name="add_menu">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Menu Modal -->
    <div class="modal fade" id="deleteMenuModal" tabindex="-1" role="dialog" aria-labelledby="deleteMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Menu Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this menu item?</p>
                </div>
                <div class="modal-footer">
                    <form action="tablemenu.php" method="POST">
                        <input type="hidden" name="delete_menu" id="delete_menu_id">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Populate the Edit Modal
        $('#editMenuModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var idMenu = button.data('menu-id');
            var nameFood = button.data('name');
            var description = button.data('description');
            var price = button.data('price');
            var image = button.data('image');

            var modal = $(this);
            modal.find('#edit_id_menu').val(idMenu);
            modal.find('#edit_name_food').val(nameFood);
            modal.find('#edit_description').val(description);
            modal.find('#edit_price').val(price);
            modal.find('#currentImage').attr('src', image); // Set current image preview
            modal.find('#current_image').val(image); // Set the hidden field with the current image path
        });

        // Set the Delete Menu ID
        $('#deleteMenuModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var idMenu = button.data('id');
            var modal = $(this);
            modal.find('#delete_menu_id').val(idMenu);
        });
    </script>
</body>

</html>