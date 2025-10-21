<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Meus Aluguéis</h1>
            <p class="text-gray-600">Gerencie suas reservas e histórico de aluguéis</p>
        </div>

        <?php if (empty($rentals)): ?>
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhum aluguel encontrado</h3>
                <p class="text-gray-600 mb-6">Você ainda não fez nenhuma reserva. Que tal explorar nosso catálogo?</p>
                <a href="/catalog" class="inline-flex items-center bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    Explorar Produtos
                </a>
            </div>
        <?php else: ?>
            <!-- Rentals List -->
            <div class="space-y-6">
                <?php foreach ($rentals as $rental): ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                <!-- Product Info -->
                                <div class="flex items-center mb-4 lg:mb-0">
                                    <img 
                                        src="/placeholder.svg?height=80&width=80" 
                                        alt="<?= htmlspecialchars($rental['product_title']) ?>"
                                        class="w-20 h-20 object-cover rounded-lg"
                                    >
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                            <?= htmlspecialchars($rental['product_title']) ?>
                                        </h3>
                                        <p class="text-gray-600 text-sm mb-1">
                                            <i class="fas fa-tag mr-1"></i>
                                            <?= htmlspecialchars($rental['product_brand']) ?>
                                        </p>
                                        <p class="text-gray-600 text-sm">
                                            <i class="fas fa-user mr-1"></i>
                                            <?= htmlspecialchars($rental['owner_name']) ?> • <?= htmlspecialchars($rental['owner_city']) ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- Rental Details -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6">
                                    <!-- Dates -->
                                    <div class="mb-4 sm:mb-0">
                                        <p class="text-sm text-gray-600">Período</p>
                                        <p class="font-medium">
                                            <?= date('d/m/Y', strtotime($rental['start_date'])) ?> - 
                                            <?= date('d/m/Y', strtotime($rental['end_date'])) ?>
                                        </p>
                                        <p class="text-sm text-gray-500"><?= $rental['total_days'] ?> dias</p>
                                    </div>

                                    <!-- Status -->
                                    <div class="mb-4 sm:mb-0">
                                        <p class="text-sm text-gray-600">Status</p>
                                        <?php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'confirmed' => 'bg-green-100 text-green-800',
                                            'active' => 'bg-blue-100 text-blue-800',
                                            'completed' => 'bg-gray-100 text-gray-800',
                                            'cancelled' => 'bg-red-100 text-red-800'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Pendente',
                                            'confirmed' => 'Confirmado',
                                            'active' => 'Ativo',
                                            'completed' => 'Concluído',
                                            'cancelled' => 'Cancelado'
                                        ];
                                        $statusColor = $statusColors[$rental['status']] ?? 'bg-gray-100 text-gray-800';
                                        $statusLabel = $statusLabels[$rental['status']] ?? ucfirst($rental['status']);
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $statusColor ?>">
                                            <?= $statusLabel ?>
                                        </span>
                                    </div>

                                    <!-- Price -->
                                    <div class="mb-4 sm:mb-0">
                                        <p class="text-sm text-gray-600">Total</p>
                                        <p class="text-lg font-bold text-primary-600">
                                            R$ <?= number_format($rental['total_amount'], 2, ',', '.') ?>
                                        </p>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex space-x-2">
                                        <a 
                                            href="/rental/<?= $rental['id'] ?>"
                                            class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                                        >
                                            Ver Detalhes
                                        </a>
                                        
                                        <?php if ($rental['status'] === 'pending'): ?>
                                            <a 
                                                href="/rental/cancel/<?= $rental['id'] ?>"
                                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                                                onclick="return confirm('Tem certeza que deseja cancelar esta reserva?')"
                                            >
                                                Cancelar
                                            </a>
                                        <?php endif; ?>
                                        
                                        <a 
                                            href="/chat?user=<?= $rental['owner_id'] ?>&product=<?= $rental['product_id'] ?>"
                                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                                        >
                                            <i class="fas fa-comment"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="mt-12 flex justify-center">
                    <nav class="flex items-center space-x-2">
                        <!-- Previous Page -->
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?= $currentPage - 1 ?>" 
                               class="px-3 py-2 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        <?php endif; ?>

                        <!-- Page Numbers -->
                        <?php
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);
                        
                        for ($i = $startPage; $i <= $endPage; $i++):
                        ?>
                            <a href="?page=<?= $i ?>" 
                               class="px-3 py-2 rounded-lg <?= $i === $currentPage ? 'bg-primary-500 text-white' : 'border border-gray-300 text-gray-500 hover:bg-gray-50' ?> transition-colors">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <!-- Next Page -->
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?= $currentPage + 1 ?>" 
                               class="px-3 py-2 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
