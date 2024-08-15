function previewImage(event, imageId, loaderId) {
    var reader = new FileReader();
    var image = document.getElementById(imageId);
    var loader = document.getElementById(loaderId);

    reader.onload = function () {
        image.src = reader.result;
        image.style.display = 'block';
        loader.style.display = 'none';
    }

    loader.style.display = 'block';
    reader.readAsDataURL(event.target.files[0]);
}

function saveQuiz(event) {
    event.preventDefault();
    const quizForm = document.getElementById('quizForm');

    let valid = true;

    // Validate required fields
    const requiredInputs = quizForm.querySelectorAll('input[required]');
    requiredInputs.forEach(input => {
        if (!input.value.trim()) {
            valid = false;
        }
    });

    if (!valid) {
        alert("All required fields must be filled.");
        return;
    }

    // Check if at least one correct answer is selected
    const radios = document.querySelectorAll('input[type="radio"]:checked');
    if (radios.length === 0) {
        alert("Please select at least one correct answer for each question.");
        return;
    }

    // Collect correct answers
    const correctAnswers = {};
    radios.forEach(radio => {
        correctAnswers[radio.name] = radio.value;
    });

    // Add correct answers to form data
    const correctAnswersInput = document.createElement('input');
    correctAnswersInput.type = 'hidden';
    correctAnswersInput.name = 'correctAnswers';
    correctAnswersInput.value = JSON.stringify(correctAnswers);
    quizForm.appendChild(correctAnswersInput);

    // Submit the form
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
                window.location.href = "../../library_page.php"; // Change to the page you want to redirect to
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
