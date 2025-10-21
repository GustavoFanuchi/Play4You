<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 text-white overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center animate-fade-in">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                Alugue os Melhores
                <span class="text-accent-400">Games</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-primary-100 max-w-3xl mx-auto">
                PlayStation, Xbox, Nintendo Switch e PCs Gamers. 
                Jogue sem limites, pague apenas pelo tempo que usar!
            </p>
            
            <!-- Search Form -->
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl p-6 animate-slide-up">
                <form method="GET" action="/catalog" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search Input -->
                    <div class="md:col-span-2">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                name="search"
                                placeholder="Buscar jogos, consoles, PCs..."
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900"
                            >
                        </div>
                    </div>
                    
                    <!-- Location -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                name="city"
                                placeholder="Cidade"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900"
                            >
                        </div>
                    </div>
                    
                    <!-- Search Button -->
                    <button 
                        type="submit"
                        class="bg-gradient-to-r from-accent-500 to-accent-600 text-white py-3 px-6 rounded-lg font-medium hover:from-accent-600 hover:to-accent-700 transition-all duration-200 transform hover:scale-105"
                    >
                        <i class="fas fa-search mr-2"></i>
                        Buscar
                    </button>
                </form>
                
                <!-- Quick Filters -->
                <div class="flex flex-wrap justify-center gap-3 mt-6">
                    <span class="inline-flex items-center px-4 py-2 bg-primary-100 text-primary-800 rounded-full text-sm font-medium">
                        <i class="fas fa-bolt mr-2"></i>
                        Entrega Rápida
                    </span>
                    <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Seguro Incluso
                    </span>
                    <span class="inline-flex items-center px-4 py-2 bg-accent-100 text-accent-800 rounded-full text-sm font-medium">
                        <i class="fas fa-star mr-2"></i>
                        Avaliado 4.8/5
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Floating Elements -->
    <div class="absolute top-20 left-10 animate-bounce-gentle opacity-20">
        <i class="fas fa-gamepad text-6xl"></i>
    </div>
    <div class="absolute bottom-20 right-10 animate-bounce-gentle opacity-20" style="animation-delay: 1s;">
        <i class="fas fa-desktop text-5xl"></i>
    </div>
</section>

<!-- Categories Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 animate-on-scroll">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Explore por Categoria
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Encontre exatamente o que você procura
            </p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
            <?php foreach ($categories as $category): ?>
                <a href="/catalog?category=<?= $category['id'] ?>" 
                   class="group bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-on-scroll">
                    <div class="text-center">
                        <div class="bg-gradient-to-r from-primary-500 to-primary-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <i class="<?= $category['icon'] ?> text-white text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2"><?= htmlspecialchars($category['name']) ?></h3>
                        <p class="text-sm text-gray-500">Ver produtos</p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 animate-on-scroll">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Produtos em Destaque
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Os mais procurados da semana
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-on-scroll">
                    <div class="relative">
                        <img 
                            src="/placeholder.svg?height=200&width=300" 
                            alt="<?= htmlspecialchars($product['title']) ?>"
                            class="w-full h-48 object-cover"
                        >
                        <div class="absolute top-3 left-3">
                            <span class="bg-primary-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                <?= htmlspecialchars($product['category_name']) ?>
                            </span>
                        </div>
                        <?php if ($product['average_rating']): ?>
                            <div class="absolute top-3 right-3 bg-white bg-opacity-90 px-2 py-1 rounded-full">
                                <div class="flex items-center">
                                    <i class="fas fa-star text-yellow-400 text-xs mr-1"></i>
                                    <span class="text-xs font-medium"><?= number_format($product['average_rating'], 1) ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                            <?= htmlspecialchars($product['title']) ?>
                        </h3>
                        
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
        
        <div class="text-center mt-12">
            <a href="/catalog" 
               class="inline-flex items-center bg-gradient-to-r from-primary-500 to-primary-600 text-white px-8 py-3 rounded-lg font-medium hover:from-primary-600 hover:to-primary-700 transition-all duration-200 transform hover:scale-105">
                <i class="fas fa-th-large mr-2"></i>
                Ver Todos os Produtos
            </a>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 animate-on-scroll">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Como Funciona
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Simples, rápido e seguro
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center animate-on-scroll">
                <div class="bg-gradient-to-r from-primary-500 to-primary-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search text-white text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">1. Encontre</h3>
                <p class="text-gray-600">
                    Busque por jogos, consoles ou PCs gamers na sua região
                </p>
            </div>
            
            <div class="text-center animate-on-scroll">
                <div class="bg-gradient-to-r from-accent-500 to-accent-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-calendar-check text-white text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">2. Reserve</h3>
                <p class="text-gray-600">
                    Escolha as datas e confirme sua reserva de forma segura
                </p>
            </div>
            
            <div class="text-center animate-on-scroll">
                <div class="bg-gradient-to-r from-green-500 to-green-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-gamepad text-white text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">3. Jogue</h3>
                <p class="text-gray-600">
                    Retire ou receba em casa e aproveite sua experiência gaming
                </p>
            </div>
        </div>
    </div>
</section>
