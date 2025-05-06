document.addEventListener('DOMContentLoaded', () => {
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatMessages = document.getElementById('chat-messages');

    chatForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const userInput = chatInput.value.trim();
        if (!userInput) return;

        // Mostrar la pregunta del usuario
        chatMessages.innerHTML += `<div class="chat-message user">${userInput}</div>`;
        chatInput.value = '';

        try {
            const response = await fetch('chat_logic.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ question: userInput })
            });
            const data = await response.json();

            // Mostrar la respuesta de la IA
            chatMessages.innerHTML += `<div class="chat-message ai">${data.answer}</div>`;
        } catch (error) {
            chatMessages.innerHTML += `<div class="chat-message error">Error: No se pudo obtener una respuesta.</div>`;
        }
    });
});
