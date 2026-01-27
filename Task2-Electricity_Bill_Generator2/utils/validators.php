<?php
/**
 * Validation Module
 * Handles valid input checks for Consumers and Usage
 */

function validateConsumerInput($name, $mobile, $service_number)
{
    $errors = [];

    // Name: Alphabets only, no numbers or special chars
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "Name must contain only alphabets (A-Z). No numbers or special characters.";
    }

    // Mobile: Exact 10 digits
    if (!preg_match("/^\d{10}$/", $mobile)) {
        $errors[] = "Phone number must be exactly 10 digits.";
    }

    // Service Number: 6 digits (Assumed based on refactor plan, though user said "example 000123")
    if (!preg_match("/^\d{6}$/", $service_number)) {
        $errors[] = "Service Number must be exactly 6 digits.";
    }

    return $errors;
}
?>