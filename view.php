<?php 
include 'config.php';

// Pagination settings
$limit = 5; // entries per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Handle search query
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Count total entries for pagination
$count_sql = "SELECT COUNT(*) as total FROM entries 
              WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
$count_result = $conn->query($count_sql);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch entries for current page
$sql = "SELECT * FROM entries 
        WHERE name LIKE '%$search%' OR email LIKE '%$search%' 
        ORDER BY id DESC
        LIMIT $limit OFFSET $offset";
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
          <th>Actions</th>
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
                    <td>
                      <a href='update.php?id={$row['id']}' class='btn btn-primary btn-sm'>Edit</a>
                      <a href='delete.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                    </td>
                  </tr>";
          }
        } else {
          echo "<tr><td colspan='6' class='text-center'>No entries found</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
      <ul class="pagination justify-content-center">
        <?php if($page > 1): ?>
          <li class="page-item">
            <a class="page-link" href="?search=<?= $search ?>&page=<?= $page-1 ?>">Previous</a>
          </li>
        <?php endif; ?>

        <?php for($i=1; $i<=$total_pages; $i++): ?>
          <li class="page-item <?= ($i==$page)?'active':'' ?>">
            <a class="page-link" href="?search=<?= $search ?>&page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>

        <?php if($page < $total_pages): ?>
          <li class="page-item">
            <a class="page-link" href="?search=<?= $search ?>&page=<?= $page+1 ?>">Next</a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3 mt-5">
    Developed by Hafsa Rizvi | 2025
  </footer>
</body>
</html>
