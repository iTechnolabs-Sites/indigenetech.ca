document.addEventListener('DOMContentLoaded', function () {
    const questions = document.querySelectorAll('.faq-question');

    questions.forEach(function (question) {
        question.addEventListener('click', function () {
            // Toggle the current question's answer
            const answer = this.nextElementSibling;
            const isOpen = this.classList.contains('open');

            // Close all other answers
            document.querySelectorAll('.faq-answer').forEach(function (answer) {
                answer.style.display = 'none';
                answer.previousElementSibling.classList.remove('open');
            });

            // Open/Close the current answer
            if (!isOpen) {
                answer.style.display = 'block';
                this.classList.add('open');
            }
        });
    });
});
