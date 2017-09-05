Build A Bot!
============

Requirements
------------

1. PHP 7.1
2. OpenSSL + ctype extensions (See Dockerfile for a full extension list)
3. DOM + mbstring extensions for PHPUnit (included in Docker build)
4. Composer
5. An api.ai account; you'll need a "Client access token" from there to populate
the API_AI_KEY environment variable.

Usage
-----

Run using one of the below methods, then visit `http://localhost:9000/bot` to view
the bot page.

### Run with Docker

```
docker build . -t build-a-bot
docker run -p 9000:80 -e API_AI_KEY=xxxxxxxxx -v $(pwd):/var/app build-a-bot
```

Some quick notes:

1. This container uses nginx and php-fpm, managed by runit. I tried to minimize
image size and resource usage while still providing a self-contained appliance.
2. App files are copied on build. If you don't need live updating, remove the
volume mount (`-v`) parameter in the second command above.

### Run Locally

```
API_AI_KEY=xxxxxxxx php -S 0.0.0.0:9000 -t public -d variables_order=EGPCS
```
