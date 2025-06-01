<?php
require_once 'authenticate.php';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Database Dashboard - Warranty System</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="database_design.css" />
</head>
<body class="min-h-screen flex flex-col bg-gray-50">
  <!-- Header Navigation -->
  <header class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <div class="flex-shrink-0 flex items-center">
            <h1 class="text-xl font-bold text-gray-900">Warranty Dashboard</h1>
          </div>
          <nav class="hidden sm:ml-6 sm:flex sm:space-x-8">
            <a href="unverify.php" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
              Unverified
            </a>
            <a href="verify.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
              Manage Data
            </a>
          </nav>
        </div>
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <input type="search" id="searchInput" placeholder="Search..." class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow rounded-lg overflow-hidden">
      <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Unverified Warranty Data</h3>
        <p class="mt-1 text-sm text-gray-500">Displaying warranty records pending verification.</p>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Person</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serial Number</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Files</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody id="warrantyTableBody" class="bg-white divide-y divide-gray-200">
            <!-- Data will be populated here -->
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <script>
    // Load unverified warranty data
    function loadUnverifiedWarranties() {
      fetch('read_warranty.php?status=unverified')
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            const tbody = document.getElementById('warrantyTableBody');
            tbody.innerHTML = '';
            
            result.data.forEach(record => {
              const tr = document.createElement('tr');
              tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${record.name}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${record.cp}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${record.serial_num}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${record.files}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button onclick="verifyWarranty(${record.id}, this)" 
                          class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Verify
                  </button>
                </td>
              `;
              tbody.appendChild(tr);
            });
          }
        })
        .catch(error => {
          console.error('Error loading warranty data:', error);
        });
    }

    // Verify warranty record
    function verifyWarranty(id, button) {
      if (!confirm('Are you sure you want to verify this warranty record?')) return;

      fetch('verify_warranty.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: id })
      })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          // Remove the row with animation
          const row = button.closest('tr');
          row.style.transition = 'opacity 0.5s ease';
          row.style.opacity = '0';
          setTimeout(() => row.remove(), 500);
        } else {
          alert(result.message);
        }
      })
      .catch(error => {
        console.error('Error verifying warranty:', error);
        alert('Failed to verify warranty. Please try again.');
      });
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const rows = document.querySelectorAll('#warrantyTableBody tr');
      
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
      });
    });

    // Load data when page loads
    document.addEventListener('DOMContentLoaded', loadUnverifiedWarranties);
  </script>
</body>
</html>
