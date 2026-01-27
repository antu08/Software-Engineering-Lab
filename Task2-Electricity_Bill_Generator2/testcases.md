# Electricity Billing System - Essential Test Cases

## Test Environment
- **URL:** http://localhost/Electricity_Bill_Generator2/
- **Database:** tgspdcl (MySQL)

**Default Credentials:**
- **Admin Username:** `admin`
- **Admin Password:** `1234`
- **Employee:** *Does not exist by default. Must be created by Admin (See Test 3).*

---

## TEST CASES

### Test 1: Admin Login
**Steps:**
1. Navigate to `index.php`
2. Enter Username: `admin`
3. Enter Password: `1234`
4. Click Login

**Expected:** Redirects to Admin Dashboard showing control panel.

---

### Test 2: Register New Consumer
**Pre-requisite:** Logged in as ADMIN

**Steps:**
1. Click "New Consumer"
2. Fill registration form:
   - Service No: `000012` (6 Digits)
   - Name: `ANTU DAS`
   - Role: `household`
   - Mobile: `1234567890`
   - Address: `Hyderabad, TG`
   - Pincode: `500001`
3. Click "Register Consumer"

**Expected:** Success message "Consumer Registered Successfully!", database updates with new consumer record.

---

### Test 3: Create First Employee (Crucial Step)
**Pre-requisite:** Logged in as ADMIN

**Steps:**
1. Click "New Employee"
2. Fill form:
   - Name: `First Employee`
   - Mobile: `9876543210`
   - Address: `Vizag`
3. Click "Create Employee"

**Expected:**
- Success message showing generated credentials.
- **System auto-generates Username: `employee1`**
- **Default Password: `1234`**
- *Note: We will use these credentials for subsequent tests.*

---

### Test 4: Access Control (Employee)
**Pre-requisite:** Logged in as EMPLOYEE (`employee1` / `1234`)

**Steps:**
1. Logout Admin.
2. Login with `employee1` / `1234`.
3. Try accessing Admin Dashboard URL directly.

**Expected:** Access Denied or Redirected. Employee Dashboard should *only* show "Generate New Bill", "Current Month Bills", and "Search History".

---

### Test 5: Generate First Bill
**Pre-requisite:** Employee logged in, Consumer `000012` exists

**Steps:**
1. Click "Generate New Bill"
2. Enter Service No: `000012` -> Click Next
   - *Verify:* Previous Reading should be 0.
3. Enter Current Reading: `150`
4. Click "Generate Bill"

**Expected:**
- Redirects to Success/Current Bills page.
- Bill Generated for 150 Units.
- Due Date set to 14 days from today.

---

### Test 6: Bill Calculation - Tier Pricing (Household)
**Pre-requisite:** Consumer `000012` (Household)

**Test Data & Expected Bills:**
- **0 units**: Minimum Charge = **Rs. 25.00** (+ Fixed/Cust Charges)
- **50 units**: (50 * 1.5) = Rs. 75.00 (+ Charges)
- **100 units**: (50 * 1.5) + (50 * 2.5) = 75 + 125 = **Rs. 200.00** (+ Charges)
- **150 units**: (50 * 1.5) + (50 * 2.5) + (50 * 3.5) = 200 + 175 = **Rs. 375.00** (+ Charges)

**Verify:** Generated bill amounts match the manual calculation logic.

---

### Test 7: View Bill History (Employee)
**Pre-requisite:** Logged in as Employee (`employee1`)

**Steps:**
1. Click "Search History"
2. Enter Service No: `000012`
3. Click Search

**Expected:** table displays the bill generated in Test 5 with Status `UNPAID` and correct Date/Amount.

---

### Test 8: Consumer View Bill
**Pre-requisite:** Bill generated for `000012`

**Steps:**
1. Navigate to Consumer Portal (`consumer/view_bill.php`)
2. Enter Service No: `000012`
3. Click "View Bill"

**Expected:**
- Shows "Current Month Bill" details for **ANTU DAS**.
- Status: **UNPAID** (Red).
- Buttons: "Pay Now" and "Download Receipt".

---

### Test 9: Input Validation (Strict)
**Test these invalid inputs:**

1. **Invalid Mobile:** Enter `12345` -> Alert "Phone number must be exactly 10 digits"
2. **Invalid Name:** Enter `Antu123` -> Alert "Name must contain only alphabets"
3. **Invalid Service No:** Enter `123` -> Alert "Service Number must be exactly 6 digits"
4. **Negative Reading:** Enter Current Reading less than Previous -> Error "Current reading cannot be less..."

**Expected:** All validations trigger correct error messages.

---

### Test 10: Duplicate Service Number
**Pre-requisite:** Service No `000012` exists

**Steps:**
1. Admin -> Register Consumer
2. Try registering `000012` again

**Expected:** Error message "Service Number already exists!".

---

### Test 11: Pay Bill On Time
**Pre-requisite:** Bill exists, Status UNPAID

**Steps:**
1. Consumer Portal -> View Bill `000012`
2. Click "Pay Now"
3. Enter Dummy Card Details -> Click Pay

**Expected:**
- Success Message "Payment Successful"
- Redirects to View Bill
- Status changes to **PAID** (Green)
- "Pay Now" button disappears.

---

### Test 12: Late Bill (Fine Logic)
**Pre-requisite:** (Manual DB Edit) Set a bill's `due_date` to past, `status` UNPAID.

**Steps:**
1. Generate **Next Month's Bill** for same consumer (`000012`)
2. Calculator detects "Previous Due"

**Expected:**
- New Bill generated includes **Fine: Rs. 150.00**
- Receipt shows "FINE / INT" row in red.

---

### Test 13: Receipt Generation
**Pre-requisite:** Bill `000012` is PAID

**Steps:**
1. Click "Download Receipt"
2. PDF Opens/Downloads

**Expected:**
- **PAID** Stamp overlay is visible.
- Layout matches TGSPDCL format (Blue borders, Logo, Breakdowns).
- Correct Service No (`000012`) and Name (`ANTU DAS`) displayed.
- "Arrears" calculated correctly (0.00 if all paid).

---

### Test 14: Session Security
**Steps:**
1. Logout Admin
2. Try accessing `admin/dashboard.php` directly
3. Try accessing `employee/generate_bill.php` without login

**Expected:** All direct access attempts redirect to Login Page (`index.php`).

---

### Test 15: Complete Lifecycle Workflow
**Full workflow test:**

1. **Admin** creates Employee. System assigns `employee1`.
2. **Admin** registers Consumer `000012` (Name: **ANTU DAS**).
3. **Employee1** logs in -> Generates Bill for `000012` (Units: 200).
4. **ANTU DAS** opens Portal -> Enters `000012` -> Sees Bill.
5. **ANTU DAS** Pays Bill online.
6. **ANTU DAS** downloads Receipt -> Verifies "PAID" stamp.

**Expected:** Complete flow works without errors, DB updates correctly at each step.

---

## Quick Pre-Test Setup

**Step 1:** Login as **Admin** (`admin` / `1234`).
**Step 2:** Create New Employee -> System will display `employee1` / `1234`.
**Step 3:** Logout Admin and Login as **Employee** (`employee1` / `1234`).
**Step 4:** Run tests 5-15.

---

## Success Criteria
✅ All 15 tests pass  
✅ Strict Validation blocks bad data  
✅ Bills calculated accurately per Tier  
✅ Receipt PDF generates with correct design  
✅ Role-based access is secure
