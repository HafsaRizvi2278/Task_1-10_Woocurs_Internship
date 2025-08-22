<?php 
include 'config.php';

// Handle search query
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM entries 
        WHERE name LIKE '%$search%' OR email LIKE '%$search%' 
        ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View Entries - Restaurant Menu App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="index.html">Restaurant Menu App</a>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="form.html">Form</a></li>
        <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
        <li class="nav-item"><a class="nav-link active" href="view.php">View Entries</a></li>
      </ul>
    </div>
  </nav>

  <div class="container mt-5">
    <h2 class="mb-3">All Entries</h2>

    <!-- Search Form -->
    <form method="GET" class="mb-3">
      <input type="text" name="search" class="form-control" 
             placeholder="Search by Name or Email"
             value="<?php if(isset($_GET['search'])) echo $_GET['search']; ?>">
    </form>

    <!-- Entries Table -->
    <table class="table table-bordered table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Created At</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['phone']}</td>
                    <td>{$row['created_at']}</td>
                  </tr>";
          }
        } else {
          echo "<tr><td colspan='5' class='text-center'>No entries found</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3 mt-5">
    Developed by Hafsa Rizvi | 2025
  </footer>
</body>
</html>
