<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-50 to-accent-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full space-y-8 animate-scale-in">
        <!-- Header -->
        <div class="text-center">
            <div class="flex justify-center mb-4">
                <img src="../public/LogoPlay4You.png" alt="Play4You" class="h-24 w-auto">
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Crie sua conta</h2>
            <p class="text-gray-600">Junte-se à maior plataforma de aluguel de games do Brasil</p>
        </div>

        <!-- Register Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <?php if (!empty($errors)): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle text-red-500 mr-2 mt-0.5"></i>
                        <div>
                            <h4 class="text-red-800 font-medium mb-1">Corrija os seguintes erros:</h4>
                            <ul class="text-red-700 text-sm space-y-1">
                                <?php foreach ($errors as $error): ?>
                                    <li>• <?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome Completo *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                                placeholder="Seu nome completo"
                                value="<?= htmlspecialchars($formData['name'] ?? '') ?>"
                            >
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email *
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
                                value="<?= htmlspecialchars($formData['email'] ?? '') ?>"
                            >
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Senha *
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
                                placeholder="Mínimo 6 caracteres"
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

                    <!-- Confirm Password -->
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Senha *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                required
                                class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                                placeholder="Repita sua senha"
                            >
                            <button 
                                type="button" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePassword('confirm_password', 'confirm-password-icon')"
                            >
                                <i id="confirm-password-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Telefone
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone text-gray-400"></i>
                            </div>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                                placeholder="(11) 99999-9999"
                                value="<?= htmlspecialchars($formData['phone'] ?? '') ?>"
                            >
                        </div>
                    </div>

                    <!-- City -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                            Cidade
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="city" 
                                name="city"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                                placeholder="Sua cidade"
                                value="<?= htmlspecialchars($formData['city'] ?? '') ?>"
                            >
                        </div>
                    </div>
                </div>

                <!-- State -->
                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                        Estado
                    </label>
                    <select 
                        id="state" 
                        name="state"
                        class="block w-full py-3 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                    >
                        <option value="">Selecione seu estado</option>
                        <option value="AC" <?= ($formData['state'] ?? '') === 'AC' ? 'selected' : '' ?>>Acre</option>
                        <option value="AL" <?= ($formData['state'] ?? '') === 'AL' ? 'selected' : '' ?>>Alagoas</option>
                        <option value="AP" <?= ($formData['state'] ?? '') === 'AP' ? 'selected' : '' ?>>Amapá</option>
                        <option value="AM" <?= ($formData['state'] ?? '') === 'AM' ? 'selected' : '' ?>>Amazonas</option>
                        <option value="BA" <?= ($formData['state'] ?? '') === 'BA' ? 'selected' : '' ?>>Bahia</option>
                        <option value="CE" <?= ($formData['state'] ?? '') === 'CE' ? 'selected' : '' ?>>Ceará</option>
                        <option value="DF" <?= ($formData['state'] ?? '') === 'DF' ? 'selected' : '' ?>>Distrito Federal</option>
                        <option value="ES" <?= ($formData['state'] ?? '') === 'ES' ? 'selected' : '' ?>>Espírito Santo</option>
                        <option value="GO" <?= ($formData['state'] ?? '') === 'GO' ? 'selected' : '' ?>>Goiás</option>
                        <option value="MA" <?= ($formData['state'] ?? '') === 'MA' ? 'selected' : '' ?>>Maranhão</option>
                        <option value="MT" <?= ($formData['state'] ?? '') === 'MT' ? 'selected' : '' ?>>Mato Grosso</option>
                        <option value="MS" <?= ($formData['state'] ?? '') === 'MS' ? 'selected' : '' ?>>Mato Grosso do Sul</option>
                        <option value="MG" <?= ($formData['state'] ?? '') === 'MG' ? 'selected' : '' ?>>Minas Gerais</option>
                        <option value="PA" <?= ($formData['state'] ?? '') === 'PA' ? 'selected' : '' ?>>Pará</option>
                        <option value="PB" <?= ($formData['state'] ?? '') === 'PB' ? 'selected' : '' ?>>Paraíba</option>
                        <option value="PR" <?= ($formData['state'] ?? '') === 'PR' ? 'selected' : '' ?>>Paraná</option>
                        <option value="PE" <?= ($formData['state'] ?? '') === 'PE' ? 'selected' : '' ?>>Pernambuco</option>
                        <option value="PI" <?= ($formData['state'] ?? '') === 'PI' ? 'selected' : '' ?>>Piauí</option>
                        <option value="RJ" <?= ($formData['state'] ?? '') === 'RJ' ? 'selected' : '' ?>>Rio de Janeiro</option>
                        <option value="RN" <?= ($formData['state'] ?? '') === 'RN' ? 'selected' : '' ?>>Rio Grande do Norte</option>
                        <option value="RS" <?= ($formData['state'] ?? '') === 'RS' ? 'selected' : '' ?>>Rio Grande do Sul</option>
                        <option value="RO" <?= ($formData['state'] ?? '') === 'RO' ? 'selected' : '' ?>>Rondônia</option>
                        <option value="RR" <?= ($formData['state'] ?? '') === 'RR' ? 'selected' : '' ?>>Roraima</option>
                        <option value="SC" <?= ($formData['state'] ?? '') === 'SC' ? 'selected' : '' ?>>Santa Catarina</option>
                        <option value="SP" <?= ($formData['state'] ?? '') === 'SP' ? 'selected' : '' ?>>São Paulo</option>
                        <option value="SE" <?= ($formData['state'] ?? '') === 'SE' ? 'selected' : '' ?>>Sergipe</option>
                        <option value="TO" <?= ($formData['state'] ?? '') === 'TO' ? 'selected' : '' ?>>Tocantins</option>
                    </select>
                </div>

                <!-- Terms -->
                <div class="flex items-start">
                    <input 
                        type="checkbox" 
                        id="terms" 
                        name="terms"
                        required
                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded mt-1"
                    >
                    <label for="terms" class="ml-3 block text-sm text-gray-700">
                        Eu concordo com os 
                        <a href="#" class="text-primary-600 hover:text-primary-500 underline">Termos de Uso</a> 
                        e 
                        <a href="#" class="text-primary-600 hover:text-primary-500 underline">Política de Privacidade</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-gradient-to-r from-primary-500 to-primary-600 text-white py-3 px-4 rounded-lg font-medium hover:from-primary-600 hover:to-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105"
                >
                    <i class="fas fa-user-plus mr-2"></i>
                    Criar Conta
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Já tem uma conta? 
                    <a href="/login" class="font-medium text-primary-600 hover:text-primary-500 transition-colors">
                        Faça login
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
