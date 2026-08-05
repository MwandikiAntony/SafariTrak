const travelerSearch = document.getElementById('travelerSearch');
const travelerStatusFilter = document.getElementById('travelerStatusFilter');
const travelerRows = document.querySelectorAll('#travelersTable tbody tr');
const emptyState = document.getElementById('travelersEmptyState');

function applyTravelerFilters() {
  const search = travelerSearch.value.trim().toLowerCase();
  const status = travelerStatusFilter.value;
  let visible = 0;

  travelerRows.forEach(row => {
    const matchesSearch = !search || row.textContent.toLowerCase().includes(search);
    const matchesStatus = status === 'all' || row.getAttribute('data-status') === status;
    const show = matchesSearch && matchesStatus;

    row.style.display = show ? '' : 'none';
    if (show) visible++;
  });

  if (emptyState) emptyState.style.display = visible === 0 ? 'block' : 'none';
}

travelerSearch?.addEventListener('input', applyTravelerFilters);
travelerStatusFilter?.addEventListener('change', applyTravelerFilters);
