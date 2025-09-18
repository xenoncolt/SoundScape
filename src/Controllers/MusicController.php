<?php

namespace App\Controllers;

use App\Database\Connection;
use PDO;
use Exception;

class MusicController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Connection::getInstance()->getConnection();
    }

    /**
     * Handle music-related actions
     */
    public function handle(string $action): void
    {
        switch ($action) {
            case 'approve':
                $this->approveSong();
                break;
            case 'reject':
                $this->rejectSong();
                break;
            case 'upload':
                $this->uploadSong();
                break;
            case 'delete':
                $this->deleteSong();
                break;
            default:
                $_SESSION['error'] = 'Invalid action';
                redirect('?page=dashboard');
        }
    }

    /**
     * Get pending songs awaiting approval
     */
    public function getPendingSongs(): array
    {
        $sql = "
            SELECT s.*, u.username as artist_name, u.display_name as artist_display_name
            FROM songs s
            JOIN users u ON s.artist_id = u.id
            WHERE s.status = 'pending'
            ORDER BY s.upload_date ASC
        ";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all songs with filtering options
     */
    public function getAllSongs(array $filters = []): array
    {
        $sql = "
            SELECT s.*, u.username as artist_name, u.display_name as artist_display_name,
                   approver.username as approved_by_username
            FROM songs s
            JOIN users u ON s.artist_id = u.id
            LEFT JOIN users approver ON s.approved_by = approver.id
            WHERE 1=1
        ";
        
        $params = [];
        
        // Apply filters
        if (!empty($filters['status'])) {
            $sql .= " AND s.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['artist_id'])) {
            $sql .= " AND s.artist_id = ?";
            $params[] = $filters['artist_id'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (s.title LIKE ? OR s.album LIKE ? OR s.genre LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql .= " ORDER BY s.upload_date DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get songs by artist (for artist dashboard)
     */
    public function getArtistSongs(int $artistId): array
    {
        $sql = "
            SELECT s.*, approver.username as approved_by_username
            FROM songs s
            LEFT JOIN users approver ON s.approved_by = approver.id
            WHERE s.artist_id = ?
            ORDER BY s.upload_date DESC
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$artistId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get music statistics
     */
    public function getMusicStats(): array
    {
        $stats = [];
        
        // Total songs by status
        $stmt = $this->pdo->query("
            SELECT status, COUNT(*) as count 
            FROM songs 
            GROUP BY status
        ");
        $songStatuses = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // Recent uploads (last 30 days)
        $stmt = $this->pdo->query("
            SELECT COUNT(*) as count 
            FROM songs 
            WHERE upload_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ");
        $recentUploads = $stmt->fetchColumn();
        
        // Top genres
        $stmt = $this->pdo->query("
            SELECT genre, COUNT(*) as count 
            FROM songs 
            WHERE genre IS NOT NULL AND status = 'approved'
            GROUP BY genre 
            ORDER BY count DESC 
            LIMIT 5
        ");
        $topGenres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'total_songs' => array_sum($songStatuses),
            'by_status' => $songStatuses,
            'recent_uploads' => $recentUploads,
            'pending_approvals' => $songStatuses['pending'] ?? 0,
            'top_genres' => $topGenres
        ];
    }

    /**
     * Approve a song
     */
    private function approveSong(): void
    {
        // Ensure admin access
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            $_SESSION['error'] = 'Admin access required';
            redirect('?page=dashboard');
            return;
        }

        $songId = $_POST['song_id'] ?? null;
        
        if (!$songId) {
            $_SESSION['error'] = 'Invalid song ID';
            redirect('?page=music-approvals');
            return;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                UPDATE songs 
                SET status = 'approved', 
                    approved_by = ?, 
                    approved_at = NOW(),
                    is_public = TRUE
                WHERE id = ? AND status = 'pending'
            ");
            
            if ($stmt->execute([$_SESSION['user_id'], $songId]) && $stmt->rowCount() > 0) {
                $_SESSION['success'] = 'Song approved successfully';
            } else {
                $_SESSION['error'] = 'Song not found or already processed';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        }
        
        redirect('?page=music-approvals');
    }

    /**
     * Reject a song
     */
    private function rejectSong(): void
    {
        // Ensure admin access
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            $_SESSION['error'] = 'Admin access required';
            redirect('?page=dashboard');
            return;
        }

        $songId = $_POST['song_id'] ?? null;
        
        if (!$songId) {
            $_SESSION['error'] = 'Invalid song ID';
            redirect('?page=music-approvals');
            return;
        }
        
        try {
            // For now, let's just mark as rejected rather than delete
            // In a real system, you might want to keep the record for audit purposes
            $stmt = $this->pdo->prepare("
                UPDATE songs 
                SET status = 'rejected', 
                    approved_by = ?, 
                    approved_at = NOW()
                WHERE id = ? AND status = 'pending'
            ");
            
            if ($stmt->execute([$_SESSION['user_id'], $songId]) && $stmt->rowCount() > 0) {
                $_SESSION['success'] = 'Song rejected';
            } else {
                $_SESSION['error'] = 'Song not found or already processed';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        }
        
        redirect('?page=music-approvals');
    }

    /**
     * Upload a new song (for artists)
     */
    private function uploadSong(): void
    {
        // Ensure artist or admin access
        if (!isset($_SESSION['user_type']) || !in_array($_SESSION['user_type'], ['artist', 'admin'])) {
            $_SESSION['error'] = 'Artist access required';
            redirect('?page=dashboard');
            return;
        }

        // For now, this is a placeholder for the upload functionality
        // In a real implementation, you'd handle file uploads, metadata extraction, etc.
        $_SESSION['info'] = 'Music upload functionality will be implemented in the next phase';
        redirect('?page=dashboard');
    }

    /**
     * Delete a song (artist can delete own songs, admin can delete any)
     */
    private function deleteSong(): void
    {
        $songId = $_POST['song_id'] ?? null;
        
        if (!$songId) {
            $_SESSION['error'] = 'Invalid song ID';
            redirect('?page=dashboard');
            return;
        }
        
        try {
            // Check ownership or admin privileges
            if ($_SESSION['user_type'] === 'admin') {
                // Admin can delete any song
                $stmt = $this->pdo->prepare("DELETE FROM songs WHERE id = ?");
                $success = $stmt->execute([$songId]) && $stmt->rowCount() > 0;
            } else {
                // Artists can only delete their own songs
                $stmt = $this->pdo->prepare("DELETE FROM songs WHERE id = ? AND artist_id = ?");
                $success = $stmt->execute([$songId, $_SESSION['user_id']]) && $stmt->rowCount() > 0;
            }
            
            if ($success) {
                $_SESSION['success'] = 'Song deleted successfully';
            } else {
                $_SESSION['error'] = 'Song not found or access denied';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        }
        
        redirect('?page=dashboard');
    }

    /**
     * Get song by ID (with permission checking)
     */
    public function getSongById(int $songId, bool $includePrivate = false): ?array
    {
        $sql = "
            SELECT s.*, u.username as artist_name, u.display_name as artist_display_name,
                   approver.username as approved_by_username
            FROM songs s
            JOIN users u ON s.artist_id = u.id
            LEFT JOIN users approver ON s.approved_by = approver.id
            WHERE s.id = ?
        ";
        
        // Add permission filtering unless explicitly including private
        if (!$includePrivate) {
            $sql .= " AND (s.is_public = TRUE OR s.status = 'approved')";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$songId]);
        $song = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $song ?: null;
    }
}