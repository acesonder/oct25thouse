/**
 * Notifications JavaScript
 * Real-time notifications functionality
 */

$(document).ready(function() {
    let notificationCheckInterval = null;
    
    // Toggle notifications panel
    $('#notifications-toggle').click(function(e) {
        e.preventDefault();
        $('#notifications-panel').toggleClass('active');
        $('#messenger-panel').removeClass('active');
        
        if ($('#notifications-panel').hasClass('active')) {
            loadNotifications();
        }
    });
    
    $('#close-notifications').click(function() {
        $('#notifications-panel').removeClass('active');
    });
    
    // Load notifications
    function loadNotifications() {
        $.ajax({
            url: '/api/notifications/get_notifications.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayNotifications(response.notifications);
                    updateNotificationBadge(response.unread_count);
                }
            }
        });
    }
    
    // Display notifications
    function displayNotifications(notifications) {
        const $list = $('#notifications-list');
        $list.empty();
        
        if (notifications.length === 0) {
            $list.html('<p style="padding: 20px; text-align: center; color: var(--text-secondary);">No notifications</p>');
            return;
        }
        
        notifications.forEach(notif => {
            const html = `
                <div class="notification-item ${notif.is_read ? '' : 'unread'}" data-id="${notif.id}" data-link="${notif.link || ''}">
                    <div class="notification-title">${notif.title}</div>
                    <div class="notification-message">${notif.message}</div>
                    <div class="notification-time">${timeAgo(notif.created_at)}</div>
                </div>
            `;
            
            $list.append(html);
        });
    }
    
    // Click on notification
    $(document).on('click', '.notification-item', function() {
        const notifId = $(this).data('id');
        const link = $(this).data('link');
        
        // Mark as read
        $.ajax({
            url: '/api/notifications/mark_read.php',
            type: 'POST',
            data: { notification_id: notifId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    loadNotifications();
                    
                    // Navigate to link if exists
                    if (link) {
                        window.location.href = link;
                    }
                }
            }
        });
    });
    
    // Update notification badge
    function updateNotificationBadge(count) {
        const $badge = $('#notifications-toggle .badge');
        
        if (count > 0) {
            if ($badge.length) {
                $badge.text(count);
            } else {
                $('#notifications-toggle').append(`<span class="badge">${count}</span>`);
            }
        } else {
            $badge.remove();
        }
    }
    
    // Update message badge
    function updateMessageBadge(count) {
        const $badge = $('#messenger-toggle .badge');
        
        if (count > 0) {
            if ($badge.length) {
                $badge.text(count);
            } else {
                $('#messenger-toggle').append(`<span class="badge">${count}</span>`);
            }
        } else {
            $badge.remove();
        }
    }
    
    // Check for new notifications and messages periodically
    function checkForUpdates() {
        $.ajax({
            url: '/api/notifications/check_updates.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const prevUnreadNotif = parseInt($('#notifications-toggle .badge').text()) || 0;
                    const prevUnreadMsg = parseInt($('#messenger-toggle .badge').text()) || 0;
                    
                    updateNotificationBadge(response.unread_notifications);
                    updateMessageBadge(response.unread_messages);
                    
                    // Play sound if new notification or message
                    if ((response.unread_notifications > prevUnreadNotif) || 
                        (response.unread_messages > prevUnreadMsg)) {
                        playNotificationSound();
                        
                        // Show browser notification if permitted
                        if (response.unread_notifications > prevUnreadNotif && response.latest_notification) {
                            showBrowserNotification(
                                response.latest_notification.title,
                                response.latest_notification.message
                            );
                        }
                    }
                }
            }
        });
    }
    
    // Play notification sound
    function playNotificationSound() {
        const audio = document.getElementById('notification-sound');
        if (audio) {
            audio.play().catch(e => {
                // Autoplay prevented
                console.log('Notification sound blocked');
            });
        }
    }
    
    // Show browser notification
    function showBrowserNotification(title, message) {
        if (!('Notification' in window)) {
            return;
        }
        
        if (Notification.permission === 'granted') {
            new Notification(title, {
                body: message,
                icon: '/assets/images/logo.png',
                badge: '/assets/images/badge.png'
            });
        } else if (Notification.permission !== 'denied') {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    new Notification(title, {
                        body: message,
                        icon: '/assets/images/logo.png'
                    });
                }
            });
        }
    }
    
    // Request notification permission
    function requestNotificationPermission() {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }
    
    // Start checking for updates
    if ($('body').data('logged-in') !== false) {
        checkForUpdates();
        notificationCheckInterval = setInterval(checkForUpdates, 10000); // Check every 10 seconds
        
        // Request notification permission after 3 seconds
        setTimeout(requestNotificationPermission, 3000);
    }
});
