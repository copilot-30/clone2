@extends('patient_layout')

@section('content')
<div class="flex flex-col  min-h-full">
    <div class="flex-grow container mx-auto p-4 flex flex-col">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">AI Medical Consultant</h1>

        <div id="chat-box" class="flex-grow bg-white p-6 rounded-lg shadow-md overflow-y-auto mb-6 border border-gray-200" style="max-height: 70vh;min-height: 70vh">
            <!-- Initial system message -->
            <div class="flex justify-start mb-4">
                <div class="bg-gray-200 text-gray-800 p-4 rounded-lg shadow-sm max-w-lg">
                    <p class="font-semibold">AI:</p>
                    <p>Hello! I'm your AI Medical Consultant. Please note that I cannot give actual medical advice or diagnoses. My purpose is to provide helpful information and suggestions based on your queries. For professional medical advice, please consult a doctor.</p>
                </div>
            </div>
            <!-- Chat messages will be appended here -->
        </div>

        <form id="chat-form" class="flex items-center space-x-4">
            <input type="text" id="user-input" class="flex-1 border border-gray-300 rounded-full py-3 px-6 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" placeholder="Ask your question...">
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                <i class="fas fa-paper-plane mr-2"></i> Send
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatForm = document.getElementById('chat-form');
        const userInput = document.getElementById('user-input');
        const chatBox = document.getElementById('chat-box');

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const query = userInput.value.trim();
            if (!query) return;

            // Display user message
            appendMessage('user', query);
            userInput.value = '';

            // Display typing indicator
            const typingIndicator = appendMessage('system', '<div class="flex items-center"><svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>AI is typing...</div>', true);

            // Send query to the backend
            fetch('/patient/ai-consult', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ query: query })
            })
            .then(response => response.json())
            .then(data => {
                // Remove typing indicator
                chatBox.removeChild(typingIndicator);

                if (data.response) {
                    appendMessage('system', data.response);
                } else if (data.error) {
                    appendMessage('system', 'Error: ' + data.error);
                }
            })
            .catch(error => {
                // Remove typing indicator
                chatBox.removeChild(typingIndicator);
                appendMessage('system', 'Error: Could not connect to the AI service.');
                console.error('Error:', error);
            });
        });

        function appendMessage(sender, message, isTypingIndicator = false) {
            const messageContainer = document.createElement('div');
            messageContainer.classList.add('flex', 'mb-4', sender === 'user' ? 'justify-end' : 'justify-start');

            const messageElement = document.createElement('div');
            messageElement.classList.add('p-4', 'rounded-lg', 'shadow-md', 'max-w-lg');

            if (sender === 'user') {
                messageElement.classList.add('bg-blue-500', 'text-white');
                messageElement.innerHTML = `<p class="font-semibold">You:</p><p>${message}</p>`;
            } else {
                messageElement.classList.add('bg-gray-200', 'text-gray-800');
                if (isTypingIndicator) {
                    messageElement.innerHTML = message; // Message already contains the typing indicator HTML
                } else {
                    messageElement.innerHTML = `<p class="font-semibold">AI:</p><p>${message}</p>`;
                }
            }
            
            messageContainer.appendChild(messageElement);
            chatBox.appendChild(messageContainer);
            chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to the latest message
            return messageContainer; // Return the container element
        }
    });
</script>
@endpush