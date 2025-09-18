<?php

namespace App\Controllers;

use App\Database\Connection;
use PDO;
use Exception;

class UserController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Connection::getInstance()->getConnection();
    }

    /**
     * Handle user management actions
     */
    public function handle(string $action): void
    {
        // Ensure admin access
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            $_SESSION['error'] = 'Admin access required';
            redirect('?page=dashboard');
            return;
        }

        switch ($action) {
            case 'update-role':
                $this->updateUserRole();
                break;
            case 'update-status':
                $this->updateUserStatus();
                break;
            case 'delete-user':
                $this->deleteUser();
                break;
            case 'approve-user':
                $this->approveUser();
                break;
            case 'reject-user':
                $this->rejectUser();
                break;
            default:
                $_SESSION['error'] = 'Invalid action';
                redirect('?page=user-management');
        }
    }

    /**
     * Get all users with filtering options
     */
    public function getAllUsers(array $filters = []): array
    {
        $sql = "SELECT id, username, email, user_type, status, display_name, 
                       profile_image, created_at, last_login_at, 
                       discord_id IS NOT NULL as has_discord
                FROM users WHERE 1=1";
        
        $params = [];
        
        // Apply filters
        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['user_type'])) {
            $sql .= " AND user_type = ?";
            $params[] = $filters['user_type'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (username LIKE ? OR email LIKE ? OR display_name LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get user statistics for dashboard
     */
    public function getUserStats(): array
    {
        $stats = [];
        
        // Total users by type
        $stmt = $this->pdo->query("
            SELECT user_type, COUNT(*) as count 
            FROM users 
            GROUP BY user_type
        ");
        $userTypes = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // Total users by status
        $stmt = $this->pdo->query("
            SELECT status, COUNT(*) as count 
            FROM users 
            GROUP BY status
        ");
        $userStatuses = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // Recent registrations (last 30 days)
        $stmt = $this->pdo->query("
            SELECT COUNT(*) as count 
            FROM users 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ");
        $recentRegistrations = $stmt->fetchColumn();
        
        return [
            'total_users' => array_sum($userTypes),
            'by_type' => $userTypes,
            'by_status' => $userStatuses,
            'recent_registrations' => $recentRegistrations,
            'pending_approvals' => $userStatuses['pending'] ?? 0
        ];
    }

    /**
     * Update user role (admin, artist, general)
     */
    private function updateUserRole(): void
    {
        $userId = $_POST['user_id'] ?? null;
        $newRole = $_POST['new_role'] ?? null;
        
        if (!$userId || !in_array($newRole, ['admin', 'artist', 'general'])) {
            $_SESSION['error'] = 'Invalid user ID or role';
            redirect('?page=user-management');
            return;
        }
        
        // Prevent admin from demoting themselves
        if ($userId == $_SESSION['user_id'] && $newRole !== 'admin') {
            $_SESSION['error'] = 'You cannot change your own admin role';
            redirect('?page=user-management');
            return;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                UPDATE users 
                SET user_type = ?, updated_at = NOW() 
                WHERE id = ?
            ");
            
            if ($stmt->execute([$newRole, $userId])) {
                $_SESSION['success'] = 'User role updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update user role';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        }
        
        redirect('?page=user-management');
    }

    /**
     * Update user status (pending, approved, banned, suspended)
     */
    private function updateUserStatus(): void
    {
        $userId = $_POST['user_id'] ?? null;
        $newStatus = $_POST['new_status'] ?? null;
        
        if (!$userId || !in_array($newStatus, ['pending', 'approved', 'banned', 'suspended'])) {
            $_SESSION['error'] = 'Invalid user ID or status';
            redirect('?page=user-management');
            return;
        }
        
        // Prevent admin from banning themselves
        if ($userId == $_SESSION['user_id'] && in_array($newStatus, ['banned', 'suspended'])) {
            $_SESSION['error'] = 'You cannot ban or suspend yourself';
            redirect('?page=user-management');
            return;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                UPDATE users 
                SET status = ?, updated_at = NOW() 
                WHERE id = ?
            ");
            
            if ($stmt->execute([$newStatus, $userId])) {
                $_SESSION['success'] = 'User status updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update user status';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        }
        
        redirect('?page=user-management');
    }

    /**
     * Approve a pending user
     */
    private function approveUser(): void
    {
        $userId = $_POST['user_id'] ?? null;
        
        if (!$userId) {
            $_SESSION['error'] = 'Invalid user ID';
            redirect('?page=user-management');
            return;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                UPDATE users 
                SET status = 'approved', updated_at = NOW() 
                WHERE id = ? AND status = 'pending'
            ");
            
            if ($stmt->execute([$userId]) && $stmt->rowCount() > 0) {
                $_SESSION['success'] = 'User approved successfully';
            } else {
                $_SESSION['error'] = 'User not found or already processed';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        }
        
        redirect('?page=user-management');
    }

    /**
     * Reject a pending user
     */
    private function rejectUser(): void
    {
        $userId = $_POST['user_id'] ?? null;
        
        if (!$userId) {
            $_SESSION['error'] = 'Invalid user ID';
            redirect('?page=user-management');
            return;
        }
        
        try {
            // Instead of deleting, we could set status to 'rejected' 
            // For now, let's delete rejected users
            $stmt = $this->pdo->prepare("
                DELETE FROM users 
                WHERE id = ? AND status = 'pending'
            ");
            
            if ($stmt->execute([$userId]) && $stmt->rowCount() > 0) {
                $_SESSION['success'] = 'User registration rejected and removed';
            } else {
                $_SESSION['error'] = 'User not found or already processed';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        }
        
        redirect('?page=user-management');
    }

    /**
     * Delete a user account (admin only, with restrictions)
     */
    private function deleteUser(): void
    {
        $userId = $_POST['user_id'] ?? null;
        
        if (!$userId) {
            $_SESSION['error'] = 'Invalid user ID';
            redirect('?page=user-management');
            return;
        }
        
        // Prevent admin from deleting themselves
        if ($userId == $_SESSION['user_id']) {
            $_SESSION['error'] = 'You cannot delete your own account';
            redirect('?page=user-management');
            return;
        }
        
        try {
            // Check if user is an admin
            $stmt = $this->pdo->prepare("SELECT user_type FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $userType = $stmt->fetchColumn();
            
            if ($userType === 'admin') {
                $_SESSION['error'] = 'Cannot delete admin accounts';
                redirect('?page=user-management');
                return;
            }
            
            // TODO: In a real app, you'd want to handle related data (songs, playlists, etc.)
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
            
            if ($stmt->execute([$userId]) && $stmt->rowCount() > 0) {
                $_SESSION['success'] = 'User account deleted successfully';
            } else {
                $_SESSION['error'] = 'User not found';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        }
        
        redirect('?page=user-management');
    }

    /**
     * Get user by ID
     */
    public function getUserById(int $userId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, username, email, user_type, status, display_name, 
                   profile_image, bio, is_private, email_verified,
                   discord_id, discord_username, created_at, updated_at, last_login_at
            FROM users 
            WHERE id = ?
        ");
        
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $user ?: null;
    }
}