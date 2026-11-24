(() => {
  document.querySelectorAll("[data-bar-chart]").forEach((chart) => {
    const rows = Array.from(chart.querySelectorAll(".chart-row[data-value]"));
    const values = rows.map((row) => Math.max(0, Number(row.dataset.value) || 0));
    const max = Math.max(1, ...values);

    rows.forEach((row, index) => {
      const ratio = (values[index] / max) * 100;
      row.querySelector(".chart-fill")?.style.setProperty("--bar-size", `${ratio}%`);
    });
  });
})();
