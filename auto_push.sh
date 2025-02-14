#!/bin/bash
while true; do
    git add .
    git commit -m "Auto-commit: $(date)"
    git push origin main
    echo "✅ Cambios subidos automáticamente. Esperando 60 segundos..."
    sleep 60  # Espera 60 segundos antes de volver a subir cambios
done
