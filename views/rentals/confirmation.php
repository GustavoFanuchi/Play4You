<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-green-600 text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Reserva Confirmada!</h1>
            <p class="text-gray-600">Sua reserva foi processada com sucesso</p>
        </div>

        <!-- Rental Details -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <!-- Header -->
            <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-6 py-4">
                <div class="flex items-center justify-between text-white">
                    <div>
                        <h2 class="text-xl font-semibold">Reserva #<?= str_pad($rental['id'], 6, '0', STR_PAD_LEFT) ?></h2>
                        <p class="text-primary-100">Status: <?= ucfirst($rental['status']) ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-primary-100">Data da reserva</p>
                        <p class="font-medium"><?= date('d/m/Y H:i', strtotime($rental['created_at'])) ?></p>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <img 
                        src="/placeholder.svg?height=100&width=100" 
                        alt="<?= htmlspecialchars($rental['product_title']) ?>"
                        class="w-24 h-24 object-cover rounded-lg"
                    >
                    <div class="ml-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            <?= htmlspecialchars($rental['product_title']) ?>
                        </h3>
                        <p class="text-gray-600 mb-1">
                            <i class="fas fa-tag mr-2"></i>
                            <?= htmlspecialchars($rental['product_brand']) ?>
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-user mr-2"></i>
                            Proprietário: <?= htmlspecialchars($rental['owner_name']) ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Rental Details -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Dates -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Período do Aluguel</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt text-primary-500 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Data de Início</p>
                                    <p class="font-medium"><?= date('d/m/Y', strtotime($rental['start_date'])) ?></p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt text-primary-500 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Data de Fim</p>
                                    <p class="font-medium"><?= date('d/m/Y', strtotime($rental['end_date'])) ?></p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock text-primary-500 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Total de Dias</p>
                                    <p class="font-medium"><?= $rental['total_days'] ?> dias</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Entrega</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-<?= $rental['delivery_method'] === 'delivery' ? 'truck' : 'store' ?> text-primary-500 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Método</p>
                                    <p class="font-medium">
                                        <?= $rental['delivery_method'] === 'delivery' ? 'Entrega em Casa' : 'Retirar no Local' ?>
                                    </p>
                                </div>
                            </div>
                            <?php if ($rental['delivery_method'] === 'delivery' && $rental['delivery_address']): ?>
                                <div class="flex items-start">
                                    <i class="fas fa-map-marker-alt text-primary-500 mr-3 mt-1"></i>
                                    <div>
                                        <p class="text-sm text-gray-600">Endereço</p>
                                        <p class="font-medium"><?= nl2br(htmlspecialchars($rental['delivery_address'])) ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Price Breakdown -->
            <div class="p-6 bg-gray-50">
                <h4 class="font-semibold text-gray-900 mb-4">Resumo Financeiro</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal (<?= $rental['total_days'] ?> dias × R$ <?= number_format($rental['daily_rate'], 2, ',', '.') ?>)</span>
                        <span class="font-medium">R$ <?= number_format($rental['subtotal'], 2, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Taxa de serviço</span>
                        <span class="font-medium">R$ <?= number_format($rental['service_fee'], 2, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Seguro</span>
                        <span class="font-medium">R$ <?= number_format($rental['insurance_fee'], 2, ',', '.') ?></span>
                    </div>
                    <?php if ($rental['delivery_method'] === 'delivery'): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Taxa de entrega</span>
                            <span class="font-medium">R$ 25,00</span>
                        </div>
                    <?php endif; ?>
                    <hr class="border-gray-300">
                    <div class="flex justify-between text-lg font-semibold">
                        <span>Total</span>
                        <span class="text-primary-600">R$ <?= number_format($rental['total_amount'], 2, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Depósito caução</span>
                        <span class="font-medium">R$ <?= number_format($rental['deposit_amount'], 2, ',', '.') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Próximos Passos</h3>
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="bg-primary-100 w-8 h-8 rounded-full flex items-center justify-center mr-4 mt-1">
                        <span class="text-primary-600 font-semibold text-sm">1</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Aguarde a confirmação</h4>
                        <p class="text-gray-600 text-sm">O proprietário tem até 24h para confirmar sua reserva.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="bg-primary-100 w-8 h-8 rounded-full flex items-center justify-center mr-4 mt-1">
                        <span class="text-primary-600 font-semibold text-sm">2</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Entre em contato</h4>
                        <p class="text-gray-600 text-sm">Use nosso chat para combinar detalhes da entrega/retirada.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="bg-primary-100 w-8 h-8 rounded-full flex items-center justify-center mr-4 mt-1">
                        <span class="text-primary-600 font-semibold text-sm">3</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Aproveite!</h4>
                        <p class="text-gray-600 text-sm">Receba o produto e divirta-se com sua experiência gaming.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a 
                href="/chat?user=<?= $rental['owner_id'] ?>&product=<?= $rental['product_id'] ?>"
                class="flex-1 bg-primary-500 hover:bg-primary-600 text-white py-3 px-6 rounded-lg font-medium transition-colors text-center"
            >
                <i class="fas fa-comment mr-2"></i>
                Conversar com Proprietário
            </a>
            <a 
                href="/rental/<?= $rental['id'] ?>"
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 px-6 rounded-lg font-medium transition-colors text-center"
            >
                <i class="fas fa-eye mr-2"></i>
                Ver Detalhes
            </a>
            <a 
                href="/rentals"
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 px-6 rounded-lg font-medium transition-colors text-center"
            >
                <i class="fas fa-list mr-2"></i>
                Meus Aluguéis
            </a>
        </div>
    </div>
</div>
