#!/bin/bash
set -e

# Define data path
PERSISTENT_DIR="/var/data"
DB_PATH="/var/www/html/database/database.sqlite"
STORAGE_PATH="/var/www/html/storage/app/public"

# 1. Handle SQLite database and uploads persistence if Render Disk is mounted
if [ -d "$PERSISTENT_DIR" ]; then
    echo "Persistent directory $PERSISTENT_DIR found!"
    
    # Setup SQLite Database
    if [ ! -f "$PERSISTENT_DIR/database.sqlite" ]; then
        echo "Initializing persistent database from baked database..."
        if [ -f "$DB_PATH" ]; then
            cp "$DB_PATH" "$PERSISTENT_DIR/database.sqlite"
        else
            touch "$PERSISTENT_DIR/database.sqlite"
        fi
    fi
    chmod 666 "$PERSISTENT_DIR/database.sqlite"
    
    # Link the app database path to the persistent one
    rm -f "$DB_PATH"
    ln -s "$PERSISTENT_DIR/database.sqlite" "$DB_PATH"
    echo "Symlinked database to persistent storage."
    
    # Setup Uploaded Files Persistence
    if [ ! -d "$PERSISTENT_DIR/uploads" ]; then
        echo "Initializing persistent uploads directory..."
        mkdir -p "$PERSISTENT_DIR/uploads"
        if [ -d "$STORAGE_PATH" ] && [ "$(ls -A "$STORAGE_PATH" 2>/dev/null)" ]; then
            cp -r "$STORAGE_PATH"/* "$PERSISTENT_DIR/uploads/" 2>/dev/null || true
        fi
    fi
    chmod -R 777 "$PERSISTENT_DIR/uploads"
    
    # Symlink app storage public directory to the persistent uploads folder
    if [ -d "$STORAGE_PATH" ] || [ -L "$STORAGE_PATH" ]; then
        rm -rf "$STORAGE_PATH"
    fi
    ln -s "$PERSISTENT_DIR/uploads" "$STORAGE_PATH"
    echo "Symlinked public storage to persistent uploads."
else
    echo "No persistent directory found at $PERSISTENT_DIR. Running in ephemeral mode."
    # Ensure database exists and is writable in ephemeral mode
    if [ ! -f "$DB_PATH" ]; then
        touch "$DB_PATH"
        chmod 666 "$DB_PATH"
    fi
fi

# 2. Run runtime optimizations (Laravel config, route, and view caching)
echo "Optimizing Laravel configuration and routing caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Ensure the public storage link exists
echo "Recreating Laravel storage link..."
if [ -L "/var/www/html/public/storage" ] || [ -d "/var/www/html/public/storage" ]; then
    rm -rf "/var/www/html/public/storage"
fi
php artisan storage:link --force

# 4. Execute the CMD (Apache HTTP server)
echo "Starting web server..."
exec "$@"
