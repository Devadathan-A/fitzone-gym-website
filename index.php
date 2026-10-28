<?php
/**
 * FitZone Gym - Dynamic Homepage & Registration Portal
 * 
 * Demonstrates:
 * - Module 3: Variables, Data Types, Control Structures, Arrays, Functions, OOP, RegEx
 * - Module 4: Sessions, Cookies, MySQL Connection & INSERT Query, Form Processing & Dynamic Content
 */

// Module 4, Page 4: session_start() must be called before ANY HTML output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Module 3: File Inclusion (require_once)
require_once "config.php";
require_once "db_connect.php";
require_once "classes/GymMember.php";

// Initialize form variables and error tracking
// Module 3, Page 56 & Module 4, Page 15: Form Processing & Sticky Form variables
$name       = isset($_POST['name']) ? trim($_POST['name']) : '';
$email      = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone      = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$date       = isset($_POST['date']) ? trim($_POST['date']) : date('Y-m-d');
$plan       = isset($_POST['plan']) ? trim($_POST['plan']) : 'Basic';
$workout_pref = isset($_POST['workout_pref']) ? trim($_POST['workout_pref']) : 'Strength Training';
$goal       = isset($_POST['goal']) ? trim($_POST['goal']) : '';
$experience = isset($_POST['experience']) ? (int)$_POST['experience'] : 5; // Type casting to int

$iserror = false;
$formerrors = array(
    "nameerror"  => false,
    "emailerror" => false,
    "phoneerror" => false,
    "dateerror"  => false,
    "planerror"  => false
);

// Module 4: Handle POST Submission for Member Registration
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_join'])) {

    // 1. Validate Name using Regular Expression (Module 3, Page 49-55)
    if (empty($name) || !validateFullName($name)) {
        $formerrors["nameerror"] = true;
        $iserror = true;
    }

    // 2. Validate Email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formerrors["emailerror"] = true;
        $iserror = true;
    }

    // 3. Validate Phone Number with PCRE Regular Expression (Module 3, Page 50-54)
    if (empty($phone) || !validatePhoneNumber($phone)) {
        $formerrors["phoneerror"] = true;
        $iserror = true;
    }

    // 4. Validate Plan existence
    if (!array_key_exists($plan, $MEMBERSHIP_PLANS)) {
        $formerrors["planerror"] = true;
        $iserror = true;
    }

    // If all inputs are valid, process registration and insert into database
    if (!$iserror) {
        $baseFee = $MEMBERSHIP_PLANS[$plan]["price"];
        
        // Module 3: Object-Oriented Programming (Listing 13.15, 13.16, 13.19)
        // Instantiate GymMember object
        $memberObj = new GymMember($name, $email, $phone, $plan, $baseFee);
        $memberObj->workout_pref = $workout_pref;

        // Module 3, Page 47: String comparison using strcmp()
        if (strcmp($plan, "Elite") === 0) {
            // Apply special 5% welcome discount for Elite members
            $memberObj->applyPlanDiscount(5);
        }

        $finalFee = $memberObj->getFinalFee();

        // Check if database is connected (Module 4)
        if ($db_connected && $conn) {
            // Module 4, Page 9, Listing 18.6: Sanitize inputs with mysqli_real_escape_string
            $clean_name    = mysqli_real_escape_string($conn, $name);
            $clean_email   = mysqli_real_escape_string($conn, $email);
            $clean_phone   = mysqli_real_escape_string($conn, $phone);
            $clean_date    = mysqli_real_escape_string($conn, $date);
            $clean_plan    = mysqli_real_escape_string($conn, $plan);
            $clean_workout = mysqli_real_escape_string($conn, $workout_pref);
            $clean_goal    = mysqli_real_escape_string($conn, $goal);
            $clean_exp     = (int)$experience;
            $clean_fee     = (float)$finalFee;

            // Module 4, Page 8: INSERT INTO SQL query
            $sql = "INSERT INTO `members` 
                    (`fullname`, `email`, `phone`, `start_date`, `plan`, `workout_pref`, `goal`, `experience`, `fee`, `status`) 
                    VALUES 
                    ('$clean_name', '$clean_email', '$clean_phone', '$clean_date', '$clean_plan', '$clean_workout', '$clean_goal', $clean_exp, $clean_fee, 'Active')";

            // Module 4, Page 7: Executing queries with mysqli_query()
            $insert_res = mysqli_query($conn, $sql);

            if ($insert_res) {
                $member_id = mysqli_insert_id($conn);

                // Module 4, Page 1-2: Set Cookies for the returning visitor
                // setcookie(name, value, expire, path)
                setcookie("fitzone_visitor", $name, time() + (86400 * 30), "/"); // 30 days
                setcookie("fitzone_last_plan", $plan, time() + (86400 * 30), "/");

                // Module 4, Page 4: Store notification in Session variable
                $_SESSION['flash_message'] = "🎉 Welcome to FitZone, " . htmlspecialchars($name) . "! Your registration has been saved to the MySQL database (Member ID: #" . $member_id . ").";
                $_SESSION['flash_type'] = "success";

                // Clear sticky fields after successful submission
                $name = $email = $phone = $goal = "";
                $plan = "Basic";
                $experience = 5;

                // Redirect to avoid form resubmission on page refresh
                header("Location: index.php#join");
                exit();
            } else {
                $_SESSION['flash_message'] = "Error saving to database: " . mysqli_error($conn);
                $_SESSION['flash_type'] = "error";
            }
        } else {
            // Database not connected yet: Guide the user to run setup_database.php
            $_SESSION['flash_message'] = "Registration received in demo mode! (To store directly in MySQL, please run the 1-click installer: setup_database.php)";
            $_SESSION['flash_type'] = "warning";
        }
    }
}

