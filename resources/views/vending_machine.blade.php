<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vending Machine</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .mall-environment {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .vending-machine {
            width: 450px;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            border-radius: 20px;
            padding: 30px;
            box-shadow:
                0 30px 60px rgba(0, 0, 0, 0.5),
                inset 0 2px 10px rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .machine-header {
            text-align: center;
            color: #ecf0f1;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .display-screen {
            background: #1a1a1a;
            border: 4px solid #34495e;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            min-height: 100px;
            box-shadow: inset 0 4px 10px rgba(0, 0, 0, 0.8);
            position: relative;
            overflow: hidden;
        }

        .display-screen::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: scan 6s infinite;
        }

        @keyframes scan {
            0%, 100% { left: -100%; }
            50% { left: 100%; }
        }

        .display-content {
            color: #00ff00;
            font-family: 'Courier New', monospace;
            font-size: 16px;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        .balance-display {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #00ff88;
        }

        .message-display {
            font-size: 14px;
            color: #00ff00;
            min-height: 20px;
        }

        .message-display.error {
            color: #ff4444;
            animation: blink 0.5s ease-in-out 3;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .items-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
            background: rgba(0, 0, 0, 0.3);
            padding: 20px;
            border-radius: 10px;
            position: relative;
        }

        .items-grid #items-disable{
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #202D3B;
            z-index: 10;
            opacity: 40%;
            display: none;
        }

        .item-slot {
            background: linear-gradient(145deg, #3a4a5c, #2c3642);
            border: 2px solid #4a5a6c;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            position: relative;
            overflow: hidden;
            pointer-events: none;
        }

        .item-slot:hover:not(.out-of-stock) {
            border-color: #3498db;
        }

        .item-slot.out-of-stock {
            opacity: 0.4;
        }

        .item-slot.out-of-stock::after {
            content: 'OUT OF STOCK';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            background: #e74c3c;
            color: white;
            padding: 5px 10px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 5px;
        }

        .item-button {
            margin-top: 10px;
            width: 100%;
            padding: 10px 10px 10px 8px;
            border: 2px solid #555;
            border-radius: 8px;
            background: linear-gradient(145deg, #2a2a2a, #1a1a1a);
            color: #666;
            font-weight: bold;
            font-size: 12px;
            cursor: not-allowed;
            transition: all 0.3s ease;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .item-button.active {
            background: linear-gradient(145deg, #27ae60, #229954);
            border-color: #27ae60;
            color: white;
            cursor: pointer;
            box-shadow:
                inset 0 2px 5px rgba(0, 0, 0, 0.3),
                0 0 15px rgba(39, 174, 96, 0.6),
                0 0 30px rgba(39, 174, 96, 0.3);
            animation: pulse 2s ease-in-out infinite;
            pointer-events: all;
        }

        .item-button.active:hover {
            transform: translateY(-2px);
            box-shadow:
                inset 0 2px 5px rgba(0, 0, 0, 0.3),
                0 0 20px rgba(39, 174, 96, 0.8),
                0 0 40px rgba(39, 174, 96, 0.4);
        }

        .item-button.active:active {
            transform: translateY(0);
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow:
                    inset 0 2px 5px rgba(0, 0, 0, 0.3),
                    0 0 15px rgba(39, 174, 96, 0.6),
                    0 0 30px rgba(39, 174, 96, 0.3);
            }
            50% {
                box-shadow:
                    inset 0 2px 5px rgba(0, 0, 0, 0.3),
                    0 0 20px rgba(39, 174, 96, 0.8),
                    0 0 40px rgba(39, 174, 96, 0.5);
            }
        }

        .item-icon {
            font-size: 40px;
            margin-bottom: 10px;
            filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.5));
        }

        .item-name {
            color: #ecf0f1;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .item-price {
            color: #3498db;
            font-size: 18px;
            font-weight: bold;
        }

        .item-stock {
            color: #95a5a6;
            font-size: 11px;
            margin-top: 5px;
        }

        .controls-section {
            background: rgba(0, 0, 0, 0.2);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            position: relative;
        }

        .controls-section #controls-disable {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #31465A;
            z-index: 10;
            opacity: 40%;
            display: none;
        }

        .coin-slots {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .coin-button {
            background: linear-gradient(145deg, #f39c12, #e67e22);
            border: none;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            font-size: 18px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .coin-button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 15px rgba(243, 156, 18, 0.5);
        }

        .coin-button:active {
            transform: scale(0.95);
        }

        .coin-button.inserting {
            animation: coinInsert 0.5s ease;
        }

        @keyframes coinInsert {
            0% { transform: translateY(0); }
            50% { transform: translateY(10px); }
            100% { transform: translateY(0); }
        }

        .return-coin-button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(145deg, #e74c3c, #c0392b);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .return-coin-button:hover {
            background: linear-gradient(145deg, #c0392b, #e74c3c);
            box-shadow: 0 6px 15px rgba(231, 76, 60, 0.5);
        }

        .return-coin-button.returning {
            animation: coinInsert 0.5s ease;
        }

        .dispenser {
            background: #000;
            border: 3px solid #34495e;
            border-radius: 10px;
            height: 80px;
            position: relative;
            overflow: hidden;
            box-shadow: inset 0 4px 10px rgba(0, 0, 0, 0.8);
        }

        .dispenser-flap {
            background: #2c3e50;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            border-top: 2px solid #34495e;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7f8c8d;
            font-size: 12px;
        }

        .dispensed-item {
            position: absolute;
            font-size: 40px;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
            animation: dispense 1s ease forwards;
        }

        @keyframes dispense {
            0% { top: -50px; opacity: 0; }
            50% { opacity: 1; }
            100% { top: 20px; opacity: 1; }
        }

        .coin-return {
            position: absolute;
            font-size: 24px;
            bottom: 5px;
            left: 20px;
            animation: coinReturn 0.8s ease forwards;
        }

        @keyframes coinReturn {
            0% { transform: translateX(-100px); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translateX(0); opacity: 1; }
        }

        .service-panel {
            position: absolute;
            top: 30px;
            left: -5px;
            width: 40px;
            height: 60px;
            background: #2c3e50;
            border: 2px solid #34495e;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.3);
        }

        .service-panel:hover {
            left: 0;
            box-shadow: -2px 0 10px rgba(52, 152, 219, 0.5);
        }

        .service-panel::before {
            content: '⚙';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            color: #7f8c8d;
        }

        .service-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .service-modal.active {
            display: flex;
        }

        .service-content {
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            padding: 30px;
            border-radius: 15px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .service-header {
            color: #ecf0f1;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .service-section {
            background: rgba(0, 0, 0, 0.2);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            position: relative;
        }

        .service-disable{
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #243342;
            z-index: 10;
            opacity: 40%;
            display: none;
        }

        .service-section h3 {
            color: #3498db;
            margin-bottom: 10px;
        }

        .service-input-group {
            margin-bottom: 10px;
        }

        .service-input-group label {
            color: #ecf0f1;
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .service-input-group input {
            width: 100%;
            padding: 8px;
            border: 2px solid #34495e;
            border-radius: 5px;
            background: #1a1a1a;
            color: #00ff00;
            font-family: 'Courier New', monospace;
        }

        .service-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .service-button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .service-button.save {
            background: linear-gradient(145deg, #27ae60, #229954);
            color: white;
        }

        .service-button.cancel {
            background: linear-gradient(145deg, #95a5a6, #7f8c8d);
            color: white;
        }

        .service-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .add-stock-btn {
            padding: 8px 15px;
            background: linear-gradient(145deg, #27ae60, #229954);
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 12px;
        }

        .add-stock-btn:hover {
            background: linear-gradient(145deg, #229954, #27ae60);
            transform: scale(1.05);
        }

        .add-stock-btn:active {
            transform: scale(0.95);
        }
    </style>
</head>
<body>
    <div class="mall-environment">
        <div class="vending-machine">
            <div class="service-panel" id="servicePanelBtn"></div>

            <div class="machine-header">VENDING MACHINE CHALLENGE</div>

            <div class="display-screen">
                <div class="display-content">
                    <div class="balance-display">Balance: $<span id="balance">0.00</span></div>
                    <div class="message-display" id="message">Insert coins to begin...</div>
                </div>
            </div>

            <div class="items-grid" id="itemsGrid">
                <!-- Items will be loaded here -->
            </div>

            <div class="controls-section">
                <div id="controls-disable"></div>
                <div class="coin-slots">
                    <button class="coin-button" data-coin="0.05">5¢</button>
                    <button class="coin-button" data-coin="0.10">10¢</button>
                    <button class="coin-button" data-coin="0.25">25¢</button>
                    <button class="coin-button" data-coin="1.00">$1</button>
                </div>
                <button class="return-coin-button" id="returnCoinBtn">RETURN COINS</button>
            </div>

            <div class="dispenser" id="dispenser">
                <div class="dispenser-flap">PUSH</div>
            </div>
        </div>
    </div>

    <div class="service-modal" id="serviceModal">
        <div class="service-content">
            <div class="service-header">🔧 SERVICE MODE</div>

            <div class="service-section">
                <div class="service-disable"></div>
                <h3>Restock Items</h3>
                <div class="service-input-group">
                    <label>Water Stock:</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <span style="color: #3498db; font-weight: bold; min-width: 30px;">Current: <span id="currentWater">0</span></span>
                        <input type="number" id="waterStock" min="0" value="0" placeholder="0" style="flex: 1;">
                        <button class="add-stock-btn" data-item="water">ADD</button>
                    </div>
                </div>
                <div class="service-input-group">
                    <label>Juice Stock:</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <span style="color: #3498db; font-weight: bold; min-width: 30px;">Current: <span id="currentJuice">0</span></span>
                        <input type="number" id="juiceStock" min="0" value="0" placeholder="0" style="flex: 1;">
                        <button class="add-stock-btn" data-item="juice">ADD</button>
                    </div>
                </div>
                <div class="service-input-group">
                    <label>Soda Stock:</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <span style="color: #3498db; font-weight: bold; min-width: 30px;">Current: <span id="currentSoda">0</span></span>
                        <input type="number" id="sodaStock" min="0" value="0" placeholder="0" style="flex: 1;">
                        <button class="add-stock-btn" data-item="soda">ADD</button>
                    </div>
                </div>
            </div>

            <div class="service-section">
                <div class="service-disable"></div>
                <h3>Refill Change</h3>
                <div class="service-input-group">
                    <label>Nickels (5¢):</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <span style="color: #3498db; font-weight: bold; min-width: 30px;">Current: <span id="currentNickels">0</span></span>
                        <input type="number" id="nickels" min="0" value="0" placeholder="0" style="flex: 1;">
                        <button class="add-stock-btn" data-coin="0.05">ADD</button>
                    </div>
                </div>
                <div class="service-input-group">
                    <label>Dimes (10¢):</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <span style="color: #3498db; font-weight: bold; min-width: 30px;">Current: <span id="currentDimes">0</span></span>
                        <input type="number" id="dimes" min="0" value="0" placeholder="0" style="flex: 1;">
                        <button class="add-stock-btn" data-coin="0.10">ADD</button>
                    </div>
                </div>
                <div class="service-input-group">
                    <label>Quarters (25¢):</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <span style="color: #3498db; font-weight: bold; min-width: 30px;">Current: <span id="currentQuarters">0</span></span>
                        <input type="number" id="quarters" min="0" value="0" placeholder="0" style="flex: 1;">
                        <button class="add-stock-btn" data-coin="0.25">ADD</button>
                    </div>
                </div>
                <div class="service-input-group">
                    <label>Dollars ($1):</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <span style="color: #3498db; font-weight: bold; min-width: 30px;">Current: <span id="currentDollars">0</span></span>
                        <input type="number" id="dollars" min="0" value="0" placeholder="0" style="flex: 1;">
                        <button class="add-stock-btn" data-coin="1.00">ADD</button>
                    </div>
                </div>
            </div>

            <div class="service-buttons">
                <button class="service-button cancel" id="closeServiceBtn">CLOSE</button>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = 'http://localhost:8000/api';
        const MOCK = false; // Set to false when backend is ready
        let currentBalance = 0;

        const itemIcons = {
            'Water': '💧',
            'Juice': '🧃',
            'Soda': '🥤'
        };

        // Mock data for testing
        const mockData = {
            balance: 5.00,
            items: [
                { name: 'Water', price: 0.65, stock: 10 },
                { name: 'Juice', price: 1.00, stock: 10 },
                { name: 'Soda', price: 1.50, stock: 10 }
            ],
            change: {
                '0.05': 20,
                '0.10': 20,
                '0.25': 20,
                '1.00': 20
            }
        };

        function showMessage(msg, isError = false) {
            const messageEl = $('#message');
            messageEl.removeClass('error');
            if (isError) {
                messageEl.addClass('error');
            }
            messageEl.html(msg);
        }

        function updateBalance(balance) {
            currentBalance = parseFloat(balance);
            $('#balance').text(currentBalance.toFixed(2));
            updateItemButtons();
        }

        function updateItemButtons() {
            $('.item-slot').each(function() {
                const slot = $(this);
                const button = slot.find('.item-button');
                const price = parseFloat(slot.data('price'));
                const stock = parseInt(slot.data('stock'));

                if (stock > 0 && currentBalance >= price) {
                    button.addClass('active');
                } else {
                    button.removeClass('active');
                }
            });
        }

        function loadMachineState(showMessages = true) {
            if (MOCK) {
                // Use mock data for testing
                renderItems(mockData.items);
                updateBalance(mockData.balance);
                updateServiceDisplay(mockData);
                showMessage('Welcome! Insert coins to begin...');
                return;
            }

            $.ajax({
                url: `${API_BASE}/machine-state`,
                method: 'GET',
                success: function(data){
                    if(data.hasOwnProperty('items') === false) {
                        if(showMessages) showMessage('Invalid machine state data', true);
                        return;
                    }

                    renderItems(data.items);
                    updateBalance(data.balance || 0);
                    updateServiceDisplay(data);
                    if(showMessages) showMessage('Welcome! Insert coins to begin...');
                },
                error: function() {
                    if(showMessages) showMessage('Failed to load machine state', true);
                }
            });
        }

        function updateServiceDisplay(data) {
            // Update item counts
            if (data.items) {
                data.items.forEach(item => {
                    if (item.name === 'Water') $('#currentWater').text(item.stock);
                    if (item.name === 'Juice') $('#currentJuice').text(item.stock);
                    if (item.name === 'Soda') $('#currentSoda').text(item.stock);
                });
            }

            // Update change counts
            if (data.change) {
                $('#currentNickels').text(data.change['0.05'] || 0);
                $('#currentDimes').text(data.change['0.10'] || 0);
                $('#currentQuarters').text(data.change['0.25'] || 0);
                $('#currentDollars').text(data.change['1.00'] || 0);
            }
        }

        function renderItems(items) {
            const grid = $('#itemsGrid');
            grid.empty();

            items.forEach(item => {
                const slot = $(`
                    <div id="items-disable"></div>
                    <div class="item-slot ${item.stock <= 0 ? 'out-of-stock' : ''}"
                         data-item="${item.name}"
                         data-price="${item.price}"
                         data-stock="${item.stock}">
                        <div class="item-icon">${itemIcons[item.name] || '📦'}</div>
                        <div class="item-name">${item.name}</div>
                        <div class="item-price">${parseFloat(item.price).toFixed(2)}</div>
                        <div class="item-stock">Stock: ${item.stock}</div>
                        <button class="item-button" data-item="${item.name}">Select</button>
                    </div>
                `);

                grid.append(slot);
            });

            // Add click handlers to buttons
            $('.item-button').on('click', function(e) {
                e.stopPropagation();
                if ($(this).hasClass('active')) {
                    const itemName = $(this).data('item');
                    vendItem(itemName);
                }
            });

            updateItemButtons();
        }

        $('.coin-button').on('click', function() {
            const coin = $(this).data('coin');
            const button = $(this);

            disableControls();

            showMessage(`Inserting a $${parseFloat(coin).toFixed(2)} coin...`);
            button.addClass('inserting');
            setTimeout(() => button.removeClass('inserting'), 500);

            $.ajax({
                url: `${API_BASE}/insert-coin`,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ value: coin }),
                success: function(data) {
                    updateBalance(data.balance);
                    showMessage(`Inserted $${parseFloat(coin).toFixed(2)}`);
                    disableControls(false);
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.message || 'Invalid coin';
                    showMessage(error, true);
                    disableControls(false);
                }
            });
        });

        $('#returnCoinBtn').on('click', function(){
            showMessage('Returning coins...');
            disableControls();

            const button = $(this);
            button.addClass('returning');
            setTimeout(() => button.removeClass('returning'), 500);

            $.ajax({
                url: `${API_BASE}/return-coin`,
                method: 'POST',
                success: function(data) {
                    if (data.coins && data.coins.length > 0) {
                        animateCoinsReturn(data.coins);

                        // Calculate total
                        const total = data.coins.reduce((sum, v) => sum + v, 0);

                        // Count each coin type
                        const counts = {};
                        data.coins.forEach(v => {
                            const key = v.toFixed(2);
                            counts[key] = (counts[key] || 0) + 1;
                        });

                        // Build detail string like "1.00 x 2, 0.25 x 2"
                        const details = Object.entries(counts)
                            .map(([value, count]) => `${value} x ${count}`)
                            .join(', ');

                        //showMessage(`Returned: $${currentBalance.toFixed(2)}`);
                        showMessage(`Returned: $${total.toFixed(2)}<br/>${details}`);
                    } else {
                        showMessage('No coins to return');
                    }
                    updateBalance(0);
                    disableControls(false);
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.message || 'Return failed';
                    showMessage(error, true);
                    disableControls(false);
                }
            });
        });

        function vendItem(itemName) {
            if(currentBalance <= 0){
                showMessage('Please insert coins first', true);
                return;
            }

            disableItems();

            if (MOCK) {
                // Mock vending logic
                const item = mockData.items.find(i => i.name === itemName);
                if (!item) return;

                if (item.stock <= 0) {
                    showMessage('Item out of stock', true);
                    return;
                }

                if (currentBalance < item.price) {
                    showMessage('Insufficient funds', true);
                    return;
                }

                // Calculate change
                const changeAmount = currentBalance - item.price;
                const changeCoins = [];

                if (changeAmount > 0) {
                    let remaining = changeAmount;
                    while (remaining >= 0.25) {
                        changeCoins.push(0.25);
                        remaining -= 0.25;
                        remaining = Math.round(remaining * 100) / 100;
                    }
                    while (remaining >= 0.10) {
                        changeCoins.push(0.10);
                        remaining -= 0.10;
                        remaining = Math.round(remaining * 100) / 100;
                    }
                    while (remaining >= 0.05) {
                        changeCoins.push(0.05);
                        remaining -= 0.05;
                        remaining = Math.round(remaining * 100) / 100;
                    }
                }

                // Update mock data
                item.stock--;
                mockData.balance = 0;

                // Animate
                animateDispense(itemName);

                if (changeCoins.length > 0) {
                    setTimeout(() => {
                        animateCoinsReturn(changeCoins);
                    }, 800);
                    showMessage(`Enjoy your ${itemName}! Change: ${changeAmount.toFixed(2)}`);
                } else {
                    showMessage(`Enjoy your ${itemName}!`);
                }

                updateBalance(0);

                setTimeout(() => {
                    renderItems(mockData.items);
                }, 2000);

                return;
            }

            showMessage(`Vending item: ${itemName}...`);

            $.ajax({
                url: `${API_BASE}/vend-item`,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ item: itemName }),
                success: function(data) {
                    animateDispense(itemName);

                    if (data.change && data.change.length > 0) {
                        setTimeout(() => {
                            animateCoinsReturn(data.change);
                        }, 800);

                        // Count each coin type
                        const counts = {};
                        data.change.forEach(v => {
                            const key = v.toFixed(2);
                            counts[key] = (counts[key] || 0) + 1;
                        });

                        // Build detail string like "1.00 x 2, 0.25 x 2"
                        const details = Object.entries(counts)
                            .map(([value, count]) => `${value} x ${count}`)
                            .join(', ');

                        showMessage(`Enjoy your ${itemName}! Change: ${calculateChangeAmount(data.change).toFixed(2)}<br/>${details}`);
                    } else {
                        showMessage(`Enjoy your ${itemName}!`);
                    }

                    updateBalance(0);

                    setTimeout(() => {
                        loadMachineState(false);
                    }, 2000);

                    disableItems(false);
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.message || 'Vending failed';
                    showMessage(error, true);
                    disableItems(false);
                }
            });
        }

        function disableItems(disabled = true){
            if(disabled){
                $('#items-disable').show();
            }else{
                $('#items-disable').hide();
            }
        }

        function disableControls(disabled = true){
            if(disabled){
                $('#controls-disable').show();
            }else{
                $('#controls-disable').hide();
            }
        }

        function disableService(disabled = true){
            if(disabled){
                $('.service-disable').show();
            }else{
                $('.service-disable').hide();
            }
        }

        function animateDispense(itemName) {
            const icon = itemIcons[itemName] || '📦';
            const item = $(`<div class="dispensed-item">${icon}</div>`);
            $('#dispenser').append(item);

            setTimeout(() => {
                item.remove();
            }, 1500);
        }

        function animateCoinsReturn(coins) {
            coins.forEach((coin, index) => {
                setTimeout(() => {
                    const coinEl = $(`<div class="coin-return">💰</div>`);
                    $('#dispenser').append(coinEl);

                    setTimeout(() => {
                        coinEl.remove();
                    }, 800);
                }, index * 200);
            });
        }

        function calculateChangeAmount(coins) {
            return coins.reduce((sum, coin) => sum + parseFloat(coin), 0);
        }

        $('#servicePanelBtn').on('click', function() {
            $('#serviceModal').addClass('active');
            // Reload state when opening service panel
            loadMachineState();
        });

        $('#closeServiceBtn').on('click', function() {
            $('#serviceModal').removeClass('active');
        });

        // Handle ADD buttons for items
        $('.add-stock-btn[data-item]').on('click', function() {
            const item = $(this).data('item');
            const inputId = `${item}Stock`;
            const amountToAdd = parseInt($(`#${inputId}`).val()) || 0;

            if (amountToAdd <= 0) {
                showMessage('Please enter a valid amount', true);
                return;
            }

            const itemName = item.charAt(0).toUpperCase() + item.slice(1);
            const restockData = {
                type: 'item',
                item_name: itemName,
                count: amountToAdd
            };

            disableService();

            $.ajax({
                url: `${API_BASE}/service/restock`,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(restockData),
                success: function(data) {
                    showMessage(`Added ${amountToAdd} ${itemName}!`);
                    $(`#${inputId}`).val('0');
                    loadMachineState();
                    disableService(false);
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.message || 'Restock failed';
                    showMessage(error, true);
                    disableService(false);
                }
            });
        });

        // Handle ADD buttons for coins
        $('.add-stock-btn[data-coin]').on('click', function() {
            const coinValue = $(this).data('coin').toString();
            let inputId;

            console.log("coinValue:", coinValue);

            switch(coinValue) {
                case "0.05":
                    inputId = 'nickels';
                    break;
                case "0.10":
                    inputId = 'dimes';
                    break;
                case "0.25":
                    inputId = 'quarters';
                    break;
                case "1.00":
                    inputId = 'dollars';
                    break;
            }

            console.log("inputId:", inputId);

            const amountToAdd = parseInt($(`#${inputId}`).val()) || 0;

            console.log("amountToAdd:", amountToAdd);

            if (amountToAdd <= 0) {
                showMessage('Please enter a valid amount', true);
                return;
            }

            const restockData = {
                type: 'change',
                value: coinValue,
                count: amountToAdd
            };

            disableService();

            $.ajax({
                url: `${API_BASE}/service/restock`,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(restockData),
                success: function(data) {
                    showMessage(`Added ${amountToAdd} coins!`);
                    $(`#${inputId}`).val('0');
                    loadMachineState();
                    disableService(false);
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.message || 'Restock failed';
                    showMessage(error, true);
                    disableService(false);
                }
            });
        });

        // Initialize
        $(document).ready(function() {
            showMessage('Loading machine state data...');
            loadMachineState();
            console.log("I took the liberty to build this frontend with AI since there was no restriction in the challenge for this part and it would make it easier and cleaner to test the backend.");
        });
    </script>
</body>
</html>
