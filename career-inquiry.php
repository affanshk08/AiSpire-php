<?php 
include 'includes/header.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$errors = [];
$success = false;
$career_id = isset($_GET['career_id']) ? (int)$_GET['career_id'] : 0;
$career = null;

// Fetch career details if career_id is provided
if ($career_id > 0) {
    $stmt = $conn->prepare("SELECT id, title FROM careers WHERE id = ?");
    $stmt->bind_param("i", $career_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $career = $result->fetch_assoc();
    }
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $career_id = isset($_POST['career_id']) ? (int)$_POST['career_id'] : 0;
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    // Validation
    if ($career_id <= 0) {
        $errors[] = "Please select a valid career.";
    }
    if (empty($subject)) {
        $errors[] = "Subject is required.";
    } elseif (strlen($subject) > 255) {
        $errors[] = "Subject must be less than 255 characters.";
    }
    if (empty($message)) {
        $errors[] = "Message is required.";
    } elseif (strlen($message) < 10) {
        $errors[] = "Message must be at least 10 characters long.";
    }
    
    // Verify career exists
    if ($career_id > 0 && empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM careers WHERE id = ?");
        $stmt->bind_param("i", $career_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            $errors[] = "Selected career does not exist.";
        }
        $stmt->close();
    }
    
    // Insert inquiry if no errors
    if (empty($errors)) {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO career_inquiries (user_id, career_id, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $user_id, $career_id, $subject, $message);
        
        if ($stmt->execute()) {
            $success = true;
            $career_id = 0;
            $subject = '';
            $message = '';
        } else {
            $errors[] = "Failed to submit inquiry. Please try again.";
        }
        $stmt->close();
    }
}

// Fetch all careers for dropdown
$careers_result = $conn->query("SELECT id, title FROM careers ORDER BY title ASC");
?>

<div class="inquiry-page container">
    <div class="page-header">
        <h1>Career Inquiry</h1>
        <p>Have questions about a specific career? Send us your inquiry and we'll get back to you.</p>
    </div>

    <?php if ($success): ?>
        <div class="success-message">
            <p>Your inquiry has been submitted successfully! We'll get back to you soon.</p>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="inquiry-form-container">
        <form method="POST" action="career-inquiry.php" class="inquiry-form">
            <div class="form-group">
                <label for="career_id">Select Career *</label>
                <select name="career_id" id="career_id" required>
                    <option value="">-- Select a Career --</option>
                    <?php while($career_option = $careers_result->fetch_assoc()): ?>
                        <option value="<?php echo $career_option['id']; ?>" <?php echo ($career_id == $career_option['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($career_option['title']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <?php if ($career): ?>
                <div class="selected-career-info">
                    <p><strong>Selected Career:</strong> <?php echo htmlspecialchars($career['title']); ?></p>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="subject">Subject *</label>
                <input type="text" name="subject" id="subject" value="<?php echo isset($subject) ? htmlspecialchars($subject) : ''; ?>" required maxlength="255" placeholder="Enter inquiry subject">
            </div>

            <div class="form-group">
                <label for="message">Your Message *</label>
                <textarea name="message" id="message" rows="6" required placeholder="Tell us what you'd like to know about this career..."><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                <small>Minimum 10 characters required</small>
            </div>

            <button type="submit" class="btn-submit">Submit Inquiry</button>
        </form>
    </div>
</div>

<style>
.inquiry-form-container {
    max-width: 700px;
    margin: 0 auto;
    background: linear-gradient(135deg, var(--card-bg) 0%, #1a1f2e 100%);
    border: 1px solid var(--card-border);
    border-radius: 12px;
    padding: 2.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    animation: fadeIn 0.6s ease-out;
}

/* Styles are now in main CSS file */

.selected-career-info {
    background: rgba(37, 99, 235, 0.1);
    border: 1px solid rgba(37, 99, 235, 0.3);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.selected-career-info p {
    margin: 0;
    color: var(--blue-light);
}

.success-message {
    background: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #86efac;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    text-align: center;
}

.inquiry-form small {
    display: block;
    margin-top: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.875rem;
}
</style>

<?php 
include 'includes/footer.php'; 
?>

