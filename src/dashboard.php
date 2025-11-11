<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Self-Love Bot</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #1a202c;
            padding: 20px;
        }

        .container {
            max-width: 1600px;
            margin: 0 auto;
        }

        .header {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header p {
            color: #718096;
            font-size: 0.95rem;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .stat-card .label {
            font-size: 0.85rem;
            color: #718096;
            font-weight: 500;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .value {
            font-size: 2rem;
            font-weight: 700;
            color: #667eea;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .main-panel {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #e2e8f0;
        }

        .panel-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a202c;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .controls {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 10px 16px 10px 40px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.9rem;
            width: 250px;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-box::before {
            content: '🔍';
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
        }

        .filter-select {
            padding: 10px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.9rem;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-select:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #f7fafc;
            color: #4a5568;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #edf2f7;
        }

        .leaderboard-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .leaderboard-table thead {
            background: #f7fafc;
        }

        .leaderboard-table th {
            padding: 14px 16px;
            text-align: left;
            font-weight: 600;
            font-size: 0.85rem;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            user-select: none;
            position: relative;
            transition: background 0.2s ease;
        }

        .leaderboard-table th:hover {
            background: #edf2f7;
        }

        .leaderboard-table th.sortable::after {
            content: '⇅';
            position: absolute;
            right: 8px;
            opacity: 0.3;
            font-size: 0.9rem;
        }

        .leaderboard-table th.sorted-asc::after {
            content: '↑';
            opacity: 1;
            color: #667eea;
        }

        .leaderboard-table th.sorted-desc::after {
            content: '↓';
            opacity: 1;
            color: #667eea;
        }

        .leaderboard-table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #e2e8f0;
        }

        .leaderboard-table tbody tr:hover {
            background: #f7fafc;
            transform: scale(1.01);
        }

        .leaderboard-table td {
            padding: 16px;
            font-size: 0.9rem;
            color: #2d3748;
        }

        .rank-cell {
            font-weight: 700;
            font-size: 1rem;
            color: #667eea;
            width: 60px;
        }

        .rank-medal {
            font-size: 1.2rem;
        }

        .user-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: #1a202c;
        }

        .user-id {
            font-size: 0.8rem;
            color: #a0aec0;
        }

        .level-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .level-1 {
            background: #e6f7ff;
            color: #0066cc;
        }

        .level-2 {
            background: #fff4e6;
            color: #d46b08;
        }

        .level-complete {
            background: #f0f9ff;
            color: #0369a1;
        }

        .progress-cell {
            min-width: 150px;
        }

        .progress-bar-container {
            width: 100%;
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 4px;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        .progress-text {
            font-size: 0.85rem;
            color: #4a5568;
            font-weight: 600;
        }

        .points-cell {
            font-weight: 700;
            color: #f59e0b;
            font-size: 1rem;
        }

        .date-cell {
            color: #718096;
            font-size: 0.85rem;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-completed {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-waiting {
            background: #fef3c7;
            color: #92400e;
        }

        .status-new {
            background: #e0e7ff;
            color: #3730a3;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #a0aec0;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 16px;
        }

        .empty-state-text {
            font-size: 1.1rem;
            font-weight: 500;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(4px);
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            display: none;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #059669;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #dc2626;
        }

        @media (max-width: 768px) {
            .container {
                padding: 12px;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }

            .controls {
                flex-direction: column;
                width: 100%;
            }

            .search-box input,
            .filter-select {
                width: 100%;
            }

            .leaderboard-table {
                font-size: 0.85rem;
            }

            .leaderboard-table th,
            .leaderboard-table td {
                padding: 10px 8px;
            }

            .user-avatar {
                width: 32px;
                height: 32px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <div id="loadingOverlay" class="loading-overlay" style="display: none;">
        <div class="spinner"></div>
    </div>

    <div class="container">
        <div class="header">
            <h1>🏆 Admin Dashboard</h1>
            <p>Self-Love Challenge Management System</p>
        </div>

        <div id="successAlert" class="alert alert-success"></div>
        <div id="errorAlert" class="alert alert-error"></div>

        <div class="stats-row">
            <div class="stat-card">
                <div class="label">Total Users</div>
                <div class="value">
                    <span id="totalUsers">0</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="label">Active Users</div>
                <div class="value">
                    <span id="activeUsers">0</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="label">Completed</div>
                <div class="value">
                    <span id="completedUsers">0</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="label">Total Responses</div>
                <div class="value">
                    <span id="totalResponses">0</span>
                </div>
            </div>
        </div>

        <div class="main-panel">
            <div class="panel-header">
                <div class="panel-title">
                    📊 User Leaderboard
                </div>
                <div class="controls">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Search users...">
                    </div>
                    <select id="levelFilter" class="filter-select">
                        <option value="">All Levels</option>
                        <option value="1">Level 1</option>
                        <option value="2">Level 2</option>
                        <option value="complete">Both Complete</option>
                    </select>
                    <button class="btn btn-primary" onclick="loadUsers()">
                        🔄 Refresh
                    </button>
                </div>
            </div>

            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="sortable" data-sort="name">User</th>
                        <th class="sortable" data-sort="level">Level</th>
                        <th class="sortable" data-sort="responses">Responses</th>
                        <th class="sortable" data-sort="progress">Progress</th>
                        <th class="sortable" data-sort="points">Points</th>
                        <th class="sortable sorted-desc" data-sort="activity">Last Active</th>
                    </tr>
                </thead>
                <tbody id="leaderboardBody">
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div class="empty-state-icon">⏳</div>
                            <div class="empty-state-text">Loading users...</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        let allUsers = [];
        let currentSort = { column: 'activity', direction: 'desc' };

        document.addEventListener('DOMContentLoaded', function() {
            loadUsers();
            
            document.getElementById('searchInput').addEventListener('input', filterAndDisplay);
            document.getElementById('levelFilter').addEventListener('change', filterAndDisplay);
            
            document.querySelectorAll('.leaderboard-table th.sortable').forEach(th => {
                th.addEventListener('click', function() {
                    const sortColumn = this.dataset.sort;
                    if (currentSort.column === sortColumn) {
                        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
                    } else {
                        currentSort.column = sortColumn;
                        currentSort.direction = 'desc';
                    }
                    updateSortIndicators();
                    filterAndDisplay();
                });
            });
        });

        function updateSortIndicators() {
            document.querySelectorAll('.leaderboard-table th.sortable').forEach(th => {
                th.classList.remove('sorted-asc', 'sorted-desc');
                if (th.dataset.sort === currentSort.column) {
                    th.classList.add(currentSort.direction === 'asc' ? 'sorted-asc' : 'sorted-desc');
                }
            });
        }

        async function loadUsers() {
            showLoading(true);
            try {
                const response = await fetch('admin_api.php?action=getUsers');
                const data = await response.json();
                
                if (data.success) {
                    allUsers = data.users;
                    updateStats(data.stats);
                    filterAndDisplay();
                } else {
                    showError('Error loading data: ' + data.message);
                }
            } catch (error) {
                showError('Connection error: ' + error.message);
            }
            showLoading(false);
        }

        function updateStats(stats) {
            document.getElementById('totalUsers').textContent = stats.total;
            document.getElementById('activeUsers').textContent = stats.active;
            document.getElementById('completedUsers').textContent = stats.completed;
            document.getElementById('totalResponses').textContent = stats.responses;
        }

        function filterAndDisplay() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const levelFilter = document.getElementById('levelFilter').value;
            
            let filtered = allUsers.filter(user => {
                const matchesSearch = !searchTerm || 
                    user.name.toLowerCase().includes(searchTerm) ||
                    user.user_id.toLowerCase().includes(searchTerm);
                
                let matchesLevel = true;
                if (levelFilter) {
                    const currentDay = user.current_day || 1;
                    if (levelFilter === '1') matchesLevel = currentDay <= 30;
                    else if (levelFilter === '2') matchesLevel = currentDay > 30 && currentDay <= 60;
                    else if (levelFilter === 'complete') matchesLevel = currentDay > 60;
                }
                
                return matchesSearch && matchesLevel;
            });
            
            filtered = sortUsers(filtered);
            displayUsers(filtered);
        }

        function sortUsers(users) {
            return users.sort((a, b) => {
                let aVal, bVal;
                
                switch(currentSort.column) {
                    case 'name':
                        aVal = a.name.toLowerCase();
                        bVal = b.name.toLowerCase();
                        break;
                    case 'level':
                        aVal = a.current_day || 0;
                        bVal = b.current_day || 0;
                        break;
                    case 'responses':
                        aVal = a.completed_days || 0;
                        bVal = b.completed_days || 0;
                        break;
                    case 'progress':
                        aVal = a.progress_percentage || 0;
                        bVal = b.progress_percentage || 0;
                        break;
                    case 'points':
                        aVal = a.points || 0;
                        bVal = b.points || 0;
                        break;
                    case 'activity':
                        aVal = new Date(a.last_activity || 0).getTime();
                        bVal = new Date(b.last_activity || 0).getTime();
                        break;
                    default:
                        return 0;
                }
                
                if (currentSort.direction === 'asc') {
                    return aVal > bVal ? 1 : aVal < bVal ? -1 : 0;
                } else {
                    return aVal < bVal ? 1 : aVal > bVal ? -1 : 0;
                }
            });
        }

        function displayUsers(users) {
            const tbody = document.getElementById('leaderboardBody');
            tbody.innerHTML = '';

            if (users.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div class="empty-state-icon">🔍</div>
                            <div class="empty-state-text">No users found</div>
                        </td>
                    </tr>
                `;
                return;
            }

            users.forEach((user, index) => {
                const row = document.createElement('tr');
                const rankDisplay = index < 3 ? ['🥇', '🥈', '🥉'][index] : `#${index + 1}`;
                const currentDay = user.current_day || 1;
                const totalDays = currentDay <= 30 ? 30 : currentDay <= 60 ? 60 : 60;
                const progress = ((user.completed_days / totalDays) * 100).toFixed(1);
                
                let levelBadge = '';
                let levelClass = '';
                if (currentDay <= 30) {
                    levelBadge = '🌱 Level 1';
                    levelClass = 'level-1';
                } else if (currentDay <= 60) {
                    levelBadge = '🚀 Level 2';
                    levelClass = 'level-2';
                } else {
                    levelBadge = '✅ Complete';
                    levelClass = 'level-complete';
                }

                const userInitial = user.name.charAt(0).toUpperCase();
                const timeAgo = getTimeAgo(user.last_activity);
                const userIdShort = String(user.user_id).substring(0, 10);

                row.innerHTML = `
                    <td class="rank-cell">
                        <span class="rank-medal">${rankDisplay}</span>
                    </td>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar">${userInitial}</div>
                            <div class="user-info">
                                <div class="user-name">${user.name}</div>
                                <div class="user-id">ID: ${userIdShort}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="level-badge ${levelClass}">${levelBadge}</span>
                    </td>
                    <td>
                        <strong>${user.completed_days}</strong> / ${totalDays}
                    </td>
                    <td class="progress-cell">
                        <div class="progress-text">${progress}%</div>
                        <div class="progress-bar-container">
                            <div class="progress-bar-fill" style="width: ${progress}%"></div>
                        </div>
                    </td>
                    <td class="points-cell">
                        ${user.points} pts
                    </td>
                    <td class="date-cell">
                        ${timeAgo}
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function getTimeAgo(dateString) {
            if (!dateString) return 'Never';
            
            const date = new Date(dateString);
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);
            
            if (diffMins < 1) return 'Just now';
            if (diffMins < 60) return `${diffMins}m ago`;
            if (diffHours < 24) return `${diffHours}h ago`;
            if (diffDays < 7) return `${diffDays}d ago`;
            if (diffDays < 30) return `${Math.floor(diffDays / 7)}w ago`;
            return `${Math.floor(diffDays / 30)}mo ago`;
        }

        function showLoading(show) {
            document.getElementById('loadingOverlay').style.display = show ? 'flex' : 'none';
        }

        function showSuccess(message) {
            const alert = document.getElementById('successAlert');
            alert.textContent = '✅ ' + message;
            alert.style.display = 'block';
            setTimeout(() => alert.style.display = 'none', 5000);
        }

        function showError(message) {
            const alert = document.getElementById('errorAlert');
            alert.textContent = '❌ ' + message;
            alert.style.display = 'block';
            setTimeout(() => alert.style.display = 'none', 8000);
        }
    </script>
</body>
</html>
