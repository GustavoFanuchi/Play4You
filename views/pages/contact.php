<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Entre em Contato</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Tem alguma dúvida, sugestão ou precisa de ajuda? Estamos aqui para você!
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Contact Info -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">Informações de Contato</h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="bg-primary-100 p-3 rounded-lg mr-4">
                                <i class="fas fa-envelope text-primary-600"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-1">Email</h3>
                                <p class="text-gray-600">contato@gamerent.com.br</p>
                                <p class="text-sm text-gray-500">Respondemos em até 24h</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-primary-100 p-3 rounded-lg mr-4">
                                <i class="fas fa-phone text-primary-600"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-1">Telefone</h3>
                                <p class="text-gray-600">(11) 9999-9999</p>
                                <p class="text-sm text-gray-500">Seg-Sex: 9h às 18h</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-primary-100 p-3 rounded-lg mr-4">
                                <i class="fas fa-map-marker-alt text-primary-600"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-1">Endereço</h3>
                                <p class="text-gray-600">
                                    Rua dos Gamers, 123<br>
                                    São Paulo - SP<br>
                                    CEP: 01234-567
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-primary-100 p-3 rounded-lg mr-4">
                                <i class="fas fa-clock text-primary-600"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-1">Horário de Atendimento</h3>
                                <p class="text-gray-600">
                                    Segunda a Sexta: 9h às 18h<br>
                                    Sábado: 9h às 14h<br>
                                    Domingo: Fechado
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <h3 class="font-medium text-gray-900 mb-4">Siga-nos</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-lg transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="bg-pink-500 hover:bg-pink-600 text-white p-3 rounded-lg transition-colors">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="bg-blue-400 hover:bg-blue-500 text-white p-3 rounded-lg transition-colors">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="bg-red-500 hover:bg-red-600 text-white p-3 rounded-lg transition-colors">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">Envie sua Mensagem</h2>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span class="text-green-700"><?= $_SESSION['success'] ?></span>
                            </div>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

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
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    required
                                    value="<?= htmlspecialchars($formData['name'] ?? '') ?>"
                                    class="block w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    placeholder="Seu nome completo"
                                >
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email *
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    required
                                    value="<?= htmlspecialchars($formData['email'] ?? '') ?>"
                                    class="block w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    placeholder="seu@email.com"
                                >
                            </div>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                Assunto *
                            </label>
                            <select 
                                id="subject" 
                                name="subject" 
                                required
                                class="block w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            >
                                <option value="">Selecione um assunto</option>
                                <option value="Dúvida Geral" <?= ($formData['subject'] ?? '') === 'Dúvida Geral' ? 'selected' : '' ?>>Dúvida Geral</option>
                                <option value="Problema Técnico" <?= ($formData['subject'] ?? '') === 'Problema Técnico' ? 'selected' : '' ?>>Problema Técnico</option>
                                <option value="Problema com Aluguel" <?= ($formData['subject'] ?? '') === 'Problema com Aluguel' ? 'selected' : '' ?>>Problema com Aluguel</option>
                                <option value="Sugestão" <?= ($formData['subject'] ?? '') === 'Sugestão' ? 'selected' : '' ?>>Sugestão</option>
                                <option value="Parceria" <?= ($formData['subject'] ?? '') === 'Parceria' ? 'selected' : '' ?>>Parceria</option>
                                <option value="Outro" <?= ($formData['subject'] ?? '') === 'Outro' ? 'selected' : '' ?>>Outro</option>
                            </select>
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Mensagem *
                            </label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="6"
                                required
                                class="block w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Descreva sua dúvida, problema ou sugestão..."
                            ><?= htmlspecialchars($formData['message'] ?? '') ?></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit"
                            class="w-full bg-gradient-to-r from-primary-500 to-primary-600 text-white py-4 px-6 rounded-lg font-medium hover:from-primary-600 hover:to-primary-700 transition-all duration-200 transform hover:scale-105"
                        >
                            <i class="fas fa-paper-plane mr-2"></i>
                            Enviar Mensagem
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-16">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Perguntas Frequentes</h2>
                <p class="text-gray-600">Talvez sua dúvida já tenha sido respondida</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Como funciona o aluguel?</h3>
                        <p class="text-gray-600 text-sm mb-4">
                            Você escolhe o produto, seleciona as datas, confirma o pagamento e combina a entrega com o proprietário.
                        </p>
                        
                        <h3 class="font-semibold text-gray-900 mb-2">É seguro alugar?</h3>
                        <p class="text-gray-600 text-sm mb-4">
                            Sim! Todos os aluguéis incluem seguro contra danos e temos um sistema de avaliações para garantir a confiabilidade.
                        </p>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Posso cancelar minha reserva?</h3>
                        <p class="text-gray-600 text-sm mb-4">
                            Sim, você pode cancelar gratuitamente até 24h antes do início do aluguel.
                        </p>
                        
                        <h3 class="font-semibold text-gray-900 mb-2">Como recebo o produto?</h3>
                        <p class="text-gray-600 text-sm mb-4">
                            Você pode retirar no local ou solicitar entrega (taxa adicional pode ser aplicada).
                        </p>
                    </div>
                </div>
                
                <div class="text-center mt-8">
                    <a 
                        href="/faq"
                        class="text-primary-600 hover:text-primary-500 font-medium"
                    >
                        Ver todas as perguntas frequentes →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
