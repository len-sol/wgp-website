document.addEventListener('DOMContentLoaded', () => {
    const adminCheckOverlay = document.getElementById('adminCheckOverlay');
    const mainContent = document.getElementById('mainContent');
    const adminAuthForm = document.getElementById('adminAuthForm');
    const addUserForm = document.getElementById('addUserForm');
    const usersTableBody = document.getElementById('usersTableBody');
    const activityLogBody = document.getElementById('activityLogBody');

    // Check if admin is already logged in
    checkAdminStatus();

    // Admin authentication form submission
    adminAuthForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(adminAuthForm);

        try {
            const response = await fetch('check_admin.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            
            if (data.success) {
                adminCheckOverlay.classList.add('hidden');
                mainContent.classList.remove('hidden');
                loadUsers();
                loadActivityLog();
            } else {
                alert(data.message || 'Admin authentication failed.');
            }
        } catch (error) {
            console.error('Admin auth error:', error);
            alert('Authentication error occurred. Please try again.');
        }
    });

    // Add user form submission
    addUserForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(addUserForm);

        try {
            const response = await fetch('add_user.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            
            if (data.success) {
                alert('User added successfully!');
                addUserForm.reset();
                loadUsers();
                loadActivityLog();
            } else {
                alert(data.message || 'Failed to add user.');
            }
        } catch (error) {
            console.error('Add user error:', error);
            alert('Error occurred while adding user.');
        }
    });

    // Load users list
    async function loadUsers() {
        try {
            const response = await fetch('get_users.php');
            const data = await response.json();
            
            usersTableBody.innerHTML = '';
            
            data.users.forEach(user => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${escapeHtml(user.username)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${escapeHtml(user.role)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${user.active ? 'Active' : 'Inactive'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        ${user.role !== 'admin' ? `
                            <button onclick="toggleUserStatus(${user.id}, ${!user.active})" 
                                class="text-indigo-600 hover:text-indigo-900 mr-4">
                                ${user.active ? 'Deactivate' : 'Activate'}
                            </button>
                        ` : ''}
                    </td>
                `;
                usersTableBody.appendChild(tr);
            });
        } catch (error) {
            console.error('Load users error:', error);
            alert('Error loading users list.');
        }
    }

    // Load activity log
    async function loadActivityLog() {
        try {
            const response = await fetch('get_activity_log.php');
            const data = await response.json();
            
            activityLogBody.innerHTML = '';
            
            data.activities.forEach(activity => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${escapeHtml(activity.username)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${escapeHtml(activity.activity)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${escapeHtml(activity.timestamp)}</td>
                `;
                activityLogBody.appendChild(tr);
            });
        } catch (error) {
            console.error('Load activity log error:', error);
            alert('Error loading activity log.');
        }
    }

    // Check admin login status
    async function checkAdminStatus() {
        try {
            const response = await fetch('check_admin_status.php');
            const data = await response.json();
            
            if (data.isAdmin) {
                adminCheckOverlay.classList.add('hidden');
                mainContent.classList.remove('hidden');
                loadUsers();
                loadActivityLog();
            }
        } catch (error) {
            console.error('Check admin status error:', error);
        }
    }

    // Toggle user status (activate/deactivate)
    window.toggleUserStatus = async (userId, newStatus) => {
        if (!confirm(`Are you sure you want to ${newStatus ? 'activate' : 'deactivate'} this user?`)) {
            return;
        }

        try {
            const response = await fetch('toggle_user_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ userId, status: newStatus })
            });

            const data = await response.json();
            
            if (data.success) {
                loadUsers();
                loadActivityLog();
            } else {
                alert(data.message || 'Failed to update user status.');
            }
        } catch (error) {
            console.error('Toggle user status error:', error);
            alert('Error updating user status.');
        }
    };

    // Helper function to escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
