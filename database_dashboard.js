// JavaScript to load dataset table data dynamically

document.addEventListener('DOMContentLoaded', () => {
  const tableBody = document.getElementById('dataset-table-body');

  // Modal elements
  const uploadBtn = document.getElementById('uploadBtn');
  const uploadModal = document.getElementById('uploadModal');
  const cancelUpload = document.getElementById('cancelUpload');
  const uploadForm = document.getElementById('uploadForm');

  // Open modal on upload button click
  uploadBtn.addEventListener('click', () => {
    uploadModal.classList.remove('hidden');
  });

  // Close modal on cancel button click
  cancelUpload.addEventListener('click', () => {
    uploadModal.classList.add('hidden');
    uploadForm.reset();
  });

  // Handle form submission
  uploadForm.addEventListener('submit', (e) => {
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
          // Optionally refresh the table data here
          location.reload();
        } else {
          alert('Error uploading data: ' + (result.message || 'Unknown error'));
        }
      })
      .catch(error => {
        alert('Error uploading data: ' + error.message);
      });
  });

  // Fetch data from API endpoint
  fetch('api_get_data.php')
    .then(response => response.json())
    .then(data => {
      // Combine warranty_submissions and warranty_main into one array for demo
      const combinedData = [];

      if (data.warranty_submissions) {
        data.warranty_submissions.forEach(item => {
          combinedData.push(item);
        });
      }

      if (data.warranty_main) {
        data.warranty_main.forEach(item => {
          combinedData.push(item);
        });
      }

      // Populate table rows
      combinedData.forEach(row => {
        const tr = document.createElement('tr');
        tr.classList.add('hover:bg-gray-50');

        tr.innerHTML = `
          <td class="py-4 px-6 text-gray-900">${escapeHtml(row.name)}</td>
          <td class="py-4 px-6 text-gray-700">${escapeHtml(row.cp)}</td>
          <td class="py-4 px-6 text-gray-700">${escapeHtml(row.serial_num)}</td>
          <td class="py-4 px-6 text-gray-700">${escapeHtml(row.files)}</td>
        `;

        tableBody.appendChild(tr);
      });
    })
    .catch(error => {
      console.error('Error loading dataset data:', error);
    });

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
