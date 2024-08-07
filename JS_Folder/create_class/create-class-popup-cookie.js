document.addEventListener('DOMContentLoaded', (event) => {
    const popup = document.getElementById('create-class-popup');
    const closeBtn = document.querySelector('.close-popup_page');
    const colorMenu = document.querySelector('.more-color-menu');
    const error = document.getElementById('clsname-error');
    
    // Check if the URL has the showPopup parameter
    const urlParams = new URLSearchParams(window.location.search);
    const showPopup = urlParams.get('showPopup');

    if (showPopup === 'true') {
        popup.classList.add('open');
        popup.classList.remove('hide');
        history.replaceState(null, '', window.location.pathname); // Remove the query parameter from the URL after it's processed
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            popup.classList.add('hide');
            popup.classList.remove('open');
            colorMenu.style.display = 'none';
            error.style.display = 'none';
            popup.style.height = '150px';
        });
    }
});


  

function classCookie(classId, exdays) {
      var d = new Date();
      d.setTime(d.getTime() + (exdays*24*60*60*1000));
      var expires = "expires=" +d.toUTCString();
      document.cookie = "class_cookie =" + classId  + ";" + expires; 
}
