up:
	docker compose up -d --build

down:
	docker compose down

back:
	docker compose exec backend sh

front:
	docker compose exec frontend sh

logs-front:
	docker logs frontend -n 1000 -f

log-back:
	docker logs backend -n 1000 -f

test-back:
	docker compose exec backend sh -c "php artisan test"