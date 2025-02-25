# SentimentalEstateDivision

A very simple php/postgres web application to allow the fair division of the sentimental items from someone's estate through coordinate-based markers.

## Features

- Auto-resizing to a consistent width (800px by default) with GD library
- Click-to-claim interface with name-based markers
- Dynamic photo gallery from `original/` directory
- Persistent marker storage in PostgreSQL

## Requirements

- PHP 7.4+ with GD extension
- PostgreSQL 12+
- Web server (Apache/Nginx)
- Modern web browser

## Installation

1. Clone repository:
```bash
git clone https://github.com/bmtwl/SentimentalEstateDivision
cd SentimentalEstateDivision
```

2. Create PostgreSQL database:
```bash
createdb estate
psql estate -c "CREATE USER estate WITH PASSWORD 'password';"
psql estate -c "GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO estate;"
```

3. Configure database credentials in `db.php`:
```php
$host = 'localhost';
$dbname = 'estate';
$user = 'estate';
$pass = 'password';
```

4. Create directories and set permissions:
```bash
mkdir original resized
chmod 775 resized
```

5. Add photos to `original/` directory

6. Configure your webserver to serve this folder securely (outside of the scope of this readme). Consider http simple auth to protect it.

## Usage

1. Access via web browser at eg `http://your-server/SentimentalEstateDivision/`
2. Enter your name in the form
3. Click anywhere on photos to place markers
4. Markers persist automatically and appear for all users

## Security Notes

- No authentication - use behind secure proxy
- Input sanitization for XSS prevention
- PostgreSQL prepared statements prevent SQL injection

## License

MIT License - See [LICENSE](LICENSE) file
