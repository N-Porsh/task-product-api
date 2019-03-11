#!/usr/bin/env bash
cd service
for service in `ls -1`; do
    echo "Initializing $service..."
    cd "$service"
    if [ -e package.json ]; then
        echo "Node.js service, running npm install";
        npm install --no-bin-links
    fi

    if [ -e composer.json ]; then
        echo "PHP service, running composer install";
        composer update
        composer install
    fi

    if [ -e init.sh ]; then
        echo "init.sh found running it for additional setup";
        bash init.sh
    fi

    cd ..
done

echo "All services initialized...";