let questionCount = 0;

function addQuestion() {
    questionCount++; // Increment before using it for new question

    const questionsContainer = document.getElementById('questionsContainer');

    const newQuestionBlock = document.createElement('div');
    newQuestionBlock.className = 'question-block';
    newQuestionBlock.id = `questionBlock${questionCount}`;

    newQuestionBlock.innerHTML = `
        <h3>Question ${questionCount}</h3>
        <label for="question${questionCount}">Question:</label>
        <input type="text" id="question${questionCount}" name="question${questionCount}" required><br><br>

        <label for="questionAudio${questionCount}">Upload Question Audio:</label>
        <input type="file" id="questionAudio${questionCount}" name="questionAudio${questionCount}" accept="audio/*"><br><br>

        <div class="options-container">
            <div class="option">
                <input type="radio" name="correctAnswer${questionCount}" value="option${questionCount}_1" required>
                <input type="text" id="option${questionCount}_1" name="option${questionCount}_1" placeholder="Option 1" required>
            </div>
            <div class="option">
                <input type="radio" name="correctAnswer${questionCount}" value="option${questionCount}_2">
                <input type="text" id="option${questionCount}_2" name="option${questionCount}_2" placeholder="Option 2" required>
            </div>
            <div class="option">
                <input type="radio" name="correctAnswer${questionCount}" value="option${questionCount}_3">
                <input type="text" id="option${questionCount}_3" name="option${questionCount}_3" placeholder="Option 3" required>
            </div>
            <div class="option">
                <input type="radio" name="correctAnswer${questionCount}" value="option${questionCount}_4">
                <input type="text" id="option${questionCount}_4" name="option${questionCount}_4" placeholder="Option 4" required>
            </div>
        </div><br><br>

        <button type="button" class="delete-question" onclick="deleteQuestion('questionBlock${questionCount}')">X</button>
    `;

    questionsContainer.appendChild(newQuestionBlock);
}

function deleteQuestion(questionBlockId) {
    const questionBlock = document.getElementById(questionBlockId);
    questionBlock.remove();
    // Renumber questions after deletion
    renumberQuestions();
}

function renumberQuestions() {
    const questionBlocks = document.querySelectorAll('.question-block');
    questionCount = questionBlocks.length; // Update questionCount to match the number of questions

    questionBlocks.forEach((block, index) => {
        const currentNumber = index + 1;
        block.querySelector('h3').textContent = `Question ${currentNumber}`;
        block.id = `questionBlock${currentNumber}`;
        block.querySelectorAll('input[type="radio"]').forEach((radio) => {
            const name = radio.getAttribute('name').replace(/\d+/, currentNumber);
            radio.setAttribute('name', name);
        });
        block.querySelectorAll('input[type="text"]').forEach((text) => {
            const id = text.getAttribute('id').replace(/\d+/, currentNumber);
            const name = text.getAttribute('name').replace(/\d+/, currentNumber);
            text.setAttribute('id', id);
            text.setAttribute('name', name);
        });
        block.querySelector('.delete-question').setAttribute('onclick', `deleteQuestion('questionBlock${currentNumber}')`);
    });
}

function saveQuiz(event) {
    event.preventDefault();
    // Submit the form
    const quizForm = document.getElementById('quizForm');
    quizForm.submit();
}