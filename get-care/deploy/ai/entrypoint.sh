#!/bin/bash

# Start Ollama in the background.
/bin/ollama serve &
# Record Process ID.
pid=$!

# Pause for Ollama to start.
sleep 5

echo "🔴 Retrieve LLAMA3 model..."
ollama pull koesn/llama3-openbiollm-8b
echo "🟢 Done!"

echo "🔴 starting model"
ollama run koesn/llama3-openbiollm-8b
# Wait for Ollama process to finish.
wait $pid 