<?php 
include 'includes/header.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$errors = [];
$success = false;
$name = '';
$email = '';
$phone = '';
$appointment_date = '';
$appointment_time = '';
$purpose = '';

// Get user info
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($user) {
    $name = $user['name'];
    $email = $user['email'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $appointment_date = trim($_POST['appointment_date']);
    $appointment_time = trim($_POST['appointment_time']);
    $purpose = trim($_POST['purpose']);
    
    // Validation
    if (empty($name)) {
        $errors[] = "Name is required.";
    } elseif (strlen($name) < 2) {
        $errors[] = "Name must be at least 2 characters.";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    
    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    } elseif (!preg_match("/^[0-9]{10,15}$/", $phone)) {
        $errors[] = "Please enter a valid phone number (10-15 digits).";
    }
    
    if (empty($appointment_date)) {
        $errors[] = "Appointment date is required.";
    } else {
        $selected_date = strtotime($appointment_date);
        $today = strtotime('today');
        if ($selected_date < $today) {
            $errors[] = "Appointment date cannot be in the past.";
        }
        // Check if date is more than 3 months in future
        $max_date = strtotime('+3 months');
        if ($selected_date > $max_date) {
            $errors[] = "Appointment date cannot be more than 3 months in the future.";
        }
    }
    
    if (empty($appointment_time)) {
        $errors[] = "Appointment time is required.";
    } else {
        // Check if time is within business hours (9 AM to 6 PM)
        $time_parts = explode(':', $appointment_time);
        $hour = (int)$time_parts[0];
        if ($hour < 9 || $hour >= 18) {
            $errors[] = "Appointment time must be between 9:00 AM and 5:59 PM.";
        }
    }
    
    if (empty($purpose)) {
        $errors[] = "Purpose is required.";
    } elseif (strlen($purpose) < 10) {
        $errors[] = "Purpose must be at least 10 characters long.";
    }
    
    // Check for existing appointment on same date and time
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM appointments WHERE appointment_date = ? AND appointment_time = ? AND status IN ('pending', 'approved')");
        $stmt->bind_param("ss", $appointment_date, $appointment_time);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = "This time slot is already booked. Please choose another time.";
        }
        $stmt->close();
    }
    
    // Insert appointment if no errors
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO appointments (user_id, name, email, phone, appointment_date, appointment_time, purpose) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $user_id, $name, $email, $phone, $appointment_date, $appointment_time, $purpose);
        
        if ($stmt->execute()) {
            $success = true;
            $name = '';
            $email = '';
            $phone = '';
            $appointment_date = '';
            $appointment_time = '';
            $purpose = '';
        } else {
            $errors[] = "Failed to book appointment. Please try again.";
        }
        $stmt->close();
    }
}
?>

<div class="appointment-page container">
    <div class="page-header">
        <h1>Book Career Counselling Appointment</h1>
        <p>Schedule a one-on-one session with our career counsellors to get personalized guidance.</p>
    </div>

    <?php if ($success): ?>
        <div class="success-message">
            <p>Your appointment has been booked successfully! We'll review your request and confirm shortly.</p>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="appointment-form-container">
        <form method="POST" action="book-appointment.php" class="appointment-form" id="appointmentForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required minlength="2" placeholder="Enter your full name">
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required placeholder="your.email@example.com">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($phone); ?>" required pattern="[0-9]{10,15}" placeholder="10-15 digits">
                </div>

                <div class="form-group">
                    <label for="appointment_date">Preferred Date *</label>
                    <input type="date" name="appointment_date" id="appointment_date" value="<?php echo htmlspecialchars($appointment_date); ?>" required min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+3 months')); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="appointment_time">Preferred Time *</label>
                <input type="time" name="appointment_time" id="appointment_time" value="<?php echo htmlspecialchars($appointment_time); ?>" required min="09:00" max="17:59">
                <small>Available hours: 9:00 AM - 5:59 PM</small>
            </div>

            <div class="form-group">
                <label for="purpose">Purpose of Appointment *</label>
                <textarea name="purpose" id="purpose" rows="5" required minlength="10" placeholder="Tell us what you'd like to discuss during the counselling session..."><?php echo htmlspecialchars($purpose); ?></textarea>
                <small>Minimum 10 characters required</small>
            </div>

            <button type="submit" class="btn-submit">Book Appointment</button>
        </form>
    </div>
</div>

<style>
.appointment-form-container {
    max-width: 800px;
    margin: 0 auto;
    background: linear-gradient(135deg, var(--card-bg) 0%, #1a1f2e 100%);
    border: 1px solid var(--card-border);
    border-radius: 12px;
    padding: 2.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    animation: fadeIn 0.6s ease-out;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

/* Styles are now in main CSS file */

.success-message {
    background: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #86efac;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    text-align: center;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Set minimum date to today
document.getElementById('appointment_date').setAttribute('min', new Date().toISOString().split('T')[0]);
</script>

<?php 
include 'includes/footer.php'; 
?>

