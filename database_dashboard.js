document.addEventListener('DOMContentLoaded', () => {
  const mainContent = document.getElementById('mainContent');
  const loginOverlay = document.getElementById('loginOverlay');
  const loginForm = document.getElementById('loginForm');
  const tableBody = document.getElementById('dataset-table-body');

  // Check if user is already logged in
  checkLoginStatus();

  // Login form submission
  loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(loginForm);

    try {
      const response = await fetch('login.php', {
        method: 'POST',
        body: formData
      });

      const data = await response.json();
      
      if (data.success) {
        // Remove blur and hide login overlay
        mainContent.classList.remove('blur-background');
        loginOverlay.style.display = 'none';
        loadDashboardData(); // Load dashboard data after successful login
      } else {
        alert(data.message || 'Login failed. Please try again.');
      }
    } catch (error) {
      console.error('Login error:', error);
      const errorMessage = error.response?.message || 'An error occurred during login. Please check your credentials and try again.';
      alert(errorMessage);
    }
  });

  // Check login status
  async function checkLoginStatus() {
    try {
      const response = await fetch('check_login.php');
      const data = await response.json();
      
      if (data.loggedIn) {
        mainContent.classList.remove('blur-background');
        loginOverlay.style.display = 'none';
        loadDashboardData();
      }
    } catch (error) {
      console.error('Error checking login status:', error);
      // Don't show error to user, just keep the login form visible
      mainContent.classList.add('blur-background');
      loginOverlay.style.display = 'flex';
    }
  }

  // Modal elements
  const uploadBtn = document.getElementById('uploadBtn');
  const uploadModal = document.getElementById('uploadModal');
  const cancelUpload = document.getElementById('cancelUpload');
  const uploadForm = document.getElementById('uploadForm');

  // Open modal on upload button click
  uploadBtn?.addEventListener('click', () => {
    uploadModal.classList.remove('hidden');
  });

  // Close modal on cancel button click
  cancelUpload?.addEventListener('click', () => {
    uploadModal.classList.add('hidden');
    uploadForm.reset();
  });

  // Handle form submission
  uploadForm?.addEventListener('submit', (e) => {
    e.preventDefault();

    const formData = new FormData(uploadForm);

    fetch('api_insert_data.php', {
      method: 'POST',
      body: formData,
    })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          alert('Data uploaded successfully!');
          uploadModal.classList.add('hidden');
          uploadForm.reset();
          loadDashboardData(); // Refresh data instead of page reload
        } else {
          alert('Error uploading data: ' + (result.message || 'Unknown error'));
        }
      })
      .catch(error => {
        alert('Error uploading data: ' + error.message);
      });
  });

  // Load dashboard data
  function loadDashboardData(showDeleted = false) {
    // Clear existing table data
    if (tableBody) {
      tableBody.innerHTML = '';
    }

    // Fetch data from API endpoint
    fetch(`api_get_data.php${showDeleted ? '?showDeleted=1' : ''}`)
      .then(response => response.json())
      .then(data => {
        if (!data.success) {
          if (data.message === 'Authentication required') {
            // Show login overlay if not authenticated
            mainContent.classList.add('blur-background');
            loginOverlay.style.display = 'flex';
          }
          return;
        }

        // Update UI based on admin status
        if (data.isAdmin) {
          document.getElementById('adminControls')?.classList.remove('hidden');
        }

        const combinedData = [];
        if (data.warranty_main) {
          data.warranty_main.forEach(item => {
            combinedData.push(item);
          });
        }

        // Populate table rows
        if (tableBody) {
          combinedData.forEach(row => {
            const tr = document.createElement('tr');
            tr.classList.add('hover:bg-gray-50');

            // Add delete status class if record is deleted
            if (row.delete === 1) {
              tr.classList.add('bg-gray-100');
            }

            let actionButton = '';
            if (data.isAdmin) {
              if (row.delete === 1) {
                actionButton = `
                  <button onclick="handleRecordAction(${row.id}, 'restore')" 
                    class="text-green-600 hover:text-green-900">
                    Restore
                  </button>
                  <div class="text-sm text-gray-500">
                    Deleted by: ${escapeHtml(row.deleted_info?.deleted_by || 'Unknown')}
                    <br>
                    At: ${escapeHtml(row.deleted_info?.deleted_at || 'Unknown')}
                  </div>
                `;
              } else {
                actionButton = `
                  <button onclick="handleRecordAction(${row.id}, 'delete')" 
                    class="text-red-600 hover:text-red-900">
                    Delete
                  </button>
                `;
              }
            }

            tr.innerHTML = `
              <td class="py-4 px-6 text-gray-900">${escapeHtml(row.name)}</td>
              <td class="py-4 px-6 text-gray-700">${escapeHtml(row.cp)}</td>
              <td class="py-4 px-6 text-gray-700">${escapeHtml(row.serial_num)}</td>
              <td class="py-4 px-6 text-gray-700">${escapeHtml(row.files)}</td>
              ${data.isAdmin ? `<td class="py-4 px-6">${actionButton}</td>` : ''}
            `;

            tableBody.appendChild(tr);
          });
        }
      })
      .catch(error => {
        console.error('Error loading dataset data:', error);
      });
  }

  // Helper function to escape HTML
  function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/[&<>"']/g, function (m) {
      switch (m) {
        case '&':
          return '&amp;';
        case '<':
          return '<';
        case '>':
          return '>';
        case '"':
          return '"';
        case "'":
          return '&#039;';
        default:
          return m;
      }
    });
  }
});
