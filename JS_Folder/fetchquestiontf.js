function fetchFeedback(isEncouragement) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch-incorrect/fetch_incorrect_feedbacktf.php?question_id=" + currentQuestionId + "&is_encouragement=" + isEncouragement, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.word_text !== "No feedback available") {
                document.getElementById('popup-img').src = response.img_path ? "./"+response.img_path : "";
                document.getElementById('popup-text').textContent = response.word_text;
                document.getElementById('popup').style.display = 'block';
            } 
            else {
                fetchNextQuestion();
            }
        }
    };
    xhr.send();
}

function fetchNextQuestion() {
    var trueButton = document.querySelector('.true-button');
    var falseButton = document.querySelector('.false-button');
    trueButton.style.backgroundColor = ''; 
    falseButton.style.backgroundColor = ''; 

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch-next/fetch_next_questiontf.php?question_id=" + currentQuestionId, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);

            if (response.question_text !== "No more questions") {
                document.getElementById('question-text').textContent = response.question_text;
                currentQuestionId = response.next_question_id;
                correctAnswer = response.is_true ? true : false;

                var audio = document.getElementById('question-audio');
                if (response.question_audio) {
                    audio.src = response.question_audio;
                    audio.load();  
                } else {
                    audio.src = "";  
                }

                var soundIcon = document.querySelector('.sound_icon');
                soundIcon.style.display = 'inline';

            } else {
                submitResults();
            }
        }
    };
    xhr.send();
}


function submitResults() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "submit_results.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            window.location.href = 'resulttf.php'; 
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

function playSound() {
var audio = document.getElementById('question-audio');
if (audio && audio.src) {
    audio.play().catch(function(error) {
        console.log("Error playing audio:", error);
    });
} else {
    console.log("No audio available for this question.");
}
}