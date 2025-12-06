$(document).ready(function () {
    console.log("Chat JS loaded.");

    // --- 1. Configuration & Global State ---
    let currentUser = '';
    const metaTagToken = document.querySelector('meta[name="csrf-token"]');
    const metaTagUser = document.querySelector('meta[name="current-user"]');
    const csrfToken = metaTagToken ? metaTagToken.getAttribute('content') : '';
    let activePollingInterval = null; // To track and clear the auto-refresh

    if (metaTagUser) {
        currentUser = metaTagUser.getAttribute('content');
    }

    // --- 2. Helper: Prevent XSS (Security) ---
    function escapeHtml(text) {
        if (!text) return text;
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // --- 3. Helper: Update Navbar Badge ---
    function updateBadgeUI(count) {
        const badge = document.getElementById('unread-badge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.classList.remove('d-none');
            } else {
                badge.textContent = '';
                badge.classList.add('d-none');
            }
        }
    }

    // --- 4. Logic: Mark Messages as Read ---
    function markMessagesAsRead(senderId) {
        if (!senderId) return;

        fetch('/messages/mark-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ sender_id: senderId })
        })
        .then(response => response.json())
        .then(data => {
            // Update the badge in the header
            if (data.unread_count !== undefined) {
                updateBadgeUI(data.unread_count);
            }
        })
        .catch(err => console.error("Error marking read:", err));
    }

    // --- 5. Logic: Load & Render Messages ---
    function loadMessages(chatId) {
        fetch(`/messages/fetch?chat_id=${chatId}`)
            .then(response => response.json())
            .then(data => {
                let chatBox = document.querySelector(`#chat-box-${chatId}`);
                if (!chatBox) return;

                // Check if user is scrolled to bottom before updating
                // (so we don't jump them around if they are reading old messages)
                const isAtBottom = chatBox.scrollHeight - chatBox.scrollTop <= chatBox.clientHeight + 100;

                let html = '';

                if (data.length === 0) {
                    html = `<div class="text-center mt-5 text-muted small opacity-50">
                                <i class="bi bi-chat-square-dots mb-2" style="font-size: 2rem;"></i>
                                <p>No messages yet. Say hi!</p>
                            </div>`;
                } else {
                    data.forEach(message => {
                        const isMe = message.sender_id == currentUser;
                        const wrapperClass = isMe ? 'sent' : 'received';
                        
                        // Format time (HH:MM AM/PM)
                        const timeString = message.created_at 
                            ? new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) 
                            : '';

                        html += `
                            <div class="message-wrapper ${wrapperClass}">
                                <div class="message-bubble">
                                    ${escapeHtml(message.message)}
                                </div>
                                <div class="message-info">${timeString}</div>
                            </div>
                        `;
                    });
                }

                // Only update DOM if content changed (simple check)
                if (chatBox.innerHTML !== html) {
                    chatBox.innerHTML = html;
                    // Auto-scroll to bottom if user was already at bottom or on first load
                    if (isAtBottom || chatBox.scrollTop === 0) {
                        chatBox.scrollTop = chatBox.scrollHeight;
                    }
                }
            })
            .catch(error => console.error("Error loading messages:", error));
    }

    // --- 6. Logic: Create Chat Window UI ---
    function createChatWindow(userId, userName) {
        // A. Sidebar Visuals: Highlight active user
        document.querySelectorAll('.user-list-item').forEach(item => item.classList.remove('active-user'));
        const activeItem = document.querySelector(`.user-list-item[data-user-id="${userId}"]`);
        if (activeItem) activeItem.classList.add('active-user');

        const chatId = `${userId}_${currentUser}`;

        // B. Cleanup: Stop polling previous chat
        if (activePollingInterval) clearInterval(activePollingInterval);

        // C. Trigger: Mark messages as read immediately
        markMessagesAsRead(userId);

        // D. Build UI
        const chatWindowHtml = `
        <div class="chat-window-card h-100 d-flex flex-column">
            <!-- Header -->
            <div class="chat-header d-flex align-items-center p-3 border-bottom bg-white">
                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(userName)}&background=random" class="rounded-circle me-3" width="40" height="40">
                <div>
                    <h6 class="mb-0 fw-bold text-dark">${userName}</h6>
                    <span class="text-success small"><i class="bi bi-circle-fill" style="font-size: 8px;"></i> Active now</span>
                </div>
            </div>

            <!-- Messages Area -->
            <div id="chat-box-${chatId}" class="chat-messages-area flex-grow-1 p-3 overflow-auto" style="background-color: #f8f9fa;">
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="chat-input-area p-3 bg-white border-top">
                <div class="chat-input-wrapper d-flex align-items-center bg-light rounded-pill px-3 py-2 border">
                    <input type="text" id="message-${chatId}" class="form-control border-0 bg-transparent shadow-none" placeholder="Type a message..." autocomplete="off">
                    <button class="btn btn-primary rounded-circle shadow-sm d-flex align-items-center justify-content-center ms-2" style="width: 40px; height: 40px;" id="send-${chatId}">
                        <i class="bi bi-send-fill" style="margin-left: -2px;"></i>
                    </button>
                </div>
            </div>
        </div>
        `;

        document.querySelector('#chat-windows-container').innerHTML = chatWindowHtml;

        // E. Start Logic
        loadMessages(chatId);
        activePollingInterval = setInterval(() => loadMessages(chatId), 3000); // Poll every 3s

        // Focus input
        setTimeout(() => {
            const input = document.getElementById(`message-${chatId}`);
            if (input) input.focus();
        }, 100);
    }

    // --- 7. Logic: Send Message ---
    function sendMessage(chatId) {
        const messageInput = document.querySelector(`#message-${chatId}`);
        if (!messageInput) return;

        const message = messageInput.value.trim();
        const receiverId = chatId.split('_')[0];

        if (message && receiverId) {
            // Optimistic UI: Add bubble immediately
            let chatBox = document.querySelector(`#chat-box-${chatId}`);
            if (chatBox) {
                const tempHtml = `
                    <div class="message-wrapper sent opacity-50">
                        <div class="message-bubble">${escapeHtml(message)}</div>
                        <div class="message-info">Sending...</div>
                    </div>`;
                chatBox.insertAdjacentHTML('beforeend', tempHtml);
                chatBox.scrollTop = chatBox.scrollHeight;
            }

            // Clear input
            messageInput.value = '';

            // Send to Server
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
                loadMessages(chatId); // Refresh to get real ID/Time
            })
            .catch(err => {
                console.error(err);
                alert("Failed to send message");
            });
        }
    }

    // --- 8. Event Listeners ---

    // A. Click User from Sidebar
    // Note: We use querySelectorAll here assuming the list is static. 
    // If you load users via AJAX, use event delegation instead.
    const userItems = document.querySelectorAll('.user-list-item');
    userItems.forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            const receiverId = this.getAttribute('data-user-id');
            // Try to get name from data attribute first, fallback to text
            const receiverName = this.getAttribute('data-user-name') || this.innerText.split('\n')[0].trim();

            if (receiverId) {
                createChatWindow(receiverId, receiverName);
            }
        });
    });

    // B. Send Button Click (Delegated)
    document.addEventListener('click', function (event) {
        const btn = event.target.closest('button[id^="send-"]');
        if (btn) {
            sendMessage(btn.id.split('-')[1]);
        }
    });

    // C. Enter Key in Input (Delegated)
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            const activeElement = document.activeElement;
            if (activeElement && activeElement.matches('input[id^="message-"]')) {
                sendMessage(activeElement.id.split('-')[1]);
            }
        }
    });

    // --- 9. Initialization: Open Chat from URL ---
    const urlParams = new URLSearchParams(window.location.search);
    const urlChatId = urlParams.get('chat_id');
    const sellerName = urlParams.get('seller_name') || 'Chat';

    if (urlChatId) {
        const [userId, currentUserId] = urlChatId.split('_');
        // Slight delay to ensure DOM is ready
        setTimeout(() => createChatWindow(userId, sellerName), 100);
    }
});