$(document).ready(function () {
    console.log("Chat JS is loaded and ready!");
    var metaTag = document.getElementsByTagName('meta');
    var csrfToken = '';

    for (var i = 0; i < metaTag.length; i++) {
        if (metaTag[i].getAttribute('name') === 'csrf-token') {
            csrfToken = metaTag[i].getAttribute('content');
            break;
        }
    }

    var metaTag = document.querySelector('meta[name="current-user"]');
    var currentUser = '';

    if (metaTag) {
        currentUser = metaTag.getAttribute('content');
    }

    console.log(currentUser);

    function loadMessages(chatId) {
        fetch(`/messages/fetch?chat_id=${chatId}`)
            .then(response => response.json())
            .then(data => {
                let chatBox = document.querySelector(`#chat-box-${chatId}`);
                chatBox.innerHTML = '';
                data.forEach(message => {
                    let messageHtml = `<div><strong>${message.sender_id === currentUser ? 'Me' : message.sender.name}:</strong> ${message.message}</div>`;
                    chatBox.innerHTML += messageHtml;
                });
                chatBox.scrollTop = chatBox.scrollHeight;
            });
    }

    function createChatWindow(userId, userName) {
        const chatId = `${userId}_${currentUser}`;
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
    }

    document.querySelector('#receiver_id').addEventListener('change', function () {
        const receiverId = this.value;
        const receiverName = this.options[this.selectedIndex].text;

        if (receiverId) {
            createChatWindow(receiverId, receiverName);
        }
    });

    document.addEventListener('click', function (event) {
        if (event.target && event.target.matches('button[id^="send-"]')) {
            const chatId = event.target.id.split('-')[1];
            const message = document.querySelector(`#message-${chatId}`).value;
            const receiverId = chatId.split('_')[0];

            if (message && receiverId) {
                fetch('/messages/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        receiver_id: receiverId,
                        message: message
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector(`#message-${chatId}`).value = '';
                        loadMessages(chatId);
                    });
            }
        }
    });
});
