<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Catálogo de Produtos</h1>
                    <p class="text-gray-600 mt-2">
                        <?= number_format($totalProducts) ?> produtos encontrados
                    </p>
                </div>
                
                <!-- Sort Options -->
                <div class="mt-4 md:mt-0">
                    <form method="GET" class="flex items-center space-x-4">
                        <!-- Preserve existing filters -->
                        <?php foreach ($filters as $key => $value): ?>
                            <?php if ($key !== 'sort' && !empty($value)): ?>
                                <input type="hidden" name="<?= $key ?>" value="<?= htmlspecialchars($value) ?>">
                            <?php endif; ?>
                        <?php endforeach; ?>
                        
                        <label class="text-sm font-medium text-gray-700">Ordenar por:</label>
                        <select name="sort" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="newest" <?= $filters['sort'] === 'newest' ? 'selected' : '' ?>>Mais Recentes</option>
                            <option value="popular" <?= $filters['sort'] === 'popular' ? 'selected' : '' ?>>Mais Populares</option>
                            <option value="price_low" <?= $filters['sort'] === 'price_low' ? 'selected' : '' ?>>Menor Preço</option>
                            <option value="price_high" <?= $filters['sort'] === 'price_high' ? 'selected' : '' ?>>Maior Preço</option>
                            <option value="rating" <?= $filters['sort'] === 'rating' ? 'selected' : '' ?>>Melhor Avaliados</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Filtros</h3>
                    
                    <form method="GET" id="filter-form">
                        <!-- Search -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="<?= htmlspecialchars($filters['search']) ?>"
                                    placeholder="Buscar produtos..."
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                >
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                            <select name="category" class="block w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Todas as categorias</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" <?= $filters['category'] == $category['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['name']) ?> (<?= $category['product_count'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Faixa de Preço (por dia)</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input 
                                    type="number" 
                                    name="min_price" 
                                    value="<?= htmlspecialchars($filters['min_price']) ?>"
                                    placeholder="Mín"
                                    class="block w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                >
                                <input 
                                    type="number" 
                                    name="max_price" 
                                    value="<?= htmlspecialchars($filters['max_price']) ?>"
                                    placeholder="Máx"
                                    class="block w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                >
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Localização</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-map-marker-alt text-gray-400"></i>
                                </div>
                                <input 
                                    type="text" 
                                    name="city" 
                                    value="<?= htmlspecialchars($filters['city']) ?>"
                                    placeholder="Cidade"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                >
                            </div>
                        </div>

                        <!-- Condition -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                            <select name="condition" class="block w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Todos os estados</option>
                                <option value="novo" <?= $filters['condition'] === 'novo' ? 'selected' : '' ?>>Novo</option>
                                <option value="seminovo" <?= $filters['condition'] === 'seminovo' ? 'selected' : '' ?>>Seminovo</option>
                                <option value="usado" <?= $filters['condition'] === 'usado' ? 'selected' : '' ?>>Usado</option>
                            </select>
                        </div>

                        <!-- Brand -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Marca</label>
                            <select name="brand" class="block w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Todas as marcas</option>
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?= htmlspecialchars($brand) ?>" <?= $filters['brand'] === $brand ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($brand) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="flex space-x-2">
                            <button 
                                type="submit"
                                class="flex-1 bg-primary-500 hover:bg-primary-600 text-white py-2 px-4 rounded-lg font-medium transition-colors"
                            >
                                Filtrar
                            </button>
                            <a 
                                href="/catalog"
                                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded-lg font-medium transition-colors text-center"
                            >
                                Limpar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="lg:w-3/4">
                <?php if (empty($products)): ?>
                    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                        <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhum produto encontrado</h3>
                        <p class="text-gray-600 mb-6">Tente ajustar os filtros ou fazer uma nova busca.</p>
                        <a href="/catalog" class="inline-flex items-center bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-refresh mr-2"></i>
                            Ver Todos os Produtos
                        </a>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <?php foreach ($products as $product): ?>
                            <?php 
                            $cardImage = '/placeholder.svg?height=200&width=300';
                            if (!empty($product['images'])) {
                                $decoded = json_decode($product['images'], true);
                                if (is_array($decoded) && !empty($decoded)) {
                                    $first = $decoded[0];
                                    if (is_string($first) && $first !== '') {
                                        $cardImage = ($first[0] === '/' ? $first : '/' . $first);
                                    }
                                }
                            }
                            ?>
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-fade-in">
                                <div class="relative">
                                    <img 
                                        src="<?= htmlspecialchars($cardImage) ?>" 
                                        alt="<?= htmlspecialchars($product['title']) ?>"
                                        class="w-full h-48 object-cover"
                                    >
                                    
                                    <!-- Category Badge -->
                                    <div class="absolute top-3 left-3">
                                        <span class="bg-primary-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                            <?= htmlspecialchars($product['category_name']) ?>
                                        </span>
                                    </div>
                                    
                                    <!-- Condition Badge -->
                                    <div class="absolute top-3 right-3">
                                        <?php
                                        $conditionColors = [
                                            'novo' => 'bg-green-500',
                                            'seminovo' => 'bg-yellow-500',
                                            'usado' => 'bg-orange-500'
                                        ];
                                        $conditionColor = $conditionColors[$product['condition_status']] ?? 'bg-gray-500';
                                        ?>
                                        <span class="<?= $conditionColor ?> text-white px-2 py-1 rounded-full text-xs font-medium">
                                            <?= ucfirst($product['condition_status']) ?>
                                        </span>
                                    </div>
                                    
                                    <!-- Rating -->
                                    <?php if ($product['average_rating']): ?>
                                        <div class="absolute bottom-3 right-3 bg-white bg-opacity-90 px-2 py-1 rounded-full">
                                            <div class="flex items-center">
                                                <i class="fas fa-star text-yellow-400 text-xs mr-1"></i>
                                                <span class="text-xs font-medium"><?= number_format($product['average_rating'], 1) ?></span>
                                                <span class="text-xs text-gray-500 ml-1">(<?= $product['review_count'] ?>)</span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="p-6">
                                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                        <?= htmlspecialchars($product['title']) ?>
                                    </h3>
                                    
                                    <?php if ($product['brand']): ?>
                                        <p class="text-sm text-gray-500 mb-2">
                                            <i class="fas fa-tag mr-1"></i>
                                            <?= htmlspecialchars($product['brand']) ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <div class="flex items-center text-sm text-gray-500 mb-3">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        <?= htmlspecialchars($product['owner_city']) ?>, <?= htmlspecialchars($product['owner_state']) ?>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="text-2xl font-bold text-primary-600">
                                                R$ <?= number_format($product['daily_price'], 2, ',', '.') ?>
                                            </span>
                                            <span class="text-sm text-gray-500">/dia</span>
                                        </div>
                                        
                                        <a href="/product/<?= $product['id'] ?>" 
                                           class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            Ver Detalhes
                                        </a>
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
                                    <a href="?<?= http_build_query(array_merge($filters, ['page' => $currentPage - 1])) ?>" 
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
                                    <a href="?<?= http_build_query(array_merge($filters, ['page' => $i])) ?>" 
                                       class="px-3 py-2 rounded-lg <?= $i === $currentPage ? 'bg-primary-500 text-white' : 'border border-gray-300 text-gray-500 hover:bg-gray-50' ?> transition-colors">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>

                                <!-- Next Page -->
                                <?php if ($currentPage < $totalPages): ?>
                                    <a href="?<?= http_build_query(array_merge($filters, ['page' => $currentPage + 1])) ?>" 
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
    </div>
</div>
