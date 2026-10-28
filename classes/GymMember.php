<?php
/**
 * FitZone Gym - GymMember Class
 * 
 * Demonstrates Module 3 concepts:
 * - Declaring a Class (Listing 13.15)
 * - Properties of Objects (Listing 13.16)
 * - Object Methods & The $this variable (Listing 13.18, 13.19)
 * - Changing property values from within a method (Listing 13.20)
 * - Instantiating objects with the 'new' keyword
 */

class GymMember {
    // Module 3, Page 44: Declaring Public Properties
    public $id;
    public $fullname;
    public $email;
    public $phone;
    public $plan;
    public $workout_pref;
    public $goal;
    public $fee;
    public $status;
    public $discount = 0.0;

    // Constructor to initialize member properties
    public function __construct($name = "", $email = "", $phone = "", $plan = "Basic", $fee = 999.00) {
        $this->fullname = $name;
        $this->email    = $email;
        $this->phone    = $phone;
        $this->plan     = $plan;
        $this->fee      = (float)$fee;
        $this->status   = "Active";
        $this->discount = 0.0;
    }

    // Module 3, Page 46: Method that uses $this to access and modify properties
    public function setPlan($newPlan, $newFee) {
        $this->plan = $newPlan;
        $this->fee  = (float)$newFee;
    }

    // Method to calculate and apply discount based on plan
    public function applyPlanDiscount($percentage) {
        $discountAmount = ($this->fee * (float)$percentage) / 100.0;
        $this->fee = $this->fee - $discountAmount;
        $this->discount = $discountAmount;
        return $this->fee;
    }

    public function getFinalFee() {
        return (float)$this->fee;
    }

    // Module 3, Page 46: Method accessing properties via $this->
    public function getMembershipSummary() {
        return "Member: " . $this->fullname . " (" . $this->email . ") — Plan: " . $this->plan . " [Fee: ₹" . number_format($this->fee, 2) . "]";
    }

    public function getStatusBadge() {
        if ($this->status === "Active") {
            return "<span class='badge badge-success'>Active</span>";
        } else {
            return "<span class='badge badge-warning'>" . htmlspecialchars($this->status) . "</span>";
        }
    }
}
?>

