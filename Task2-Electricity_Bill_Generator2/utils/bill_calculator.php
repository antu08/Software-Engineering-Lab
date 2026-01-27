<?php
/**
 * Electricity Bill Calculator Module
 * Handles computation logic for different consumer types with Tiered Rates
 */

function calculateBill($type, $units, $hasPreviousDue)
{
    $energy_charge = 0;
    $units_left = $units;

    // 1. Calculate Energy Charges based on Tiers
    if ($type == 'household') {
        // First 50 @ 1.5
        if ($units_left > 0) {
            $slab = min($units_left, 50);
            $energy_charge += $slab * 1.5;
            $units_left -= $slab;
        }
        // Next 50 @ 2.5
        if ($units_left > 0) {
            $slab = min($units_left, 50);
            $energy_charge += $slab * 2.5;
            $units_left -= $slab;
        }
        // Next 50 @ 3.5 (3rd fifty)
        if ($units_left > 0) {
            $slab = min($units_left, 50);
            $energy_charge += $slab * 3.5;
            $units_left -= $slab;
        }
        // Later onwards @ 4.5
        if ($units_left > 0) {
            $energy_charge += $units_left * 4.5;
        }

    } elseif ($type == 'commercial') {
        // First 100 @ 2.5
        if ($units_left > 0) {
            $slab = min($units_left, 100);
            $energy_charge += $slab * 2.5;
            $units_left -= $slab;
        }
        // Second 100 @ 4.5
        if ($units_left > 0) {
            $slab = min($units_left, 100);
            $energy_charge += $slab * 4.5;
            $units_left -= $slab;
        }
        // Later onwards @ 7
        if ($units_left > 0) {
            $energy_charge += $units_left * 7.0;
        }

    } elseif ($type == 'industry') {
        // First 100 @ 2.5
        if ($units_left > 0) {
            $slab = min($units_left, 100);
            $energy_charge += $slab * 2.5;
            $units_left -= $slab;
        }
        // Second 100 @ 3.5
        if ($units_left > 0) {
            $slab = min($units_left, 100);
            $energy_charge += $slab * 3.5;
            $units_left -= $slab;
        }
        // Later onwards @ 6
        if ($units_left > 0) {
            $energy_charge += $units_left * 6.0;
        }
    }

    // 2. Minimum Charge Logic
    // "If the number of units consumed are 0, then minimum charge 25/- has to be levied."
    // This implies if units == 0, the Bill (or Energy Charge?) is 25.
    // Usually 'Minimum Charge' replaces Energy Charges if they are lower than Min Charge.
    // But specific instruction says "If units are 0".
    if ($units == 0) {
        $energy_charge = 25.00;
    }

    // Fixed Charges & Customer Charges (Keeping previous logic unless asked to remove)
    // The prompt only mentioned changing rates. 
    // Usually Fixed/Customer charges exist ON TOP of Energy charges.
    // However, if units=0 and min charge is 25, usually that's the Total Payable (excluding arrears).
    // Let's keep Fixed/Customer charges as they are standard components, 
    // BUT maybe reset them if units=0 if strict "Min Charge 25" means "ONLY 25".
    // "minimum charge 25/- has to be levied" -> usually means the computed Energy Charge becomes 25.
    // Let's stick to: Energy Charge = Calculated OR 25 (if units=0).
    // Plus other charges.

    $fixed_charge = 12.00;
    $customer_charge = 75.00;

    // Calculate Electricity Duty (7% of Energy Charges)
    $ed = $energy_charge * 0.07;

    // Surcharge
    $surcharge = 0;

    // Fine
    $fine = 0;
    if ($hasPreviousDue) {
        $fine = 150;
    }

    // Total Calculation
    $total = $energy_charge + $fixed_charge + $customer_charge + $ed + $surcharge + $fine;

    return [
        'energy_charge' => number_format($energy_charge, 2, '.', ''),
        'fixed_charge' => number_format($fixed_charge, 2, '.', ''),
        'customer_charge' => number_format($customer_charge, 2, '.', ''),
        'ed' => number_format($ed, 2, '.', ''),
        'surcharge' => number_format($surcharge, 2, '.', ''),
        'fine' => number_format($fine, 2, '.', ''),
        'total' => number_format($total, 2, '.', '')
    ];
}
?>