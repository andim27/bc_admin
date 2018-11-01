curl -X POST "https://api.cloudflare.com/client/v4/zones/8e589d6b9d1d7f80312df86d13010bf5/purge_cache" \
     -H "X-Auth-Email: admin@ooo.ua" \
     -H "X-Auth-Key: a46bba5b6228a55d4af214e8a506775080138" \
     -H "Content-Type: application/json" \
     --data '{"purge_everything":true}'