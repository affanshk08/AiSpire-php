<?php 
include 'includes/header.php'; 

$message_sent = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // In a real application, you would add code here to send an email.
    // For this project, we'll just set a flag that the message was "sent".
    $message_sent = true;
}
?>

<div class="contact-page container">
    <div class="page-header">
        <h1>Get in Touch</h1>
        <p>
            Have questions or need personalized advice? Reach out to our team of experts.
        </p>
    </div>

    <?php if ($message_sent): ?>
        <div class="success-message">
            <p>Thank you for your message! We will get back to you shortly.</p>
        </div>
    <?php else: ?>
        <div class="contact-content">
            <form method="POST" action="contact.php" class="contact-form">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message" rows="6" required></textarea>
                </div>
                <button type="submit" class="btn-submit">Send Message</button>
            </form>
            <div class="contact-info">
                <h3>Contact Information</h3>
                <p>For direct inquiries, you can also reach us through the following channels.</p>
                <ul>
                    <li><strong>Email:</strong> contact@careercounsel.com</li>
                    <li><strong>Phone:</strong> +91 123-456-7890</li>
                    <li><strong>Address:</strong> 123 Career Path, Surat, Gujarat, India</li>
                </ul>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
/* You can copy the styles from your MERN project's Contact.css */
.contact-content { display: grid; grid-template-columns: 2fr 1fr; gap: 3rem; align-items: flex-start; }
.contact-form .form-group { margin-bottom: 1.5rem; text-align: left; }
.contact-form label { display: block; font-weight: 500; margin-bottom: 0.5rem; }
.contact-form input, .contact-form textarea { width: 100%; padding: 1rem; border: 1px solid #e5e5e5; border-radius: 8px; font-size: 1rem; font-family: 'Satoshi', sans-serif; resize: vertical; }
.contact-form .btn-submit { width: 100%; padding: 1rem; border-radius: 8px; font-weight: 500; font-size: 1.1rem; cursor: pointer; border: none; background-color: var(--black); color: var(--white); transition: all 0.2s ease-in-out; }
.contact-form .btn-submit:hover { opacity: 0.8; }
.contact-info { background-color: var(--off-white); padding: 2rem; border-radius: 12px; }
.contact-info h3 { font-size: 1.5rem; margin-bottom: 1rem; }
.contact-info p { color: var(--grey); margin-bottom: 1.5rem; }
.contact-info ul { list-style: none; }
.contact-info ul li { margin-bottom: 1rem; color: var(--grey); }
.contact-info ul li strong { color: var(--black); }
.success-message { text-align: center; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 2rem; border-radius: 12px; }
</style>

<?php 
include 'includes/footer.php'; 
?>