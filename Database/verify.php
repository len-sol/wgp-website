<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manage Warranty Data</title>
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
            <h1 class="text-xl font-bold text-gray-900">Warranty Management</h1>
          </div>
          <nav class="hidden sm:ml-6 sm:flex sm:space-x-8">
            <a href="unverify.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
              Unverified
            </a>
            <a href="verify.php" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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
        <h3 class="text-lg leading-6 font-medium text-gray-900">All Warranty Records</h3>
        <p class="mt-1 text-sm text-gray-500">Edit or delete warranty records.</p>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Person</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serial Number</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Files</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Warranty Start</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Warranty End</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
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

  <!-- Edit Modal -->
  <div id="editModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center transition-opacity duration-200 ease-in-out opacity-0 z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4 shadow-xl relative">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Edit Warranty Record</h2>
        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
      
      <!-- Error Message Display -->
      <div id="errorMessage" class="hidden mb-4 p-4 rounded-md bg-red-50 text-red-600 text-sm font-medium"></div>
      
      <form id="editForm" class="space-y-4">
        <input type="hidden" id="editId">
        <div>
          <label for="editName" class="block text-sm font-medium text-gray-700">Name</label>
          <input type="text" id="editName" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150">
        </div>
        <div>
          <label for="editCp" class="block text-sm font-medium text-gray-700">Contact Person</label>
          <input type="text" id="editCp" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150">
        </div>
        <div>
          <label for="editSerial" class="block text-sm font-medium text-gray-700">Serial Number</label>
          <input type="text" id="editSerial" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150">
        </div>
        <div>
          <label for="editFiles" class="block text-sm font-medium text-gray-700">Files</label>
          <input type="text" id="editFiles" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150">
        </div>
        <div>
          <label for="editWarrantyStart" class="block text-sm font-medium text-gray-700">Warranty Start Date</label>
          <input type="date" id="editWarrantyStart" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150">
        </div>
        <div>
          <label for="editWarrantyEnd" class="block text-sm font-medium text-gray-700">Warranty End Date</label>
          <input type="date" id="editWarrantyEnd" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150">
        </div>
        <div>
          <label for="editDelete" class="block text-sm font-medium text-gray-700">Status</label>
          <select id="editDelete" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150">
            <option value="1">Unverified</option>
            <option value="2">Verified</option>
          </select>
        </div>
        <div class="flex justify-end space-x-3 mt-6">
          <button type="button" onclick="closeEditModal()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition duration-150 focus:outline-none focus:ring-2 focus:ring-gray-400">
            Cancel
          </button>
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
            Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Load all warranty data
    function loadWarrantyData() {
      fetch('read_warranty.php')
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            const tbody = document.getElementById('warrantyTableBody');
            tbody.innerHTML = '';
            
            result.data.forEach(record => {
              const tr = document.createElement('tr');
              const formattedStartDate = record.warranty_start ? new Date(record.warranty_start).toLocaleDateString() : '-';
              const formattedEndDate = record.warranty_end ? new Date(record.warranty_end).toLocaleDateString() : '-';
              
              tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${record.name || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${record.cp || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${record.serial_num || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${record.files || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formattedStartDate}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formattedEndDate}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                    record.delete === '1' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'
                  }">
                    ${record.delete === '1' ? 'Unverified' : 'Verified'}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                  <button type="button" 
                          onclick='openEditModal(${JSON.stringify(record)})' 
                          class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-sm font-medium transition-colors">
                    Edit
                  </button>
                  <button type="button"
                          onclick="deleteWarranty(${record.id})" 
                          class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm font-medium transition-colors">
                    Delete
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

    // Edit modal functions
    function openEditModal(record) {
      console.log("Opening modal with record:", record); // Debug log
      
      // Reset error message
      const errorMessage = document.getElementById('errorMessage');
      errorMessage.classList.add('hidden');
      errorMessage.textContent = '';
      
      try {
        // If record is a string (from JSON.stringify), parse it
        const data = typeof record === 'string' ? JSON.parse(record) : record;
        
        // Populate form fields
        document.getElementById('editId').value = data.id;
        document.getElementById('editName').value = data.name || '';
        document.getElementById('editCp').value = data.cp || '';
        document.getElementById('editSerial').value = data.serial_num || '';
        document.getElementById('editFiles').value = data.files || '';
        document.getElementById('editWarrantyStart').value = data.warranty_start ? data.warranty_start.split(' ')[0] : '';
        document.getElementById('editWarrantyEnd').value = data.warranty_end ? data.warranty_end.split(' ')[0] : '';
        document.getElementById('editDelete').value = data.delete || '2';
        
        // Show modal with animation
        const modal = document.getElementById('editModal');
        modal.classList.remove('hidden');
        modal.classList.add('opacity-100');
      } catch (error) {
        console.error('Error opening modal:', error);
        alert('Error opening edit form. Please try again.');
      }
    }

    function closeEditModal() {
      // Hide modal with animation
      const modal = document.getElementById('editModal');
      modal.classList.remove('opacity-100');
      setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('editForm').reset();
        document.getElementById('errorMessage').classList.add('hidden');
      }, 200);
    }

    // Handle edit form submission
    document.getElementById('editForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Client-side validation
      const requiredFields = ['editName', 'editCp', 'editSerial', 'editFiles', 'editWarrantyStart', 'editWarrantyEnd'];
      const errorMessage = document.getElementById('errorMessage');
      
      for (const fieldId of requiredFields) {
        const field = document.getElementById(fieldId);
        if (!field.value.trim()) {
          errorMessage.textContent = `Please fill in the ${field.previousElementSibling.textContent}`;
          errorMessage.classList.remove('hidden');
          field.focus();
          return;
        }
      }
      
      // Validate warranty dates
      const startDate = new Date(document.getElementById('editWarrantyStart').value);
      const endDate = new Date(document.getElementById('editWarrantyEnd').value);
      
      if (endDate < startDate) {
        errorMessage.textContent = 'Warranty end date cannot be earlier than start date';
        errorMessage.classList.remove('hidden');
        document.getElementById('editWarrantyEnd').focus();
        return;
      }
      
      const data = {
        id: document.getElementById('editId').value,
        name: document.getElementById('editName').value,
        cp: document.getElementById('editCp').value,
        serial_num: document.getElementById('editSerial').value,
        files: document.getElementById('editFiles').value,
        warranty_start: document.getElementById('editWarrantyStart').value,
        warranty_end: document.getElementById('editWarrantyEnd').value,
        delete: document.getElementById('editDelete').value
      };

      // Show loading state
      const submitButton = e.target.querySelector('button[type="submit"]');
      const originalText = submitButton.textContent;
      submitButton.disabled = true;
      submitButton.textContent = 'Saving...';

      fetch('update_warranty.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          closeEditModal();
          loadWarrantyData(); // Refresh the table
          // Show success message
          const successMessage = document.createElement('div');
          successMessage.className = 'fixed bottom-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded';
          successMessage.textContent = result.message;
          document.body.appendChild(successMessage);
          setTimeout(() => successMessage.remove(), 3000);
        } else {
          // Show error in the modal
          const errorMessage = document.getElementById('errorMessage');
          errorMessage.textContent = result.message;
          errorMessage.classList.remove('hidden');
        }
      })
      .catch(error => {
        console.error('Error updating warranty:', error);
        // Show error in the modal
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.textContent = 'Failed to update warranty. Please try again.';
        errorMessage.classList.remove('hidden');
      })
      .finally(() => {
        // Reset button state
        submitButton.disabled = false;
        submitButton.textContent = originalText;
      });
    });

    // Delete warranty record
    function deleteWarranty(id) {
      if (!confirm('Are you sure you want to delete this warranty record?')) return;

      fetch('delete_warranty.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: id })
      })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          loadWarrantyData();
        } else {
          alert(result.message);
        }
      })
      .catch(error => {
        console.error('Error deleting warranty:', error);
        alert('Failed to delete warranty. Please try again.');
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
    document.addEventListener('DOMContentLoaded', loadWarrantyData);
  </script>
</body>
</html>
