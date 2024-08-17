function fetchFeedback(isEncouragement) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch-incorrect/fetch_incorrect_feedbackmcqs.php?question_id=" + currentQuestionId + "&is_encouragement=" + isEncouragement, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.word_text !== "No feedback available") {
                document.getElementById('popup-img').src = response.img_path ? "./"+response.img_path : "";
                document.getElementById('popup-text').textContent = response.word_text;
                document.getElementById('popup').style.display = 'block';
            } else {
                fetchNextQuestion();
            }
        }
    };
    xhr.send();
}

function fetchNextQuestion() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "fetch-next/fetch_next_questionmcqs.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);

            if (response.question_text !== "No more questions") {
                document.querySelector('.question h1').textContent = response.question_text;
                currentQuestionId = response.question_id;
                correctAnswer = response.options.find(option => option.is_correct).answer_text;

                // Update answer buttons
                var buttonsContainer = document.querySelector('.buttons_MCQs');
                var existingButtons = buttonsContainer.querySelectorAll('button');

                response.options.forEach((option, index) => {
                    if (index < existingButtons.length) {
                        existingButtons[index].textContent = option.answer_text;
                        existingButtons[index].setAttribute('onclick', 'checkAnswer(\'' + escapeJavaScriptString(option.answer_text) + '\', this)');
                        existingButtons[index].style.backgroundColor = ''; 
                        existingButtons[index].disabled = false;
                    } else {
                        var button = document.createElement('button');
                        button.className = 'mcq-button'; 
                        button.id = 'button-' + index; 
                        button.textContent = option.answer_text;
                        button.setAttribute('onclick', 'checkAnswer(\'' + escapeJavaScriptString(option.answer_text) + '\', this)');
                        buttonsContainer.appendChild(button);
                        button.disabled = false; 
                    }
                });

                for (var i = response.options.length; i < existingButtons.length; i++) {
                    existingButtons[i].style.display = 'none';
                }

                var existingAudio = document.getElementById('question-audio');
                if (existingAudio) {
                    existingAudio.remove(); 
                }

                if (response.question_audio) {
                    var newAudio = document.createElement('audio');
                    newAudio.id = 'question-audio';
                    newAudio.src = response.question_audio;
                    document.body.appendChild(newAudio); 
                }
            } else {
                submitResults();
            }
        }
    };
    xhr.send(JSON.stringify({ currentQuestionId: currentQuestionId }));
}



function submitResults() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "submit_results.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            window.location.href = 'resultmcqs.php'; 
        }
    };
    xhr.send(JSON.stringify({
        score: score,
        incorrectQuestions: incorrectQuestions
    }));
}

function closePopup() {
    document.getElementById('popup').style.display = 'none';
    fetchNextQuestion();
}

function escapeJavaScriptString(str) {
    return str.replace(/\\/g, '\\\\')
            .replace(/'/g, "\\'")
            .replace(/"/g, '\\"')
            .replace(/\n/g, '\\n')
            .replace(/\r/g, '\\r')
            .replace(/\x07/g, '\\x07')
            .replace(/\x08/g, '\\x08')
            .replace(/\t/g, '\\t')
             .replace(/\x0b/g, '\\x0b')
            .replace(/\x0c/g, '\\x0c')
            .replace(/\x0e/g, '\\x0e')
            .replace(/\x0f/g, '\\x0f');
}

function playSound() {
    var audio = document.getElementById('question-audio');
    if (audio) {
        audio.play();
    }
}