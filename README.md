# lgsm-cp
This is a PHP web panel made for LinuxGSM, it uses `bash` and `PHP` to do magical stuff with the compiled RCON script. The light and messy PHP script has been made to output 100 lines of `tmux` console, which should be enough. Feel free to modify `tmux-run` (`-S -<line-num>`) to your liking. The logs part has Sourcemod in it, which of course is srcds-only.

### Source and instruction
RCON script is written by me, but it shouldn't deserve any attention tbh

To properly use the LinuxGSM Web Panel, you need to add this to `visudo`:

```
www-data ALL=(gameserver-user) NOPASSWD: /home/gameserver-user/gameserver
www-data ALL=(gameserver-user) NOPASSWD: webserver-root/tmux-run
```

(`gameserver-user` is your gameserver username, `/home/gameserver-user/gameserver` is the LinuxGSM location, `gameserver` is the type of gameserver and `webserver-root` is the webserver root)

For authentication method, please use Apache/NGINX basic authentication methods for now: https://www.digitalocean.com/community/tutorials/how-to-set-up-basic-http-authentication-with-nginx-on-ubuntu-14-04

### Mini-wiki
- The "LGSM User" box is obviously for the user the LinuxGSM script is running on.
- The "Game" box is for gameserver script names, such as `tf2server` or `csgoserver`.
- While running commands such as Update or Monitor, if the PHP load is loading for more than one minute, chances are it's still running stuff. Do NOT close the page (please I don't know what will happen, you might screw up your server).
- Console outputs are actually reversed (`tac`) so the latest is at the top.

### Screenshot
![Web UI](http://i.cubeupload.com/yaiuwW.png)

### Todo
- [x] Add support for other types of RCON
- [x] Maybe split the srcds-only part to a different file
- [x] Add more LinuxGSM actions (will do first)
- [ ] Have some non-idiot do the cookies for me because I can't do it :(
- [ ] Maybe add Bash coloring support
- [ ] Maybe make it so that the LinuxGSM fast commands don't hang up when bash is not completed, but instead returns the stdout live.
- [ ] Add support for multiple servers in one user
