let tfQuestionCount = 0;

function addQuestion() {
    tfQuestionCount++;

    const questionsContainer = document.getElementById('questionsContainer');

    const newQuestionBlock = document.createElement('div');
    newQuestionBlock.className = 'question-block';
    newQuestionBlock.id = `questionBlock${tfQuestionCount}`;

    newQuestionBlock.innerHTML = `
        <h3>Question ${tfQuestionCount}</h3>
        <label for="question${tfQuestionCount}">Question:</label>
        <input type="text" id="question${tfQuestionCount}" name="question${tfQuestionCount}" required><br><br>

        <label for="questionAudio${tfQuestionCount}">Upload Question Audio:</label>
        <input type="file" id="questionAudio${tfQuestionCount}" name="questionAudio${tfQuestionCount}" accept="audio/*"><br><br>

        <div class="options-container">
            <div class="option">
                <input type="radio" id="true${tfQuestionCount}" name="correctAnswer${tfQuestionCount}" value="true" required>
                <label for="true${tfQuestionCount}">True</label>
            </div>
            <div class="option">
                <input type="radio" id="false${tfQuestionCount}" name="correctAnswer${tfQuestionCount}" value="false" required>
                <label for="false${tfQuestionCount}">False</label>
            </div>
        </div><br><br>

        <label for="questionImage${tfQuestionCount}">Upload Encouragement Image:</label>
        <input type="file" id="questionImage${tfQuestionCount}" name="questionImage${tfQuestionCount}" accept="image/*" onchange="previewImage(event, 'imagePreview${tfQuestionCount}', 'loader${tfQuestionCount}')"><br><br>
        <img id="imagePreview${tfQuestionCount}" src="" alt="Encouragement Image Preview" style="display: none;"><br><br>
        <div id="loader${tfQuestionCount}" class="loader" style="display: none;"></div><br><br>

        <label for="encouragement${tfQuestionCount}">Encouragement Text:</label>
        <input type="text" id="encouragement${tfQuestionCount}" name="encouragement${tfQuestionCount}" placeholder="Enter encouragement text"><br><br>

        <label for="isEncouragement${tfQuestionCount}">
            <input type="checkbox" id="isEncouragement${tfQuestionCount}" name="isEncouragement${tfQuestionCount}">
            Mark as encouragement
        </label><br><br>

        <button type="button" class="delete-question" onclick="deleteQuestion('questionBlock${tfQuestionCount}')"><i class="fas fa-trash-alt"></i></button>
    `;

    questionsContainer.appendChild(newQuestionBlock);
}

function deleteQuestion(questionBlockId) {
    const questionBlock = document.getElementById(questionBlockId);
    questionBlock.remove();
    renumberTFQuestions();
}

function renumberTFQuestions() {
    const questionBlocks = document.querySelectorAll('.question-block');
    tfQuestionCount = questionBlocks.length;

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

    let valid = true;

    for (let [key, value] of formData.entries()) {
        if (key.includes('question') && !key.includes('Text') && !key.includes('Image') && !key.includes('Audio') && !value) {
            valid = false;
            break;
        }
    }

    if (!valid) {
        alert("All fields must be filled or marked as encouragement.");
        return;
    }

    const radios = document.querySelectorAll('input[type="radio"]');
    let radioCheck = Array.from(radios).some(radio => radio.checked);

    if (!radioCheck) {
        alert("Please select at least one correct answer for each question.");
        return;
    }

    quizForm.submit();
}

function previewImage(event, previewId, loaderId) {
    const file = event.target.files[0];
    const preview = document.getElementById(previewId);
    const loader = document.getElementById(loaderId);
    const reader = new FileReader();

    // Show loader
    loader.style.display = 'block';

    reader.onload = function(e) {
        preview.src = e.target.result;
        preview.style.display = 'block';

        // Hide loader
        loader.style.display = 'none';
    };

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = '';
        preview.style.display = 'none';
        loader.style.display = 'none';
    }
}
