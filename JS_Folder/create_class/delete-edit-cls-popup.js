const classCards = document.querySelectorAll('.class-card');
const editPopup = document.getElementById('edit-cls-popup');
const deletePopup = document.getElementById('delete-cls-popup');
const assignPopup = document.getElementById('add-lesson-popup');
const editForm = document.getElementById('editClassForm');
const editClassIdInput = document.getElementById('edit_class_id');
const editClassNameInput = document.getElementById('edit_class_name');

classCards.forEach(card => {
    const moreButton = card.querySelector('.class-more');
    const popup = card.querySelector('.delete-edit-cls-popup');
    const editButton = popup.querySelector('.edit-cls');
    const deleteButton = popup.querySelector('.delete-cls');
    const assignButton = popup.querySelector('.assign-lesson');

    moreButton.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
        handlePopup(popup);
    });

    popup.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
    });


});

function handlePopup(popup) {
    if (popup.classList.contains('show')) {
        popup.classList.remove('show');
        setTimeout(() => {
            popup.style.display = 'none';
        }, 100);
    } else {
        // Hide all other popups
        document.querySelectorAll('.delete-edit-cls-popup.show').forEach(p => {
            p.classList.remove('show');
            p.style.display = 'none';
        });
        // Show this popup
        popup.style.display = 'block';
        setTimeout(() => {
            popup.classList.add('show');
        }, 0);
    }
}


document.addEventListener('click', (event) => {
    if (!event.target.closest('.class-more') && !event.target.closest('.delete-edit-cls-popup')) {
        document.querySelectorAll('.delete-edit-cls-popup.show').forEach(popup => {
            popup.classList.remove('show');
            popup.style.display = 'none';
        });
    }
});

document.addEventListener('DOMContentLoaded', (event) => {
    // Cache DOM elements
    const addLessonPopup = document.getElementById('add-lesson-popup');
    const editPopup = document.getElementById('edit-cls-popup');
    const deletePopup = document.getElementById('delete-cls-popup');

    // Function to show popup
    function showPopup(popup) {
        closeAllPopups(); // Close all other popups first
        popup.classList.add('open');
        popup.classList.remove('hide');
        popup.style.display = 'block';
    
        // Add this new event listener
        popup.addEventListener('click', (event) => {
            event.stopPropagation();
        }, { once: true });
    }

    // Function to hide popup
    function hidePopup(popup) {
        popup.classList.remove('open');
        popup.classList.add('hide');
        popup.style.display = 'none';
    }

    // Close all popups
    function closeAllPopups() {
        hidePopup(addLessonPopup);
        hidePopup(editPopup);
        hidePopup(deletePopup);
    }

    // Close popups when clicking outside of them
    document.addEventListener('click', (event) => {
        if (!event.target.closest('.popup') && !event.target.closest('.class-card')) {
            closeAllPopups();
        }
    });

    // Assign lesson popup
    document.querySelectorAll('.assign-lesson').forEach(btn => {
        btn.addEventListener('click', (event) => {
            event.stopPropagation();
            showPopup(addLessonPopup);
        });
    });

    document.getElementById('add-lesson-close').addEventListener('click', (event) => {
        event.stopPropagation();
        hidePopup(addLessonPopup);
    });

    document.getElementById('add-lesson-cancel-btn').addEventListener('click', (event) => {
        event.stopPropagation();
        hidePopup(addLessonPopup);
    });

    // Edit popup
    document.querySelectorAll('.edit-cls').forEach(btn => {
        btn.addEventListener('click', (event) => {
            event.stopPropagation();
            const classCard = btn.closest('.class-card');
            const classId = classCard.dataset.classId;
            const className = classCard.querySelector('h3').textContent;
            document.getElementById('edit_class_id').value = classId;
            document.getElementById('edit_class_name').value = className;
            showPopup(editPopup);
        });
    });

    document.querySelector('.edit-cls-close').addEventListener('click', (event) => {
        event.stopPropagation();
        hidePopup(editPopup);
    });

    document.getElementById('edit-cancel-btn').addEventListener('click', (event) => {
        event.stopPropagation();
        hidePopup(editPopup);
    });

    // Delete popup
    document.querySelectorAll('.delete-cls').forEach(btn => {
        btn.addEventListener('click', (event) => {
            event.stopPropagation();
            const classCard = btn.closest('.class-card');
            const classId = classCard.dataset.classId;
            document.getElementById('delete_class_id').value = classId;
            showPopup(deletePopup);
        });
    });

    document.querySelector('.delete-cls-close').addEventListener('click', (event) => {
        event.stopPropagation();
        hidePopup(deletePopup);
    });

    document.getElementById('delete-cancel-btn').addEventListener('click', (event) => {
        event.stopPropagation();
        hidePopup(deletePopup);
    });

    // Delete form submission
    const deleteForm = document.getElementById('deleteClassForm');
    if (deleteForm) {
        deleteForm.addEventListener('submit', (event) => {
            if (!confirm('Are you sure you want to delete this class?')) {
                event.preventDefault();
            }
        });
    }

    // Edit form submission 
    const editForm = document.getElementById('editClassForm');
    if (editForm) {
        editForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const classId = document.getElementById('edit_class_id').value;
            const newClassName = document.getElementById('edit_class_name').value.trim();

            if (newClassName === document.getElementById('edit_class_name').defaultValue.trim()) {
                alert('No changes were made to the class name.');
                return;
            }

            // AJAX request to update class name
            fetch('PHP-backend/edit_class.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `class_id=${encodeURIComponent(classId)}&class_name=${encodeURIComponent(newClassName)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the class name in the DOM
                    const classCard = document.querySelector(`.class-card[data-class-id="${classId}"]`)
                    if (classCard) {
                        const classNameElement = classCard.querySelector('h3');
                        if (classNameElement) {
                            classNameElement.textContent = newClassName
                        }
                        const editButton = classCard.querySelector('.edit-cls');
                        if (editButton) {
                            editButton.dataset.className = newClassName
                        }
                    }
                    hidePopup(editPopup)
                    alert('Class name updated successfully!');
                } else {
                    alert('Error updating class name: ' + data.message);
                }
            })
            .catch(error => {
                alert('An error occurred while updating the class name.');
            });
        })
    }
});  