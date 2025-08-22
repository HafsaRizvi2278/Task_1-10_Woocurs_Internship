<?php 
include 'config.php';

// Pagination settings
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Handle search query
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Count total entries
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
  tr:hover { background-color: #f2f2f2; cursor: pointer; }
  th { cursor: pointer; }
</style>
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

  <!-- Table -->
  <div class="table-responsive">
  <table class="table table-bordered table-striped table-hover" id="entriesTable">
    <thead class="table-dark">
      <tr>
        <th onclick="sortTable(0)">ID</th>
        <th onclick="sortTable(1)">Name</th>
        <th onclick="sortTable(2)">Email</th>
        <th onclick="sortTable(3)">Phone</th>
        <th onclick="sortTable(4)">Created At</th>
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
                    <button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteModal{$row['id']}'>Delete</button>
                    
                    <!-- Delete Modal -->
                    <div class='modal fade' id='deleteModal{$row['id']}' tabindex='-1' aria-hidden='true'>
                      <div class='modal-dialog'>
                        <div class='modal-content'>
                          <div class='modal-header'>
                            <h5 class='modal-title'>Confirm Delete</h5>
                            <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                          </div>
                          <div class='modal-body'>
                            Are you sure you want to delete this entry?
                          </div>
                          <div class='modal-footer'>
                            <a href='delete.php?id={$row['id']}' class='btn btn-danger'>Yes, Delete</a>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>";
        }
      } else {
        echo "<tr><td colspan='6' class='text-center'>No entries found</td></tr>";
      }
      ?>
    </tbody>
  </table>
  </div>

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

<!-- Sorting JS -->
<script>
function sortTable(n) {
  let table = document.getElementById("entriesTable");
  let switching = true;
  while(switching) {
    switching = false;
    let rows = table.rows;
    for(let i = 1; i < rows.length - 1; i++){
      let shouldSwitch = false;
      let x = rows[i].getElementsByTagName("TD")[n];
      let y = rows[i+1].getElementsByTagName("TD")[n];
      if(x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) { shouldSwitch = true; break; }
      if(shouldSwitch) {
        rows[i].parentNode.insertBefore(rows[i+1], rows[i]);
        switching = true;
      }
    }
  }
}
</script>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3 mt-5">
  Developed by Hafsa Rizvi | 2025
</footer>
</body>
</html>
