<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Editar Produto</h1>
            <p class="text-gray-600">Atualize as informações do seu produto</p>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg">
            <form method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700">Título do Produto *</label>
                        <input type="text" name="title" id="title" value="<?= htmlspecialchars($product['title']) ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Categoria *</label>
                        <select name="category_id" id="category_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Selecione uma categoria</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= $category['id'] == $product['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Brand -->
                    <div>
                        <label for="brand" class="block text-sm font-medium text-gray-700">Marca</label>
                        <input type="text" name="brand" id="brand" value="<?= htmlspecialchars($product['brand']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <!-- Model -->
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700">Modelo</label>
                        <input type="text" name="model" id="model" value="<?= htmlspecialchars($product['model']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <!-- Condition -->
                    <div>
                        <label for="condition_status" class="block text-sm font-medium text-gray-700">Condição *</label>
                        <select name="condition_status" id="condition_status" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                            <option value="novo" <?= $product['condition_status'] == 'novo' ? 'selected' : '' ?>>Novo</option>
                            <option value="seminovo" <?= $product['condition_status'] == 'seminovo' ? 'selected' : '' ?>>Seminovo</option>
                            <option value="usado" <?= $product['condition_status'] == 'usado' ? 'selected' : '' ?>>Usado</option>
                        </select>
                    </div>

                    <!-- Daily Price -->
                    <div>
                        <label for="daily_price" class="block text-sm font-medium text-gray-700">Preço por Dia (R$) *</label>
                        <input type="number" name="daily_price" id="daily_price" value="<?= $product['daily_price'] ?>" step="0.01" min="0" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <!-- Weekly Price -->
                    <div>
                        <label for="weekly_price" class="block text-sm font-medium text-gray-700">Preço por Semana (R$)</label>
                        <input type="number" name="weekly_price" id="weekly_price" value="<?= $product['weekly_price'] ?>" step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <!-- Monthly Price -->
                    <div>
                        <label for="monthly_price" class="block text-sm font-medium text-gray-700">Preço por Mês (R$)</label>
                        <input type="number" name="monthly_price" id="monthly_price" value="<?= $product['monthly_price'] ?>" step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <!-- Deposit -->
                    <div>
                        <label for="deposit_amount" class="block text-sm font-medium text-gray-700">Valor do Depósito (R$) *</label>
                        <input type="number" name="deposit_amount" id="deposit_amount" value="<?= $product['deposit_amount'] ?>" step="0.01" min="0" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="location_city" class="block text-sm font-medium text-gray-700">Cidade *</label>
                        <input type="text" name="location_city" id="location_city" value="<?= htmlspecialchars($product['location_city']) ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div>
                        <label for="location_state" class="block text-sm font-medium text-gray-700">Estado *</label>
                        <input type="text" name="location_state" id="location_state" value="<?= htmlspecialchars($product['location_state']) ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Descrição *</label>
                    <textarea name="description" id="description" rows="4" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"><?= htmlspecialchars($product['description']) ?></textarea>
                </div>

                <!-- Policies -->
                <div>
                    <label for="policies" class="block text-sm font-medium text-gray-700">Políticas de Uso</label>
                    <textarea name="policies" id="policies" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"><?= htmlspecialchars($product['policies']) ?></textarea>
                </div>

                <!-- Images -->
                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700">Adicionar novas fotos</label>
                    <input 
                        type="file" 
                        name="images[]" 
                        id="images" 
                        accept="image/*" 
                        multiple 
                        class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100"
                    >
                    <p class="mt-1 text-xs text-gray-500">As imagens novas serão adicionadas às existentes.</p>
                </div>

                <!-- Delivery Options -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Opções de Entrega</label>
                    <div class="mt-2 space-y-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="delivery_available" value="1" <?= $product['delivery_available'] ? 'checked' : '' ?> class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Entrega disponível</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="pickup_available" value="1" <?= $product['pickup_available'] ? 'checked' : '' ?> class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Retirada disponível</span>
                        </label>
                    </div>
                </div>

                <!-- Availability -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Disponibilidade</label>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_available" value="1" <?= $product['is_available'] ? 'checked' : '' ?> class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Produto disponível para aluguel</span>
                        </label>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3">
                    <a href="/dashboard" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition-colors duration-200">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        Atualizar Produto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
