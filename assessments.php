<?php 
include 'includes/header.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// --- Quiz Logic ---
$questions = [
    1 => ['question' => 'Which activity sounds most appealing?', 'options' => ['Analyzing data and solving complex problems.', 'Creating art, music, or writing.', 'Helping and teaching others.']],
    2 => ['question' => 'In a team project, you prefer to:', 'options' => ['Focus on the details and ensure accuracy.', 'Brainstorm creative ideas.', 'Lead and organize the team.']],
    3 => ['question' => 'What kind of work environment do you prefer?', 'options' => ['A structured, predictable environment.', 'A creative and flexible studio or office.', 'A dynamic, fast-paced setting.']]
];

$current_question_num = isset($_GET['q']) ? (int)$_GET['q'] : 1;
$show_result = isset($_GET['result']);
$suggestion = '';

if ($show_result) {
    // Very simple logic to determine a result based on the 'a' (answer) parameter
    $last_answer = isset($_GET['a']) ? $_GET['a'] : '';
    if ($last_answer == '0') {
        $suggestion = "Data Scientist";
    } else if ($last_answer == '1') {
        $suggestion = "UX/UI Designer";
    } else {
        $suggestion = "Software Developer";
    }
}
?>

<div class="assessments-page container">
    <div class="page-header">
        <h1>Career Pathfinder</h1>
        <p>Answer a few simple questions to get a personalized career suggestion.</p>
    </div>
      
    <div class="quiz-container">
        <?php if ($show_result): ?>
            <div class="result-section">
                <h2>Based on your answers, you might enjoy being a...</h2>
                <div class="suggested-career"><?php echo $suggestion; ?></div>
                <p class="result-description">
                    This is a preliminary suggestion. Explore our careers page to learn more about this and other exciting professions!
                </p>
                <a href="assessments.php" class="btn-restart">Take the Quiz Again</a>
            </div>
        <?php else: 
            $question = $questions[$current_question_num];
            $next_q = $current_question_num + 1;
        ?>
            <div class="question-section">
                <div class="question-count">
                    <span>Question <?php echo $current_question_num; ?></span>/<?php echo count($questions); ?>
                </div>
                <div class="question-text"><?php echo $question['question']; ?></div>
                <div class="answer-options">
                    <?php foreach ($question['options'] as $index => $option): ?>
                        <?php if ($next_q > count($questions)): ?>
                            <a href="assessments.php?result=true&a=<?php echo $index; ?>" class="option-button">
                                <?php echo $option; ?>
                            </a>
                        <?php else: ?>
                            <a href="assessments.php?q=<?php echo $next_q; ?>" class="option-button">
                                <?php echo $option; ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Add these styles to your main style.css file */
.quiz-container { max-width: 800px; margin: 2rem auto; background-color: var(--off-white); border-radius: 12px; padding: 3rem; min-height: 400px; display: flex; flex-direction: column; justify-content: center; }
.question-count { margin-bottom: 1rem; font-size: 1.2rem; font-weight: 500; color: var(--grey); }
.question-text { font-size: 2rem; font-weight: 700; margin-bottom: 2.5rem; }
.answer-options { display: grid; grid-template-columns: 1fr; gap: 1rem; }
.option-button { display: block; width: 100%; padding: 1.5rem; border-radius: 8px; font-weight: 500; font-size: 1.1rem; cursor: pointer; border: 1px solid #e5e5e5; background-color: var(--white); color: var(--black); text-align: left; transition: all 0.2s ease-in-out; }
.option-button:hover { background-color: var(--black); color: var(--white); border-color: var(--black); }
.result-section { text-align: center; }
.result-section h2 { color: var(--grey); font-weight: 500; margin-bottom: 0.5rem; }
.suggested-career { font-size: 3.5rem; font-weight: 700; margin-bottom: 1rem; }
.result-description { font-size: 1.1rem; color: var(--grey); max-width: 500px; margin: 0 auto 2rem; }
.btn-restart { font-size: 1.1rem; font-weight: 500; background-color: transparent; color: var(--black); padding: 1rem 2rem; border-radius: 8px; border: 1px solid var(--black); cursor: pointer; transition: all 0.2s ease-in-out; text-decoration: none; display: inline-block; }
.btn-restart:hover { background-color: var(--black); color: var(--white); }
</style>