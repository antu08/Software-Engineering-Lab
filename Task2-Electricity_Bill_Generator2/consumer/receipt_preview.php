<?php
include("../config/db.php");

$service_number = $_GET['service_number'] ?? '';
if (!$service_number)
    die("Service Number Required");

// Current month bill (latest)
$currentBill = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM bills WHERE service_number='$service_number' ORDER BY id DESC LIMIT 1"
    )
);

if (!$currentBill)
    die("No Bill Found");

// Fetch Consumer Details
$consumerDetails = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM consumers WHERE service_number='$service_number'"
    )
);

// Fetch Previous Reading from Meter Readings
$reading = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM meter_readings WHERE service_number='$service_number' ORDER BY id DESC LIMIT 1"
    )
);
$prev_unit = $reading['prev_unit'] ?? 0;
$curr_unit = $reading['curr_unit'] ?? 0;

// Calculate Arrears (Previous Unpaid Bills)
$arrearsQuery = mysqli_query($conn, "SELECT SUM(total) as arrears FROM bills WHERE service_number='$service_number' AND status='UNPAID' AND id != '{$currentBill['id']}'");
$arrearsData = mysqli_fetch_assoc($arrearsQuery);
$arrears = $arrearsData['arrears'] ? $arrearsData['arrears'] : 0.00;

// Update Total with Arrears for Display
$total_due = $currentBill['total'] + $arrears;


?>
<!DOCTYPE html>
<html>

