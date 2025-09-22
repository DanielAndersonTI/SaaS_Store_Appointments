document.getElementById("topheader").innerHTML = `
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center px-4 py-3 bg-black gap-4">
        <!-- Logo + Nome -->
        <div class="flex items-center gap-3 justify-center sm:justify-start min-w-0">
            <a href="index.html">
                <img src="assets/img/logo.png" alt="Logo da Barbearia" class="w-20 shrink-0 cursor-pointer">
            </a>
            <h1 class="text-2xl font-bold text-gray-200 whitespace-nowrap">D'Barber Shop</h1>
        </div>

        <!-- Botões -->
        <div class="flex items-center justify-center sm:justify-end gap-3 min-w-0">
            <a href="https://wa.me/558398866079" target="_blank"
                class="flex items-center bg-gray-700 hover:bg-yellow-600 text-white px-4 py-2 rounded-full whitespace-nowrap">
                <span>WhatsApp</span>
                <img src="assets/icons/whatsapp.svg" alt="WhatsApp" class="w-5 h-5 ml-2">
            </a>
            <a href="https://maps.app.goo.gl/hxsTXqWzWWwz2y7t9" target="_blank"
                class="flex items-center bg-gray-700 hover:bg-yellow-600 text-white px-4 py-2 rounded-full whitespace-nowrap">
                <span>Localização</span>
                <img src="assets/icons/map.svg" alt="Ícone de mapa" class="w-5 h-5 ml-2">
            </a>
        </div>
    </div>
`;
