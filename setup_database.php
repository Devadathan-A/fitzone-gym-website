<?php
/**
 * FitZone Gym - Automated Database & Table Installer
 * 
 * Demonstrates Module 4 concepts:
 * - Connecting to MySQL server
 * - Executing DDL queries (CREATE DATABASE, CREATE TABLE)
 * - Checking query success with mysqli_query()
 * - Inserting starter data with INSERT INTO
 */

$servername = "localhost";
$username   = "root";
$password   = "";

// Step 1: Connect to MySQL server without selecting a DB
$initial_conn = @mysqli_connect($servername, $username, $password);

$messages = [];
$has_error = false;

if (mysqli_connect_errno()) {
    $has_error = true;
    $messages[] = [
        'type' => 'error',
        'text' => "Connection to MySQL server failed: " . mysqli_connect_error() . ". Make sure MySQL is running in your XAMPP/WAMP control panel."
    ];
} else {
    // Step 2: Create database if it does not exist
    $create_db_sql = "CREATE DATABASE IF NOT EXISTS `fitzone_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if (mysqli_query($initial_conn, $create_db_sql)) {
        $messages[] = ['type' => 'success', 'text' => "Database 'fitzone_db' checked/created successfully."];
    } else {
        $has_error = true;
        $messages[] = ['type' => 'error', 'text' => "Error creating database: " . mysqli_error($initial_conn)];
    }
    mysqli_close($initial_conn);

    if (!$has_error) {
        // Step 3: Connect to the specific database
        $conn = mysqli_connect($servername, $username, $password, "fitzone_db");

        if (!$conn) {
            $has_error = true;
            $messages[] = ['type' => 'error', 'text' => "Failed to select 'fitzone_db': " . mysqli_connect_error()];
        } else {
            // Module 4, Page 7: CREATE TABLE query execution
            $table_members_sql = "CREATE TABLE IF NOT EXISTS `members` (
                `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `fullname` VARCHAR(100) NOT NULL,
                `email` VARCHAR(100) NOT NULL,
                `phone` VARCHAR(25) NOT NULL,
                `start_date` DATE NOT NULL,
                `plan` VARCHAR(50) NOT NULL,
                `workout_pref` VARCHAR(50) DEFAULT 'Strength Training',
                `goal` VARCHAR(150) DEFAULT 'General Fitness',
                `experience` INT DEFAULT 5,
                `fee` DECIMAL(10, 2) NOT NULL DEFAULT 999.00,
                `status` VARCHAR(20) NOT NULL DEFAULT 'Active',
                `reg_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

            if (mysqli_query($conn, $table_members_sql)) {
                $messages[] = ['type' => 'success', 'text' => "Table 'members' created/verified successfully."];
                
                // Ensure workout_pref column exists if table already existed
                $check_col = mysqli_query($conn, "SHOW COLUMNS FROM `members` LIKE 'workout_pref'");
                if ($check_col && mysqli_num_rows($check_col) == 0) {
                    mysqli_query($conn, "ALTER TABLE `members` ADD `workout_pref` VARCHAR(50) DEFAULT 'Strength Training' AFTER `plan`");
                }
            } else {
                $has_error = true;
                $messages[] = ['type' => 'error', 'text' => "Error creating 'members' table: " . mysqli_error($conn)];
            }

            // Create workouts table
            $table_workouts_sql = "CREATE TABLE IF NOT EXISTS `workouts` (
                `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `title` VARCHAR(100) NOT NULL,
                `category` VARCHAR(50) NOT NULL,
                `description` TEXT NOT NULL,
                `exercises` TEXT NOT NULL,
                `image_url` VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

            if (mysqli_query($conn, $table_workouts_sql)) {
                $messages[] = ['type' => 'success', 'text' => "Table 'workouts' created/verified successfully."];
            } else {
                $has_error = true;
                $messages[] = ['type' => 'error', 'text' => "Error creating 'workouts' table: " . mysqli_error($conn)];
            }

            // Step 4: Populate starter seed data if tables are empty
            // Check members count
            $check_members = mysqli_query($conn, "SELECT COUNT(*) as total FROM `members`");
            $member_count_row = mysqli_fetch_assoc($check_members);
            if ($member_count_row && (int)$member_count_row['total'] === 0) {
                // Module 4, Page 8: Inserting Data with PHP
                $seed_members = "INSERT INTO `members` (`fullname`, `email`, `phone`, `start_date`, `plan`, `goal`, `experience`, `fee`, `status`) VALUES
                    ('John Doe', 'john.doe@example.com', '9876543210', '2026-11-01', 'Pro', 'Build Muscle & Strength', 6, 2499.00, 'Active'),
                    ('Sarah Connor', 'sarah.c@example.com', '9812345678', '2026-11-05', 'Elite', 'Athletic Conditioning', 8, 4499.00, 'Active'),
                    ('Alex Miller', 'alex.m@example.com', '9701122334', '2026-11-10', 'Basic', 'Weight Loss & Endurance', 3, 999.00, 'Active')";
                mysqli_query($conn, $seed_members);
                $messages[] = ['type' => 'success', 'text' => "Sample member records inserted."];
            }

            // Check workouts count
            $check_workouts = mysqli_query($conn, "SELECT COUNT(*) as total FROM `workouts`");
            $workout_count_row = mysqli_fetch_assoc($check_workouts);
            if ($workout_count_row && (int)$workout_count_row['total'] === 0) {
                $seed_workouts = "INSERT INTO `workouts` (`title`, `category`, `description`, `exercises`, `image_url`) VALUES
                    ('Strength Training', 'Strength', 'Build strength with compound movements and progressive overload training.', 'Squats,Bench Press,Barbell Rows,Overhead Press', 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=900&q=80'),
                    ('Cardio Fitness', 'Cardio', 'Improve stamina, endurance and cardiovascular health with high-energy sessions.', 'Warm up,HIIT Intervals,Treadmill Run,Cool down', 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=900&q=80'),
                    ('Muscle Building', 'Hypertrophy', 'A balanced routine combining resistance training, targeted isolation and recovery.', 'Push exercises,Pull exercises,Leg exercises,Core finish', 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=900&q=80')";
                mysqli_query($conn, $seed_workouts);
                $messages[] = ['type' => 'success', 'text' => "Sample workout routines inserted."];
            }

            mysqli_close($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitZone | Database Setup & Installer</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .installer-container {
            max-width: 650px;
            margin: 60px auto;
            background: #fff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .installer-title {
            text-align: center;
            margin-bottom: 25px;
        }
        .installer-title h1 {
            color: #111820;
            font-size: 1.8rem;
        }
        .installer-title span {
            color: #ffb703;
        }
        .log-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 25px;
            font-family: monospace;
            font-size: 0.95rem;
        }
        .log-item {
            margin-bottom: 8px;
            padding: 6px 10px;
            border-radius: 4px;
        }
        .log-item.success {
            background: #dcfce7;
            color: #166534;
        }
        .log-item.error {
            background: #fee2e2;
            color: #991b1b;
        }
        .actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="installer-container">
        <div class="installer-title">
            <h1>FIT<span>ZONE</span> Database Setup</h1>
            <p>PHP &amp; MySQL Automated Table &amp; Schema Installer</p>
        </div>

        <div class="log-box">
            <?php foreach ($messages as $msg): ?>
                <div class="log-item <?php echo $msg['type']; ?>">
                    <?php echo ($msg['type'] === 'success' ? '✔ ' : '✖ ') . htmlspecialchars($msg['text']); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (!$has_error): ?>
            <p style="text-align: center; margin-bottom: 20px; color: #166534; font-weight: bold;">
                Setup completed successfully! Your FitZone database is ready.
            </p>
            <div class="actions">
                <a href="index.php" class="button">Go to FitZone Website</a>
                <a href="members_list.php" class="button" style="background: #111820;">View Member Directory</a>
            </div>
        <?php else: ?>
            <div class="actions">
                <a href="setup_database.php" class="button">Retry Setup</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

