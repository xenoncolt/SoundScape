<?php
$currentUser = getCurrentUser();
$isLoggedIn = isLoggedIn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScape</title>

    <link rel="preload" href="assets/css/styles.css" as="style">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" as="style">
    <link href="assets/css/styles.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
</head>
<body class="bg-cus-dark text-white">
    <!--Navigation Bar-->
    <nav class="bg-black bg-opacity-50 backdrop-blur-sm fixed w-full z-50 border-b border-gray-800">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center">
                        <img src="/assets/images/SOUNDSCAPE.svg" alt="Logo">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white">SoundScape</h1>
                        <p class="text-xs text-cus-gray hidden sm:block">Free listen every music</p>
                    </div>
                </div>
                
                <div class="hidden md:flex items-center space-x-6">
                    <?php if ($isLoggedIn): ?>
                        <a href="?page=browse" class="nav-item">Browse Music</a>
                    <?php endif; ?>
                </div>
                
                <div class="flex items-center space-x-3">
                    <?php if ($isLoggedIn): ?>
                        <div class="relative group">
                            <button class="flex items-center space-x-2 nav-item">
                                <?php if ($currentUser['profile_image']): ?>
                                    <img src="<?= htmlspecialchars($currentUser['profile_image']) ?>" 
                                         alt="Profile" 
                                         class="w-8 h-8 rounded-full object-cover">
                                <?php else: ?>
                                    <div class="w-8 h-8 bg-cus-primary rounded-full flex items-center justify-center">
                                        <span class="text-sm font-bold">
                                            <?= strtoupper(substr($currentUser['display_name'] ?? $currentUser['username'] ?? 'U', 0, 1)) ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <span class="hidden sm:inline"><?= htmlspecialchars($currentUser['display_name'] ?? $currentUser['username'] ?? 'User') ?></span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <div class="py-2">
                                    <a href="?page=dashboard" class="block px-4 py-2 text-sm hover:bg-gray-700">
                                        Dashboard
                                    </a>
                                    <a href="?page=profile" class="block px-4 py-2 text-sm hover:bg-gray-700">
                                        Profile
                                    </a>
                                    <?php if ($currentUser['user_type'] === 'admin'): ?>
                                        <a href="?page=admin" class="block px-4 py-2 text-sm hover:bg-gray-700">
                                            Admin Panel
                                        </a>
                                    <?php endif; ?>
                                    <hr class="my-1 border-gray-700">
                                    <a href="?page=logout" class="block px-4 py-2 text-sm hover:bg-gray-700 text-red-400">
                                        Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="?page=login" class="nav-item">Sign In</a>
                        <a href="?page=register" class="btn-primary">Get Started</a>
                    <?php endif; ?>
                    
                    <!--mobile view-->
                    <button class="md:hidden p-2" onclick="toggleMobileMenu()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div id="mobile-menu" class="md:hidden border-t border-gray-800 hidden">
                <div class="py-4 space-y-2">
                    <?php if ($isLoggedIn): ?>
                        <a href="?page=browse" class="block py-2 nav-item" onclick="closeMobileMenu()">Browse Music</a>
                        <a href="?page=dashboard" class="block py-2 nav-item" onclick="closeMobileMenu()">Dashboard</a>
                    <?php else: ?>
                        <a href="?page=login" class="block py-2 nav-item" onclick="closeMobileMenu()">Sign In</a>
                        <a href="?page=register" class="block py-2 nav-item" onclick="closeMobileMenu()">Get Started</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    
    <section class="pt-20 pb-16 px-4 bg-gradient-to-br from-cus-dark via-gray-900 to-black relative overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-cus-primary rounded-full mix-blend-multiply filter blur-xl animate-pulse-slow"></div>
            <div class="absolute top-1/3 right-1/4 w-64 h-64 bg-green-400 rounded-full mix-blend-multiply filter blur-xl animate-pulse-slow animation-delay-2000"></div>
            <div class="absolute bottom-1/4 left-1/3 w-64 h-64 bg-cus-primary rounded-full mix-blend-multiply filter blur-xl animate-pulse-slow animation-delay-4000"></div>
        </div>
        
        <div class="container mx-auto text-center relative">
            <div class="max-w-4xl mx-auto">
                
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 text-shadow-lg animate-fade-in">
                    Listen Music, 
                    <span class="text-cus-primary bg-gradient-cus bg-clip-text text-transparent">
                        You Like
                    </span>
                </h1>
                
                <p class="text-xl md:text-2xl text-cus-light-gray mb-8 leading-relaxed animate-slide-up max-w-3xl mx-auto">
                    Upload your tracks, create playlists and listen free music from anywhere you want.
                </p>
                

                <?php if ($isLoggedIn): ?>
                    <div class="flex justify-center space-x-8 mb-8 animate-slide-up">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-cus-primary">12.5K</div>
                            <div class="text-sm text-cus-gray">Songs</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-cus-primary">1.2K</div>
                            <div class="text-sm text-cus-gray">Artists</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-cus-primary">450</div>
                            <div class="text-sm text-cus-gray">Playlists</div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12 animate-slide-up">
                    <?php if (!$isLoggedIn): ?>
                        <a href="?page=register" 
                           class="btn-primary text-lg px-8 py-4 shadow-cus hover:shadow-cus-lg">
                            Ready to listen?
                        </a>
                        <a href="#features" 
                           class="btn-outline text-lg px-8 py-4">
                            Learn More
                        </a>
                    <?php else: ?>
                        <a href="?page=dashboard" 
                           class="btn-primary text-lg px-8 py-4 shadow-cus hover:shadow-cus-lg">
                            Go to Dashboard
                        </a>
                        <a href="?page=browse" 
                           class="btn-outline text-lg px-8 py-4">
                            Browse Music
                        </a>
                    <?php endif; ?>
                </div>
                
                <!-- need to change -->
                <div class="relative max-w-4xl mx-auto animate-slide-up">
                    <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden">
                        <div class="aspect-video bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-20 h-20 bg-cus-primary rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                                <p class="text-cus-gray">Demo video coming soon</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if (!$isLoggedIn): ?>
    <section class="py-20 px-4 bg-gradient-to-br from-gray-900 via-black to-gray-600 relative overflow-hidden">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6 text-white">
                Ready to join free music platform?
            </h2>
            <p class="text-xl text-green-100 mb-8 max-w-2xl mx-auto">
                Join thousands of music lovers who have reclaimed their listening experience with SoundScape.
            </p>
            <a href="?page=register" 
               class="inline-block bg-black text-cus-primary font-bold py-4 px-8 rounded-lg text-lg hover:bg-green-400 transition-colors shadow-lg">
                Get Started Free
            </a>
        </div>
    </section>
    <?php endif; ?>
    
    <footer class="bg-black py-12 px-4">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center">
                            <img src="/assets/images/SOUNDSCAPE.svg" alt="Logo">
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">SoundScape</h3>
                            <p class="text-sm text-cus-gray">Free listen every music</p>
                        </div>
                    </div>
                    <p class="text-cus-gray mb-4">
                        Self-hosted music streaming server. Make every music accessible to you.
                    </p>
                    <div class="flex space-x-4">
                        <a href="https://github.com/xenoncolt/SoundScape" target="_blank" class="text-cus-gray hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.374 0 0 5.373 0 12 0 17.302 3.438 21.8 8.207 23.387c.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.300 24 12c0-6.627-5.373-12-12-12z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="https://github.com/xenoncolt/SoundScape" class="text-cus-gray hover:text-white transition-colors">Github</a></li>
                        <li><a href="https://discord.gg/xr6NpHfCFz" class="text-cus-gray hover:text-white transition-colors">Discord Server</a></li>
                        <?php if ($isLoggedIn): ?>
                            <li><a href="?page=dashboard" class="text-cus-gray hover:text-white transition-colors">Dashboard</a></li>
                            <li><a href="?page=browse" class="text-cus-gray hover:text-white transition-colors">Browse</a></li>
                        <?php else: ?>
                            <li><a href="?page=register" class="text-cus-gray hover:text-white transition-colors">Get Started</a></li>
                            <li><a href="?page=login" class="text-cus-gray hover:text-white transition-colors">Sign In</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <!-- Support Column -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="https://github.com/xenoncolt/SoundScape/wiki" target="_blank" class="text-cus-gray hover:text-white transition-colors">Documentation</a></li>
                        <li><a href="https://github.com/xenoncolt/SoundScape/discussions" target="_blank" class="text-cus-gray hover:text-white transition-colors">Community</a></li>
                        <li><a href="https://github.com/xenoncolt/SoundScape/issues" target="_blank" class="text-cus-gray hover:text-white transition-colors">Report Bug</a></li>
                        <li><a href="mailto:contact@xenoncolt.live" class="text-cus-gray hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
            </div>
            
            <hr class="border-gray-800 my-8">
            
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-cus-gray text-sm">
                    &copy; 2025 SoundScape.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-cus-gray hover:text-white text-sm transition-colors">Privacy Policy</a>
                    <a href="#" class="text-cus-gray hover:text-white text-sm transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
    
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
        
        function closeMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.add('hidden');
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        const offsetTop = target.offsetTop - 80; 
                        window.scrollTo({
                            top: offsetTop,
                            behavior: 'smooth'
                        });
                        closeMobileMenu(); 
                    }
                });
            });
            
            const navbar = document.querySelector('nav');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('bg-opacity-90');
                } else {
                    navbar.classList.remove('bg-opacity-90');
                }
            });
            
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-slide-up');
                    }
                });
            }, observerOptions);
            
            document.querySelectorAll('.card').forEach(card => {
                observer.observe(card);
            });
        });
        
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                // TODO: Open search modal i will do later if got time
                console.log('Search shortcut pressed');
            }
            
            if (e.key === 'Escape') {
                closeMobileMenu();
            }
        });
    </script>
</body>
</html>