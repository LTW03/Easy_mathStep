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

function deleteQuestion(questionId) {
    const questionBlock = document.getElementById(`questionBlock${questionId}`);
    questionBlock.remove();
    renumberQuestions();
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
    const questionBlocks = document.querySelectorAll('.question-block');
    questionBlocks.forEach((block, index) => {
        const currentNumber = index + 1;
        block.id = `questionBlock${currentNumber}`;
        block.querySelector('h3').textContent = `Question ${currentNumber}`;

        block.querySelector('label[for^="question"]').setAttribute('for', `question${currentNumber}`);
        block.querySelector('input[type="text"]').name = `question${currentNumber}`;
        block.querySelector('input[type="text"]').id = `question${currentNumber}`;

        block.querySelector('label[for^="questionAudio"]').setAttribute('for', `questionAudio${currentNumber}`);
        block.querySelector('input[type="file"]').id = `questionAudio${currentNumber}`;
        block.querySelector('input[type="file"]').name = `questionAudio${currentNumber}`;

        block.querySelectorAll('input[type="radio"]').forEach((radio, radioIndex) => {
            radio.name = `correctAnswer${currentNumber}`;
            radio.value = `option${currentNumber}_${radioIndex + 1}`;
        });

        block.querySelectorAll('input[type="text"]').forEach((text, textIndex) => {
            text.id = `option${currentNumber}_${textIndex + 1}`;
            text.name = `option${currentNumber}_${textIndex + 1}`;
        });

        block.querySelectorAll('input[type="file"]').forEach((file, fileIndex) => {
            file.id = file.id.includes('questionAudio') ? 
                `questionAudio${currentNumber}` : 
                `optionAudio${currentNumber}_${fileIndex + 1}`;
            file.name = file.id;
        });

        const img = block.querySelector('img');
        const imgId = `imagePreview${currentNumber}`;
        img.id = imgId;
        img.parentNode.querySelector('.loader').id = `loader${currentNumber}`;

        block.querySelectorAll('label[for]').forEach(label => {
            label.setAttribute('for', label.getAttribute('for').replace(/\d+$/, currentNumber));
        });

        block.querySelector('input[type="checkbox"]').id = `isEncouragement${currentNumber}`;
        block.querySelector('input[type="checkbox"]').name = `isEncouragement${currentNumber}`;
    });
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