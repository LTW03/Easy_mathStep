let questionCount = 0;
let optionCounts = {}; // Object to store option counts for each question

function addQuestion() {
    questionCount++;
    optionCounts[questionCount] = 1; // Initialize option count for this question

    const questionsContainer = document.getElementById('questionsContainer');
    const newQuestionBlock = document.createElement('div');
    newQuestionBlock.className = 'question-block';
    newQuestionBlock.id = `questionBlock${questionCount}`;

    newQuestionBlock.innerHTML = `
        <h3>Question ${questionCount}</h3>
        <label for="question${questionCount}">Question Text:</label>
        <input type="text" id="question${questionCount}" name="question${questionCount}" required>
        <label for="questionAudio${questionCount}">Upload Question Audio:</label>
        <input type="file" id="questionAudio${questionCount}" name="questionAudio${questionCount}" accept="audio/*">
        
        <div id="optionsContainer${questionCount}" class="options-container">
            <!-- dynamically option input -->
        </div>
        <button type="button" class = 'addOption_btn' onclick="addOption(${questionCount});">Add Option</button>
        </br>
        <label for="questionImage${questionCount}">Upload Encouragement Image:</label>
        <input type="file" id="questionImage${questionCount}" name="questionImage${questionCount}" accept="image/*" onchange="previewImage(event, 'imagePreview${questionCount}', 'loader${questionCount}')">
        <img id="imagePreview${questionCount}" src="" alt="Encouragement Image Preview" style="display: none;"><br><br>
        <div id="loader${questionCount}" class="loader" style="display: none;"></div>

        <label for="encouragement${questionCount}">Encouragement Text:</label>
        <input type="text" id="encouragement${questionCount}" name="encouragement${questionCount}" placeholder="Enter encouragement text">

        <label for="isEncouragement${questionCount}">
            <input type="checkbox" id="isEncouragement${questionCount}" name="isEncouragement${questionCount}">
            Mark as encouragement
        </label>

        <button type="button" class="delete-question" onclick="deleteQuestion(${questionCount})"><i class="fas fa-trash-alt"></i></button>
    `;

    questionsContainer.appendChild(newQuestionBlock);

    // Adding the first option for the new question block
    addOption(questionCount);
}

function addOption(questionId) {
    const optionsContainer = document.getElementById(`optionsContainer${questionId}`);
    const optionNumber = optionsContainer.children.length + 1; // Get the current number of options

    const newOptionDiv = document.createElement('div');
    newOptionDiv.className = 'option';
    
    newOptionDiv.innerHTML = `
        <label>Option ${optionNumber}:</label>
        <input type="text" name="options${questionId}[]" required>
        <input type="checkbox" name="is_correct${questionId}[]" value="${optionCounts[questionId]}"> Correct Answer
        <input type="number" class='positionBlank_input' name="blank_position${questionId}[]" min="1" placeholder="Blank Position">
    `;
    
    optionsContainer.appendChild(newOptionDiv);
    optionCounts[questionId]++; // Increment the option count for this specific question
}

function deleteQuestion(questionNumber) {
    const questionBlock = document.getElementById(`questionBlock${questionNumber}`);
    if (questionBlock) {
        questionBlock.remove();
        renumberQuestions();
    }
}

function previewImage(event, previewId, loaderId) {
    const file = event.target.files[0];
    const preview = document.getElementById(previewId);
    const loader = document.getElementById(loaderId);
    const reader = new FileReader();

    // Show loader
    loader.style.display = 'block';
    preview.style.display = 'none'; // Hide preview image initially

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


function renumberQuestions() {
    // Get all question blocks and sort them based on their current order
    const questionBlocks = Array.from(document.querySelectorAll('.question-block')).sort((a, b) => {
        const numA = parseInt(a.id.replace('questionBlock', ''), 10);
        const numB = parseInt(b.id.replace('questionBlock', ''), 10);
        return numA - numB;
    });

    // Update each question block's number and associated elements
    questionBlocks.forEach((block, index) => {
        const newNumber = index + 1; // New sequential number (1, 2, 3, ...)

        // Update question block id
        block.id = `questionBlock${newNumber}`;

        // Update question header
        const questionHeader = block.querySelector('h3');
        if (questionHeader) questionHeader.textContent = `Question ${newNumber}`;

        // Update the main question input fields
        const questionInput = block.querySelector('input[name^="question"]');
        if (questionInput) {
            questionInput.name = `question${newNumber}`;
            questionInput.id = `question${newNumber}`;
        }

        const questionAudio = block.querySelector('input[type="file"][name^="questionAudio"]');
        if (questionAudio) {
            questionAudio.name = `questionAudio${newNumber}`;
            questionAudio.id = `questionAudio${newNumber}`;
        }

        // Update options within the question block
        const options = block.querySelectorAll('.option');
        options.forEach((option, optionIndex) => {
            const optionNumber = optionIndex + 1;

            // Update option text and attributes
            const optionLabel = option.querySelector('label');
            if (optionLabel) optionLabel.textContent = `Option ${optionNumber}:`;

            const optionTextInput = option.querySelector('input[type="text"][name^="options"]');
            if (optionTextInput) {
                optionTextInput.name = `options${newNumber}[]`;
                optionTextInput.id = `options${newNumber}_${optionNumber}`;
            }

            const isCorrectCheckbox = option.querySelector('input[type="checkbox"][name^="is_correct"]');
            if (isCorrectCheckbox) {
                isCorrectCheckbox.name = `is_correct${newNumber}[]`;
                isCorrectCheckbox.value = `${optionNumber}`;
            }

            const blankPositionInput = option.querySelector('input[type="number"][name^="blank_position"]');
            if (blankPositionInput) {
                blankPositionInput.name = `blank_position${newNumber}[]`;
            }
        });

        // Update image and encouragement fields
        const img = block.querySelector('img[id^="imagePreview"]');
        if (img) img.id = `imagePreview${newNumber}`;

        const loader = block.querySelector('.loader[id^="loader"]');
        if (loader) loader.id = `loader${newNumber}`;

        const questionImage = block.querySelector('input[type="file"][name^="questionImage"]');
        if (questionImage) {
            questionImage.name = `questionImage${newNumber}`;
            questionImage.id = `questionImage${newNumber}`;
        }

        const encouragementText = block.querySelector('input[type="text"][name^="encouragement"]');
        if (encouragementText) {
            encouragementText.name = `encouragement${newNumber}`;
            encouragementText.id = `encouragement${newNumber}`;
        }

        const isEncouragementCheckbox = block.querySelector('input[type="checkbox"][name^="isEncouragement"]');
        if (isEncouragementCheckbox) {
            isEncouragementCheckbox.name = `isEncouragement${newNumber}`;
            isEncouragementCheckbox.id = `isEncouragement${newNumber}`;
        }

        // Update delete button
        const deleteButton = block.querySelector('.delete-question');
        if (deleteButton) deleteButton.setAttribute('onclick', `deleteQuestion(${newNumber})`);
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
        alert("All fields must be filled");
        return;
    }

    const checkbox = document.querySelectorAll('input[type="checkbox"]');
    let checkboxCheck = Array.from(checkbox).some(checkbox => checkbox.checked);

    if (!checkboxCheck) {
        alert("Please select at least one correct answer for each question.");
        return;
    }

    const numberbox = document.querySelectorAll('input[type="number"]');
    let numberboxCheck = Array.from(numberbox).some(number => number.value);

    if (!numberboxCheck) {
        alert("Please insert the blank position");
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