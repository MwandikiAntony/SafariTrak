document.getElementById('submitJourney')?.addEventListener('click', () => {
  const startPoint = document.getElementById('startPoint');
  const endPoint = document.getElementById('endPoint');

  if (!startPoint.value.trim() || !endPoint.value.trim()) {
    alert('Please fill in both your starting point and destination.');
    return;
  }

  alert('Trip saved: ' + startPoint.value.trim() + ' to ' + endPoint.value.trim() + '.\nOnce the backend is connected, this will start live tracking and notify the contacts you picked.');
});
