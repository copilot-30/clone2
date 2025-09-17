#!/bin/bash 

echo "🔴 OLLAMA STARTING 🔴!"
# Start Ollama in the background.
/bin/ollama serve &
# Record Process ID.
pid=$!
# Pause for Ollama to start.
sleep 3

echo "🟢 OLLAMA STARTED 🟢!"

echo "🔴 Retrieve LLAMA3 model..."
ollama pull koesn/llama3-openbiollm-8b:q6_K
echo "🟢 Model retrieved!"
# Pause for Ollama to start.
sleep 1

echo "🔴 starting model"
ollama run koesn/llama3-openbiollm-8b:q6_K
echo "🟢 Model has been started!"

wait $pid