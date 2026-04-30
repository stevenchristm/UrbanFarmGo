@extends('layouts.app')

@section('title', 'Asisten AI')

@section('styles')
<style>
    .chat-container {
        height: calc(100vh - 200px);
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    #chat-box {
        flex-grow: 1;
        overflow-y: auto;
        padding: 2rem;
        background: var(--bg-white);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 24px;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        scroll-behavior: smooth;
        box-shadow: var(--shadow-soft);
        backdrop-filter: blur(10px);
    }

    .message {
        max-width: 75%;
        padding: 1.1rem 1.6rem;
        font-size: 0.95rem;
        line-height: 1.6;
        position: relative;
        animation: fadeInUp 0.4s ease;
    }

    .message-user {
        align-self: flex-end;
        background: var(--primary-emerald);
        color: white;
        border-radius: 20px 20px 4px 20px;
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.2);
    }

    .message-ai {
        align-self: flex-start;
        background: var(--bg-white);
        border: 1px solid rgba(255, 255, 255, 0.8);
        color: var(--text-slate);
        border-radius: 20px 20px 20px 4px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02);
    }

    .message-error {
        align-self: center;
        background: #fff5f5;
        border: 1px solid #feb2b2;
        color: #c53030;
        border-radius: 12px;
        font-weight: 500;
        text-align: center;
        max-width: 90%;
    }

    .chat-input-area {
        background: var(--bg-white);
        border: 1px solid rgba(255, 255, 255, 0.8);
        padding: 1rem 1.5rem;
        border-radius: 20px;
        display: flex;
        gap: 15px;
        align-items: center;
        box-shadow: 0 15px 30px -10px rgba(0,0,0,0.08);
        backdrop-filter: blur(10px);
    }

    #user-input {
        flex-grow: 1;
        background: transparent;
        border: none;
        color: var(--text-slate);
        padding: 10px;
        outline: none;
        font-family: inherit;
        font-size: 1rem;
        font-weight: 500;
    }

    .typing-indicator {
        font-size: 0.85rem;
        color: var(--primary-emerald);
        margin-bottom: 12px;
        display: none;
        font-weight: 600;
        padding-left: 1rem;
    }

    .chat-image {
        max-width: 100%;
        border-radius: 12px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: 0.3s;
        border: 2px solid rgba(255,255,255,0.2);
    }

    .chat-image:hover { transform: scale(1.02); }

    .btn-circle {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg-soft);
        border: 1px solid var(--border-soft);
        color: var(--text-muted);
        border-radius: 50%;
        cursor: pointer;
        transition: var(--transition-standard);
    }

    .btn-circle:hover {
        background: var(--primary-emerald);
        color: white;
        border-color: var(--primary-emerald);
        transform: translateY(-2px);
    }

    #image-preview-container {
        display: none;
        position: absolute;
        bottom: 110px;
        left: 310px;
        z-index: 100;
    }

    .preview-box {
        background: var(--bg-white);
        border: 2px solid var(--primary-emerald);
        padding: 8px;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<div class="page-header animate-up">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>🧬 Pakar Botani AI</h1>
            <p>Konsultasi otomatis mengenai kesehatan tanaman, cuaca, dan strategi budidaya.</p>
        </div>
        <button onclick="clearChat()" class="cyber-btn cyber-btn-outline" style="font-size: 0.85rem; padding: 8px 18px;">
            <i class="fas fa-history"></i> Reset Sesi
        </button>
    </div>
</div>

<div class="chat-container animate-up" style="animation-delay: 0.1s;">
    <div id="chat-box">
        @forelse($history as $c)
            <div class="message message-user">
                @if($c->image)
                    <img src="{{ asset('storage/' . $c->image) }}" class="chat-image" onclick="window.open(this.src)">
                @endif
                {{ $c->message }}
            </div>
            <div class="message message-ai">{!! nl2br(e($c->response)) !!}</div>
        @empty
            <div class="message message-ai">
                <strong>Selamat Datang di Hub Konsultasi UrbanFarm.</strong><br>
                Sistem AI kami siap membantu Anda hari ini. Kirimkan pesan atau ajukan pertanyaan spesifik mengenai lahan Anda untuk memulai analisa.
            </div>
        @endforelse
    </div>

    <div id="loading-text" class="typing-indicator">
        <i class="fas fa-microchip fa-spin"></i> Algoritma sedang melakukan pemrosesan data...
    </div>

    <div id="image-preview-container">
        <div class="preview-box">
            <img src="" id="image-preview" style="height: 120px; border-radius: 12px; display: block;">
            <button onclick="removeImage()" style="position: absolute; top: -12px; right: -12px; background: var(--accent-red); border: none; color: white; border-radius: 50%; width: 28px; height: 28px; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">&times;</button>
        </div>
    </div>

    <div class="chat-input-area">
        <input type="file" id="file-input" accept="image/*" style="display: none" onchange="previewImage(this)">
        <button class="btn-circle" onclick="document.getElementById('file-input').click()" title="Lampirkan Gambar">
            <i class="fas fa-camera"></i>
        </button>
        
        <input type="text" id="user-input" placeholder="Ketik pesan konsultasi Anda di sini..." autocomplete="off">
        
        <button onclick="sendChat()" id="btn-send" class="cyber-btn" style="padding: 12px 30px;">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const chatBox = document.getElementById('chat-box');
    const userInput = document.getElementById('user-input');
    const btnSend = document.getElementById('btn-send');
    const loadingText = document.getElementById('loading-text');
    const fileInput = document.getElementById('file-input');
    const imagePreviewContainer = document.getElementById('image-preview-container');
    const imagePreview = document.getElementById('image-preview');

    let userLat = null;
    let userLon = null;

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                userLat = position.coords.latitude;
                userLon = position.coords.longitude;
            });
        }
    }
    getLocation();

    function scrollToBottom() {
        chatBox.scrollTo({ top: chatBox.scrollHeight, behavior: 'smooth' });
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreviewContainer.style.display = 'block';
                scrollToBottom();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage() {
        fileInput.value = '';
        imagePreviewContainer.style.display = 'none';
    }

    chatBox.scrollTop = chatBox.scrollHeight;

    async function sendChat() {
        let message = userInput.value.trim();
        let file = fileInput.files[0];
        if (!message && !file) return;

        appendMessage('user', message, file);
        userInput.value = '';
        removeImage();

        btnSend.disabled = true;
        loadingText.style.display = 'block';
        scrollToBottom();

        try {
            const formData = new FormData();
            formData.append('message', message);
            if (file) formData.append('image', file);
            if (userLat) formData.append('lat', userLat);
            if (userLon) formData.append('lon', userLon);

            const response = await fetch("{{ route('ai.ask') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            });

            const data = await response.json();
            if (data.response) {
                // Periksa apakah pesan mengandung Error Sistem
                const isError = data.response.includes('Error Sistem:');
                appendMessage(isError ? 'error' : 'ai', data.response);
            }
        } catch (error) {
            console.error(error);
            appendMessage('error', "Terjadi kesalahan fatal. Mohon periksa koneksi internet Anda atau coba lagi nanti.");
        } finally {
            btnSend.disabled = false;
            loadingText.style.display = 'none';
            scrollToBottom();
        }
    }

    function appendMessage(sender, text, file = null) {
        const msgDiv = document.createElement('div');
        msgDiv.classList.add('message');
        
        if (sender === 'user') msgDiv.classList.add('message-user');
        else if (sender === 'error') msgDiv.classList.add('message-error');
        else msgDiv.classList.add('message-ai');

        if (file) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.classList.add('chat-image');
            img.onclick = () => window.open(img.src);
            msgDiv.appendChild(img);
        }

        if (text) {
            const textSpan = document.createElement('span');
            textSpan.innerHTML = text.replace(/\n/g, '<br>');
            msgDiv.appendChild(textSpan);
        }

        chatBox.appendChild(msgDiv);
        scrollToBottom();
    }

    userInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendChat();
    });

    async function clearChat() {
        if (!confirm('Hapus riwayat konsultasi saat ini?')) return;
        try {
            const response = await fetch("{{ route('ai.clear') }}", {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                }
            });
            if (response.ok) {
                chatBox.innerHTML = '<div class="message message-ai">Riwayat telah dibersihkan. Sistem siap menerima pertanyaan baru.</div>';
            }
        } catch (error) { console.error(error); }
    }
</script>
@endsection