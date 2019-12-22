# fluxcam-pi
This is a super basic, free, privacy-focussed camming/chat setup for raspberry pi using RPi camera. Should work on all devices: pc/mac/tablet/phone

**PLEASE NOTE: I've only just shoved this up onto github, it's not yet anywhere near ready for simple deployment yet, don't expect to just clone and it to work. This is very much early days/WIP**

# Intro:
Whilst camgirls mostly chat on paid sites where calls are charged per minute, there are also free sites which let you host a room and chat people without the pay-per-minute thing. The problem with both of these setups is that you're always on someone else's platform, always monitored and always subject to their rules and terms-of-service.

I'm a Domme and camgirl - I have several face-to-face clients who I session with in person regularly, and they're my focus rather than pay-per-min camming. In between face-to-face sessions sometimes I like to chat with those clients (or other kinky friends), but without a pay-per-minute setup and also perhaps with a bit more privacy.

Especially if you're into some more kinky stuff or have clients where discretion is particularly important, you maybe don't want to be pushing that content through a third party system, nor do you want your conversations logged.

I envisaged a system which runs on a Pi, is self-hosted has *everything* (both the video and the chat) encrypted, keeps no logs and is free. It should be as simple and lightweight as possible and as secure as possible.

Whilst currently the installation process is a lot more complex than I'd like (e.g. currently it needs a bit of setting up of ports on your router, among other things), that's something which will get improved over time. 

But isn't hosting a system like this on your home broadband and having randos from wherever connecting a little bit sketchy? Well, that's why I'm using the idea of proxying through cloudflare's free tier to somewhat obscure your IP address. That way people connect to fluxcampi.yourdomain.com (or whatever) instead of directly to your IP address and CF also filter out some nasties. You could reverse proxy thru a VPS which might give better obfuscation (since CF origin IPs can be found afaik) but that's a little more complex to set up.

It uses a free SSL cert from LetsEncrypt which means the connection to cloudflare is encrypted too.

I've built cam setups in the past and normally you'd have to run a separate RTMP server or similar, and encrypting the video feed there is a royal pain in the ass, as well as requiring a second webserver (e.g. nginx with RTMP plugin) to actually handle the streaming.

I wanted this to be simpler, and so I took a leaf out of the playbook of the IP camera web interfaces, which just fetch a series of JPGs off the camera's CMOS sensor really quickly using Javascript. So that's what we do here too - users can pick their "framerate", and since the "video" is really just JPEGs-served-really-quickly, it all runs over standard HTTPS, no second server needed. 

I wasn't sure if it would work in practice but it actually works really well and we've tested it literally across the world and it works ok.

The camera part of this uses the excellent https://elinux.org/RPi-Cam-Web-Interface#Web_Server_choice library but with a customised web bit.


I wrote this primarily because I needed something like this myself. Signal chats are fine but the video sucks and you can't type while the video is open. Skype is well...skype...and whilst I've seen self-hosted camming things in the past they tended to be either loaded with spyware, restricted or just rubbish, nevermind encrypted/SSL. There was no decent, free open-source system, and this hopefully addresses that. 

I've actually been using this setup for a while now and it actually works surprisingly well. I've since added a shared file upload facility so that users can share pics/files. The interface is deliberately very customisable so that you can adjust the layout to suit the device you're on (e.g. even on a smartphone, adjust the layout so that you can leave the on-screen keyboard up, but still see the video) and simple enough that it can be operated whilst visually or otherwise impaired.

# Requirements:
A raspberry pi of some sort and the matching RPi-camera. Normal webcams are waay too cpu-heavy and don't work in the same way so there's really no way around that. The RPi camera gives excellent quality for very little cpu overhead.


# THE FUTURE:
Currently it's a one-to-many setup, so only the host can send video (and there's no sound currently either, though one could add an icecast2 server if needed - I prefer typing though). My hope is that if another person (or more) had a similar setup perhaps there could be 2-way (or more) video communication. Perhaps there could even be some sort of self-discovering protocol so it works a bit like Bittorrent and you end up with a totally decentralised, serverless, uncensorable peer-to-peer camming network. Tbh achieving that is a bit beyond my skills at this point but it's possibly something nice to aim for ;)

It might even be possible to link something like an electrum wallet to the back end, throw up QR-codes and then have a sort of "x satoshis for 5 mins" type thing, where access is granted as soon as the payment is seen on the Bitcoin network...but again, waay into the future there. Lightning network transactions would be ideal but the requirement of a full node + LND server would put it way outside the technical scope of the target userbase.


I'm pretty new to Github etc so please excuse that this project is likely a mess at this point - I'm working on improving it :)


Any questions or whatever please do leave a message, comment or however the hell it works on here.


# Features:
- all free
- everything encrypted (uses SSL certs from LetsEncrypt)
- based on RPi-Cam-Web-Interface and works with the raspberry pi camera (other webcams are too cpu-heavy)
- no databases or logs (just uses textfiles which can be nuked at the end of a session)
- no second server required for video feed
- self hosted (uses Cloudflare's free tier to somewhat obscure your home IP)
- allows file upload/sharing to chat members
- allows very high quality video feed
- allows users to choose framerate which best suits them
- allows users to customise the interface to suit their device and level of impairment
- user "accounts" are basically basic-authentication users managed via .htaccess and .htpasswd
- has a super basic admin back-end to allow creating/managing new user accounts


