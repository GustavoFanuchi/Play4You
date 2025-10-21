-- Insert sample users
INSERT INTO users (name, email, password, phone, city, state, profile_image, is_verified) VALUES
('João Silva', 'joao@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(11) 99999-0001', 'São Paulo', 'SP', 'placeholder-user.jpg', 1),
('Maria Santos', 'maria@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(11) 99999-0002', 'Rio de Janeiro', 'RJ', 'placeholder-user.jpg', 1),
('Pedro Costa', 'pedro@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(11) 99999-0003', 'Belo Horizonte', 'MG', 'placeholder-user.jpg', 1),
('Ana Oliveira', 'ana@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(11) 99999-0004', 'Brasília', 'DF', 'placeholder-user.jpg', 1),
('Carlos Lima', 'carlos@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(11) 99999-0005', 'Salvador', 'BA', 'placeholder-user.jpg', 1);

-- Insert sample products
INSERT INTO products (user_id, category_id, title, description, brand, model, condition_status, daily_price, weekly_price, monthly_price, deposit_amount, is_available, location_city, location_state, delivery_available, pickup_available, views_count) VALUES
(1, 1, 'PlayStation 5 Standard Edition', 'Console PlayStation 5 com 825GB de armazenamento. Inclui controle DualSense e cabo HDMI.', 'Sony', 'PS5 Standard', 'novo', 45.00, 280.00, 1000.00, 200.00, 1, 'São Paulo', 'SP', 1, 1, 15),
(1, 1, 'PlayStation 5 com Spider-Man 2', 'PS5 + Jogo Spider-Man 2 incluso. Console em perfeito estado.', 'Sony', 'PS5 + Game', 'seminovo', 50.00, 320.00, 1200.00, 250.00, 1, 'São Paulo', 'SP', 1, 1, 23),
(2, 2, 'Xbox Series X', 'Console Xbox Series X com 1TB de armazenamento. Performance máxima para jogos.', 'Microsoft', 'Xbox Series X', 'novo', 48.00, 300.00, 1100.00, 220.00, 1, 'Rio de Janeiro', 'RJ', 1, 1, 18),
(2, 4, 'PC Gamer RTX 4070', 'PC Gamer completo com RTX 4070, 16GB RAM, SSD 1TB. Ideal para jogos em 4K.', 'Custom', 'Gaming PC RTX 4070', 'seminovo', 80.00, 500.00, 1800.00, 400.00, 1, 'Rio de Janeiro', 'RJ', 1, 1, 31),
(3, 4, 'PC Gamer RTX 4090', 'PC Gamer de alta performance com RTX 4090. Máxima qualidade gráfica.', 'Custom', 'Gaming PC RTX 4090', 'novo', 120.00, 750.00, 2800.00, 600.00, 1, 'Belo Horizonte', 'MG', 1, 1, 42),
(3, 3, 'Nintendo Switch OLED', 'Nintendo Switch OLED com tela de 7 polegadas. Inclui Joy-Cons e dock.', 'Nintendo', 'Switch OLED', 'seminovo', 35.00, 220.00, 800.00, 150.00, 1, 'Belo Horizonte', 'MG', 1, 1, 27),
(4, 3, 'Steam Deck', 'Steam Deck 512GB. Console portátil para jogos PC.', 'Valve', 'Steam Deck 512GB', 'usado', 40.00, 250.00, 900.00, 180.00, 1, 'Brasília', 'DF', 1, 1, 19),
(4, 5, 'Headset Gamer HyperX', 'Headset gamer HyperX Cloud II com som 7.1 surround.', 'HyperX', 'Cloud II', 'seminovo', 15.00, 90.00, 320.00, 80.00, 1, 'Brasília', 'DF', 1, 1, 12),
(5, 1, 'PlayStation 5 Controle Extra', 'Controle DualSense extra para PlayStation 5. Cor branca.', 'Sony', 'DualSense', 'novo', 8.00, 45.00, 160.00, 40.00, 1, 'Salvador', 'BA', 1, 1, 8);

