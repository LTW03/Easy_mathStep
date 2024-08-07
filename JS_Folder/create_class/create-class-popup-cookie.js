document.addEventListener('DOMContentLoaded', (event) => {
      const createClassLink = document.getElementById('create-class-link');
      const popup = document.getElementById('create-popup');
      const closeBtn = document.querySelector('.close');
      const colorMenu = document.querySelector('.more-color-menu');
      const error = document.getElementById('clsname-error');
  
      createClassLink.addEventListener('click', (e) => {
          e.preventDefault();
          // Navigate to classes_page.php
          window.location.href = "classes_page.php";
          // Show the popup after the navigation
          window.onload = function() {
              popup.classList.add('open');
              popup.classList.remove('hide');
          };
      });
  
      closeBtn.addEventListener('click', () => {
          popup.classList.add('hide');
          popup.classList.remove('open');
          colorMenu.style.display = 'none';
          error.style.display = 'none';
          popup.style.height = '150px';
      });
  });
  

function classCookie(classId, exdays) {
      var d = new Date();
      d.setTime(d.getTime() + (exdays*24*60*60*1000));
      var expires = "expires=" +d.toUTCString();
      document.cookie = "class_cookie =" + classId  + ";" + expires; 
}
