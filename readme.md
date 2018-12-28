[download]: https://github.com/ushahidi/platform-facebook-bo/releases

# COMRADES Platform Facebook Bot
============================

here <- make sure to link back to landing repo

You need to deploy the [COMRADES Platform API](http://github.com/ushahidi/platform-comrades) first

# COMRADES Platform Client installation

[Download][download]


### What is the COMRADES platform facebook bot?
The Facebook bot is used for communicating with users through facebook-messenger. Users can create reporrts by chatting with the bot and they are then sent back to the platform, wheere they can be processed by the service proxy if the user configures the webhooks for it. The Facebook bot runs in a server, it can be run either in the same server or a different one. The platform URL and other configuration settings can be changed through an .ENV file in the repository. Setup instructions can be found in its own repository

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


## References
This is one of three repositories related to COMRADES deployment of Ushahidi, [which is being tested here](https://comrades-stg.ushahidi.com/views/map).
* This repo contains code for the deployment’s graphical user interface. The “development” branch is our sandbox and the “master” branch is production.
* The primary source code for the functionality of the platform can be found in the [“Platform-comrades” repo here](https://github.com/ushahidi/platform-comrades). The “development” branch is our sandbox and the “master” branch is production.
* The test deployment also connects to other web services. In the [comrades-service-proxy repo](https://github.com/ushahidi/comrades-service-proxy) you will find code for an intermediary proxy which uses [YODIE from the University of Sheffield](https://gate.ac.uk/applications/yodie.html) to annotate posts in the COMRADES test Platform.
* The project website for this [COMRADES H2020 European Project](http://www.comrades-project.eu) can be found here. It contains a variety of outputs from the project such as [specific documentation within reports](http://www.comrades-project.eu/outputs/deliverables.html), access to our training [data and ontologies](http://www.comrades-project.eu/outputs/datasets-and-ontologies.html), and [academic research](http://www.comrades-project.eu/outputs/papers.html). 

## Acknowledgment
This work has received support from the European Union’s Horizon 2020 research and innovation programme under [grant agreement No 687847](http://cordis.europa.eu/project/rcn/198819_en.html).
