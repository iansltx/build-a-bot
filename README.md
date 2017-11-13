Build A Bot!
============

Welcome to the Build A Bot workshop! This branch represents the finished project.
To pick up at an earlier stage of the project, use one of these branches:

1. [initial-setup](https://github.com/iansltx/build-a-bot/tree/initial-setup)
2. [implement-text-requests](https://github.com/iansltx/build-a-bot/tree/implement-text-requests)
3. [add-speech-synthesis](https://github.com/iansltx/build-a-bot/tree/add-speech-synthesis)
4. [add-voice-recognition](https://github.com/iansltx/build-a-bot/tree/add-voice-recognition)
5. [add-web-hook-pnwphp-2017](https://github.com/iansltx/build-a-bot/tree/add-web-hook-pnwphp-2017)

If you haven't already, please [review the requirements](https://gist.github.com/iansltx/a93354ba27d9066021c557e41e436b44)
for this workshop, and [join the PNWPHP 2017 Slack](https://join.slack.com/t/pnwphp2017/shared_invite/MjM0NTcxNDU5NTg3LTE1MDQyMDMxMzQtZTI0NGU5YTU3Ng)
to get help when you need it. If you want to use the same agent I set up during the workshop,
plus a fix for the context step I showed, create an agent, then restore from api-ai-agent.zip here.

Happy hacking!

P.S. This is the only branch that has a composer.lock file. For quicker dependency downloads,
`composer install` from here before switching to another branch. Installing from here means
you'll have all the dependencies you'll need for the workshop.

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
the bot page. Use a tool like [ngrok](https://ngrok.com) to make your application
available to the outside world, then set `https://your-hostname-here.ngrok.io/hook`
as the destination of your api.ai agent web hook.

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
