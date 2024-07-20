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
                <input type="radio" id="true${questionCount}" name="correctAnswer${questionCount}" value="true" required>
                <label for="true${questionCount}">True</label>
            </div>
            <div class="option">
                <input type="radio" id="false${questionCount}" name="correctAnswer${questionCount}" value="false" required>
                <label for="false${questionCount}">False</label>
            </div>
        </div><br><br>

        <button type="button" class="delete-question" onclick="deleteQuestion('questionBlock${questionCount}')"> <i class="fas fa-trash-alt"></i></button>
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
        
        const questionInput = block.querySelector('input[type="text"]');
        questionInput.id = `question${currentNumber}`;
        questionInput.name = `question${currentNumber}`;
        block.querySelector('label[for^="question"]').setAttribute('for', `question${currentNumber}`);

        const questionAudioInput = block.querySelector('input[type="file"]');
        questionAudioInput.id = `questionAudio${currentNumber}`;
        questionAudioInput.name = `questionAudio${currentNumber}`;
        block.querySelector('label[for^="questionAudio"]').setAttribute('for', `questionAudio${currentNumber}`);
        
        block.querySelectorAll('.option').forEach(option => {
            const input = option.querySelector('input[type="radio"]');
            const label = option.querySelector('label');
            input.name = `correctAnswer${currentNumber}`;
            if (input.id.includes('true')) {
                input.id = `true${currentNumber}`;
                label.setAttribute('for', `true${currentNumber}`);
            } else {
                input.id = `false${currentNumber}`;
                label.setAttribute('for', `false${currentNumber}`);
            }
        });

        block.querySelector('.delete-question').setAttribute('onclick', `deleteQuestion('questionBlock${currentNumber}')`);
    });
}

function saveQuiz(event) {
    event.preventDefault();
    const quizForm = document.getElementById('quizForm');
    const formData = new FormData(quizForm);
    
    for (let [key, value] of formData.entries()) {
        if (!value) {
            alert("All fields must be filled.");
            return;
        }
    }

    const radios = document.querySelectorAll('input[type="radio"]');
    let radioCheck = Array.from(radios).some(radio => radio.checked);

    if (!radioCheck) {
        alert("Please select at least one correct answer for each question.");
        return;
    }
    
    quizForm.submit();
}