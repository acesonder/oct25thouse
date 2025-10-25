/**
 * Messenger JavaScript
 * Real-time messaging functionality
 */

$(document).ready(function() {
    let currentChatUserId = null;
    let messageCheckInterval = null;
    
    // Toggle messenger panel
    $('#messenger-toggle').click(function(e) {
        e.preventDefault();
        $('#messenger-panel').toggleClass('active');
        $('#notifications-panel').removeClass('active');
        
        if ($('#messenger-panel').hasClass('active')) {
            loadConversations();
        }
    });
    
    $('#close-messenger').click(function() {
        $('#messenger-panel').removeClass('active');
    });
    
    // Load conversations
    function loadConversations() {
        $.ajax({
            url: '/api/messenger/get_conversations.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayConversations(response.conversations);
                }
            }
        });
    }
    
    // Display conversations
    function displayConversations(conversations) {
        const $list = $('#conversations-list');
        $list.empty();
        
        if (conversations.length === 0) {
            $list.html('<p style="padding: 20px; text-align: center; color: var(--text-secondary);">No conversations yet</p>');
            return;
        }
        
        conversations.forEach(conv => {
            const initials = conv.name.split(' ').map(n => n[0]).join('');
            const unreadBadge = conv.unread_count > 0 ? 
                `<span class="unread-badge">${conv.unread_count}</span>` : '';
            
            const html = `
                <div class="conversation-item" data-user-id="${conv.user_id}">
                    <div class="conversation-avatar">${initials}</div>
                    <div class="conversation-info">
                        <div class="conversation-name">${conv.name}</div>
                        <div class="conversation-preview">${conv.last_message || 'No messages yet'}</div>
                    </div>
                    <div class="conversation-meta">
                        <div class="conversation-time">${timeAgo(conv.last_message_time)}</div>
                        ${unreadBadge}
                    </div>
                </div>
            `;
            
            $list.append(html);
        });
    }
    
    // Click on conversation
    $(document).on('click', '.conversation-item', function() {
        const userId = $(this).data('user-id');
        openChat(userId);
    });
    
    // Search user
    $('#search-user-btn').click(function() {
        const userID = $('#search-user').val().trim();
        
        if (!userID) {
            showAlert('Please enter a User ID', 'warning');
            return;
        }
        
        $.ajax({
            url: '/api/messenger/search_user.php',
            type: 'POST',
            data: { user_id: userID },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    openChat(response.user.id);
                    $('#search-user').val('');
                } else {
                    showAlert(response.message, 'danger');
                }
            }
        });
    });
    
    // Open chat window
    function openChat(userId) {
        currentChatUserId = userId;
        
        $.ajax({
            url: '/api/messenger/get_user.php',
            type: 'GET',
            data: { user_id: userId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#chat-user-name').text(response.user.name);
                    $('.messenger-conversations').hide();
                    $('#chat-window').show();
                    
                    loadMessages(userId);
                    
                    // Start polling for new messages
                    if (messageCheckInterval) {
                        clearInterval(messageCheckInterval);
                    }
                    messageCheckInterval = setInterval(() => loadMessages(userId), 3000);
                }
            }
        });
    }
    
    // Load messages
    function loadMessages(userId) {
        $.ajax({
            url: '/api/messenger/get_messages.php',
            type: 'GET',
            data: { user_id: userId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayMessages(response.messages);
                }
            }
        });
    }
    
    // Display messages
    function displayMessages(messages) {
        const $chatMessages = $('#chat-messages');
        const wasAtBottom = $chatMessages[0].scrollHeight - $chatMessages.scrollTop() === $chatMessages.outerHeight();
        
        $chatMessages.empty();
        
        messages.forEach(msg => {
            const isSent = msg.is_sent;
            const initials = msg.sender_name.split(' ').map(n => n[0]).join('');
            
            let content = '';
            
            if (msg.message_type === 'text') {
                content = `<div class="message-bubble">${escapeHtml(msg.message)}</div>`;
            } else if (msg.message_type === 'image') {
                content = `
                    <div class="message-bubble">
                        <img src="${msg.file_path}" alt="Image" class="message-image">
                    </div>
                `;
            } else if (msg.message_type === 'video') {
                content = `
                    <div class="message-bubble">
                        <video src="${msg.file_path}" controls class="message-video"></video>
                    </div>
                `;
            } else if (msg.message_type === 'location') {
                content = `
                    <div class="message-bubble">
                        <div class="message-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <div>Location Shared</div>
                                <small>Lat: ${msg.location_lat}, Lng: ${msg.location_lng}</small>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            const html = `
                <div class="message ${isSent ? 'sent' : 'received'}">
                    <div class="message-avatar">${initials}</div>
                    <div class="message-content">
                        ${content}
                        <div class="message-time">${timeAgo(msg.created_at)}</div>
                    </div>
                </div>
            `;
            
            $chatMessages.append(html);
        });
        
        // Scroll to bottom if was at bottom or if new message
        if (wasAtBottom || messages.length > 0) {
            $chatMessages.scrollTop($chatMessages[0].scrollHeight);
        }
    }
    
    // Send message
    $('#send-message').click(function() {
        sendMessage();
    });
    
    $('#message-input').keypress(function(e) {
        if (e.which === 13) {
            sendMessage();
        }
    });
    
    function sendMessage() {
        const message = $('#message-input').val().trim();
        
        if (!message || !currentChatUserId) {
            return;
        }
        
        $.ajax({
            url: '/api/messenger/send_message.php',
            type: 'POST',
            data: {
                receiver_id: currentChatUserId,
                message: message,
                message_type: 'text'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#message-input').val('');
                    loadMessages(currentChatUserId);
                } else {
                    showAlert(response.message, 'danger');
                }
            }
        });
    }
    
    // Back to conversations
    $('#back-to-conversations').click(function() {
        $('#chat-window').hide();
        $('.messenger-conversations').show();
        currentChatUserId = null;
        
        if (messageCheckInterval) {
            clearInterval(messageCheckInterval);
        }
        
        loadConversations();
    });
    
    // Attach file
    $('#attach-file').click(function() {
        const input = $('<input type="file" accept="image/*,video/*">');
        
        input.on('change', function() {
            const file = this.files[0];
            
            if (!file) return;
            
            const formData = new FormData();
            formData.append('file', file);
            formData.append('receiver_id', currentChatUserId);
            formData.append('message_type', file.type.startsWith('image/') ? 'image' : 'video');
            
            showLoading();
            
            $.ajax({
                url: '/api/messenger/send_message.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    hideLoading();
                    
                    if (response.success) {
                        loadMessages(currentChatUserId);
                    } else {
                        showAlert(response.message, 'danger');
                    }
                },
                error: function() {
                    hideLoading();
                    showAlert('Failed to upload file', 'danger');
                }
            });
        });
        
        input.click();
    });
    
    // Share location
    $('#share-location').click(function() {
        if (!navigator.geolocation) {
            showAlert('Geolocation is not supported by your browser', 'warning');
            return;
        }
        
        showLoading();
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                $.ajax({
                    url: '/api/messenger/send_message.php',
                    type: 'POST',
                    data: {
                        receiver_id: currentChatUserId,
                        message: 'Shared location',
                        message_type: 'location',
                        location_lat: position.coords.latitude,
                        location_lng: position.coords.longitude
                    },
                    dataType: 'json',
                    success: function(response) {
                        hideLoading();
                        
                        if (response.success) {
                            loadMessages(currentChatUserId);
                        } else {
                            showAlert(response.message, 'danger');
                        }
                    },
                    error: function() {
                        hideLoading();
                        showAlert('Failed to share location', 'danger');
                    }
                });
            },
            function() {
                hideLoading();
                showAlert('Unable to retrieve your location', 'danger');
            }
        );
    });
    
    // Helper function to escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
