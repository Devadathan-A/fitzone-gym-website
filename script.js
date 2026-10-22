// Basic JavaScript: variables, functions, if/else, switch and events.

function calculateBMI() {
    var height = parseFloat(document.getElementById("height").value);
    var weight = parseFloat(document.getElementById("weight").value);
    var result = document.getElementById("bmiResult");

    if (height <= 0 || weight <= 0 || isNaN(height) || isNaN(weight)) {
        result.textContent = "Please enter a valid height and weight.";
        return;
    }

    var heightInMeters = height / 100;
    var bmi = weight / (heightInMeters * heightInMeters);
    var category;

    if (bmi < 18.5) {
        category = "Underweight";
    } else if (bmi < 25) {
        category = "Normal range";
    } else if (bmi < 30) {
        category = "Overweight";
    } else {
        category = "Obesity range";
    }

    result.textContent = "Your BMI is " + bmi.toFixed(1) + " — " + category + ".";
}

function showPlanMessage() {
    var plan = document.getElementById("plan").value;
    var message = document.getElementById("planMessage");
    var text;

    switch (plan) {
        case "Basic":
            text = "Basic plan selected.";
            break;
        case "Pro":
            text = "Pro plan selected — trainer support included.";
            break;
        case "Elite":
            text = "Elite plan selected — premium training support.";
            break;
        default:
            text = "Please select a plan.";
    }

    message.textContent = text;
}

function submitForm(event) {
    event.preventDefault();

    var name = document.getElementById("name").value;
    alert("Thank you, " + name + "! Your FitZone registration has been submitted.");
}
