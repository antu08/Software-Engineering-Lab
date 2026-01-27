// Validate mobile number
function validateMobile(mobile) {
    let regex = /^[0-9]{10}$/;
    if (!regex.test(mobile.value)) {
        alert("Mobile number must be 10 digits");
        mobile.focus();
        return false;
    }
    return true;
}

// Validate pincode
function validatePincode(pin) {
    let regex = /^[0-9]{6}$/;
    if (!regex.test(pin.value)) {
        alert("Pincode must be 6 digits");
        pin.focus();
        return false;
    }
    return true;
}

// Confirm bill generation
function confirmGenerate() {
    return confirm("Are you sure you want to generate this month's bill?");
}

// Disable payment if due date crossed
function checkDue(dueDate) {
    let today = new Date().toISOString().split('T')[0];
    if (today > dueDate) {
        document.getElementById("payBtn").disabled = true;
        document.getElementById("status").innerHTML = "Payment window closed";
    }
}

// Confirm payment
function confirmPayment() {
    return confirm("Confirm payment of this bill?");
}
