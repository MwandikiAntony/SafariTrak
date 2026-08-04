const conversations = {
  jm: {
    name: 'John Mwangi',
    status: 'Watching your journey',
    initials: 'JM',
    messages: [
      { from: 'them', text: 'Hey, are you on the road yet?' },
      { from: 'me', text: 'Yes, just left Nairobi heading to Nyeri.' },
      { from: 'them', text: 'Sure, I can see you on the map now' }
    ]
  },
  mw: {
    name: 'Mary Wanjiku',
    status: 'Available',
    initials: 'MW',
    messages: [
      { from: 'them', text: 'How is the trip going?' },
      { from: 'me', text: 'Going well, about halfway there.' },
      { from: 'them', text: 'Let me know when you arrive' }
    ]
  },
  pk: {
    name: 'Peter Kariuki',
    status: 'Offline',
    initials: 'PK',
    messages: [
      { from: 'me', text: 'Heading out now, will share my location.' },
      { from: 'them', text: 'Safe travels!' }
    ]
  }
};

const chatItems = document.querySelectorAll('.chat-item');
const chatHead = document.getElementById('chatHead');
const chatMessages = document.getElementById('chatMessages');
const chatForm = document.getElementById('chatForm');
const chatInput = document.getElementById('chatInput');

function renderChat(key) {
  const convo = conversations[key];
  if (!convo) return;

  chatHead.innerHTML = '<span class="person">' + convo.initials + '</span><div><b>' + convo.name + '</b><small>&#9679; ' + convo.status + '</small></div>';

  chatMessages.innerHTML = '';
  convo.messages.forEach(m => {
    const bubble = document.createElement('div');
    bubble.className = 'bubble ' + (m.from === 'me' ? 'me' : 'them');
    bubble.textContent = m.text;
    chatMessages.appendChild(bubble);
  });
  chatMessages.scrollTop = chatMessages.scrollHeight;
}

chatItems.forEach(item => {
  item.addEventListener('click', () => {
    chatItems.forEach(i => i.classList.remove('active'));
    item.classList.add('active');
    renderChat(item.getAttribute('data-chat'));
  });
});

chatForm?.addEventListener('submit', e => {
  e.preventDefault();
  const text = chatInput.value.trim();
  if (!text) return;

  const activeItem = document.querySelector('.chat-item.active');
  const key = activeItem ? activeItem.getAttribute('data-chat') : 'jm';

  conversations[key].messages.push({ from: 'me', text });
  renderChat(key);
  chatInput.value = '';
});

renderChat('jm');
