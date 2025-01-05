up:
	docker compose up -d --build

down:
	docker compose down

back:
	docker compose exec backend sh

front:
	docker compose exec frontend sh

logs:
	docker logs frontend -f

echo:
	docker compose exec echo sh