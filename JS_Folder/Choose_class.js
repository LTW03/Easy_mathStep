const carousel = document.querySelector('.carousel');
const items = document.querySelectorAll('.carousel-item');
const prevButton = document.getElementById('prev');
const nextButton = document.getElementById('next');

let currentIndex = 0;

function updateCarousel() {
    const itemWidth = items[0].offsetWidth;
    const containerWidth = carousel.parentElement.offsetWidth;
    const centerIndex = Math.floor(items.length / 2);
    
    items.forEach((item, index) => {
        item.classList.remove('active');
        item.style.transform = 'scale(1)';
        item.style.fontSize = '1em';
    });

    items[currentIndex].classList.add('active');
    items[currentIndex].style.transform = 'scale(1.5)';
    items[currentIndex].style.fontSize = '1.5em';

    // Calculate the offset to center the enlarged item
    const offset = -((currentIndex - centerIndex) * (100 / items.length));
    carousel.style.transform = `translateX(${offset}%)`;
}

prevButton.addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + items.length) % items.length;
    updateCarousel();
});

nextButton.addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % items.length;
    updateCarousel();
});

updateCarousel();





