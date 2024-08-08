let questionCount = 0;

function addQuestion() {
    questionCount++;

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
                <input type="file" id="optionAudio${questionCount}_1" name="optionAudio${questionCount}_1" accept="audio/*">
            </div>
            <div class="option">
                <input type="radio" name="correctAnswer${questionCount}" value="option${questionCount}_2">
                <input type="text" id="option${questionCount}_2" name="option${questionCount}_2" placeholder="Option 2" required>
                <input type="file" id="optionAudio${questionCount}_2" name="optionAudio${questionCount}_2" accept="audio/*">
            </div>
            <div class="option">
                <input type="radio" name="correctAnswer${questionCount}" value="option${questionCount}_3">
                <input type="text" id="option${questionCount}_3" name="option${questionCount}_3" placeholder="Option 3" required>
                <input type="file" id="optionAudio${questionCount}_3" name="optionAudio${questionCount}_3" accept="audio/*">
            </div>
            <div class="option">
                <input type="radio" name="correctAnswer${questionCount}" value="option${questionCount}_4">
                <input type="text" id="option${questionCount}_4" name="option${questionCount}_4" placeholder="Option 4" required>
                <input type="file" id="optionAudio${questionCount}_4" name="optionAudio${questionCount}_4" accept="audio/*">
            </div>
        </div><br><br>

        <label for="questionImage${questionCount}">Upload Encouragement Image:</label>
        <input type="file" id="questionImage${questionCount}" name="questionImage${questionCount}" accept="image/*" onchange="previewImage(event, 'imagePreview${questionCount}', 'loader${questionCount}')"><br><br>
        <img id="imagePreview${questionCount}" src="" alt="Encouragement Image Preview" style="display: none;"><br><br>
        <div id="loader${questionCount}" class="loader" style="display: none;"></div><br><br>

        <label for="encouragement${questionCount}">Encouragement Text:</label>
        <input type="text" id="encouragement${questionCount}" name="encouragement${questionCount}" placeholder="Enter encouragement text"><br><br>

        <label for="isEncouragement${questionCount}">
            <input type="checkbox" id="isEncouragement${questionCount}" name="isEncouragement${questionCount}">
            Mark as encouragement
        </label><br><br>

        <button type="button" class="delete-question" onclick="deleteQuestion('questionBlock${questionCount}')">X</button>
    `;

    questionsContainer.appendChild(newQuestionBlock);
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

function deleteQuestion(questionBlockId) {
    const questionBlock = document.getElementById(questionBlockId);
    questionBlock.remove();
    renumberQuestions();
}

function renumberQuestions() {
    const questionBlocks = document.querySelectorAll('.question-block');
    questionCount = questionBlocks.length;

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
        block.querySelectorAll('input[type="file"]').forEach((file) => {
            const id = file.getAttribute('id').replace(/\d+/, currentNumber);
            const name = file.getAttribute('name').replace(/\d+/, currentNumber);
            file.setAttribute('id', id);
            file.setAttribute('name', name);
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

document.addEventListener("DOMContentLoaded", function() {
    const backBtn = document.getElementById("back_btn");

    if (backBtn) {
        backBtn.addEventListener("click", function(event) {
            event.preventDefault();

            // Create popup elements
            const popup = document.createElement("div");
            popup.id = "not_savingDraft";
            popup.className = "not_savingDraft";

            const popupContent = document.createElement("div");
            popupContent.className = "not_savingDraft-content";

            const popupText = document.createElement("p");
            popupText.innerText = "Do you want to leave without saving your draft?";

            const yesBtn = document.createElement("button");
            yesBtn.id = "not_savingDraft-yes-btn";
            yesBtn.innerText = "Yes";

            const noBtn = document.createElement("button");
            noBtn.id = "not_savingDraft-no-btn";
            noBtn.innerText = "No";

            // Append elements to popup
            popupContent.appendChild(popupText);
            popupContent.appendChild(yesBtn);
            popupContent.appendChild(noBtn);
            popup.appendChild(popupContent);
            document.body.appendChild(popup);

            // Show popup
            popup.style.display = "flex";

            yesBtn.addEventListener("click", function() {
                window.location.href = "library_page.php"; // Change to the page you want to redirect to
                document.body.removeChild(popup);
            });

            noBtn.addEventListener("click", function() {
                document.body.removeChild(popup);
            });

            // Close the popup if the user clicks outside of it
            window.addEventListener("click", function(event) {
                if (event.target === popup) {
                    document.body.removeChild(popup);
                }
            });
        });
    } else {
        console.error('Back button not found.');
    }
});