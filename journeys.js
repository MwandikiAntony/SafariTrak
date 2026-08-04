const journeyTabs = document.querySelectorAll('[data-tab-group="journeys"] .tab');
const journeyRows = document.querySelectorAll('#journeyList .journey-row');
const emptyState = document.getElementById('emptyState');

journeyTabs.forEach(tab => {
  tab.addEventListener('click', () => {
    journeyTabs.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    const filter = tab.getAttribute('data-tab');
    let visibleCount = 0;

    journeyRows.forEach(row => {
      const show = filter === 'all' || row.getAttribute('data-status') === filter;
      row.style.display = show ? 'flex' : 'none';
      if (show) visibleCount++;
    });

    if (emptyState) emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
  });
});
