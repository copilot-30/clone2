@extends('patient_layout')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">AI Medical Consultant</h1>

    <div id="chat-box" class="bg-gray-100 p-4 rounded-lg h-96 overflow-y-auto mb-4">
        <!-- Chat messages will be appended here -->
        <div class="message system-message mb-2 p-2 rounded-lg bg-gray-200">
            <p><strong>AI:</strong> Hello! I'm your AI Medical Consultant. Please note that I cannot give actual medical advice or diagnoses. My purpose is to provide helpful information and suggestions based on your queries. For professional medical advice, please consult a doctor.</p>
        </div>
    </div>

    <form id="chat-form" class="flex">
        <input type="text" id="user-input" class="flex-1 border border-gray-300 rounded-l-lg p-2 focus:outline-none" placeholder="Ask your medical question...">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-r-lg hover:bg-green-700">Send</button>
    </form>
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
            const typingIndicator = appendMessage('system', 'AI is typing...', true);

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
            const messageElement = document.createElement('div');
            messageElement.classList.add('message', `${sender}-message`, 'mb-2', 'p-2', 'rounded-lg');

            if (sender === 'user') {
                messageElement.classList.add('bg-blue-200', 'text-right');
            } else {
                messageElement.classList.add('bg-gray-200');
            }

            if (isTypingIndicator) {
                messageElement.innerHTML = `<p><strong>AI:</strong> <em>${message}</em></p>`;
            } else {
                messageElement.innerHTML = `<p><strong>${sender === 'user' ? 'You' : 'AI'}:</strong> ${message}</p>`;
            }
            
            chatBox.appendChild(messageElement);
            chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to the latest message
            return messageElement; // Return the element for potential removal (e.g., typing indicator)
        }
    });
</script>
@endpush