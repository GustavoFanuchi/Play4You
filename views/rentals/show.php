<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Detalhes do Aluguel</h1>
        </div>
        <div class="bg-white shadow rounded-lg divide-y">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h2 class="text-sm text-gray-500">Produto</h2>
                        <p class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($rental['product_title']) ?></p>
                        <p class="text-gray-600 text-sm"><?= htmlspecialchars($rental['product_brand'] . ' ' . $rental['product_model']) ?></p>
                    </div>
                    <div>
                        <h2 class="text-sm text-gray-500">Período</h2>
                        <p class="text-gray-900"><?= date('d/m/Y', strtotime($rental['start_date'])) ?> a <?= date('d/m/Y', strtotime($rental['end_date'])) ?> (<?= (int)$rental['total_days'] ?> dias)</p>
                    </div>
                    <div>
                        <h2 class="text-sm text-gray-500">Status</h2>
                        <p class="text-gray-900">Pagamento: <?= ucfirst($rental['payment_status']) ?> | Aluguel: <?= ucfirst($rental['status']) ?></p>
                    </div>
                    <div>
                        <h2 class="text-sm text-gray-500">Entrega</h2>
                        <p class="text-gray-900">
                            <?php if ($rental['delivery_method'] === 'delivery'): ?>
                                Entrega em: <?= htmlspecialchars($rental['delivery_address']) ?>
                            <?php else: ?>
                                Retirada combinada com o proprietário
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumo</h2>
                <dl class="space-y-2 text-gray-800">
                    <div class="flex justify-between"><dt>Dias x Diária</dt><dd>R$ <?= number_format($rental['subtotal'], 2, ',', '.') ?></dd></div>
                    <div class="flex justify-between"><dt>Taxa de Serviço</dt><dd>R$ <?= number_format($rental['service_fee'], 2, ',', '.') ?></dd></div>
                    <div class="flex justify-between"><dt>Seguro</dt><dd>R$ <?= number_format($rental['insurance_fee'], 2, ',', '.') ?></dd></div>
                    <?php if (!empty($rental['deposit_amount'])): ?><div class="flex justify-between"><dt>Depósito</dt><dd>R$ <?= number_format($rental['deposit_amount'], 2, ',', '.') ?></dd></div><?php endif; ?>
                    <div class="flex justify-between font-semibold"><dt>Total</dt><dd>R$ <?= number_format($rental['total_amount'], 2, ',', '.') ?></dd></div>
                </dl>
                <?php if ($rental['payment_status'] !== 'paid' && $rental['renter_id'] == $_SESSION['user_id']): ?>
                    <a href="/rental/checkout?id=<?= (int)$rental['id'] ?>" class="mt-4 inline-block bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg">Ir para Pagamento</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