// Set page title and load modular header (includes/header.php)
$page_title = "Home & Fitness Programs";
require_once "includes/header.php";
?>

    <!-- Hero Section with Dynamic PHP Content -->
    <div class="hero">
        <div class="hero-text">
            <p class="tag"><?php echo GYM_TAGLINE; ?></p>
            <h2>Build a stronger<br><span>version of you.</span></h2>
            <p>Simple workouts, practical nutrition and consistent habits for a healthier lifestyle.</p>
            
            <!-- Module 3: Dynamic quote using array shuffle() & global variables -->
            <div class="hero-quote-box">
                <small>💡 <em>"<?php echo htmlspecialchars(getRandomQuote()); ?>"</em></small>
            </div>

            <a class="button" href="#join" style="margin-top: 15px;">Start Your Journey</a>
            <a class="button secondary-hero-btn" href="members_list.php" style="margin-left: 10px; background: transparent; border: 2px solid #ffb703;">Member Directory</a>
        </div>
    </div>

    <main>
        <!-- Dynamic Workouts Section -->
        <!-- Demonstrates Module 3 Array Iteration and Module 4 Database Reading -->
        <section class="section" id="workouts">
            <div class="section-title">
                <p class="tag">TRAIN SMART</p>
                <h2>Popular Workouts</h2>
                <p>Choose a workout style that matches your goal. (Rendered dynamically with PHP &amp; MySQL)</p>
            </div>

            <div class="cards">
                <?php
                // Fetch workouts dynamically from MySQL if available, or fall back to default array
                $workouts_loaded = false;
                if ($db_connected && $conn) {
                    // Module 4, Page 11: SELECT Query & Reading from Database
                    $workout_query = "SELECT * FROM `workouts`";
                    $workout_res = mysqli_query($conn, $workout_query);

                    if ($workout_res && mysqli_num_rows($workout_res) > 0) {
                        $workouts_loaded = true;
                        // Module 4, Page 20: Fetching rows from result set
                        while ($row = mysqli_fetch_assoc($workout_res)) {
                            // Module 3, Page 32: explode() splits comma-delimited string into array
                            $exerciseList = explode(",", $row['exercises']);
                            ?>
                            <article class="card">
                                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                                <div class="card-content">
                                    <span class="category-pill"><?php echo htmlspecialchars($row['category']); ?></span>
                                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                                    
                                    <!-- Module 3, Page 31 & 36: foreach loop through exercise array -->
                                    <ul>
                                        <?php foreach ($exerciseList as $ex): ?>
                                            <li><?php echo htmlspecialchars(trim($ex)); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </article>
                            <?php
                        }
                        // Module 4, Page 11: Free result memory
                        mysqli_free_result($workout_res);
                    }
                }

                // Fallback static array if database not yet initialized
                if (!$workouts_loaded) {
                    $defaultWorkouts = array(
                        array("title" => "Strength Training", "cat" => "Strength", "desc" => "Build strength with compound movements and progressive training.", "items" => array("Squats", "Bench Press", "Rows"), "img" => "https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=900&q=80"),
                        array("title" => "Cardio Fitness", "cat" => "Cardio", "desc" => "Improve endurance and cardiovascular fitness with regular activity.", "items" => array("Warm up", "Cardio session", "Cool down"), "img" => "https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=900&q=80"),
                        array("title" => "Muscle Building", "cat" => "Hypertrophy", "desc" => "A balanced routine combining resistance training, food and recovery.", "items" => array("Push exercises", "Pull exercises", "Leg exercises"), "img" => "https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=900&q=80")
                    );
                    foreach ($defaultWorkouts as $dw) {
                        ?>
                        <article class="card">
                            <img src="<?php echo $dw['img']; ?>" alt="<?php echo $dw['title']; ?>">
                            <div class="card-content">
                                <span class="category-pill"><?php echo $dw['cat']; ?></span>
                                <h3><?php echo $dw['title']; ?></h3>
                                <p><?php echo $dw['desc']; ?></p>
                                <ul>
                                    <?php foreach ($dw['items'] as $item): ?>
                                        <li><?php echo htmlspecialchars($item); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </article>
                        <?php
                    }
                }
                ?>
            </div>
        </section>

        <!-- About Section -->
        <section class="about section">
            <img class="float-image"
                 src="https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?auto=format&fit=crop&w=800&q=80"
                 alt="Athlete exercising with weights">
            <h2>Why Choose FitZone?</h2>
            <p>
                Fitness is not about doing everything at once. Start with small,
                consistent habits and gradually improve your training.
            </p>
            <p>
                Our server-powered fitness platform records your training profile,
                manages dynamic memberships with MySQL, and stores your workout preferences securely.
            </p>
            <a href="#nutrition">Explore Nutrition Tips →</a>
        </section>

        <!-- Dynamic Membership Plans Section -->
        <!-- Demonstrates Module 3 Associative Arrays & foreach loop -->
        <section class="section dark-section" id="plans">
            <div class="section-title">
                <p class="tag">MEMBERSHIP</p>
                <h2>Choose Your Plan</h2>
                <p>Prices generated dynamically via PHP configuration array</p>
            </div>

            <div class="table-wrap">
                <table>
                    <caption>FitZone Membership Plans</caption>
                    <thead>
                        <tr>
                            <th>Plan</th>
                            <th>Duration</th>
                            <th>Gym Access</th>
                            <th>Trainer Support</th>
                            <th>Perks</th>
                            <th>Base Price</th>
                            <th>Total (incl. 5% Tax)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Module 3, Page 33: Iterating through Associative Array with foreach ($arr as $key => $val)
                        foreach ($MEMBERSHIP_PLANS as $pName => $pData): 
                            // Module 3, Page 37-38: Calling functions and calculations
                            $totalWithTax = calculateTotalFee($pData['price']);
                        ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($pName); ?></strong></td>
                                <td><?php echo htmlspecialchars($pData['duration']); ?></td>
                                <td><?php echo $pData['gym_access'] ? '✔ Yes' : '✖ No'; ?></td>
                                <td><?php echo $pData['trainer'] ? '✔ Included' : '✖ None'; ?></td>
                                <td><small><?php echo htmlspecialchars($pData['perks']); ?></small></td>
                                <td><?php echo formatPrice($pData['price']); ?></td>
                                <td><strong style="color: #e85d04;"><?php echo formatPrice($totalWithTax); ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Nutrition & BMI Section -->
        <section class="section" id="nutrition">
            <div class="section-title">
                <p class="tag">FUEL YOUR BODY</p>
                <h2>Simple Nutrition Rules</h2>
            </div>

            <div class="nutrition-grid">
                <div class="info-box">
                    <h3>Daily Checklist</h3>
                    <ul>
                        <li>Eat adequate protein (1.6 - 2.2g per kg bodyweight).</li>
                        <li>Include fresh fruits, leafy greens, and complex carbs.</li>
                        <li>Drink at least 3-4 liters of water daily.</li>
                        <li>Prioritize whole, nutrient-dense foods over ultra-processed snacks.</li>
                        <li>Aim for 7 to 8 hours of uninterrupted recovery sleep.</li>
                    </ul>
                </div>

                <div class="calculator">
                    <h3>BMI Calculator</h3>
                    <p>Enter your height and weight to calculate your BMI.</p>

                    <label for="height">Height (cm)</label>
                    <input type="number" id="height" placeholder="e.g. 170">

                    <label for="weight">Weight (kg)</label>
                    <input type="number" id="weight" placeholder="e.g. 65">

                    <button class="button" onclick="calculateBMI()">Calculate BMI</button>
                    <p id="bmiResult">Your result will appear here.</p>
                </div>
            </div>
        </section>

        <!-- Dynamic Form Processing Section -->
        <!-- Demonstrates Module 3 Form Processing, Regular Expressions, Sticky Form, and Module 4 Database INSERT -->
        <section class="section form-section" id="join">
            <div class="section-title">
                <p class="tag">GET STARTED</p>
                <h2>Join FitZone</h2>
                <p>Register online. Your data is verified with PHP Regular Expressions and saved directly to MySQL!</p>
            </div>

            <?php if ($iserror): ?>
                <div class="form-error-summary">
                    <strong>Please fix the errors indicated below in red.</strong>
                </div>
            <?php endif; ?>

            <!-- Module 4, Page 9, Listing 18.5: Form using method="post" and action="index.php#join" -->
            <form id="joinForm" action="index.php#join" method="post">
                
                <!-- Full Name Field with sticky value and error indicator -->
                <label for="name">
                    Full Name: <?php if ($formerrors["nameerror"]) echo "<span class='error-marker'>* (Alphabetic characters only, 2-50 chars)</span>"; ?>
                </label>
                <input type="text" id="name" name="name" maxlength="50" 
                       value="<?php echo htmlspecialchars($name); ?>" 
                       class="<?php echo $formerrors["nameerror"] ? 'input-error' : ''; ?>" 
                       placeholder="e.g. John Doe" required>

                <!-- Email Field -->
                <label for="email">
                    Email Address: <?php if ($formerrors["emailerror"]) echo "<span class='error-marker'>* (Enter a valid email address)</span>"; ?>
                </label>
                <input type="email" id="email" name="email" 
                       value="<?php echo htmlspecialchars($email); ?>" 
                       class="<?php echo $formerrors["emailerror"] ? 'input-error' : ''; ?>" 
                       placeholder="e.g. member@example.com" required>

                <!-- Phone Field with RegEx validation -->
                <label for="phone">
                    Phone Number: <?php if ($formerrors["phoneerror"]) echo "<span class='error-marker'>* (Must be a valid 10-digit phone number)</span>"; ?>
                </label>
                <input type="tel" id="phone" name="phone" 
                       value="<?php echo htmlspecialchars($phone); ?>" 
                       class="<?php echo $formerrors["phoneerror"] ? 'input-error' : ''; ?>" 
                       placeholder="e.g. 9876543210" required>

                <!-- Preferred Start Date -->
                <label for="date">Preferred Start Date:</label>
                <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>">

                <!-- Membership Plan Dropdown -->
                <label for="plan">Membership Plan:</label>
                <select id="plan" name="plan" onchange="showPlanMessage()">
                    <?php 
                    // Module 3: Dynamically populating dropdown options using foreach
                    foreach ($MEMBERSHIP_PLANS as $key => $info): 
                        $selected = ($plan === $key) ? "selected" : "";
                    ?>
                        <option value="<?php echo htmlspecialchars($key); ?>" <?php echo $selected; ?>>
                            <?php echo htmlspecialchars($key); ?> Plan (<?php echo formatPrice($info['price']); ?> - <?php echo $info['duration']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Preferred Workout Program Dropdown (Module 3 Indexed Arrays & foreach) -->
                <label for="workout_pref">Preferred Workout Program:</label>
                <select id="workout_pref" name="workout_pref">
                    <?php 
                    foreach ($WORKOUT_PROGRAMS as $wProgram): 
                        $wSelected = ($workout_pref === $wProgram) ? "selected" : "";
                    ?>
                        <option value="<?php echo htmlspecialchars($wProgram); ?>" <?php echo $wSelected; ?>>
                            <?php echo htmlspecialchars($wProgram); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Fitness Goal Field -->
                <label for="goal">Fitness Goal:</label>
                <input type="text" id="goal" name="goal" 
                       value="<?php echo htmlspecialchars($goal); ?>" 
                       placeholder="e.g. Muscle Gain, Fat Loss, Endurance">

                <!-- Training Experience Slider -->
                <label for="experience">Training Experience Level (1 to 10):</label>
                <input type="range" id="experience" name="experience" min="1" max="10" 
                       value="<?php echo htmlspecialchars($experience); ?>">

                <p id="planMessage">Selected plan details will appear here.</p>

                <div class="form-buttons">
                    <input class="button" type="submit" name="submit_join" value="Complete Registration (Save to MySQL)">
                    <input class="reset-button" type="reset" value="Clear Form">
                </div>
            </form>
        </section>
    </main>

<?php
// Module 4, Page 7: Close the database connection explicitly at script termination
if ($db_connected && $conn) {
    closeDbConnection($conn);
}

// Module 3: Reusable Footer Template
require_once "includes/footer.php";
?>

