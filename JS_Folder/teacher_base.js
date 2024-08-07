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
var popup = document.getElementById("create_popup");
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
//logout
document.addEventListener("DOMContentLoaded", function() {
    const logoutLink = document.querySelector(".bx-log-out").parentElement;

    logoutLink.addEventListener("click", function(event) {
          event.preventDefault();

          // Create popup elements
          const popup = document.createElement("div");
          popup.id = "logout";
          popup.className = "logout";

          const popupContent = document.createElement("div");
          popupContent.className = "logout-content";

          const popupText = document.createElement("p");
          popupText.innerText = "Do you want to log out?";

          const yesBtn = document.createElement("button");
          yesBtn.id = "logout-yes-btn";
          yesBtn.innerText = "Yes";

          const noBtn = document.createElement("button");
          noBtn.id = "logout-no-btn";
          noBtn.innerText = "No";

          // Append elements to popup
          popupContent.appendChild(popupText);
          popupContent.appendChild(yesBtn);
          popupContent.appendChild(noBtn);
          popup.appendChild(popupContent);
          document.body.appendChild(popup);

          // Show popup
          popup.style.display = "block";

          yesBtn.addEventListener("click", function() {
                document.cookie = "user_email=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                window.location.href = "landing_page.html";
                document.body.removeChild(popup);
                
          });

          noBtn.addEventListener("click", function() {
                document.body.removeChild(popup);
          });

          // Close the popup if the user clicks outside of it
          window.addEventListener("click", function(event) {
                if (event.target == popup) {
                      document.body.removeChild(popup);
                }
          });
    });
});
