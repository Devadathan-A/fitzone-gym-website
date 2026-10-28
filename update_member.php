<?php
/**
 * FitZone Gym - Update Member Record
 * 
 * Demonstrates Module 4 concepts:
 * - Page 14: Executing UPDATE query on MySQL table
 * - Basic syntax: UPDATE members SET ... WHERE id = ...
 * - mysqli_query() and error handling with mysqli_error()
 * - Closing connection with mysqli_close()
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config.php";
require_once "db_connect.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : 0);

if ($id <= 0) {
    $_SESSION['flash_message'] = "Invalid member ID requested.";
    $_SESSION['flash_type'] = "error";
    header("Location: members_list.php");
    exit();
}

$error_msg = null;

// Handle Form Submission for UPDATE
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_submit'])) {
    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $plan     = trim($_POST['plan']);
    $workout  = trim($_POST['workout_pref'] ?? 'Strength Training');
    $goal     = trim($_POST['goal']);
    $status   = trim($_POST['status']);

    if (empty($fullname) || !validateFullName($fullname)) {
        $error_msg = "Please provide a valid full name.";
    } elseif (empty($phone) || !validatePhoneNumber($phone)) {
        $error_msg = "Please provide a valid 10-digit phone number.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Please provide a valid email address.";
    } else {
        // Module 4, Page 9 & 14: Escaping strings & building UPDATE query
        $clean_name    = mysqli_real_escape_string($conn, $fullname);
        $clean_email   = mysqli_real_escape_string($conn, $email);
        $clean_phone   = mysqli_real_escape_string($conn, $phone);
        $clean_plan    = mysqli_real_escape_string($conn, $plan);
        $clean_workout = mysqli_real_escape_string($conn, $workout);
        $clean_goal    = mysqli_real_escape_string($conn, $goal);
        $clean_status  = mysqli_real_escape_string($conn, $status);

        // Recalculate fee if plan changed
        $new_fee = isset($MEMBERSHIP_PLANS[$clean_plan]) ? $MEMBERSHIP_PLANS[$clean_plan]['price'] : 999.00;

        // Module 4, Page 14: UPDATE SQL query
        $update_sql = "UPDATE `members` SET 
                        `fullname`     = '$clean_name',
                        `email`        = '$clean_email',
                        `phone`        = '$clean_phone',
                        `plan`         = '$clean_plan',
                        `workout_pref` = '$clean_workout',
                        `goal`         = '$clean_goal',
                        `fee`          = $new_fee,
                        `status`       = '$clean_status'
                       WHERE `id` = $id";

        if (mysqli_query($conn, $update_sql)) {
            $_SESSION['flash_message'] = "Member record #$id updated successfully!";
            $_SESSION['flash_type'] = "success";
            closeDbConnection($conn);
            header("Location: members_list.php");
            exit();
        } else {
            $error_msg = "Error updating record: " . mysqli_error($conn);
        }
    }
}

// Fetch current record for this member
$select_sql = "SELECT * FROM `members` WHERE `id` = $id LIMIT 1";
$res = mysqli_query($conn, $select_sql);

if (!$res || mysqli_num_rows($res) === 0) {
    $_SESSION['flash_message'] = "Member #$id not found in database.";
    $_SESSION['flash_type'] = "error";
    header("Location: members_list.php");
    exit();
}

$member = mysqli_fetch_assoc($res);
mysqli_free_result($res);

$page_title = "Edit Member #" . $id;
require_once "includes/header.php";
?>

<main class="section form-section" style="max-width: 650px; margin: 40px auto;">
    <div class="section-title">
        <p class="tag">MODULE 4: UPDATE OPERATION</p>
        <h2>Edit Member Record #<?php echo $id; ?></h2>
        <p>Demonstrates updating an existing MySQL record using <code>UPDATE members SET ... WHERE id=...</code></p>
    </div>

    <?php if ($error_msg): ?>
        <div class="form-error-summary">
            <?php echo htmlspecialchars($error_msg); ?>
        </div>
    <?php endif; ?>

    <form action="update_member.php?id=<?php echo $id; ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($member['fullname']); ?>" required>

        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($member['email']); ?>" required>

        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($member['phone']); ?>" required>

        <label for="plan">Membership Plan:</label>
        <select id="plan" name="plan">
            <?php foreach ($MEMBERSHIP_PLANS as $pKey => $pInfo): ?>
                <option value="<?php echo htmlspecialchars($pKey); ?>" <?php echo ($member['plan'] === $pKey ? 'selected' : ''); ?>>
                    <?php echo htmlspecialchars($pKey); ?> Plan (<?php echo formatPrice($pInfo['price']); ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label for="workout_pref">Preferred Workout Program:</label>
        <select id="workout_pref" name="workout_pref">
            <?php foreach ($WORKOUT_PROGRAMS as $wProg): ?>
                <option value="<?php echo htmlspecialchars($wProg); ?>" <?php echo (($member['workout_pref'] ?? '') === $wProg ? 'selected' : ''); ?>>
                    <?php echo htmlspecialchars($wProg); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="goal">Fitness Goal:</label>
        <input type="text" id="goal" name="goal" value="<?php echo htmlspecialchars($member['goal']); ?>">

        <label for="status">Membership Status:</label>
        <select id="status" name="status">
            <option value="Active" <?php echo ($member['status'] === 'Active' ? 'selected' : ''); ?>>Active</option>
            <option value="Suspended" <?php echo ($member['status'] === 'Suspended' ? 'selected' : ''); ?>>Suspended</option>
            <option value="Expired" <?php echo ($member['status'] === 'Expired' ? 'selected' : ''); ?>>Expired</option>
        </select>

        <div class="form-buttons">
            <input class="button" type="submit" name="update_submit" value="Save Changes (Run UPDATE Query)">
            <a href="members_list.php" class="reset-button" style="text-decoration:none; text-align:center;">Cancel</a>
        </div>
    </form>
</main>

<?php
closeDbConnection($conn);
require_once "includes/footer.php";
?>

