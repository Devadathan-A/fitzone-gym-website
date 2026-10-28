<?php
/**
 * FitZone Gym - Core Configuration & Educational Demonstrator
 * 
 * Demonstrates Module 3 concepts:
 * - Constants (define())
 * - Variables & Data Types (int, float, string, bool, array)
 * - Array manipulation (count, array_merge, array_keys, explode, implode, shuffle)
 * - Functions (return values, passing by reference, variable scope, global variables)
 * - String comparisons (strcmp)
 * - Regular expressions (preg_match, preg_replace)
 */

// Module 3, Page 10: Creating Constants using define()
define("GYM_NAME", "FitZone");
define("GYM_TAGLINE", "TRAIN • EAT • REST • REPEAT");
define("CURRENCY_SYMBOL", "₹");
define("TAX_RATE", 0.05); // 5% GST/Tax

// Module 3, Page 33: Associative Arrays for Membership Plans
$MEMBERSHIP_PLANS = array(
    "Basic" => array(
        "duration"   => "1 Month",
        "months"     => 1,
        "price"      => 999.00,
        "gym_access" => true,
        "trainer"    => false,
        "perks"      => "Locker access, General gym floor access, Free Wi-Fi",
        "description"=> "Affordable self-paced access for fitness enthusiasts."
    ),
    "Pro" => array(
        "duration"   => "3 Months",
        "months"     => 3,
        "price"      => 2499.00,
        "gym_access" => true,
        "trainer"    => true,
        "perks"      => "Locker access, Personal trainer sessions, Free diet chart",
        "description"=> "Our most popular choice with certified trainer guidance."
    ),
    "Elite" => array(
        "duration"   => "6 Months",
        "months"     => 6,
        "price"      => 4499.00,
        "gym_access" => true,
        "trainer"    => true,
        "perks"      => "All-hours VIP access, Diet & physique consultation, Steam/Sauna",
        "description"=> "Comprehensive coaching for elite athletes and serious lifters."
    )
);

// Module 3, Page 30: Indexed Array of Workout Programs
$WORKOUT_PROGRAMS = array(
    "Strength Training",
    "Cardio Fitness",
    "Muscle Building",
    "HIIT & Conditioning"
);

// Module 3, Page 31 & 32: Array of motivational gym quotes to demonstrate shuffle()
$MOTIVATIONAL_QUOTES = array(
    "Discipline is the bridge between goals and accomplishment.",
    "The body achieves what the mind believes.",
    "Action is the foundational key to all fitness success.",
    "Small daily improvements over time lead to stunning results.",
    "Your only limit is you."
);

/**
 * Module 3, Page 32: shuffle() demonstration
 * Returns a randomized motivational quote
 */
function getRandomQuote() {
    global $MOTIVATIONAL_QUOTES; // Module 3, Page 41-42: Accessing variables with global statement
    $quotes = $MOTIVATIONAL_QUOTES;
    shuffle($quotes); // Module 3, Page 32: shuffle() randomizes array elements
    return $quotes[0];
}

/**
 * Module 3, Page 37-38: Defining a Function and Returning Values
 * Calculates the total membership fee including tax
 */
function calculateTotalFee($basePrice, $taxRate = TAX_RATE) {
    // Module 3, Page 10: Type casting (float)
    $cleanPrice = (float)$basePrice;
    $taxAmount = $cleanPrice * $taxRate;
    $total = $cleanPrice + $taxAmount;
    return $total;
}

/**
 * Module 3, Page 39, Listing 13.13: Passing Variable References to Functions (&$variable)
 * Applies a seasonal discount directly modifying the original fee variable by reference
 */
function applySeasonalDiscount(&$fee, $discountPercent = 10) {
    $discountAmount = ($fee * (float)$discountPercent) / 100.0;
    $fee = $fee - $discountAmount;
    return $discountAmount;
}

/**
 * Module 3, Page 47: String Comparisons with strcmp()
 * Checks if a requested plan is the highest tier
 */
function isEliteTier($selectedPlan) {
    // strcmp returns 0 when strings are equal
    if (strcmp(trim($selectedPlan), "Elite") === 0) {
        return true;
    }
    return false;
}

/**
 * Module 3, Page 49-56: Regular Expressions with preg_match() and preg_replace()
 * Validates phone numbers (supports 10-digit formats, optional brackets/dashes)
 */
function validatePhoneNumber($phone) {
    // Clean string using preg_replace (Module 3, Page 50 Listing 19.9)
    $digits = preg_replace("/[^0-9]/", "", $phone);
    
    // Pattern matching 10 consecutive digits (Module 3, Page 53-54: quantifiers {10})
    if (preg_match("/^[0-9]{10}$/", $digits)) {
        return true;
    }
    return false;
}

/**
 * Module 3, Page 49-55: Name validation using Character Classes
 * Ensures name contains only alphabetic characters and spaces
 */
function validateFullName($name) {
    // Uses PCRE pattern /^[a-zA-Z\s]+$/
    if (preg_match("/^[a-zA-Z\s]{2,50}$/", trim($name))) {
        return true;
    }
    return false;
}

/**
 * Formats currency amount
 */
function formatPrice($amount) {
    return CURRENCY_SYMBOL . number_format((float)$amount, 2);
}
?>

