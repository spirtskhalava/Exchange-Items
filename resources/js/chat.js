$(document).ready(function () {
    console.log("Chat JS is loaded and ready!");
    let metaTag = '';
    let metaTagToken ='';
    let currentUser = '';
    metaTagToken = document.querySelector('meta[name="csrf-token"]');
    metaTag = document.querySelector('meta[name="current-user"]');
    console.log("metaTagToken",metaTagToken);

    if (metaTag) {
        currentUser = metaTag.getAttribute('content');
    }

    function loadMessages(chatId) {
        fetch(`/messages/fetch?chat_id=${chatId}`)
            .then(response => response.json())
            .then(data => {
                let chatBox = document.querySelector(`#chat-box-${chatId}`);
                chatBox.innerHTML = '';
                data.forEach(message => {
                    console.log("message",message);
                    let messageHtml = `<div><strong>${message.sender_id == currentUser ? 'Me' : message.sender.name}:</strong> ${message.message}</div>`;
                    chatBox.innerHTML += messageHtml;
                });
                chatBox.scrollTop = chatBox.scrollHeight;
            });
    }

    function createChatWindow(userId, userName) {
        const chatId = `${userId}_${currentUser}`;
    
        // Hide all existing chat windows
        document.querySelectorAll('.chat-window').forEach(window => {
            window.style.display = 'none';
        });
    
        // Check if the chat window already exists
        let existingChatWindow = document.querySelector(`#chat-window-${chatId}`);
        if (!existingChatWindow) {
            const chatWindowHtml = `
                <div class="chat-window" id="chat-window-${chatId}" style="margin-bottom: 10px; border: 1px solid #ccc; padding: 10px; height: 400px; overflow-y: scroll;">
                    <h5>Chat with ${userName}</h5>
                    <div id="chat-box-${chatId}" style="height: 300px; overflow-y: auto;"></div>
                    <div class="input-group mt-3">
                        <input type="text" id="message-${chatId}" class="form-control" placeholder="Type your message...">
                        <div class="input-group-append">
                            <button class="btn btn-primary" id="send-${chatId}">Send</button>
                        </div>
                    </div>
                </div>
            `;
            document.querySelector('#chat-windows-container').innerHTML += chatWindowHtml;
            loadMessages(chatId);
            setInterval(() => loadMessages(chatId), 3000);
        } else {
            // Show the existing chat window if it already exists
            existingChatWindow.style.display = 'block';
        }
    }

    document.querySelectorAll('.user-list-item').forEach(item => {
        item.addEventListener('click', function () {
            const receiverId = this.getAttribute('data-user-id');
            const receiverName = this.textContent;
    
            if (receiverId) {
                createChatWindow(receiverId, receiverName);
            }
        });
    });

    document.addEventListener('click', function (event) {
        if (event.target && event.target.matches('button[id^="send-"]')) {
            sendMessage(event.target.id.split('-')[1]);
        }
    });
    
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            const activeElement = document.activeElement;
            if (activeElement && activeElement.matches('input[id^="message-"]')) {
                sendMessage(activeElement.id.split('-')[1]);
            }
        }
    });
    
    function sendMessage(chatId) {
        const messageInput = document.querySelector(`#message-${chatId}`);
        const message = messageInput.value;
        const receiverId = chatId.split('_')[0];
    
        if (message && receiverId) {
            fetch('/messages/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': metaTagToken.getAttribute('content')
                },
                body: JSON.stringify({
                    receiver_id: receiverId,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                messageInput.value = '';
                loadMessages(chatId);
            });
        }
    }
});
