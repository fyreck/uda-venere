function openCard(eventId) {
    // Mostra il modal per l'evento
    document.getElementById('modal-' + eventId).style.display = 'flex';
}

function closeCard(eventId) {
    // Nascondi il modal
    document.getElementById('modal-' + eventId).style.display = 'none';
}