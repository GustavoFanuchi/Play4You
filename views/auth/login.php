<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-50 to-accent-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 animate-scale-in">
        <!-- Header -->
        <div class="text-center">
            <div class="flex justify-center mb-4">
                <img src="../public/LogoPlay4You.png" alt="Play4You" class="h-24 w-auto">
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Bem-vindo de volta!</h2>
            <p class="text-gray-600">Entre na sua conta para continuar</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <?php if (isset($error)): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                        <span class="text-red-700"><?= htmlspecialchars($error) ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                            placeholder="seu@email.com"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        >
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Senha
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                            placeholder="Sua senha"
                        >
                        <button 
                            type="button" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            onclick="togglePassword('password', 'password-icon')"
                        >
                            <i id="password-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="remember" 
                            name="remember"
                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                        >
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Lembrar de mim
                        </label>
                    </div>
                    <a href="#" class="text-sm text-primary-600 hover:text-primary-500 transition-colors">
                        Esqueceu a senha?
                    </a>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-gradient-to-r from-primary-500 to-primary-600 text-white py-3 px-4 rounded-lg font-medium hover:from-primary-600 hover:to-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Entrar
                </button>
            </form>

            <!-- Social Login -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Ou continue com</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <button class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors">
                        <i class="fab fa-google text-red-500 mr-2"></i>
                        Google
                    </button>
                    <button class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors">
                        <i class="fab fa-github mr-2"></i>
                        GitHub
                    </button>
                </div>
            </div>

            <!-- Register Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Não tem uma conta? 
                    <a href="/register" class="font-medium text-primary-600 hover:text-primary-500 transition-colors">
                        Cadastre-se gratuitamente
                    </a>
                </p>
            </div>
        </div>

        <!-- Benefits -->
        <div class="bg-white rounded-xl p-6 shadow-lg">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">
                Por que escolher a Play4You?
            </h3>
            <div class="space-y-3">
                <div class="flex items-center">
                    <div class="bg-green-100 p-2 rounded-full mr-3">
                        <i class="fas fa-shield-alt text-green-600"></i>
                    </div>
                    <span class="text-sm text-gray-700">Transações 100% seguras</span>
                </div>
                <div class="flex items-center">
                    <div class="bg-blue-100 p-2 rounded-full mr-3">
                        <i class="fas fa-headset text-blue-600"></i>
                    </div>
                    <span class="text-sm text-gray-700">Suporte 24/7</span>
                </div>
                <div class="flex items-center">
                    <div class="bg-purple-100 p-2 rounded-full mr-3">
                        <i class="fas fa-gamepad text-purple-600"></i>
                    </div>
                    <span class="text-sm text-gray-700">Milhares de jogos disponíveis</span>
                </div>
            </div>
        </div>
    </div>
</div>
