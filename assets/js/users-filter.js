(() => {
  const search = document.getElementById("user-prefix-filter");
  const role = document.getElementById("user-role-filter");
  const cards = Array.from(document.querySelectorAll(".portal-card[data-user-name][data-user-role]"));
  if (!search || !role || cards.length === 0) return;

  const applyFilters = () => {
    const query = search.value.toLowerCase();
    const selectedRole = role.value.toLowerCase();

    cards.forEach((card) => {
      const name = (card.dataset.userName || "").toLowerCase();
      const userRole = (card.dataset.userRole || "").toLowerCase();
      card.hidden = !(name.startsWith(query) && (!selectedRole || userRole === selectedRole));
    });
  };

  search.addEventListener("input", applyFilters);
  role.addEventListener("change", applyFilters);
})();