<head>
    <title>Bill Receipt - <?= $service_number ?></title>
    <style>
        body {
            background: #555;
            font-family: 'Arial Narrow', Arial, sans-serif;
        }

        .receipt-container {
            background: white;
            width: 750px;
            margin: 30px auto;
            padding: 5px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            border: 1px solid #000;
            position: relative;
        }

        .header {
            border: 2px solid #00aeef;
            padding: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #00aeef;
        }

        .header-logo {
            text-align: left;
            font-weight: bold;
            font-size: 24px;
        }

        .header-title {
            text-align: right;
            font-weight: bold;
            font-size: 18px;
            color: red;
        }

        .blue-box {
            border: 2px solid #00aeef;
            margin-top: 5px;
            font-size: 14px;
            position: relative;
        }

        .row {
            display: flex;
            border-bottom: 1px solid #00aeef;
        }

        .cell {
            padding: 4px 8px;
            border-right: 1px solid #00aeef;
            flex: 1;
        }

        .cell:last-child {
            border-right: none;
        }

        .label {
            color: #00aeef;
            font-weight: bold;
        }

        .value {
            color: #000;
            font-weight: bold;
        }

        .big-sc {
            font-size: 24px;
            font-weight: bold;
            color: #000;
            margin: 5px 0;
        }

        .readings-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 13px;
        }

        .readings-table th {
            background: white;
            color: 00aeef;
            border: 1px solid #00aeef;
            padding: 2px;
        }

        .readings-table td {
            border: 1px solid #00aeef;
            padding: 4px;
            text-align: center;
        }

        .charges-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 13px;
        }

        .charges-table td {
            padding: 2px 5px;
            border: none;
        }

        .col-left {
            text-align: left;
            color: #00aeef;
            font-weight: bold;
        }

        .col-right {
            text-align: right;
            color: #000;
            font-weight: bold;
        }

        .total-box {
            border-top: 2px solid #00aeef;
            margin-top: 5px;
            padding-top: 5px;
            font-size: 18px;
            font-weight: bold;
        }

        .btn-download {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
        }

        /* Paid Stamp */
        .paid-stamp {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            border: 5px solid green;
            color: green;
            font-size: 80px;
            font-weight: bold;
            padding: 10px 20px;
            text-transform: uppercase;
            opacity: 0.8;
            border-radius: 10px;
            z-index: 100;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>

<body>

    <button onclick="downloadPDF()" class="btn-download" id="downloadBtn">Download PDF</button>

    <div id="receipt-content" class="receipt-container">

        <?php if ($currentBill['status'] == 'PAID') { ?>
            <div class="paid-stamp">PAID</div>
        <?php } ?>

        <div class="header">
            <div class="header-logo">
                <span style="color:red">TG</span>SPDCL <br>
                <span style="font-size:12px; color:black;">SOUTHERN POWER DISTRIBUTION COMPANY OF T.G. LTD.</span>
            </div>
            <div class="header-title">
                ELECTRICITY BILL<br>CUM NOTICE
            </div>
        </div>

        <div class="blue-box">
            <div class="row">
                <div class="cell">
                    <span class="label">BILL DATE:</span> <?= date('d-m-Y', strtotime($currentBill['generated_at'])) ?>
                </div>
                <div class="cell">
                    <span class="label">TIME:</span> <?= date('H:i', strtotime($currentBill['generated_at'])) ?>
                </div>
            </div>

            <div class="row">
                <div class="cell" style="flex:2">
                    <span class="label">ERO NAME:</span> HYDERABAD CENTRAL
                </div>
                <div class="cell">
                    <span class="label">GRP:</span> M
                </div>
            </div>

            <div class="row" style="background: #e0f7fa;"> <!-- Highlight Service No -->
                <div class="cell">
                    <span class="label">SC NO:</span> <span class="big-sc"><?= $consumerDetails['service_number'] ?>
                        5456</span>
                    <!-- Added dummy suffix to match image style 00101 5456 -->
                </div>
            </div>

            <div class="row">
                <div class="cell">
                    <span class="label">USCNO:</span> <span class="value"
                        style="border:1px solid #000; padding:1px 5px;"><?= $consumerDetails['service_number'] ?></span>
                    <span class="label" style="margin-left:10px;">AREA:</span> 100202
                </div>
            </div>

            <div class="row">
                <div class="cell">
                    <span class="label">NAME:</span> <span
                        class="value"><?= strtoupper($consumerDetails['name']) ?></span><br>
                    <span class="label">ADDR:</span> <span
                        class="value"><?= strtoupper($consumerDetails['address']) ?></span>
                </div>
            </div>

            <div class="row">
                <div class="cell"><span class="label">CAT:</span> <?= strtoupper($consumerDetails['type']) ?></div>
                <div class="cell"><span class="label">SC:</span> 0</div>
                <div class="cell"><span class="label">PH:</span> 1</div> <!-- Phase -->
            </div>

            <!-- Readings Section -->
            <div style="border-bottom: 1px solid #00aeef; padding: 5px;">
                <table class="readings-table">
                    <tr>
                        <th>READING</th>
                        <th>MONTH</th>
                        <th>STATUS</th>
                    </tr>
                    <tr>
                        <td>PRES: <strong><?= $curr_unit ?></strong></td>
                        <td><?= date('m/Y') ?></td>
                        <td>01</td>
                    </tr>
                    <tr>
                        <td>PREV: <strong><?= $prev_unit ?></strong></td>
                        <td><?= date('m/Y', strtotime('-1 month')) ?></td>
                        <td>01</td>
                    </tr>
                    <tr>
                        <td>UNITS: <strong><?= $currentBill['units'] ?></strong></td>
                        <td>AVG: <?= round($currentBill['units']) ?></td>
                        <td></td>
                    </tr>
                </table>
            </div>

            <!-- Charges Section -->
            <div style="padding: 10px;">
                <table class="charges-table">
                    <tr>
                        <td class="col-left">ENERGY CHARGES :</td>
                        <td class="col-right"><?= $currentBill['energy_charge'] ?></td>
                    </tr>
                    <tr>
                        <td class="col-left">CUST. CHARGES :</td>
                        <td class="col-right"><?= $currentBill['customer_charge'] ?></td>
                    </tr>
                    <tr>
                        <td class="col-left">FIXED CHARGES :</td>
                        <td class="col-right"><?= $currentBill['fixed_charge'] ?></td>
                    </tr>
                    <tr>
                        <td class="col-left">ED (7%) :</td>
                        <td class="col-right"><?= $currentBill['ed'] ?></td>
                    </tr>
                    <tr>
                        <td class="col-left">SURCHARGE :</td>
                        <td class="col-right"><?= $currentBill['surcharge'] ?></td>
                    </tr>
                    <?php if ($currentBill['fine'] > 0) { ?>
                        <tr>
                            <td class="col-left" style="color:red">FINE / INT :</td>
                            <td class="col-right" style="color:red"><?= $currentBill['fine'] ?></td>
                        </tr>
                    <?php } ?>

                    <tr class="total-box">
                        <td class="col-left" style="color:#000; font-size:16px;">NET AMOUNT :</td>
                        <td class="col-right" style="font-size:20px;"><?= $currentBill['total'] ?></td>
                    </tr>
                    <tr>
                        <td class="col-left">ARREARS :</td>
                        <td class="col-right"><?= number_format($arrears, 2) ?></td>
                    </tr>
                    <tr style="border-top: 1px solid #000;">
                        <td class="col-left" style="color:#000; font-weight:bold;">TOTAL AMOUNT DUE :</td>
                        <td class="col-right" style="font-size:22px;"><?= number_format($total_due, 2) ?></td>
                    </tr>
                </table>
            </div>

            <!-- Dates Footer -->
            <div class="row" style="border-top:2px solid #00aeef; padding:10px;">
                <div class="cell" style="border:none;">
                    <span class="label">DUE DATE:</span> <span class="value"><?= $currentBill['due_date'] ?></span>
                </div>
                <div class="cell" style="border:none;">
                    <span class="label">DISCONN DATE:</span> <span
                        class="value"><?= date('Y-m-d', strtotime($currentBill['due_date'] . ' +7 days')) ?></span>
                </div>
            </div>

            <div style="text-align:center; padding:5px; font-size:10px;">
                NOTE: PAYMENT AFTER DUE DATE ATTRACTS SURCHARGE & DISCONNECTION
            </div>

        </div>

    </div>

    <script>
        function downloadPDF() {
            var element = document.getElementById('receipt-content');
            var btn = document.getElementById('downloadBtn');
            btn.style.display = 'none'; // Hide button in PDF

            var opt = {
                margin: 0.2,
                filename: 'Bill_<?= $service_number ?>_<?= date('Ymd') ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save().then(function () {
                btn.style.display = 'block'; // Show button again
            });
        }
    </script>
</body>

</html>