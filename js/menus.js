document.addEventListener("DOMContentLoaded", function () {
  const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
  const menuCollapse = document.getElementById('menuNavbar');

  // cuenta(); // Llama a cuenta al cargar la página

  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      const bsCollapse = bootstrap.Collapse.getInstance(menuCollapse);
      if (bsCollapse && menuCollapse.classList.contains('show')) {
        bsCollapse.hide();
      }
    });
  });
});