-- Insert sample rentals
INSERT INTO rentals (product_id, renter_id, owner_id, start_date, end_date, total_days, daily_rate, subtotal, service_fee, insurance_fee, total_amount, deposit_amount, delivery_method, status, payment_status, notes) VALUES
(1, 2, 1, '2024-01-15', '2024-01-17', 3, 45.00, 135.00, 15.00, 13.50, 163.50, 200.00, 'pickup', 'completed', 'paid', 'Cliente pontual, produto devolvido em perfeito estado.'),
(2, 3, 1, '2024-01-20', '2024-01-25', 6, 50.00, 300.00, 15.00, 30.00, 345.00, 250.00, 'delivery', 'completed', 'paid', 'Entrega realizada com sucesso. Cliente satisfeito.'),
(3, 4, 2, '2024-02-01', '2024-02-03', 3, 48.00, 144.00, 15.00, 14.40, 173.40, 220.00, 'pickup', 'completed', 'paid', 'Console funcionando perfeitamente.'),
(4, 5, 2, '2024-02-10', '2024-02-15', 6, 80.00, 480.00, 15.00, 48.00, 543.00, 400.00, 'delivery', 'active', 'paid', 'PC em uso. Cliente reportou excelente performance.'),
(5, 1, 3, '2024-02-20', '2024-02-22', 3, 120.00, 360.00, 15.00, 36.00, 411.00, 600.00, 'pickup', 'pending', 'pending', 'Aguardando confirmação de pagamento.'),
(6, 2, 3, '2024-02-25', '2024-02-28', 4, 35.00, 140.00, 15.00, 14.00, 169.00, 150.00, 'pickup', 'confirmed', 'paid', 'Reserva confirmada para fim de semana.'),
(7, 4, 4, '2024-03-01', '2024-03-05', 5, 40.00, 200.00, 15.00, 20.00, 235.00, 180.00, 'delivery', 'pending', 'pending', 'Aguardando confirmação.'),
(8, 5, 4, '2024-03-10', '2024-03-12', 3, 15.00, 45.00, 15.00, 4.50, 64.50, 80.00, 'pickup', 'completed', 'paid', 'Headset devolvido em perfeito estado.'),
(9, 1, 5, '2024-03-15', '2024-03-17', 3, 8.00, 24.00, 15.00, 2.40, 41.40, 40.00, 'pickup', 'completed', 'paid', 'Controle funcionando perfeitamente.');

-- Insert sample reviews
INSERT INTO reviews (rental_id, reviewer_id, reviewed_id, product_id, rating, title, comment, response, response_date) VALUES
(1, 2, 1, 1, 5, 'Excelente console!', 'PS5 funcionando perfeitamente. Entrega rápida e produto em ótimo estado.', 'Obrigado pelo feedback! Foi um prazer atendê-lo.', '2024-01-18 10:30:00'),
(2, 3, 1, 2, 5, 'Perfeito!', 'Console e jogo funcionando perfeitamente. Recomendo!', 'Que bom que gostou! Volte sempre.', '2024-01-26 14:20:00'),
(3, 4, 2, 3, 4, 'Muito bom', 'Xbox funcionando bem, só demorou um pouco para entregar.', 'Obrigado pelo feedback. Vamos melhorar a entrega.', '2024-02-04 09:15:00'),
(8, 5, 4, 8, 5, 'Headset excelente', 'Som de qualidade e conforto incrível. Recomendo!', 'Fico feliz que tenha gostado!', '2024-03-13 16:45:00'),
(9, 1, 5, 9, 4, 'Controle bom', 'Controle funcionando bem, só o preço um pouco alto.', 'Obrigado pelo feedback. Vamos revisar os preços.', '2024-03-18 11:30:00');
