<?php
/**
 * Settings Page
 * User customization and preferences
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

Auth::requireLogin();

$db = getDB();
$userId = $_SESSION['user_id'];

// Get current settings
$stmt = $db->prepare("SELECT * FROM user_settings WHERE user_id = ?");
$stmt->execute([$userId]);
$settings = $stmt->fetch();

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare("
        UPDATE user_settings 
        SET theme = ?,
            primary_color = ?,
            secondary_color = ?,
            font_family = ?,
            font_size = ?,
            layout_style = ?,
            enable_animations = ?,
            enable_notifications = ?,
            notification_sound = ?
        WHERE user_id = ?
    ");
    
    $stmt->execute([
        $_POST['theme'],
        $_POST['primary_color'],
        $_POST['secondary_color'],
        $_POST['font_family'],
        $_POST['font_size'],
        $_POST['layout_style'],
        isset($_POST['enable_animations']) ? 1 : 0,
        isset($_POST['enable_notifications']) ? 1 : 0,
        isset($_POST['notification_sound']) ? 1 : 0,
        $userId
    ]);
    
    Auth::logAudit($userId, 'SETTINGS_UPDATED', 'user_settings', $userId);
    
    $success = 'Settings updated successfully!';
    
    // Reload settings
    $stmt = $db->prepare("SELECT * FROM user_settings WHERE user_id = ?");
    $stmt->execute([$userId]);
    $settings = $stmt->fetch();
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>

<div class="container">
    <?php if (isset($success)): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
    </div>
    <?php endif; ?>
    
    <div class="card fade-in">
        <div class="card-header">
            <h1 class="card-title">
                <i class="fas fa-cog"></i> Settings
            </h1>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <!-- Theme Settings -->
                <h3><i class="fas fa-palette"></i> Theme & Appearance</h3>
                
                <div class="form-group">
                    <label class="form-label">Theme</label>
                    <select class="form-control" name="theme" id="theme-select">
                        <option value="light" <?php echo $settings['theme'] === 'light' ? 'selected' : ''; ?>>Light</option>
                        <option value="dark" <?php echo $settings['theme'] === 'dark' ? 'selected' : ''; ?>>Dark</option>
                    </select>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Primary Color</label>
                        <input type="color" class="form-control" name="primary_color" value="<?php echo htmlspecialchars($settings['primary_color']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Secondary Color</label>
                        <input type="color" class="form-control" name="secondary_color" value="<?php echo htmlspecialchars($settings['secondary_color']); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Font Family</label>
                    <select class="form-control" name="font_family">
                        <option value="Arial" <?php echo $settings['font_family'] === 'Arial' ? 'selected' : ''; ?>>Arial</option>
                        <option value="Helvetica" <?php echo $settings['font_family'] === 'Helvetica' ? 'selected' : ''; ?>>Helvetica</option>
                        <option value="Verdana" <?php echo $settings['font_family'] === 'Verdana' ? 'selected' : ''; ?>>Verdana</option>
                        <option value="Georgia" <?php echo $settings['font_family'] === 'Georgia' ? 'selected' : ''; ?>>Georgia</option>
                        <option value="Times New Roman" <?php echo $settings['font_family'] === 'Times New Roman' ? 'selected' : ''; ?>>Times New Roman</option>
                        <option value="Courier New" <?php echo $settings['font_family'] === 'Courier New' ? 'selected' : ''; ?>>Courier New</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Font Size: <span id="font-size-value"><?php echo $settings['font_size']; ?>px</span></label>
                    <input type="range" class="form-control" name="font_size" min="12" max="20" value="<?php echo $settings['font_size']; ?>" id="font-size-slider">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Layout Style</label>
                    <select class="form-control" name="layout_style">
                        <option value="default" <?php echo $settings['layout_style'] === 'default' ? 'selected' : ''; ?>>Default</option>
                        <option value="compact" <?php echo $settings['layout_style'] === 'compact' ? 'selected' : ''; ?>>Compact</option>
                        <option value="wide" <?php echo $settings['layout_style'] === 'wide' ? 'selected' : ''; ?>>Wide</option>
                        <option value="full" <?php echo $settings['layout_style'] === 'full' ? 'selected' : ''; ?>>Full Width</option>
                    </select>
                </div>
                
                <hr>
                
                <!-- Behavior Settings -->
                <h3><i class="fas fa-sliders-h"></i> Behavior</h3>
                
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="enable_animations" name="enable_animations" <?php echo $settings['enable_animations'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="enable_animations">
                        Enable Animations & Transitions
                    </label>
                </div>
                
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="enable_notifications" name="enable_notifications" <?php echo $settings['enable_notifications'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="enable_notifications">
                        Enable Browser Notifications
                    </label>
                </div>
                
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="notification_sound" name="notification_sound" <?php echo $settings['notification_sound'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="notification_sound">
                        Enable Notification Sounds
                    </label>
                </div>
                
                <hr>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Settings
                </button>
                
                <a href="<?php echo Auth::getRedirectByRole($_SESSION['role']); ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </form>
        </div>
    </div>
</div>

<script>
// Theme preview
$('#theme-select').change(function() {
    $('html').attr('data-theme', $(this).val());
});

// Font size slider
$('#font-size-slider').on('input', function() {
    $('#font-size-value').text($(this).val() + 'px');
});

$('body').attr('data-logged-in', 'true');
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
