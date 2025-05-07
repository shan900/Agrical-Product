<?php
include('db.php');

// Fetch the farmers from the database
$sql = "SELECT * FROM farmer_info";  // Replace with your table name
$result = $conn->query($sql);
$farmers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Check if the keys exist before adding to the farmers array
        $farmers[] = [
            'FarmerId' => isset($row['FarmerId']) ? $row['FarmerId'] : '',
            'Name' => isset($row['Name']) ? $row['Name'] : '',
            'Age' => isset($row['Age']) ? $row['Age'] : '',
            'Address' => isset($row['Address']) ? $row['Address'] : '',
            'City' => isset($row['City']) ? $row['City'] : '',
            'Zip' => isset($row['Zip']) ? $row['Zip'] : ''
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Farmer Information</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Arial', sans-serif;
      background-color: #f7f7f7;
      color: #333;
      padding: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    h1 {
      font-size: 36px;
      color: #4CAF50;
      margin-bottom: 20px;
      text-align: center;
    }

    .back-to-dashboard-btn {
      position: absolute;
      top: 20px;
      left: 20px;
      background-color: #f1c40f;
      padding: 7px 15px;
      font-size: 14px;
      border-radius: 5px;
      color: white;
      cursor: pointer;
    }

    .back-to-dashboard-btn:hover {
      background-color: #e67e22;
    }

    .button {
      padding: 10px 20px;
      margin: 10px;
      cursor: pointer;
      font-size: 14px;
      border: none;
      border-radius: 5px;
      color: white;
      transition: all 0.3s ease;
    }

    .button:hover {
      transform: translateY(-2px);
    }

    .edit-button {
      background-color: #4CAF50;
    }

    .delete-button {
      background-color: #f44336;
    }

    .new-farmer-btn {
      background-color: #008CBA;
    }

    .new-farmer-btn:hover {
      background-color: #006f8e;
    }

    .form-container {
      margin-top: 20px;
      display: none;
      margin-bottom: 20px;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      width: 80%;
      max-width: 500px;
      position: relative;
    }

    .form-container input, .form-container button {
      padding: 10px;
      margin: 5px 0;
      font-size: 14px;
      border-radius: 5px;
      width: 100%;
    }

    .form-container input {
      border: 1px solid #ddd;
    }

    .form-container input:focus {
      border-color: #4CAF50;
      outline: none;
    }

    .form-container button {
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }

    .form-container button:hover {
      background-color: #45a049;
    }

    .search-container {
      margin-bottom: 20px;
      width: 100%;
      max-width: 500px;
    }

    .search-container input {
      padding: 10px;
      font-size: 14px;
      width: 100%;
      border-radius: 5px;
      border: 1px solid #ddd;
      transition: all 0.3s ease;
    }

    .search-container input:focus {
      border-color: #4CAF50;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background-color: #fff;
      box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      overflow: hidden;
    }

    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #4CAF50;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    .actions {
      display: flex;
      gap: 10px;
      justify-content: center;
    }

    .actions button {
      font-size: 12px;
      padding: 5px 10px;
    }

    .close-btn {
      background-color: #f44336;
      color: white;
      font-size: 14px;
      border: none;
      padding: 10px 15px;
      cursor: pointer;
      border-radius: 5px;
      margin-left: 10px;
    }

    .close-btn:hover {
      background-color: #e53935;
    }

    .form-buttons {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }

    #deleteConfirmation {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: white;
      padding: 20px;
      box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
      border-radius: 8px;
    }

    #deleteConfirmation button {
      background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      font-size: 14px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    #deleteConfirmation button:nth-child(2) {
      background-color: #f44336;
    }
  </style>
