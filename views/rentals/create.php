<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Finalizar Reserva</h1>
            <p class="text-gray-600">Complete os detalhes da sua reserva</p>
        </div>

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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Booking Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Detalhes da Reserva</h2>
                    
                    <form method="POST" id="rental-form">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        
                        <!-- Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Data de Início *
                                </label>
                                <input 
                                    type="date" 
                                    name="start_date" 
                                    id="start_date"
                                    required
                                    min="<?= date('Y-m-d') ?>"
                                    value="<?= htmlspecialchars($formData['start_date'] ?? '') ?>"
                                    class="block w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    onchange="calculateTotal()"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Data de Fim *
                                </label>
                                <input 
                                    type="date" 
                                    name="end_date" 
                                    id="end_date"
                                    required
                                    min="<?= date('Y-m-d') ?>"
                                    value="<?= htmlspecialchars($formData['end_date'] ?? '') ?>"
                                    class="block w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    onchange="calculateTotal()"
                                >
                            </div>
                        </div>

                        <!-- Delivery Method -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-4">
                                Método de Entrega *
                            </label>
                            <div class="space-y-4">
                                <?php if ($product['pickup_available']): ?>
                                    <label class="flex items-start p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input 
                                            type="radio" 
                                            name="delivery_method" 
                                            value="pickup" 
                                            <?= ($formData['delivery_method'] ?? 'pickup') === 'pickup' ? 'checked' : '' ?>
                                            class="mt-1 text-primary-600 focus:ring-primary-500"
                                            onchange="toggleDeliveryAddress()"
                                        >
                                        <div class="ml-3">
                                            <div class="flex items-center">
                                                <i class="fas fa-store text-primary-600 mr-2"></i>
                                                <span class="font-medium text-gray-900">Retirar no Local</span>
                                                <span class="ml-2 text-sm bg-green-100 text-green-800 px-2 py-1 rounded-full">Grátis</span>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Retire o produto diretamente com o proprietário em <?= htmlspecialchars($product['owner_city']) ?>
                                            </p>
                                        </div>
                                    </label>
                                <?php endif; ?>
                                
                                <?php if ($product['delivery_available']): ?>
                                    <label class="flex items-start p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input 
                                            type="radio" 
                                            name="delivery_method" 
                                            value="delivery" 
                                            <?= ($formData['delivery_method'] ?? '') === 'delivery' ? 'checked' : '' ?>
                                            class="mt-1 text-primary-600 focus:ring-primary-500"
                                            onchange="toggleDeliveryAddress()"
                                        >
                                        <div class="ml-3">
                                            <div class="flex items-center">
                                                <i class="fas fa-truck text-primary-600 mr-2"></i>
                                                <span class="font-medium text-gray-900">Entrega em Casa</span>
                                                <span class="ml-2 text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full">R$ 25,00</span>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Receba o produto no conforto da sua casa
                                            </p>
                                        </div>
                                    </label>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Delivery Address -->
                        <div id="delivery-address-section" class="mb-6 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Endereço de Entrega *
                            </label>
                            <textarea 
                                name="delivery_address" 
                                rows="3"
                                placeholder="Digite seu endereço completo..."
                                class="block w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            ><?= htmlspecialchars($formData['delivery_address'] ?? '') ?></textarea>
                        </div>

                        <!-- Terms -->
                        <div class="mb-6">
                            <label class="flex items-start">
                                <input 
                                    type="checkbox" 
                                    name="terms" 
                                    required
                                    class="mt-1 h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                >
                                <span class="ml-3 text-sm text-gray-700">
                                    Eu concordo com os 
                                    <a href="#" class="text-primary-600 hover:text-primary-500 underline">Termos de Aluguel</a> 
                                    e 
                                    <a href="#" class="text-primary-600 hover:text-primary-500 underline">Política de Cancelamento</a>
                                </span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit"
                            class="w-full bg-gradient-to-r from-primary-500 to-primary-600 text-white py-4 px-6 rounded-lg font-medium hover:from-primary-600 hover:to-primary-700 transition-all duration-200 transform hover:scale-105"
                        >
                            <i class="fas fa-credit-card mr-2"></i>
                            Confirmar Reserva
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <!-- Product Info -->
                    <div class="flex items-center mb-6 pb-6 border-b border-gray-200">
                        <img 
                            src="/placeholder.svg?height=80&width=80" 
                            alt="<?= htmlspecialchars($product['title']) ?>"
                            class="w-20 h-20 object-cover rounded-lg"
                        >
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900 line-clamp-2">
                                <?= htmlspecialchars($product['title']) ?>
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                <?= htmlspecialchars(trim(($product['brand'] ?? '') . ' ' . ($product['model'] ?? ''))) ?>
                            </p>
                            <div class="flex items-center mt-2">
                                <i class="fas fa-map-marker-alt text-gray-400 text-sm mr-1"></i>
                                <span class="text-sm text-gray-600">
                                    <?= htmlspecialchars($product['owner_city']) ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Preço diário</span>
                            <span class="font-medium">R$ <?= number_format($product['daily_price'], 2, ',', '.') ?></span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dias</span>
                            <span class="font-medium" id="total-days">-</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium" id="subtotal">-</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Taxa de serviço (10%)</span>
                            <span class="font-medium" id="service-fee">-</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Seguro (5%)</span>
                            <span class="font-medium" id="insurance-fee">-</span>
                        </div>
                        
                        <div class="flex justify-between" id="delivery-fee-row" style="display: none;">
                            <span class="text-gray-600">Taxa de entrega</span>
                            <span class="font-medium">R$ 25,00</span>
                        </div>
                        
                        <hr class="border-gray-200">
                        
                        <div class="flex justify-between text-lg font-semibold">
                            <span>Total</span>
                            <span class="text-primary-600" id="total-amount">-</span>
                        </div>
                        
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Depósito caução</span>
                            <span class="font-medium" id="deposit-amount">R$ <?= number_format($product['deposit_amount'] ?? ($product['daily_price'] * 2), 2, ',', '.') ?></span>
                        </div>
                    </div>

                    <!-- Security Info -->
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-green-500 mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-medium text-green-900 mb-1">Proteção Garantida</h4>
                                <p class="text-sm text-green-700">
                                    Sua reserva está protegida pelo nosso seguro contra danos e roubo.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const dailyPrice = <?= $product['daily_price'] ?>;

