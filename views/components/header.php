<header class="bg-white shadow-lg sticky top-0 z-50 animate-fade-in">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- LogoPlay4You -->
            <div class="flex items-center">
                <a href="/" class="flex items-center space-x-3 group">
                    <img src="../public/LogoPlay4You.png" alt="Play4You" class="h-16 w-auto group-hover:scale-110 transition-transform duration-200">
                </a>
            </div>

            <!-- Navigation -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="/" class="text-gray-700 hover:text-primary-600 transition-colors duration-200 font-medium">
                    Início
                </a>
                <a href="/catalog" class="text-gray-700 hover:text-primary-600 transition-colors duration-200 font-medium">
                    Catálogo
                </a>
                <a href="/about" class="text-gray-700 hover:text-primary-600 transition-colors duration-200 font-medium">
                    Quem Somos
                </a>
                <a href="/contact" class="text-gray-700 hover:text-primary-600 transition-colors duration-200 font-medium">
                    Contato
                </a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/dashboard" class="text-gray-700 hover:text-primary-600 transition-colors duration-200 font-medium">
                        Dashboard
                    </a>
                    <a href="/chat" class="text-gray-700 hover:text-primary-600 transition-colors duration-200 font-medium">
                        Chat
                    </a>
                <?php endif; ?>
            </nav>

            <!-- User Actions -->
            <div class="flex items-center space-x-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="flex items-center space-x-3">
                        <?php $__avatar = !empty($_SESSION['user_profile_image']) ? $_SESSION['user_profile_image'] : '../public/placeholder-user.jpg'; ?>
                        <a href="/profile" class="block">
                            <img src="<?= htmlspecialchars($__avatar) ?>" alt="Perfil" class="h-10 w-10 rounded-full object-cover border border-gray-200 hover:ring-2 hover:ring-primary-500 transition" />
                        </a>
                        <span class="text-gray-700">Olá, <?= htmlspecialchars($_SESSION['user_name'] ?? $_SESSION['name'] ?? 'Usuário') ?></span>
                        <a href="/logout" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            Sair
                        </a>
                    </div>
                <?php else: ?>
                    <a href="/login" class="text-primary-600 hover:text-primary-700 font-medium transition-colors duration-200">
                        Entrar
                    </a>
                    <a href="/register" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition-all duration-200 hover:scale-105">
                        Cadastrar
                    </a>
                <?php endif; ?>
                
                <!-- Mobile menu button -->
                <button class="md:hidden p-2 rounded-lg hover:bg-gray-100" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-gray-700"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
        <div class="px-4 py-2 space-y-2">
            <a href="/" class="block py-2 text-gray-700 hover:text-primary-600">Início</a>
            <a href="/catalog" class="block py-2 text-gray-700 hover:text-primary-600">Catálogo</a>
            <a href="/about" class="block py-2 text-gray-700 hover:text-primary-600">Quem Somos</a>
            <a href="/contact" class="block py-2 text-gray-700 hover:text-primary-600">Contato</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/dashboard" class="block py-2 text-gray-700 hover:text-primary-600">Dashboard</a>
                <a href="/chat" class="block py-2 text-gray-700 hover:text-primary-600">Chat</a>
            <?php endif; ?>
        </div>
    </div>
</header>
