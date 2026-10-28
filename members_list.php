<?php
/**
 * FitZone Gym - Members Directory & Management Dashboard
 * 
 * Directly demonstrates Module 4 concepts:
 * - Fig 19.21 formDatabase.php (Reading from Database & Dynamic Content)
 * - Listing 18.7: SELECT query execution with mysqli_query()
 * - Retrieving row counts using mysqli_num_rows()
 * - Stepping through rows with mysqli_fetch_assoc()
 * - Memory deallocation with mysqli_free_result()
 * - Closing database connections with mysqli_close()
 * - Providing links for UPDATE and DELETE operations
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config.php";
require_once "db_connect.php";

$page_title = "Member Directory (MySQL Data)";
require_once "includes/header.php";

// Handle optional search filter
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
?>

<div class="admin-header-section">
    <div class="section-title" style="margin-bottom: 20px;">
        <p class="tag">DATABASE RECORDS</p>
        <h2>FitZone Registered Members</h2>
        <p>Dynamic records fetched directly from MySQL database table <code>members</code></p>
    </div>

    <!-- Search / Filter Form -->
    <div class="filter-bar">
        <form action="members_list.php" method="get" class="search-form">
            <input type="text" name="search" placeholder="Search by name, email, or plan..." 
                   value="<?php echo htmlspecialchars($search_term); ?>" class="search-input">
            <button type="submit" class="button">Search</button>
            <?php if (!empty($search_term)): ?>
                <a href="members_list.php" class="reset-button" style="text-decoration:none;">Clear</a>
            <?php endif; ?>
        </form>
        <div class="quick-links">
            <a href="index.php#join" class="button">+ Add New Member</a>
        </div>
    </div>
</div>

<main class="section" style="padding-top: 20px;">
    <div class="table-wrap">
        <?php
        if (!$db_connected || !$conn) {
            echo "<div class='flash-alert flash-error'>";
            echo "<h3>Database Not Connected!</h3>";
            echo "<p>Could not connect to MySQL server. Error: " . htmlspecialchars($db_connection_error) . "</p>";
            echo "<p>Please ensure Apache &amp; MySQL are running in XAMPP, then <a href='setup_database.php'>click here to run the 1-click installer</a>.</p>";
            echo "</div>";
        } else {
            // Module 4, Page 11: Build SELECT Query
            if (!empty($search_term)) {
                $clean_search = mysqli_real_escape_string($conn, $search_term);
                $query = "SELECT * FROM `members` WHERE `fullname` LIKE '%$clean_search%' OR `email` LIKE '%$clean_search%' OR `plan` LIKE '%$clean_search%' ORDER BY `id` DESC";
            } else {
                $query = "SELECT * FROM `members` ORDER BY `id` DESC";
            }

            // Module 4, Page 7 & 11: Execute SELECT query
            $result = mysqli_query($conn, $query);

            if (!$result) {
                echo "<p class='error-marker'>Error executing query: " . mysqli_error($conn) . "</p>";
            } else {
                // Module 4, Page 12: mysqli_num_rows() to retrieve row count
                $number_of_rows = mysqli_num_rows($result);
                ?>
                
                <div class="directory-stats">
                    <p>Total Members Found: <strong><?php echo $number_of_rows; ?></strong></p>
                    <small>Demonstrating Module 4: <code>mysqli_num_rows()</code> &amp; <code>mysqli_fetch_assoc()</code></small>
                </div>

                <?php if ($number_of_rows > 0): ?>
                    <table class="styled-database-table">
                        <caption>FitZone Active Members in Database</caption>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Start Date</th>
                                <th>Plan</th>
                                <th>Workout Style</th>
                                <th>Goal</th>
                                <th>Fee</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_revenue = 0.0;

                            // Module 4, Page 20: Stepping through each record in result set
                            while ($row = mysqli_fetch_assoc($result)) {
                                $total_revenue += (float)$row['fee'];
                                ?>
                                <tr>
                                    <td><strong>#<?php echo htmlspecialchars($row['id']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                                    <td>
                                        <span class="plan-badge plan-<?php echo strtolower($row['plan']); ?>">
                                            <?php echo htmlspecialchars($row['plan']); ?>
                                        </span>
                                    </td>
                                    <td><span class="category-pill" style="margin:0;"><?php echo htmlspecialchars($row['workout_pref'] ?? 'Strength Training'); ?></span></td>
                                    <td><?php echo htmlspecialchars($row['goal']); ?></td>
                                    <td><strong><?php echo formatPrice($row['fee']); ?></strong></td>
                                    <td>
                                        <span class="badge badge-<?php echo ($row['status'] === 'Active' ? 'success' : 'warning'); ?>">
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                    <td class="action-buttons">
                                        <!-- Module 4: UPDATE operation link -->
                                        <a href="update_member.php?id=<?php echo urlencode($row['id']); ?>" 
                                           class="btn-action btn-edit" title="Edit Member">Edit</a>

                                        <!-- Module 4: DELETE operation link -->
                                        <a href="delete_member.php?id=<?php echo urlencode($row['id']); ?>" 
                                           class="btn-action btn-delete" 
                                           onclick="return confirm('Are you sure you want to delete member: <?php echo addslashes($row['fullname']); ?>?');"
                                           title="Delete from MySQL">Delete</a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="8" style="text-align: right;">Total Estimated Revenue:</th>
                                <th colspan="3" style="text-align: left; color: #ffb703; background: #111820;">
                                    <?php echo formatPrice($total_revenue); ?>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <p>No member records found matching your search.</p>
                        <a href="index.php#join" class="button">Register a New Member</a>
                    </div>
                <?php endif; ?>

                <?php
                // Module 4, Page 12: Free result memory
                mysqli_free_result($result);
            }

            // Module 4, Page 7: Close connection
            closeDbConnection($conn);
        }
        ?>
    </div>
</main>

<?php require_once "includes/footer.php"; ?>

