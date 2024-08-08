document.addEventListener('DOMContentLoaded', function() {
    const deleteStudentPopup = document.querySelector('.delete-std-popup');
    const editStudentPopup = document.querySelector('.edit-student-popup');
    const deleteStudentClose = document.querySelector('.delete-std-close');
    const editStudentClose = document.querySelector('.edit-student-close');
    const deleteStudentConfirm = document.querySelector('.edit-delete-btn');
    const deleteStudentCancel = document.querySelector('.edit-cancel-btn');
    const editStudentForm = document.querySelector('#form-1');

    let currentStudentEmail = null;

    function showDeletePopup() {
        deleteStudentPopup.style.display = 'block';
    }

    function hideDeletePopup() {
        deleteStudentPopup.style.display = 'none';
    }

    function showEditPopup() {
        editStudentPopup.style.display = 'block';
    }

    function hideEditPopup() {
        editStudentPopup.style.display = 'none';
    }

    // Use event delegation for delete and edit buttons
    document.querySelector('.student-list').addEventListener('click', function(e) {
        if (e.target.classList.contains('bxs-trash-alt')) {
            handleDeleteClick(e);
        } else if (e.target.classList.contains('bxs-edit')) {
            handleEditClick(e);
        }
    });

    function handleDeleteClick(e) {
        const row = e.target.closest('tr');
        currentStudentEmail = row.querySelector('td:nth-child(4)').textContent;
        showDeletePopup();
    }

    function handleEditClick(e) {
        const row = e.target.closest('tr');
        currentStudentEmail = row.querySelector('td:nth-child(4)').textContent;
        
        document.querySelector('#fname_edit').value = row.querySelector('td:nth-child(1)').textContent;
        document.querySelector('#lname_edit').value = row.querySelector('td:nth-child(2)').textContent;
        document.querySelector('#gender_edit').value = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        document.querySelector('#email_edit').value = currentStudentEmail;

        
        showEditPopup();
    }

    deleteStudentClose.addEventListener('click', hideDeletePopup);

    editStudentClose.addEventListener('click', hideEditPopup);

    deleteStudentConfirm.addEventListener('click', function() {
        if (currentStudentEmail) {
            fetch('Classes-backend/delete_student.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'email=' + encodeURIComponent(currentStudentEmail)
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                location.reload();
            })
            .catch((error) => {
                alert('An error occurred while kicking student out.');
            });
        }
        hideDeletePopup();
    });

    deleteStudentCancel.addEventListener('click', hideDeletePopup);

    editStudentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(editStudentForm);
        formData.append('original_email', currentStudentEmail);

        fetch('Classes-backend/edit_student.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            location.reload();
        })
        .catch((error) => {
            alert('An error occurred while editing student detail.');
        });

        hideEditPopup();
    });

    window.selectCharacterEdit = function(characterId) {
        document.querySelector('#character_edit').value = characterId;
        const profiles = document.querySelectorAll('.profile');
        profiles.forEach(profile => profile.classList.remove('selected'));
        event.currentTarget.classList.add('selected');
    };
});

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchIcon = document.getElementById('searchIcon');
    const studentTableBody = document.getElementById('studentTableBody');
    const originalTableContent = studentTableBody.innerHTML;

    function performSearch() {
        const searchTerm = searchInput.value.trim().toLowerCase();
        const rows = studentTableBody.getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }

        if (searchTerm === '') {
            studentTableBody.innerHTML = originalTableContent;
        }
    }

    searchInput.addEventListener('input', performSearch);
    searchIcon.addEventListener('click', performSearch);
});

document.addEventListener('DOMContentLoaded', (event) => {
    const newStdpopupBtn = document.getElementById('add-student');
    const newStudentpopup = document.getElementById('new-student-popup');
    const newStudentpopupClose = document.getElementById('student-close');
    const newStudentForm = document.querySelector('#new-student-popup form');

    newStdpopupBtn.addEventListener('click', (e) => {
        const classTitleCard = e.target.closest('.class-title-card');
        const classId = classTitleCard.dataset.classId;
        
        // Add a hidden input for class_id to the form
        let classIdInput = newStudentForm.querySelector('input[name="class_id"]');
        if (!classIdInput) {
            classIdInput = document.createElement('input');
            classIdInput.type = 'hidden';
            classIdInput.name = 'class_id';
            newStudentForm.appendChild(classIdInput);
        }
        classIdInput.value = classId;


        newStudentpopup.classList.add('open');
        newStudentpopup.classList.remove('hide');
    });

    newStudentpopupClose.addEventListener('click', () => {
          newStudentpopup.classList.add('hide')
          newStudentpopup.classList.remove('open')

    });

    const icons = document.querySelectorAll('.profile');

          icons.forEach(icon => {
              icon.addEventListener('click', () => {
                  icons.forEach(i => i.classList.remove('selected'));
                  icon.classList.add('selected');
              });
    
});
});

function selectCharacter(characterId) {
  document.getElementById('character').value = characterId;
  var profiles = document.getElementsByClassName('profile');
  for (var i = 0; i < profiles.length; i++) {
      profiles[i].classList.remove('selected');
  }
  event.currentTarget.classList.add('selected');
}