function calculateTotal() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        const timeDiff = end.getTime() - start.getTime();
        const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
        
        if (daysDiff > 0) {
            const subtotal = daysDiff * dailyPrice;
            const serviceFee = subtotal * 0.10;
            const insuranceFee = subtotal * 0.05;
            const deliveryFee = document.querySelector('input[name="delivery_method"]:checked')?.value === 'delivery' ? 25 : 0;
            const total = subtotal + serviceFee + insuranceFee + deliveryFee;
            
            document.getElementById('total-days').textContent = daysDiff;
            document.getElementById('subtotal').textContent = 'R$ ' + subtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2});
            document.getElementById('service-fee').textContent = 'R$ ' + serviceFee.toLocaleString('pt-BR', {minimumFractionDigits: 2});
            document.getElementById('insurance-fee').textContent = 'R$ ' + insuranceFee.toLocaleString('pt-BR', {minimumFractionDigits: 2});
            document.getElementById('total-amount').textContent = 'R$ ' + total.toLocaleString('pt-BR', {minimumFractionDigits: 2});
            
            // Show/hide delivery fee
            const deliveryFeeRow = document.getElementById('delivery-fee-row');
            if (deliveryFee > 0) {
                deliveryFeeRow.style.display = 'flex';
            } else {
                deliveryFeeRow.style.display = 'none';
            }
        }
    }
}

function toggleDeliveryAddress() {
    const deliveryMethod = document.querySelector('input[name="delivery_method"]:checked').value;
    const addressSection = document.getElementById('delivery-address-section');
    
    if (deliveryMethod === 'delivery') {
        addressSection.classList.remove('hidden');
        document.querySelector('textarea[name="delivery_address"]').required = true;
    } else {
        addressSection.classList.add('hidden');
        document.querySelector('textarea[name="delivery_address"]').required = false;
    }
    
    calculateTotal();
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
    toggleDeliveryAddress();
    
    // Add event listeners to delivery method radio buttons
    document.querySelectorAll('input[name="delivery_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            toggleDeliveryAddress();
        });
    });
});
</script>
