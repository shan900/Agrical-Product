<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sales Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background: #f4f4f4;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }
    .container {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      justify-content: center;
      align-items: center;
      max-width: 1200px;
      width: 100%;
    }
    .chart-container {
      width: 45%;
      height: 300px;
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      position: relative;
    }
    .edit-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      padding: 5px 10px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .edit-btn:hover {
      background-color: #0056b3;
    }
    .back-btn {
      position: absolute;
      top: 20px;
      left: 20px;
      padding: 8px 15px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .back-btn:hover {
      background-color: #218838;
    }
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
    }
    .modal-content {
      background-color: white;
      padding: 20px;
      border-radius: 5px;
      width: 300px;
      text-align: center;
    }
    .modal input {
      margin: 10px 0;
      padding: 8px;
      width: 100%;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .modal button {
      padding: 10px 15px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .modal button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

  <button class="back-btn" onclick="window.location.href='dashboard.html'">Back to Dashboard</button>
  <h1>Sales Dashboard</h1>
  
  <div class="container">
    <!-- Revenue by Product Line -->
    <div class="chart-container">
      <canvas id="revenueByProductLine"></canvas>
      <button class="edit-btn" onclick="editChart('revenueByProductLine')">Edit</button>
    </div>

    <!-- Product Type Share in Total Revenue -->
    <div class="chart-container">
      <canvas id="productShareRevenue"></canvas>
      <button class="edit-btn" onclick="editChart('productShareRevenue')">Edit</button>
    </div>

    <!-- Profit & Quantity Running Total -->
    <div class="chart-container">
      <canvas id="profitQuantityRunningTotal"></canvas>
      <button class="edit-btn" onclick="editChart('profitQuantityRunningTotal')">Edit</button>
    </div>

    <!-- Product Lines' Revenue Share -->
    <div class="chart-container">
      <canvas id="productLinesRevenueShare"></canvas>
      <button class="edit-btn" onclick="editChart('productLinesRevenueShare')">Edit</button>
    </div>
  </div>

  <!-- Modal for Editing Chart -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <h3>Edit Chart</h3>
      <label for="chartName">Chart Name:</label>
      <input type="text" id="chartName" placeholder="Enter chart name">
      <label for="chartData">Chart Data (comma-separated):</label>
      <input type="text" id="chartData" placeholder="Enter chart data">
      <button onclick="saveChanges()">Save</button>
      <button onclick="closeModal()">Cancel</button>
    </div>
  </div>

  <script>
    // Data for each chart
    var revenueByProductLine = {
      labels: ['Personal Accessories', 'Outdoor Protection', 'Mountaineering Equip.', 'Golf Equipment', 'Camping Equipment'],
      datasets: [{
        label: 'Revenue by Product Line',
        data: [2000, 1500, 3000, 2500, 2800], // Replace with actual data
        backgroundColor: ['#FF6347', '#FF4500', '#4682B4', '#32CD32', '#FFD700'],
        borderColor: ['#FF6347', '#FF4500', '#4682B4', '#32CD32', '#FFD700'],
        borderWidth: 1
      }]
    };

    var productShareRevenue = {
      labels: ['Personal Accessories', 'Outdoor Protection', 'Mountaineering Equip.', 'Golf Equipment', 'Camping Equipment'],
      datasets: [{
        label: 'Product Type Share in Revenue',
        data: [12, 15, 20, 25, 28], // Replace with actual data
        backgroundColor: ['#FF6347', '#FF4500', '#4682B4', '#32CD32', '#FFD700'],
        borderColor: ['#FF6347', '#FF4500', '#4682B4', '#32CD32', '#FFD700'],
        borderWidth: 1
      }]
    };

    var profitQuantityRunningTotal = {
      labels: ['2013', '2014', '2015'],
      datasets: [{
        label: 'Profit',
        data: [500000, 700000, 1000000], // Replace with actual data
        borderColor: '#4682B4',
        fill: false,
        borderWidth: 2
      }, {
        label: 'Quantity',
        data: [200000, 250000, 300000], // Replace with actual data
        borderColor: '#32CD32',
        fill: false,
        borderWidth: 2
      }]
    };

    var productLinesRevenueShare = {
      labels: ['Personal Accessories', 'Outdoor Protection', 'Mountaineering Equip.', 'Golf Equipment', 'Camping Equipment'],
      datasets: [{
        label: 'Revenue Share by Product Line',
        data: [22, 18, 20, 25, 15], // Replace with actual data
        backgroundColor: ['#FF6347', '#FF4500', '#4682B4', '#32CD32', '#FFD700'],
        borderColor: ['#FF6347', '#FF4500', '#4682B4', '#32CD32', '#FFD700'],
        borderWidth: 1
      }]
    };

    // Initialize all charts
    var revenueChart = new Chart(document.getElementById('revenueByProductLine'), {
      type: 'pie',
      data: revenueByProductLine
    });

    var productShareChart = new Chart(document.getElementById('productShareRevenue'), {
      type: 'bar',
      data: productShareRevenue
    });

    var profitQuantityChart = new Chart(document.getElementById('profitQuantityRunningTotal'), {
      type: 'line',
      data: profitQuantityRunningTotal
    });

    var productLinesChart = new Chart(document.getElementById('productLinesRevenueShare'), {
      type: 'bar',
      data: productLinesRevenueShare
    });

    // Function to open the edit modal
    function editChart(chartId) {
      var chart = window[chartId + 'Chart'];
      var chartName = chart.data.datasets[0].label;
      var chartData = chart.data.datasets[0].data.join(',');

      // Populate modal with current chart name and data
      document.getElementById('chartName').value = chartName;
      document.getElementById('chartData').value = chartData;
      document.getElementById('editModal').style.display = 'flex';
      
      // Store the current chart ID to apply the changes
      window.currentChart = chartId;
    }

    // Function to save the changes
    function saveChanges() {
      var chartName = document.getElementById('chartName').value;
      var chartData = document.getElementById('chartData').value.split(',').map(Number);

      var chart = window[currentChart + 'Chart'];
      chart.data.datasets[0].label = chartName;
      chart.data.datasets[0].data = chartData;

      chart.update();  // Update the chart with the new data
      closeModal();    // Close the modal
    }

    // Function to close the modal
    function closeModal() {
      document.getElementById('editModal').style.display = 'none';
    }
  </script>

</body>
</html>
