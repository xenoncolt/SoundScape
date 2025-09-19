<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class EmailService {
    private $mailer;
    private $config;
    private $enabled;
    
    public function __construct() {
        $this->loadConfig();
        $this->enabled = $this->config['smtp']['enabled'] ?? false;
        
        if ($this->enabled) {
            $this->setupMailer();
        }
    }
    
    private function loadConfig(): void {
        $configFile = __DIR__ . '/../../config/app.json';
        
        if (file_exists($configFile)) {
            $this->config = json_decode(file_get_contents($configFile), true);
        } else {
            // Fallback to environment variables if config file doesn't exist yet
            $this->config = [
                'smtp' => [
                    'enabled' => $_ENV['SMTP_ENABLED'] ?? false,
                    'host' => $_ENV['SMTP_HOST'] ?? '',
                    'port' => intval($_ENV['SMTP_PORT'] ?? 587),
                    'username' => $_ENV['SMTP_USERNAME'] ?? '',
                    'password' => $_ENV['SMTP_PASSWORD'] ?? '',
                    'encryption' => $_ENV['SMTP_ENCRYPTION'] ?? 'tls',
                    'from_email' => $_ENV['SMTP_FROM_EMAIL'] ?? '',
                    'from_name' => $_ENV['SMTP_FROM_NAME'] ?? 'SoundScape'
                ],
                'server' => [
                    'name' => $_ENV['SERVER_NAME'] ?? 'SoundScape Server',
                    'require_email_verification' => $_ENV['REQUIRE_EMAIL_VERIFICATION'] ?? false
                ]
            ];
        }
    }
    
    private function setupMailer(): void {
        $this->mailer = new PHPMailer(true);
        
        try {
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->config['smtp']['host'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $this->config['smtp']['username'];
            $this->mailer->Password = $this->config['smtp']['password'];
            $this->mailer->Port = $this->config['smtp']['port'];
            
            // Set encryption
            if ($this->config['smtp']['encryption'] === 'ssl') {
                $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            
            // Default sender
            $this->mailer->setFrom(
                $this->config['smtp']['from_email'], 
                $this->config['smtp']['from_name']
            );
            
            // Enable verbose debug output for development
            if ($_ENV['APP_ENV'] === 'development') {
                $this->mailer->SMTPDebug = SMTP::DEBUG_OFF; // Change to DEBUG_SERVER for debugging
                $this->mailer->Debugoutput = 'html';
            }
            
        } catch (PHPMailerException $e) {
            error_log("EmailService setup failed: " . $e->getMessage());
            $this->enabled = false;
        }
    }
    
    public function isEnabled(): bool {
        return $this->enabled;
    }
    
    public function sendVerificationEmail(string $email, string $username, string $verificationCode): bool {
        if (!$this->enabled) {
            return false;
        }
        
        try {
            // Recipients
            $this->mailer->addAddress($email, $username);
            
            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Verify Your Email Address - ' . $this->config['server']['name'];
            
            $verificationUrl = $this->generateVerificationUrl($verificationCode);
            $serverName = $this->config['server']['name'];
            
            $this->mailer->Body = $this->getVerificationEmailTemplate([
                'username' => $username,
                'server_name' => $serverName,
                'verification_code' => $verificationCode,
                'verification_url' => $verificationUrl,
                'expiry_hours' => 24
            ]);
            
            $this->mailer->AltBody = $this->getVerificationEmailTextVersion([
                'username' => $username,
                'server_name' => $serverName,
                'verification_code' => $verificationCode,
                'verification_url' => $verificationUrl,
                'expiry_hours' => 24
            ]);
            
            $result = $this->mailer->send();
            
            // Clear addresses for next use
            $this->mailer->clearAddresses();
            
            return $result;
            
        } catch (PHPMailerException $e) {
            error_log("Failed to send verification email to {$email}: " . $e->getMessage());
            
            // Clear addresses even on failure
            $this->mailer->clearAddresses();
            
            return false;
        }
    }
    
    public function sendPasswordResetEmail(string $email, string $username, string $resetToken): bool {
        if (!$this->enabled) {
            return false;
        }
        
        try {
            // Recipients
            $this->mailer->addAddress($email, $username);
            
            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Password Reset Request - ' . $this->config['server']['name'];
            
            $resetUrl = $this->generatePasswordResetUrl($resetToken);
            $serverName = $this->config['server']['name'];
            
            $this->mailer->Body = $this->getPasswordResetEmailTemplate([
                'username' => $username,
                'server_name' => $serverName,
                'reset_url' => $resetUrl,
                'expiry_hours' => 2
            ]);
            
            $this->mailer->AltBody = $this->getPasswordResetEmailTextVersion([
                'username' => $username,
                'server_name' => $serverName,
                'reset_url' => $resetUrl,
                'expiry_hours' => 2
            ]);
            
            $result = $this->mailer->send();
            
            // Clear addresses for next use
            $this->mailer->clearAddresses();
            
            return $result;
            
        } catch (PHPMailerException $e) {
            error_log("Failed to send password reset email to {$email}: " . $e->getMessage());
            
            // Clear addresses even on failure
            $this->mailer->clearAddresses();
            
            return false;
        }
    }
    
    private function generateVerificationUrl(string $code): string {
        $baseUrl = $this->getBaseUrl();
        return $baseUrl . '?page=verify-email&code=' . urlencode($code);
    }
    
    private function generatePasswordResetUrl(string $token): string {
        $baseUrl = $this->getBaseUrl();
        return $baseUrl . '?page=reset-password&token=' . urlencode($token);
    }
    
    private function getBaseUrl(): string {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $path = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        
        return $protocol . '://' . $host . $path;
    }
    
    private function getVerificationEmailTemplate(array $data): string {
        return "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Email Verification</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4; }
                .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
                .header { text-align: center; margin-bottom: 30px; }
                .logo { font-size: 24px; font-weight: bold; color: #1DB584; }
                .btn { display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #1DB584, #15A76A); color: white; text-decoration: none; border-radius: 6px; font-weight: bold; margin: 20px 0; }
                .code-box { background: #f8f9fa; padding: 15px; border-radius: 6px; text-align: center; font-family: monospace; font-size: 18px; letter-spacing: 2px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
                .warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 6px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <div class='logo'>🎵 {$data['server_name']}</div>
                    <h1>Welcome to SoundScape!</h1>
                </div>
                
                <p>Hello <strong>{$data['username']}</strong>,</p>
                
                <p>Thank you for registering with <strong>{$data['server_name']}</strong>! To complete your account setup and start enjoying music, please verify your email address.</p>
                
                <div style='text-align: center;'>
                    <a href='{$data['verification_url']}' class='btn'>Verify Email Address</a>
                </div>
                
                <p>If the button above doesn't work, you can also verify your account by entering this verification code:</p>
                
                <div class='code-box'>{$data['verification_code']}</div>
                
                <div class='warning'>
                    <strong>⚠️ Important:</strong> This verification link will expire in {$data['expiry_hours']} hours. If you don't verify your email within this time, you'll need to register again.
                </div>
                
                <p>Once verified, you'll be able to:</p>
                <ul>
                    <li>🎵 Discover and stream music</li>
                    <li>🎶 Create personalized playlists</li>
                    <li>🎤 Request artist privileges to upload your own music</li>
                    <li>👥 Connect with other music lovers</li>
                </ul>
                
                <p>If you didn't create an account with us, you can safely ignore this email.</p>
                
                <div class='footer'>
                    <p>This email was sent by <strong>{$data['server_name']}</strong><br>
                    Need help? Contact your server administrator.</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    private function getVerificationEmailTextVersion(array $data): string {
        return "
Welcome to {$data['server_name']}!

Hello {$data['username']},

Thank you for registering with {$data['server_name']}! To complete your account setup, please verify your email address.

Verification Code: {$data['verification_code']}

Verification Link: {$data['verification_url']}

This verification link will expire in {$data['expiry_hours']} hours.

Once verified, you'll be able to:
- Discover and stream music
- Create personalized playlists  
- Request artist privileges to upload your own music
- Connect with other music lovers

If you didn't create an account with us, you can safely ignore this email.

---
{$data['server_name']}
        ";
    }
    
    private function getPasswordResetEmailTemplate(array $data): string {
        return "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Password Reset</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4; }
                .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
                .header { text-align: center; margin-bottom: 30px; }
                .logo { font-size: 24px; font-weight: bold; color: #1DB584; }
                .btn { display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #DC3545, #B02A37); color: white; text-decoration: none; border-radius: 6px; font-weight: bold; margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
                .warning { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 6px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <div class='logo'>🎵 {$data['server_name']}</div>
                    <h1>Password Reset Request</h1>
                </div>
                
                <p>Hello <strong>{$data['username']}</strong>,</p>
                
                <p>We received a request to reset your password for your account on <strong>{$data['server_name']}</strong>.</p>
                
                <div style='text-align: center;'>
                    <a href='{$data['reset_url']}' class='btn'>Reset Password</a>
                </div>
                
                <div class='warning'>
                    <strong>⚠️ Security Notice:</strong> This password reset link will expire in {$data['expiry_hours']} hours. If you don't reset your password within this time, you'll need to request a new reset link.
                </div>
                
                <p>If you didn't request a password reset, you can safely ignore this email. Your password will not be changed.</p>
                
                <div class='footer'>
                    <p>This email was sent by <strong>{$data['server_name']}</strong><br>
                    Need help? Contact your server administrator.</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    private function getPasswordResetEmailTextVersion(array $data): string {
        return "
Password Reset Request - {$data['server_name']}

Hello {$data['username']},

We received a request to reset your password for your account on {$data['server_name']}.

Reset Link: {$data['reset_url']}

This password reset link will expire in {$data['expiry_hours']} hours.

If you didn't request a password reset, you can safely ignore this email.

---
{$data['server_name']}
        ";
    }
    
    public function generateVerificationCode(): string {
        // Generate a 6-digit verification code
        return str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }
    
    public function generateSecureToken(): string {
        // Generate a secure token for password resets
        return bin2hex(random_bytes(32));
    }
}