<?php
include('db.php'); // Database connection

// Search functionality
$search_query = "";
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
}

// Fetch sellers from the database with search filter if provided
$sql = "SELECT * FROM sellers WHERE seller_id LIKE '%$search_query%' OR seller_name LIKE '%$search_query%'";
$result = $conn->query($sql);  // Execute the query
$sellers = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $sellers[] = $row;
    }
}

// Function to add a new seller
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $seller_id = $_POST['seller_id']; // Get seller_id from form input
    $name = $_POST['name'];
    $contract = $_POST['contract'];
    $city = $_POST['city'];

    // Insert the new seller into the database
    $insert_sql = "INSERT INTO sellers (seller_id, seller_name, contract_info, city) VALUES ('$seller_id', '$name', '$contract', '$city')";
    $conn->query($insert_sql);

    // Redirect to refresh the page with new data
    header("Location: seller_info.php");
    exit;
}

// Function to edit seller
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $contract = $_POST['contract'];
    $city = $_POST['city'];
    
    $update_sql = "UPDATE sellers SET seller_name='$name', contract_info='$contract', city='$city' WHERE seller_id=$id";
    $conn->query($update_sql);
    header("Location: seller_info.php"); // Redirect after updating
    exit;
}

// Function to delete seller
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = $_POST['id'];
    
    $delete_sql = "DELETE FROM sellers WHERE seller_id=$id";
    $conn->query($delete_sql);
    header("Location: seller_info.php"); // Redirect after deleting
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seller Info</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * { 
      margin: 0; 
      padding: 0; 
      box-sizing: border-box; 
    }
    body {
      font-family: 'Poppins', sans-serif;
      background: #f0fdf4;
      color: #2d3748;
      padding: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    table, th, td {
      border: 1px solid #4CAF50;
    }
    th, td {
      padding: 12px;
      text-align: center;
    }
    th {
      background-color: #4CAF50;
      color: white;
    }
    .btn {
      padding: 5px 10px;
      color: white;
      background-color: #4CAF50;
      border: none;
      cursor: pointer;
      margin: 2px;
    }
    .btn:hover {
      background-color: #45a049;
    }
    .container {
      max-width: 1000px;
      margin: 0 auto;
    }

    /* Modal Styles */
    .modal {
      display: none; /* Hidden by default */
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.4);
      padding-top: 60px;
      box-sizing: border-box;
    }

    .modal-content {
      background-color: #fefefe;
      margin: 5% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 400px;
      border-radius: 8px;
    }

    .modal-header, .modal-footer {
      padding: 10px;
      text-align: center;
    }

    .modal-header {
      font-size: 20px;
    }

    .modal-footer {
      display: flex;
      justify-content: space-between;
    }

    .modal input {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    /* Back Button Styles */
    .back-button {
      padding: 10px 20px;
      background-color: #388e3c;
      border: none;
      cursor: pointer;
      font-size: 16px;
      color: white;
      margin-bottom: 20px;
      transition: background-color 0.3s;
    }

    .back-button:hover {
      background-color: #2c6e2f;
    }
  </style>
</head>
<body>

  <!-- Back Button -->
  <button class="back-button" onclick="goBack()">Back to Dashboard</button>

  <div class="container">
    <h1>Seller Information</h1>
    
    <!-- Search Bar -->
    <form method="POST">
      <input type="text" name="search" value="<?php echo $search_query; ?>" placeholder="Search by Seller ID or Name" />
      <button type="submit" class="btn">Search</button>
    </form>
    
    <button class="btn" onclick="openModal()">Add Seller</button>
    
    <table id="sellerTable">
      <thead>
        <tr>
          <th>Seller ID</th> <!-- Add seller_id as the first column -->
          <th>Seller Name</th>
          <th>Contract Info</th>
          <th>City</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="sellerTableBody">
        <!-- Data rows will be loaded here dynamically from the database -->
        <?php foreach ($sellers as $seller): ?>
          <tr>
            <td><?php echo $seller['seller_id']; ?></td> <!-- Display seller_id -->
            <td><?php echo $seller['seller_name']; ?></td>
            <td><?php echo $seller['contract_info']; ?></td>
            <td><?php echo $seller['city']; ?></td>
            <td>
              <button class="btn" onclick="editSeller(<?php echo $seller['seller_id']; ?>, '<?php echo $seller['seller_name']; ?>', '<?php echo $seller['contract_info']; ?>', '<?php echo $seller['city']; ?>')">Edit</button>
              <form method="POST" style="display:inline;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?php echo $seller['seller_id']; ?>">
                <button class="btn" type="submit">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Modal for Adding Seller -->
  <div id="addSellerModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Add New Seller</h2>
        <span class="close" onclick="closeModal()">&times;</span>
      </div>
      <div class="modal-body">
        <label for="sellerID">Seller ID</label>
        <input type="text" id="sellerID" placeholder="Enter Seller ID"> <!-- Input for Seller ID -->
        <label for="sellerName">Seller Name</label>
        <input type="text" id="sellerName" placeholder="Enter Seller Name">
        <label for="contractInfo">Contract Info</label>
        <input type="text" id="contractInfo" placeholder="Enter Contract Info">
        <label for="city">City</label>
        <input type="text" id="city" placeholder="Enter City">
      </div>
      <div class="modal-footer">
        <form method="POST" action="seller_info.php">
          <input type="hidden" name="action" value="add">
          <input type="hidden" name="name" id="hiddenSellerName">
          <input type="hidden" name="contract" id="hiddenContractInfo">
          <input type="hidden" name="city" id="hiddenCity">
          <input type="hidden" name="seller_id" id="hiddenSellerID"> <!-- Hidden input for seller_id -->
          <button class="btn" type="submit">Save</button>
        </form>
        <button class="btn" onclick="closeModal()">Cancel</button>
      </div>
    </div>
  </div>

  <script>
    // Open the modal
    function openModal() {
      document.getElementById('addSellerModal').style.display = 'block';
    }

    // Close the modal
    function closeModal() {
      document.getElementById('addSellerModal').style.display = 'none';
    }

    // Function to fill the modal with existing seller data for editing
    function editSeller(id, name, contract, city) {
      document.getElementById('sellerName').value = name;
      document.getElementById('contractInfo').value = contract;
      document.getElementById('city').value = city;

      document.getElementById('hiddenSellerName').value = name;
      document.getElementById('hiddenContractInfo').value = contract;
      document.getElementById('hiddenCity').value = city;

      // Add hidden input for the seller ID to be used during edit
      const form = document.querySelector('form');
      form.innerHTML += `<input type="hidden" name="id" value="${id}">`;
      
      openModal();
    }

    // Function to navigate back to the dashboard
    function goBack() {
      window.location.href = 'dashboard.html'; // Redirect to dashboard.html
    }
  </script>

</body>
</html>
