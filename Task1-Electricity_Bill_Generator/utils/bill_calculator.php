<?php
function calculateBill($type, $units, $hasPreviousDue){

    $energy = 0;
    $fixed = 12;
    $customer = 75;
    $fine = 0;
    $surcharge = 0;
    $ed = 0;

    if($units == 0){
        return 60; // service charge
    }

    if($type == 'household'){
        if($units <= 50) $energy = $units * 1.90;
        elseif($units <= 200)
            $energy = (50*1.90) + (($units-50)*3.20);
        else
            $energy = (50*1.90) + (150*3.20) + (($units-200)*5.10);

        $ed = ($units/100) * 7;
        $surcharge = 18;
        if($hasPreviousDue) $fine = 100;
    }

    if($type == 'commercial'){
        if($units <= 450)
            $energy = $units * 10.30;
        else
            $energy = (450*10.30) + (($units-450)*13);

        $ed = ($units/100) * 12;
        $surcharge = 27;
        if($hasPreviousDue) $fine = 500;
    }

    if($type == 'industry'){
        if($units <= 700)
            $energy = $units * 7.40;
        else
            $energy = (700*7.40) + (($units-700)*10);

        $ed = ($units/100) * 12;
        $surcharge = 27;
        if($hasPreviousDue) $fine = 500;
    }

    return round($energy + $fixed + $customer + $ed + $surcharge + $fine,2);
}
