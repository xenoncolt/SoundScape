<?php

namespace App\Controllers;

use App\Database\Connection;
use PDO;
use Exception;

class UserProfileController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Connection::getInstance()->getConnection();
    }

    /**
     * Handle profile-related actions
     */
    public function handle(string $action): void
    {
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Please log in to continue';
            redirect('?page=login');
            return;
        }

        switch ($action) {
            case 'update-profile':
                $this->updateProfile();
                break;
            case 'update-password':
                $this->updatePassword();
                break;
            case 'upload-avatar':
                $this->uploadAvatar();
                break;
            case 'delete-avatar':
                $this->deleteAvatar();
                break;
            default:
                $_SESSION['error'] = 'Invalid action';
                redirect('?page=profile');
        }
    }

    /**
     * Get current user's complete profile
     */
    public function getCurrentUserProfile(): ?array
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        $stmt = $this->pdo->prepare("
            SELECT id, username, email, user_type, status, display_name, 
                   profile_image, bio, is_private, email_verified,
                   discord_id, discord_username, created_at, updated_at, last_login_at
            FROM users 
            WHERE id = ?
        ");
        
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $user ?: null;
    }

    /**
     * Update user profile information (not password or sensitive data)
     */
    private function updateProfile(): void
    {
        $displayName = trim($_POST['display_name'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $isPrivate = isset($_POST['is_private']);
        
        $errors = [];
        
        // Validation
        if (empty($displayName)) {
            $errors[] = 'Display name is required';
        } elseif (strlen($displayName) > 100) {
            $errors[] = 'Display name must be 100 characters or less';
        }
        
        if (strlen($bio) > 500) {
            $errors[] = 'Bio must be 500 characters or less';
        }
        
        if (!empty($errors)) {
            $_SESSION['profile_errors'] = $errors;
            redirect('?page=profile');
            return;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                UPDATE users 
                SET display_name = ?, bio = ?, is_private = ?, updated_at = NOW()
                WHERE id = ?
            ");
            
            if ($stmt->execute([$displayName, $bio, $isPrivate, $_SESSION['user_id']])) {
                // Update session data
                $_SESSION['display_name'] = $displayName;
                $_SESSION['success'] = 'Profile updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update profile';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        }
        
        redirect('?page=profile');
    }

    /**
     * Update user password
     */
    private function updatePassword(): void
    {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        $errors = [];
        
        // Validation
        if (empty($currentPassword)) {
            $errors[] = 'Current password is required';
        }
        
        if (empty($newPassword)) {
            $errors[] = 'New password is required';
        } elseif (strlen($newPassword) < 6) {
            $errors[] = 'New password must be at least 6 characters';
        }
        
        if ($newPassword !== $confirmPassword) {
            $errors[] = 'New passwords do not match';
        }
        
        if (!empty($errors)) {
            $_SESSION['password_errors'] = $errors;
            redirect('?page=profile');
            return;
        }
        
        try {
            // Verify current password
            $stmt = $this->pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $currentHash = $stmt->fetchColumn();
            
            if (!password_verify($currentPassword, $currentHash)) {
                $_SESSION['password_errors'] = ['Current password is incorrect'];
                redirect('?page=profile');
                return;
            }
            
            // Update password
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("
                UPDATE users 
                SET password_hash = ?, updated_at = NOW()
                WHERE id = ?
            ");
            
            if ($stmt->execute([$newHash, $_SESSION['user_id']])) {
                $_SESSION['success'] = 'Password updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update password';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        }
        
        redirect('?page=profile');
    }

    /**
     * Upload avatar image (placeholder - in real app you'd handle file uploads)
     */
    private function uploadAvatar(): void
    {
        // For now, this is a placeholder
        // In a real implementation, you'd handle file uploads, image processing, etc.
        $_SESSION['info'] = 'Avatar upload functionality will be implemented in the next phase';
        redirect('?page=profile');
    }

    /**
     * Delete user's avatar
     */
    private function deleteAvatar(): void
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE users 
                SET profile_image = NULL, updated_at = NOW()
                WHERE id = ?
            ");
            
            if ($stmt->execute([$_SESSION['user_id']])) {
                $_SESSION['pfp_img'] = null; // Update session
                $_SESSION['success'] = 'Avatar removed successfully';
            } else {
                $_SESSION['error'] = 'Failed to remove avatar';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        }
        
        redirect('?page=profile');
    }

    /**
     * Get user's activity stats
     */
    public function getUserStats(int $userId): array
    {
        $stats = [];
        
        try {
            // Get songs uploaded (for artists)
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as total_songs,
                       SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_songs,
                       SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_songs
                FROM songs 
                WHERE artist_id = ?
            ");
            $stmt->execute([$userId]);
            $musicStats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Get total plays (placeholder for now)
            $stats['total_songs'] = (int)$musicStats['total_songs'];
            $stats['approved_songs'] = (int)$musicStats['approved_songs'];
            $stats['pending_songs'] = (int)$musicStats['pending_songs'];
            $stats['total_plays'] = 0; // Placeholder
            $stats['total_likes'] = 0; // Placeholder
            
        } catch (Exception $e) {
            $stats = [
                'total_songs' => 0,
                'approved_songs' => 0,
                'pending_songs' => 0,
                'total_plays' => 0,
                'total_likes' => 0
            ];
        }
        
        return $stats;
    }
}