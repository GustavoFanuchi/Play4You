<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Meu Perfil</h1>
            <p class="text-gray-600">Gerencie suas informações pessoais</p>
        </div>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="mb-4 p-4 rounded bg-green-50 text-green-800 border border-green-200">
                <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="mb-4 p-4 rounded bg-red-50 text-red-800 border border-red-200">
                <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="bg-white shadow rounded-lg">
            <form method="POST" action="/profile/update" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" value="<?= htmlspecialchars($profile['email']) ?>" disabled class="mt-1 block w-full bg-gray-100 border-gray-200 rounded-md shadow-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nome *</label>
                        <input type="text" name="name" id="name" value="<?= htmlspecialchars($profile['name']) ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Telefone</label>
                        <input type="tel" name="phone" id="phone" value="<?= htmlspecialchars($profile['phone']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" placeholder="(11) 99999-9999">
                    </div>

                    <div>
                        <label for="zip_code" class="block text-sm font-medium text-gray-700">CEP</label>
                        <input type="text" name="zip_code" id="zip_code" value="<?= htmlspecialchars($profile['zip_code']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" placeholder="00000-000">
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700">Endereço</label>
                        <input type="text" name="address" id="address" value="<?= htmlspecialchars($profile['address']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Rua, número, complemento">
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">Cidade</label>
                        <input type="text" name="city" id="city" value="<?= htmlspecialchars($profile['city']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700">Estado</label>
                        <input type="text" name="state" id="state" value="<?= htmlspecialchars($profile['state']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">Salvar Alterações</button>
                </div>
            </form>
        </div>

        <div class="mt-8 bg-white shadow rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-red-600">Excluir Conta</h2>
                <p class="text-sm text-gray-600 mt-1">Esta ação é irreversível. Todos os seus dados e produtos serão removidos.</p>
            </div>
            <form method="POST" action="/profile/delete" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm text-gray-700">Confirme digitando <strong>DELETE</strong></label>
                    <input type="text" name="confirm" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                </div>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Excluir Minha Conta</button>
            </form>
        </div>
    </div>
</div>


