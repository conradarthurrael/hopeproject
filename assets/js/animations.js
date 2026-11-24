(() => {
  const revealTargets = document.querySelectorAll(
    "main > *, section, .card, .dashboard-card, .service-card, .info-card, .mission, .vision"
  );
  if (!revealTargets.length) return;
  revealTargets.forEach((element) => element.classList.add("reveal", "is-pending"));

  const show = (element) => {
    element.classList.remove("is-pending");
    element.classList.add("is-visible");
  };

  if (window.matchMedia("(prefers-reduced-motion: reduce)").matches || !("IntersectionObserver" in window)) {
    revealTargets.forEach(show);
    return;
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      show(entry.target);
      observer.unobserve(entry.target);
    });
  }, { threshold: 0.08 });

  revealTargets.forEach((element, index) => {
    element.style.transitionDelay = `${Math.min(index % 5, 4) * 45}ms`;
    observer.observe(element);
  });

  window.setTimeout(() => revealTargets.forEach(show), 4000);
})();
