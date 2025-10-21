<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="/" class="text-gray-500 hover:text-gray-700 transition-colors">Início</a>
                </li>
                <li>
                    <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
                </li>
                <li>
                    <a href="/catalog" class="text-gray-500 hover:text-gray-700 transition-colors">Catálogo</a>
                </li>
                <li>
                    <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
                </li>
                <li>
                    <span class="text-gray-900 font-medium"><?= htmlspecialchars($product['title']) ?></span>
                </li>
            </ol>
        </nav>

        <?php 
        $images = [];
        if (!empty($product['images'])) {
            // Debug: verificar o que está no banco
            error_log("Images from DB: " . $product['images']);
            $decoded = json_decode($product['images'], true);
            if (is_array($decoded)) { $images = $decoded; }
            error_log("Decoded images: " . print_r($images, true));
        }
        // Normaliza caminhos: garante barra inicial para funcionar em qualquer rota
        $normalize = function($path) {
            if (!$path) return null;
            return $path[0] === '/' ? $path : '/' . $path;
        };
        $mainImage = $normalize($images[0] ?? '') ?: '/placeholder.svg?height=400&width=600';
        ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Product Images -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <!-- Main Image -->
                    <div class="aspect-w-16 aspect-h-9">
                        <img 
                            src="<?= htmlspecialchars($mainImage) ?>" 
                            alt="<?= htmlspecialchars($product['title']) ?>"
                            class="w-full h-96 object-cover"
                            id="main-image"
                        >
                    </div>
                    
                    <!-- Image Gallery -->
                    <div class="p-4">
                        <div class="grid grid-cols-4 gap-2">
                            <?php if (!empty($images)): ?>
                                <?php foreach ($images as $index => $img): ?>
                                    <img 
                                        src="<?= htmlspecialchars($normalize($img)) ?>" 
                                        alt="<?= htmlspecialchars($product['title']) ?> - Imagem <?= $index + 1 ?>"
                                        class="w-full h-20 object-cover rounded-lg cursor-pointer border-2 <?= $index === 0 ? 'border-primary-500' : 'border-gray-200 hover:border-primary-500 transition-colors' ?>"
                                        onclick="changeMainImage(this.src)"
                                    >
                                <?php endforeach; ?>
                            <?php else: ?>
                                <img 
                                    src="/placeholder.svg?height=100&width=100" 
                                    alt="<?= htmlspecialchars($product['title']) ?> - Imagem 1"
                                    class="w-full h-20 object-cover rounded-lg cursor-pointer border-2 border-primary-500"
                                    onclick="changeMainImage(this.src)"
                                >
                                <img 
                                    src="/placeholder.svg?height=100&width=100" 
                                    alt="<?= htmlspecialchars($product['title']) ?> - Imagem 2"
                                    class="w-full h-20 object-cover rounded-lg cursor-pointer border-2 border-gray-200 hover:border-primary-500 transition-colors"
                                    onclick="changeMainImage(this.src)"
                                >
                                <img 
                                    src="/placeholder.svg?height=100&width=100" 
                                    alt="<?= htmlspecialchars($product['title']) ?> - Imagem 3"
                                    class="w-full h-20 object-cover rounded-lg cursor-pointer border-2 border-gray-200 hover:border-primary-500 transition-colors"
                                    onclick="changeMainImage(this.src)"
                                >
                                <img 
                                    src="/placeholder.svg?height=100&width=100" 
                                    alt="<?= htmlspecialchars($product['title']) ?> - Imagem 4"
                                    class="w-full h-20 object-cover rounded-lg cursor-pointer border-2 border-gray-200 hover:border-primary-500 transition-colors"
                                    onclick="changeMainImage(this.src)"
                                >
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Product Details Tabs -->
                <div class="bg-white rounded-xl shadow-lg mt-8">
                    <div class="border-b border-gray-200">
                        <nav class="flex space-x-8 px-6" aria-label="Tabs">
                            <button class="tab-button active py-4 px-1 border-b-2 border-primary-500 font-medium text-sm text-primary-600" onclick="showTab('description')">
                                Descrição
                            </button>
                            <button class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" onclick="showTab('specifications')">
                                Especificações
                            </button>
                            <button class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" onclick="showTab('included')">
                                Itens Inclusos
                            </button>
                            <button class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" onclick="showTab('policies')">
                                Políticas
                            </button>
                        </nav>
                    </div>

                    <div class="p-6">
                        <!-- Description Tab -->
                        <div id="description-tab" class="tab-content">
                            <p class="text-gray-700 leading-relaxed">
                                <?= nl2br(htmlspecialchars($product['description'])) ?>
                            </p>
                        </div>

                        <!-- Specifications Tab -->
                        <div id="specifications-tab" class="tab-content hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-900 mb-2">Marca</h4>
                                    <p class="text-gray-700"><?= htmlspecialchars($product['brand']) ?></p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-900 mb-2">Modelo</h4>
                                    <p class="text-gray-700"><?= htmlspecialchars($product['model']) ?></p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-900 mb-2">Estado</h4>
                                    <p class="text-gray-700"><?= ucfirst($product['condition_status']) ?></p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-900 mb-2">Categoria</h4>
                                    <p class="text-gray-700"><?= htmlspecialchars($product['category_name']) ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Included Items Tab -->
                        <div id="included-tab" class="tab-content hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span class="text-gray-700">Console principal</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span class="text-gray-700">Controle original</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span class="text-gray-700">Cabos necessários</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span class="text-gray-700">Manual de instruções</span>
                                </div>
                            </div>
                        </div>

                        <!-- Policies Tab -->
                        <div id="policies-tab" class="tab-content hidden">
                            <div class="space-y-4">
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">Cancelamento</h4>
                                    <p class="text-gray-700">Cancelamento gratuito até 24h antes do início do aluguel.</p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">Danos</h4>
                                    <p class="text-gray-700">O locatário é responsável por danos causados durante o período de aluguel.</p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">Atraso na Devolução</h4>
                                    <p class="text-gray-700">Taxa adicional de 50% do valor diário para cada dia de atraso.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <!-- Price -->
                    <div class="mb-6">
                        <div class="flex items-baseline">
                            <span class="text-3xl font-bold text-primary-600">
                                R$ <?= number_format($product['daily_price'], 2, ',', '.') ?>
                            </span>
                            <span class="text-gray-500 ml-2">/dia</span>
                        </div>
                        
                        <?php if ($product['weekly_price']): ?>
                            <p class="text-sm text-gray-600 mt-1">
                                Semanal: R$ <?= number_format($product['weekly_price'], 2, ',', '.') ?>
                            </p>
                        <?php endif; ?>
                        
                        <?php if ($product['monthly_price']): ?>
                            <p class="text-sm text-gray-600">
                                Mensal: R$ <?= number_format($product['monthly_price'], 2, ',', '.') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Rating -->
                    <?php if ($product['average_rating']): ?>
                        <div class="flex items-center mb-6">
                            <div class="flex items-center">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= round($product['average_rating']) ? 'text-yellow-400' : 'text-gray-300' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="ml-2 text-sm text-gray-600">
                                <?= number_format($product['average_rating'], 1) ?> (<?= $product['review_count'] ?> avaliações)
                            </span>
                        </div>
                    <?php endif; ?>

                    <!-- Booking Form -->
                    <form method="POST" action="/rental" class="space-y-4">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        
                        <!-- Dates -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Data Início</label>
                                <input 
                                    type="date" 
                                    name="start_date" 
                                    required
                                    min="<?= date('Y-m-d') ?>"
                                    class="block w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Data Fim</label>
                                <input 
                                    type="date" 
                                    name="end_date" 
                                    required
                                    min="<?= date('Y-m-d') ?>"
                                    class="block w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                >
                            </div>
                        </div>

                        <!-- Delivery Method -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Método de Entrega</label>
                            <div class="space-y-2">
                                <?php if ($product['pickup_available']): ?>
                                    <label class="flex items-center">
                                        <input type="radio" name="delivery_method" value="pickup" checked class="text-primary-600 focus:ring-primary-500">
                                        <span class="ml-2 text-sm text-gray-700">Retirar no local</span>
                                    </label>
                                <?php endif; ?>
                                
                                <?php if ($product['delivery_available']): ?>
                                    <label class="flex items-center">
                                        <input type="radio" name="delivery_method" value="delivery" class="text-primary-600 focus:ring-primary-500">
                                        <span class="ml-2 text-sm text-gray-700">Entrega (taxa adicional)</span>
                                    </label>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Book Button -->
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <button 
                                type="submit"
                                class="w-full bg-gradient-to-r from-primary-500 to-primary-600 text-white py-3 px-4 rounded-lg font-medium hover:from-primary-600 hover:to-primary-700 transition-all duration-200 transform hover:scale-105"
                            >
                                <i class="fas fa-calendar-check mr-2"></i>
                                Reservar Agora
                            </button>
                        <?php else: ?>
                            <a 
                                href="/login"
                                class="block w-full bg-gradient-to-r from-primary-500 to-primary-600 text-white py-3 px-4 rounded-lg font-medium hover:from-primary-600 hover:to-primary-700 transition-all duration-200 transform hover:scale-105 text-center"
                            >
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Faça Login para Reservar
                            </a>
                        <?php endif; ?>

                        <!-- Contact Owner -->
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $product['user_id']): ?>
                            <a 
                                href="/chat?user=<?= $product['user_id'] ?>&product=<?= $product['id'] ?>"
                                class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-4 rounded-lg font-medium transition-colors text-center"
                            >
                                <i class="fas fa-comment mr-2"></i>
                                Conversar com o Proprietário
                            </a>
                        <?php elseif (!isset($_SESSION['user_id'])): ?>
                            <a 
                                href="/login"
                                class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-4 rounded-lg font-medium transition-colors text-center"
                            >
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Faça Login para Conversar
                            </a>
                        <?php else: ?>
                            <div class="block w-full bg-gray-100 text-gray-500 py-3 px-4 rounded-lg font-medium text-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                Este é seu produto
                            </div>
                        <?php endif; ?>
                    </form>

                    <!-- Owner Info -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="font-medium text-gray-900 mb-3">Proprietário</h4>
                        <div class="flex items-center">
                            <img 
                                src="/placeholder.svg?height=50&width=50" 
                                alt="<?= htmlspecialchars($owner['name']) ?>"
                                class="w-12 h-12 rounded-full object-cover"
                            >
                            <div class="ml-3">
                                <p class="font-medium text-gray-900"><?= htmlspecialchars($owner['name']) ?></p>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <?= htmlspecialchars($owner['city']) ?>, <?= htmlspecialchars($owner['state']) ?>
                                </p>
                                <?php if ($owner['average_rating']): ?>
                                    <div class="flex items-center mt-1">
                                        <i class="fas fa-star text-yellow-400 text-xs mr-1"></i>
                                        <span class="text-xs text-gray-600">
                                            <?= number_format($owner['average_rating'], 1) ?> • <?= $owner['total_products'] ?> produtos
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <?php if (!empty($reviews)): ?>
            <div class="mt-12 bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-bold text-gray-900">Avaliações</h3>
                    <?php if ($reviewStats['total_reviews'] > 0): ?>
                        <div class="flex items-center">
                            <div class="flex items-center mr-4">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= round($reviewStats['average_rating']) ? 'text-yellow-400' : 'text-gray-300' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="text-lg font-medium text-gray-900">
                                <?= number_format($reviewStats['average_rating'], 1) ?>
                            </span>
                            <span class="text-gray-500 ml-2">
                                (<?= $reviewStats['total_reviews'] ?> avaliações)
                            </span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($reviews as $review): ?>
                        <div class="border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <img 
                                    src="/placeholder.svg?height=40&width=40" 
                                    alt="<?= htmlspecialchars($review['reviewer_name']) ?>"
                                    class="w-10 h-10 rounded-full object-cover"
                                >
                                <div class="ml-3">
                                    <p class="font-medium text-gray-900"><?= htmlspecialchars($review['reviewer_name']) ?></p>
                                    <div class="flex items-center">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?= $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300' ?> text-sm"></i>
                                        <?php endfor; ?>
                                        <span class="text-sm text-gray-500 ml-2">
                                            <?= date('d/m/Y', strtotime($review['created_at'])) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($review['title']): ?>
                                <h4 class="font-medium text-gray-900 mb-2"><?= htmlspecialchars($review['title']) ?></h4>
                            <?php endif; ?>
                            
                            <p class="text-gray-700 mb-4"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                            
                            <?php if ($review['response']): ?>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm font-medium text-gray-900 mb-1">Resposta do proprietário:</p>
                                    <p class="text-sm text-gray-700"><?= nl2br(htmlspecialchars($review['response'])) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Similar Products -->
        <?php if (!empty($similarProducts)): ?>
            <div class="mt-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-8">Produtos Similares</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach ($similarProducts as $similar): ?>
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <div class="relative">
                                <img 
                                    src="/placeholder.svg?height=200&width=300" 
                                    alt="<?= htmlspecialchars($similar['title']) ?>"
                                    class="w-full h-48 object-cover"
                                >
                                <?php if ($similar['average_rating']): ?>
                                    <div class="absolute top-3 right-3 bg-white bg-opacity-90 px-2 py-1 rounded-full">
                                        <div class="flex items-center">
                                            <i class="fas fa-star text-yellow-400 text-xs mr-1"></i>
                                            <span class="text-xs font-medium"><?= number_format($similar['average_rating'], 1) ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="p-4">
                                <h4 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                    <?= htmlspecialchars($similar['title']) ?>
                                </h4>
                                
                                <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <?= htmlspecialchars($similar['owner_city']) ?>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-lg font-bold text-primary-600">
                                            R$ <?= number_format($similar['daily_price'], 2, ',', '.') ?>
                                        </span>
                                        <span class="text-xs text-gray-500">/dia</span>
                                    </div>
                                    
                                    <a href="/product/<?= $similar['id'] ?>" 
                                       class="bg-primary-500 hover:bg-primary-600 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                                        Ver
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function changeMainImage(src) {
    document.getElementById('main-image').src = src;
    
    // Update active thumbnail
    document.querySelectorAll('.grid img').forEach(img => {
        img.classList.remove('border-primary-500');
        img.classList.add('border-gray-200');
    });
    event.target.classList.remove('border-gray-200');
    event.target.classList.add('border-primary-500');
}

function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-primary-500', 'text-primary-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Add active class to clicked button
    event.target.classList.add('active', 'border-primary-500', 'text-primary-600');
    event.target.classList.remove('border-transparent', 'text-gray-500');
}
</script>
