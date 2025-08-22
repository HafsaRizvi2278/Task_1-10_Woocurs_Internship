<?php
include 'config.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = "SELECT * FROM entries WHERE id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Server-side validation
    if(empty($name) || empty($email) || empty($phone)){
        die("All fields are required!");
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        die("Invalid email format!");
    }
    if(!is_numeric($phone) || strlen($phone) != 10){
        die("Phone must be 10 digits!");
    }

    $sql = "UPDATE entries SET name='$name', email='$email', phone='$phone' WHERE id=$id";

    if($conn->query($sql) === TRUE){
        header("Location: view.php");
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Entry - Restaurant Menu App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
<h2>Edit Entry</h2>
<form method="POST" action="">
    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" value="<?php echo $row['name']; ?>" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" value="<?php echo $row['email']; ?>" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo $row['phone']; ?>" class="form-control" required>
    </div>
    <button type="submit" name="update" class="btn btn-success">Update</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
</body>
</html>