</head>
<body>

  <h1>Farmer Information</h1>

  <button class="button back-to-dashboard-btn" onclick="goToDashboard()">Back to Dashboard</button>

  <!-- Delete Confirmation Modal -->
  <div id="deleteConfirmation">
    <p>Are you sure you want to delete this data?</p>
    <button class="button" onclick="confirmDelete()">Yes</button>
    <button class="button" onclick="cancelDelete()">No</button>
  </div>

  <div class="form-container" id="formContainer">
    <h3>Enter New Farmer Details</h3>
    <label for="farmerId">FarmerID:</label>
    <input type="text" id="farmerId" required><br>
    <label for="name">Name:</label>
    <input type="text" id="name" required><br>
    <label for="age">Age:</label>
    <input type="number" id="age" required><br>
    <label for="address">Address:</label>
    <input type="text" id="address" required><br>
    <label for="city">City:</label>
    <input type="text" id="city" required><br>
    <label for="zip">Zip:</label>
    <input type="text" id="zip" required><br>

    <div class="form-buttons">
      <button class="button" onclick="addNewFarmer()">Submit</button>
      <button class="close-btn" onclick="toggleForm()">Close</button>
    </div>
  </div>

  <button class="button new-farmer-btn" onclick="toggleForm()">Add New Farmer</button>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search by FarmerID or Name" onkeyup="searchFarmers()">
  </div>

  <table>
    <thead>
      <tr>
        <th>FarmerID</th>
        <th>Name</th>
        <th>Age</th>
        <th>Address</th>
        <th>City</th>
        <th>Zip</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="farmerTableBody">
      <?php
      foreach ($farmers as $farmer) {
        echo "<tr>
                <td>{$farmer['FarmerId']}</td>
                <td>{$farmer['Name']}</td>
                <td>{$farmer['Age']}</td>
                <td>{$farmer['Address']}</td>
                <td>{$farmer['City']}</td>
                <td>{$farmer['Zip']}</td>
                <td class='actions'>
                  <button class='button edit-button' onclick='editFarmer(\"{$farmer['FarmerId']}\")'>Edit</button>
                  <button class='button delete-button' onclick='showDeleteConfirmation(\"{$farmer['FarmerId']}\")'>Delete</button>
                </td>
              </tr>";
      }
      ?>
    </tbody>
  </table>

  <script>
    let deleteFarmerId = null; // Track the farmer to delete

    function toggleForm() {
      const formContainer = document.getElementById('formContainer');
      formContainer.style.display = formContainer.style.display === 'none' ? 'block' : 'none';
    }

    function addNewFarmer() {
      const farmer = {
        farmerId: document.getElementById('farmerId').value,
        name: document.getElementById('name').value,
        age: document.getElementById('age').value,
        address: document.getElementById('address').value,
        city: document.getElementById('city').value,
        zip: document.getElementById('zip').value
      };

      if (!farmer.farmerId || !farmer.name || !farmer.age || !farmer.address || !farmer.city || !farmer.zip) {
        alert('Please fill out all fields');
        return;
      }

      fetch('insert_farmer.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(farmer)
      })
      .then(() => {
        toggleForm();
        location.reload();
      });
    }

    function editFarmer(farmerId) {
      fetch(`get_farmers.php?id=${farmerId}`)
        .then(res => res.json())
        .then(data => {
          const farmer = data[0];
          document.getElementById('farmerId').value = farmer.farmerId;
          document.getElementById('name').value = farmer.name;
          document.getElementById('age').value = farmer.age;
          document.getElementById('address').value = farmer.address;
          document.getElementById('city').value = farmer.city;
          document.getElementById('zip').value = farmer.zip;
          toggleForm();
        });
    }

    function showDeleteConfirmation(farmerId) {
      deleteFarmerId = farmerId;
      document.getElementById('deleteConfirmation').style.display = 'block';
    }

    function confirmDelete() {
      fetch('delete_farmer.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ farmerId: deleteFarmerId })
      })
      .then(() => {
        location.reload();
        document.getElementById('deleteConfirmation').style.display = 'none';
      });
    }

    function cancelDelete() {
      deleteFarmerId = null;
      document.getElementById('deleteConfirmation').style.display = 'none';
    }

    function searchFarmers() {
      const searchQuery = document.getElementById('searchInput').value.toLowerCase();
      const tableBody = document.getElementById('farmerTableBody');
      const rows = tableBody.getElementsByTagName('tr');

      for (let i = 0; i < rows.length; i++) {
        const farmerId = rows[i].cells[0].innerText.toLowerCase();
        const name = rows[i].cells[1].innerText.toLowerCase();

        if (farmerId.includes(searchQuery) || name.includes(searchQuery)) {
          rows[i].style.display = '';
        } else {
          rows[i].style.display = 'none';
        }
      }
    }

    function goToDashboard() {
      window.location.href = 'dashboard.html';  // Change the link to your dashboard file
    }
  </script>

</body>
</html>
