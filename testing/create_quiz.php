<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Creator</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            font-weight: bold;
        }

        select, input[type="text"], textarea {
            margin-top: 5px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        .hidden {
            display: none;
        }

        .radio-group {
            display: flex;
            align-items: center;
            margin-top: 5px;
        }

        .radio-group input[type="radio"] {
            margin-right: 10px;
        }

        .radio-group i {
            margin-right: 10px;
            font-size: 1.5em;
        }

        button {
            margin-top: 20px;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create a Quiz</h1>
        <form id="quizForm">
            <div id="step1">
                <label for="quizType">Select Quiz Type:</label>
                <div class="radio-group">
                    <input type="radio" id="mcq" name="quizType" value="MCQ">
                    <i class="fas fa-question-circle" aria-label="Multiple Choice Icon"></i>
                    <label for="mcq">Multiple Choice Questions</label>
                </div>
                <div class="radio-group">
                    <input type="radio" id="tf" name="quizType" value="TF">
                    <i class="far fa-hand-lizard" aria-label="True/False Icon"></i>
                    <label for="tf">True/False</label>
                </div>
                <div class="radio-group">
                    <input type="radio" id="dragdrop" name="quizType" value="DragDrop">
                    <i class="fas fa-check-circle" aria-label="Drag and Drop Icon"></i>
                    <label for="dragdrop">Drag and Drop</label>
                </div>
                <button type="button" onclick="nextStep()">Next</button>
            </div>
            
            <div id="step2" class="hidden">
                <label for="questionText">Enter Question:</label>
                <textarea id="questionText" name="questionText" required></textarea>
                
                <div id="mcqOptions" class="hidden">
                    <label>Enter options:</label>
                    <div class="radio-group">
                        <input type="radio" name="mcqCorrect" value="1">
                        <input type="text" name="mcqOption[]" placeholder="Option 1" required>
                    </div>
                    <div class="radio-group">
                        <input type="radio" name="mcqCorrect" value="2">
                        <input type="text" name="mcqOption[]" placeholder="Option 2" required>
                    </div>
                    <div class="radio-group">
                        <input type="radio" name="mcqCorrect" value="3">
                        <input type="text" name="mcqOption[]" placeholder="Option 3">
                    </div>
                    <div class="radio-group">
                        <input type="radio" name="mcqCorrect" value="4">
                        <input type="text" name="mcqOption[]" placeholder="Option 4">
                    </div>
                </div>

                <div id="tfOptions" class="hidden">
                    <label for="tfOption">Select True or False:</label>
                    <select id="tfOption" name="tfOption" required>
                        <option value="true">True</option>
                        <option value="false">False</option>
                    </select>
                </div>

                <div id="dragDropOptions" class="hidden">
                    <label for="dragItem">Draggable Item:</label>
                    <input type="text" id="dragItem" name="dragItem" required>
                    <label for="dropTarget">Droppable Target:</label>
                    <input type="text" id="dropTarget" name="dropTarget" required>
                </div>
                
                <button type="button" onclick="saveQuestion()">Save</button>
            </div>
        </form>
    </div>

    <script>
        function nextStep() {
            const quizTypeRadio = document.querySelector('input[name="quizType"]:checked');
            if (!quizTypeRadio) {
                alert("Please select one quiz type.");
                return;
            }

            document.getElementById('step1').classList.add('hidden');
            document.getElementById('step2').classList.remove('hidden');
            
            const quizType = quizTypeRadio.value;
            document.getElementById('mcqOptions').classList.add('hidden');
            document.getElementById('tfOptions').classList.add('hidden');
            document.getElementById('dragDropOptions').classList.add('hidden');
            
            if (quizType === 'MCQ') {
                document.getElementById('mcqOptions').classList.remove('hidden');
            } else if (quizType === 'TF') {
                document.getElementById('tfOptions').classList.remove('hidden');
            } else if (quizType === 'DragDrop') {
                document.getElementById('dragDropOptions').classList.remove('hidden');
            }
        }

        function saveQuestion() {
            const formData = new FormData(document.getElementById('quizForm'));
            
            fetch('save_question.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                document.getElementById('quizForm').reset();
                document.getElementById('step1').classList.remove('hidden');
                document.getElementById('step2').classList.add('hidden');
                document.getElementById('mcqOptions').classList.add('hidden');
                document.getElementById('tfOptions').classList.add('hidden');
                document.getElementById('dragDropOptions').classList.add('hidden');
            });
        }
    </script>
</body>
</html>
