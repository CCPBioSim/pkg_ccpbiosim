window.addEventListener('load', () => {
  document.querySelectorAll('.fade-up').forEach((el, i) => {
    setTimeout(() => el.classList.add('show'), i * 120);
  });
});
