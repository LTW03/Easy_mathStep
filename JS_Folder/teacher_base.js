/*==================== SHOW MENU ====================*/
const showMenu = (headerToggle, navbarId) =>{
    const toggleBtn = document.getElementById(headerToggle),
    nav = document.getElementById(navbarId)
    
    // Validate that variables exist
    if(headerToggle && navbarId){
        toggleBtn.addEventListener('click', ()=>{
            // We add the show-menu class to the div tag with the nav__menu class
            nav.classList.toggle('show-menu')

            //change icon
            toggleBtn.classList.toggle('bx-x')
        })
    }
}
showMenu('header-toggle','navbar')

//   link active
const linkColor = document.querySelectorAll('.nav-link')

function colorLink(){
    linkColor.forEach(l => l.classList.remove('active'))
    this.classList.add('active')
}

linkColor.forEach(l => l.addEventListener('click',colorLink))

// show pop up selection
var btn = document.getElementById("create_btn");
var popup = document.getElementById("create-popup");
btn.onclick = function() {
    popup.style.display = "block";
}

// close btn
var span = document.getElementById("popup-close");
span.onclick = function() {
    popup.style.display = "none";
}
//user clicks outside the popup close
window.onclick = function(event) {
    if (event.target == popup) {
        popup.style.display = "none";
    }
}
