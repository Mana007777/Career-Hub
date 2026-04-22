# PHP app (Octane + FrankenPHP) – use this instead of php artisan serve
# php artisan octane:start --watch
# With project Caddyfile (optional): --caddyfile=./Caddyfile

# You can ignore these warnings:
# - "Caddyfile input is not formatted" – harmless (env placeholders)
# - "HTTP/2 / HTTP/3 skipped" – normal without TLS; use --https to enable

# npm run dev
# php artisan reverb:start
# php artisan horizon
# php artisan schedule:work


cd /home/marllax/Desktop/PythonRec/career_hub_ai && source venv/bin/activate && python manage.py runserver 0.0.0.0:8001


cd /home/marllax/Desktop/PythonRec/career_hub_ai && source venv/bin/activate && celery -A career_hub_ai worker -l info