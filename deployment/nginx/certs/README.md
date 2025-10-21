# SSL Certificates Directory

Place your SSL certificates here:

- `fullchain.pem` - Full certificate chain
- `privkey.pem` - Private key

## For Let's Encrypt:

```bash
sudo certbot certonly --standalone -d your-domain.com
sudo cp /etc/letsencrypt/live/your-domain.com/fullchain.pem ./
sudo cp /etc/letsencrypt/live/your-domain.com/privkey.pem ./
```

## For self-signed (development):

```bash
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout privkey.pem \
  -out fullchain.pem \
  -subj "/C=VN/ST=HCM/L=HCM/O=Hanaya/CN=localhost"
```
