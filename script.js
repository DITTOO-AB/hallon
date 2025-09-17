// ----- Meny toggle -----
const menuToggle = document.getElementById("menu-toggle");
const navbar = document.getElementById("navbar");

if (menuToggle) {
  menuToggle.addEventListener("click", () => {
    navbar.classList.toggle("active");
  });
}

// ----- Fade-in för sektioner -----
const hiddenElements = document.querySelectorAll(".hidden");

if (hiddenElements.length > 0) {
  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("show");
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.2 });

  hiddenElements.forEach(el => observer.observe(el));
}

// ----- kontaktformulär -----
const form = document.getElementById('contact-form');
const successMessage = document.getElementById('success-message');
const errorMessage = document.getElementById('error-message');

if (form) {
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: { 'Accept': 'application/json' }
      });

      const result = await response.json();

      if (result.success) {
        successMessage.style.display = 'block';
        errorMessage.style.display = 'none';
        form.reset();
      } else {
        throw new Error(result.message);
      }
    } catch (err) {
      successMessage.style.display = 'none';
      errorMessage.style.display = 'block';
    }
  });
}
