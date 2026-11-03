<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Niellstore - Reset License Key</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#0f0a1a] text-purple-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-[#181028] border border-purple-900/50 rounded-2xl p-6 shadow-2xl shadow-purple-950/50">
        <!-- Header -->
        <div class="text-center mb-6">
            <span class="inline-block px-3 py-1 bg-purple-900/40 border border-purple-700/50 text-purple-300 text-xs font-semibold rounded-full mb-2">
                DRIP STORE API
            </span>
            <h1 class="text-xl font-bold tracking-tight text-white">Reset License Key</h1>
            <p class="text-xs text-purple-400 mt-1">Masukkan data dengan benar untuk mereset key kamu.</p>
        </div>

        <!-- Form Input -->
        <form id="resetForm" class="space-y-4">
            <div>
                <label class="block text-xs font-medium text-purple-300 mb-1">Pilih Provider / API</label>
                <select name="api" id="apiProvider" class="w-full bg-[#11071f] border border-purple-900/80 rounded-xl px-3.5 py-2.5 text-sm text-purple-100 focus:outline-none focus:border-purple-500 transition">
                    <option value="phantom">Phantom (Username)</option>
                    <option value="velora">Velora (iOS)</option>
                    <option value="brmod">BRMOD (Username/Password)</option>
                    <option value="drip">Drip (Key)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-purple-300 mb-1" id="labelInput">License Key / Username</label>
                <input type="text" name="username" id="keyValue" required placeholder="Contoh: PHTM-5338-3CA1-D7F9" class="w-full bg-[#11071f] border border-purple-900/80 rounded-xl px-3.5 py-2.5 text-sm text-purple-100 placeholder-purple-600 focus:outline-none focus:border-purple-500 transition">
            </div>

            <button type="submit" id="submitBtn" class="w-full mt-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-medium py-2.5 px-4 rounded-xl text-sm transition shadow-lg shadow-purple-900/40 cursor-pointer">
                Proses Reset Key
            </button>
        </form>

        <!-- Loading Bar Container (Hidden by default) -->
        <div id="loadingContainer" class="hidden mt-6 space-y-2">
            <div class="flex justify-between text-xs text-purple-300">
                <span id="loadingText">Menghubungkan ke Server...</span>
                <span id="progressPercent" class="font-bold text-purple-400">0%</span>
            </div>
            <div class="w-full bg-[#11071f] h-2 rounded-full overflow-hidden border border-purple-900/50">
                <div id="progressBar" class="bg-gradient-to-r from-purple-500 to-indigo-500 h-full w-0 transition-all duration-75"></div>
            </div>
        </div>

        <!-- Result Box -->
        <div id="resultBox" class="hidden mt-5 p-3.5 rounded-xl text-xs font-mono break-all border"></div>
    </div>

    <script>
        // Ubah label dinamis jika user ganti pilihan provider
        document.getElementById('apiProvider').addEventListener('change', function() {
            const label = document.getElementById('labelInput');
            if(this.value === 'drip') {
                label.innerText = 'License Key (Drip)';
            } else if(this.value === 'brmod') {
                label.innerText = 'Username / Password';
            } else {
                label.innerText = 'License Key / Username';
            }
        });

        document.getElementById('resetForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const api = document.getElementById('apiProvider').value;
            const username = document.getElementById('keyValue').value;
            const submitBtn = document.getElementById('submitBtn');
            const loadingContainer = document.getElementById('loadingContainer');
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');
            const loadingText = document.getElementById('loadingText');
            const resultBox = document.getElementById('resultBox');

            // Disable form & show loading
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            resultBox.classList.add('hidden');
            loadingContainer.classList.remove('hidden');

            // Simulasi Increment Loading 1% sampai 100%
            let width = 0;
            const intervalTime = 15; // Kecepatan animasi
            
            const loadInterval = setInterval(() => {
                if (width >= 100) {
                    clearInterval(loadInterval);
                    executeAjax(api, username);
                } else {
                    width++;
                    progressBar.style.width = width + '%';
                    progressPercent.innerText = width + '%';

                    if(width === 30) loadingText.innerText = 'Memverifikasi Token API...';
                    if(width === 70) loadingText.innerText = 'Mereset Database Key...';
                    if(width === 90) loadingText.innerText = 'Menyelesaikan Proses...';
                }
            }, intervalTime);
        });

        function executeAjax(api, username) {
            const loadingContainer = document.getElementById('loadingContainer');
            const submitBtn = document.getElementById('submitBtn');
            const resultBox = document.getElementById('resultBox');

            // Kirim request ke backend PHP handler
            const formData = new FormData();
            formData.append('api', api);
            formData.append('username', username);

            fetch('process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                loadingContainer.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                
                resultBox.classList.remove('hidden');
                resultBox.innerHTML = `<strong>Respon Server:</strong><br>${data}`;
                resultBox.className = "mt-5 p-3.5 rounded-xl text-xs font-mono break-all border bg-purple-950/30 border-purple-800 text-purple-200";
            })
            .catch(error => {
                loadingContainer.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                
                resultBox.classList.remove('hidden');
                resultBox.innerText = 'Gagal terhubung ke server backend: ' + error;
                resultBox.className = "mt-5 p-3.5 rounded-xl text-xs font-mono break-all border bg-red-950/30 border-red-800 text-red-200";
            });
        }
    </script>
</body>
</html>
