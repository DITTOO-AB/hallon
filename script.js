// ----- Meny toggle -----
const menuToggle = document.getElementById("menu-toggle");
const navbar = document.getElementById("navbar");

menuToggle.addEventListener("click", () => {
  navbar.classList.toggle("active");
});

// ----- Fade-in för sektioner -----
const hiddenElements = document.querySelectorAll(".hidden");

const observer = new IntersectionObserver((entries, observer) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add("show");
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.2 });

hiddenElements.forEach(el => observer.observe(el));

// ----- Formspree kontaktformulär -----
const form = document.getElementById('contact-form');
const successMessage = document.getElementById('success-message');
const errorMessage = document.getElementById('error-message');

if (form) {
  form.addEventListener('submit', async (e) => {
    e.preventDefault(); // Stoppar formuläret från att ladda om sidan
    const formData = new FormData(form);

    try {
      const response = await fetch(form.action, {
        method: form.method,
        body: formData,
        headers: { 'Accept': 'application/json' }
      });

      if (response.ok) {
        successMessage.style.display = 'block';
        errorMessage.style.display = 'none';
        form.reset(); // Tömmer formuläret
      } else {
        throw new Error('Något gick fel');
      }
    } catch (error) {
      successMessage.style.display = 'none';
      errorMessage.style.display = 'block';
    }
  });
}
