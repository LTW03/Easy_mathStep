// function show more color
document.addEventListener('DOMContentLoaded', (event) => {
      const moreColorBtn = document.querySelector('.choose-color');
      const colorMenu = document.querySelector('.more-color-menu');

      moreColorBtn.addEventListener('click', () => {
            if (colorMenu.classList.contains('visible')){
                  colorMenu.classList.remove('visible');
                setTimeout(function() {
                  colorMenu.style.display = 'none';
                },10); // Wait for the transition to finish
            } else {
                  colorMenu.style.display = 'block';
                setTimeout(function() {
                  colorMenu.classList.add('visible');
                },100); // Slight delay to ensure display:block is applied before transition
            }
      });      
});

// Function to select a color
function selectColor(element) {
      var selectedColor = element.getAttribute('data-color');
      document.getElementById('selectedColorInput').value = selectedColor;
      document.getElementById('selected-color').style.backgroundColor = selectedColor;
}
  


function applyClassColor() {
      const className = document.getElementById('classname').value;
      const error = document.getElementById('clsname-error');
      const elements = document.getElementsByClassName(className);
      const color = document.getElementsByClassName(className);


      

      const popup = document.getElementById('create-popup');

      if (className) {
          alert("Your class " + className +" created sucessful")

  
          for (const element of elements) {
              element.style.backgroundColor = color;
          }
      } else {
            
            error.style.display = 'flex';
            popup.style.height = '180px';
      }
}
