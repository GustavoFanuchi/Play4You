<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Perguntas Frequentes</h1>
            <p class="text-xl text-gray-600">
                Encontre respostas para as dúvidas mais comuns sobre a Play4You
            </p>
        </div>

        <!-- Search -->
        <div class="mb-8">
            <div class="relative max-w-md mx-auto">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input 
                    type="text" 
                    id="faq-search"
                    placeholder="Buscar perguntas..."
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    onkeyup="searchFAQ()"
                >
            </div>
        </div>

        <!-- FAQ Categories -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- General Questions -->
            <div class="faq-category">
                <div class="bg-primary-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-primary-900">Perguntas Gerais</h2>
                </div>
                
                <div class="divide-y divide-gray-200">
                    <div class="faq-item p-6">
                        <button class="faq-question w-full text-left flex items-center justify-between" onclick="toggleFAQ(this)">
                            <span class="font-medium text-gray-900">O que é a Play4You?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden mt-4 text-gray-600">
                            <p>
                                A Play4You é uma plataforma online que conecta proprietários de videogames, consoles e equipamentos 
                                relacionados com pessoas interessadas em alugá-los. É como um "Airbnb" para equipamentos de gaming.
                            </p>
                        </div>
                    </div>

                    <div class="faq-item p-6">
                        <button class="faq-question w-full text-left flex items-center justify-between" onclick="toggleFAQ(this)">
                            <span class="font-medium text-gray-900">Como funciona o processo de aluguel?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden mt-4 text-gray-600">
                            <p>
                                É simples: você busca o produto desejado, seleciona as datas, confirma o pagamento e combina 
                                a entrega/retirada com o proprietário. Após o uso, você devolve o produto nas mesmas condições.
                            </p>
                        </div>
                    </div>

                    <div class="faq-item p-6">
                        <button class="faq-question w-full text-left flex items-center justify-between" onclick="toggleFAQ(this)">
                            <span class="font-medium text-gray-900">É seguro alugar equipamentos?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden mt-4 text-gray-600">
                            <p>
                                Sim! Todos os aluguéis incluem seguro contra danos acidentais e roubo. Além disso, temos um 
                                sistema de avaliações que ajuda a garantir a confiabilidade dos usuários.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rental Process -->
            <div class="faq-category">
                <div class="bg-accent-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-accent-900">Processo de Aluguel</h2>
                </div>
                
                <div class="divide-y divide-gray-200">
                    <div class="faq-item p-6">
                        <button class="faq-question w-full text-left flex items-center justify-between" onclick="toggleFAQ(this)">
                            <span class="font-medium text-gray-900">Como faço uma reserva?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden mt-4 text-gray-600">
                            <p>
                                Encontre o produto desejado, clique em "Ver Detalhes", selecione as datas de início e fim, 
                                escolha o método de entrega e confirme o pagamento. Você receberá uma confirmação por email.
                            </p>
                        </div>
                    </div>

                    <div class="faq-item p-6">
                        <button class="faq-question w-full text-left flex items-center justify-between" onclick="toggleFAQ(this)">
                            <span class="font-medium text-gray-900">Posso cancelar minha reserva?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden mt-4 text-gray-600">
                            <p>
                                Sim, você pode cancelar gratuitamente até 24 horas antes do início do aluguel. 
                                Cancelamentos após este prazo podem estar sujeitos a taxas de cancelamento.
                            </p>
                        </div>
                    </div>

                    <div class="faq-item p-6">
                        <button class="faq-question w-full text-left flex items-center justify-between" onclick="toggleFAQ(this)">
                            <span class="font-medium text-gray-900">Como recebo o produto?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden mt-4 text-gray-600">
                            <p>
                                Você pode escolher entre retirar no local (gratuito) ou solicitar entrega em casa 
                                (taxa adicional de R$ 25,00). Os detalhes são combinados diretamente com o proprietário.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payments -->
            <div class="faq-category">
                <div class="bg-green-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-green-900">Pagamentos e Taxas</h2>
                </div>
                
                <div class="divide-y divide-gray-200">
                    <div class="faq-item p-6">
                        <button class="faq-question w-full text-left flex items-center justify-between" onclick="toggleFAQ(this)">
                            <span class="font-medium text-gray-900">Quais são as taxas cobradas?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden mt-4 text-gray-600">
                            <p>
                                Cobramos uma taxa de serviço de 10% sobre o valor do aluguel e uma taxa de seguro de 5%. 
                                Entrega em casa tem taxa adicional de R$ 25,00.
                            </p>
                        </div>
                    </div>

                    <div class="faq-item p-6">
                        <button class="faq-question w-full text-left flex items-center justify-between" onclick="toggleFAQ(this)">
                            <span class="font-medium text-gray-900">O que é o depósito caução?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden mt-4 text-gray-600">
                            <p>
                                É um valor temporariamente bloqueado no seu cartão para cobrir possíveis danos. 
                                Ele é liberado automaticamente após a devolução do produto em perfeitas condições.
                            </p>
                        </div>
                    </div>

                    <div class="faq-item p-6">
                        <button class="faq-question w-full text-left flex items-center justify-between" onclick="toggleFAQ(this)">
                            <span class="font-medium text-gray-900">Quais formas de pagamento aceitas?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden mt-4 text-gray-600">
                            <p>
                                Aceitamos cartões de crédito (Visa, Mastercard, Elo), cartões de débito, 
                                PIX e boleto bancário (para pagamentos com antecedência).
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Safety -->
            <div class="faq-category">
                <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-blue-900">Segurança</h2>
                </div>
                
                <div class="divide-y divide-gray-200">
                    <div class="faq-item p-6">
                        <button class="faq-question w-full text-left flex items-center justify-between" onclick="toggleFAQ(this)">
                            <span class="font-medium text-gray-900">E se o produto for danificado?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden mt-4 text-gray-600">
                            <p>
                                Danos acidentais estão cobertos pelo nosso seguro. Você deve reportar qualquer problema 
                                imediatamente. Danos intencionais ou por negligência grave são de responsabilidade do locatário.
                            </p>
                        </div>
                    </div>

                    <div class="faq-item p-6">
                        <button class="faq-question w-full text-left flex items-center justify-between" onclick="toggleFAQ(this)">
                            <span class="font-medium text-gray-900">Como vocês verificam os usuários?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden mt-4 text-gray-600">
                            <p>
                                Todos os usuários passam por verificação de identidade e temos um sistema de avaliações 
                                mútuas. Usuários com histórico problemático podem ter suas contas suspensas.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact CTA -->
        <div class="mt-12 text-center">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">
                    Não encontrou sua resposta?
                </h3>
                <p class="text-gray-600 mb-6">
                    Nossa equipe de suporte está sempre pronta para ajudar você
                </p>
                <a 
                    href="/contact"
                    class="inline-flex items-center bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-medium transition-colors"
                >
                    <i class="fas fa-headset mr-2"></i>
                    Entrar em Contato
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFAQ(button) {
    const answer = button.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (answer.classList.contains('hidden')) {
        answer.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        answer.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

function searchFAQ() {
    const searchTerm = document.getElementById('faq-search').value.toLowerCase();
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question span').textContent.toLowerCase();
        const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
        
        if (question.includes(searchTerm) || answer.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = searchTerm === '' ? 'block' : 'none';
        }
    });
}
</script>
