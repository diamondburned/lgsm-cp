# lgsm-cp
This is a PHP web panel made for LinuxGSM, it uses `bash` and `PHP` to do magical stuff with the compiled RCON script.

### Source and instruction:
RCON script from https://github.com/n0la/rcon

To properly use the LinuxGSM part of the script, you need to add this to `visudo`:

```www-data ALL=(gameserver-user) NOPASSWD: /home/gameserver-user/gameserver```

(`gameserver-user` is your gameserver username, and `/home/gameserver-user/gameserver` is the script location)

For authentication method, please use Apache/NGINX basic authentication methods for now: https://www.digitalocean.com/community/tutorials/how-to-set-up-basic-http-authentication-with-nginx-on-ubuntu-14-04
