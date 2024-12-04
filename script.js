const slides = document.querySelectorAll('.slide');
const indicators = document.querySelectorAll('.indicator');
let currentSlide = 0;
let slideInterval;

// Função para mostrar o slide atual
function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.remove('active');
        if (i === index) {
            slide.classList.add('active');
        }
    });
    indicators.forEach((indicator, i) => {
        indicator.classList.remove('active');
        if (i === index) {
            indicator.classList.add('active');
        }
    });

    const offset = -index * 100;
    document.querySelector('.slides').style.transform = `translateX(${offset}%)`;
}

// Mudar slides automaticamente a cada 3 segundos
function startSlideShow() {
    slideInterval = setInterval(() => {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }, 2000);
}

// Parar o slide automático ao clicar nos indicadores
function stopSlideShow() {
    clearInterval(slideInterval);
}

// Mudar slide ao clicar no indicador
indicators.forEach(indicator => {
    indicator.addEventListener('click', (e) => {
        const index = parseInt(e.target.getAttribute('data-slide'));
        currentSlide = index;
        showSlide(currentSlide);
        stopSlideShow(); // Para a rotação automática ao clicar
        startSlideShow(); // Reinicia após clique
    });
});

// Inicializa o slideshow
startSlideShow();
