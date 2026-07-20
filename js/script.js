// Smooth fade-in effect for gallery items on scroll
document.addEventListener("DOMContentLoaded", function () {
    const items = document.querySelectorAll('.gallery-item');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.1 // Triggers when 10% of the element is visible
    });

    items.forEach(item => {
        observer.observe(item);
    });
});
document.addEventListener("DOMContentLoaded", () => {
    // 1. Récupération des éléments de la modale
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalTargetImg");
    const captionText = document.getElementById("modalCaption");
    const closeBtn = document.querySelector(".modal-close");

    // Sécurité : on vérifie que la modale existe sur la page actuelle
    if (!modal || !modalImg) return;

    // 2. Sélection de toutes les images de la galerie
    const galleryImages = document.querySelectorAll(".zoomable");

    // 3. Ajout de l'événement de clic sur chaque image
    galleryImages.forEach(img => {
        img.addEventListener("click", () => {
            modal.style.display = "block";
            modalImg.src = img.src;
            captionText.innerHTML = img.alt || "Illustration";
        });
    });

    // 4. Fermeture de la modale au clic sur le bouton (X)
    if (closeBtn) {
        closeBtn.addEventListener("click", () => {
            modal.style.display = "none";
        });
    }

    // 5. Fermeture de la modale au clic n'importe où en dehors de l'image
    modal.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
});