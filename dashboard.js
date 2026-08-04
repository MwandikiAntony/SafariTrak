const sidebar = document.getElementById('sidebar');
document.getElementById('menu')?.addEventListener('click', () => sidebar.classList.toggle('open'));

document.querySelectorAll('[data-open-modal]').forEach(trigger => {
  trigger.addEventListener('click', () => {
    const id = trigger.getAttribute('data-open-modal');
    document.getElementById(id)?.classList.add('open');
  });
});

document.querySelectorAll('[data-close-modal]').forEach(trigger => {
  trigger.addEventListener('click', () => {
    trigger.closest('.modal-overlay')?.classList.remove('open');
  });
});

document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', e => {
    if (e.target === overlay) overlay.classList.remove('open');
  });
});

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal-overlay.open').forEach(o => o.classList.remove('open'));
  }
});

document.querySelectorAll('[data-tab-group]').forEach(group => {
  const name = group.getAttribute('data-tab-group');
  group.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', () => {
      group.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      const value = tab.getAttribute('data-tab');
      document.querySelectorAll('[data-tab-panel-group="' + name + '"]').forEach(panel => {
        panel.classList.toggle('active', panel.getAttribute('data-tab-panel') === value);
      });
    });
  });
});
