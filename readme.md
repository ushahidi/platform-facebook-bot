
## Installation
Make sure the following is installed:
- [Composer](https://getcomposer.org)
- [Vagrant](https://www.vagrantup.com)
- [Virtual box](https://www.virtualbox.org/wiki/Downloads)

Clone https://github.com/ushahidi/platform-facebook-bot  
run ` composer install `  
add a .env-file. See .env.example for guidance  
run ` vagrant up `  
run ` artisan php migrate --seed `  

## Setting up bot
- Create page, organisation or group for the app
- Create a facebook-app on https://developers.facebook.com
- Get access_token
- Set up messenger
- Setup webhooks:
- Add callback-url
- Add secret-token
