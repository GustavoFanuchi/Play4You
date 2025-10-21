<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Pagamento</h1>
            <p class="text-gray-600">Finalize o pagamento do seu aluguel</p>
        </div>
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="mb-4 p-4 rounded bg-red-50 text-red-800 border border-red-200">
                <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <div class="bg-white shadow rounded-lg p-6 space-y-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Resumo</h2>
                <div class="mt-2 text-gray-800 space-y-1">
                    <div class="flex justify-between"><span>Produto</span><span><?= htmlspecialchars($rental['product_title']) ?></span></div>
                    <div class="flex justify-between"><span>Período</span><span><?= date('d/m/Y', strtotime($rental['start_date'])) ?> a <?= date('d/m/Y', strtotime($rental['end_date'])) ?></span></div>
                    <div class="flex justify-between"><span>Total</span><span>R$ <?= number_format($rental['total_amount'], 2, ',', '.') ?></span></div>
                </div>
            </div>
            <form method="POST" action="/rental/pay" class="space-y-4">
                <input type="hidden" name="rental_id" value="<?= (int)$rental['id'] ?>" />
                <div>
                    <label class="block text-sm font-medium text-gray-700">Método de Pagamento</label>
                    <select name="payment_method" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                        <option value="pix">PIX</option>
                        <option value="card">Cartão de Crédito</option>
                        <option value="boleto">Boleto</option>
                    </select>
                </div>
                <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg">Pagar Agora</button>
            </form>
        </div>
    </div>
</div>


