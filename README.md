# lgsm-cp
This is a PHP web panel made for LinuxGSM, it uses `bash` and `PHP` to do magical stuff with the compiled RCON script. The light and messy PHP script has been made to output 32767 lines of `tmux` console, which should be enough. This control panel should be compatible with all LinuxGSM-supported servers.

### Source and instruction
RCON script is written by me, but it shouldn't deserve any attention tbh

To properly use the LinuxGSM Web Panel, you need to add this to `visudo`:

```
www-data ALL=(gameserver-user) NOPASSWD: /home/gameserver-user/gameserver
www-data ALL=(gameserver-user) NOPASSWD: webserver-root/tmux-run
```

(`gameserver-user` is your gameserver username, `/home/gameserver-user/gameserver` is the LinuxGSM location, `gameserver` is the type of gameserver and `webserver-root` is the webserver root)

### Mini-wiki
- Before trying out the Webpanel, PLEASE edit the `config.php` file.
- The login method is already implemented. To change the password, run `echo -n "PASSWORD HERE" | sha1sum` under Linux and copy the password to `config.php`. The default password is `admin`.
- While running commands such as Monitor, if the PHP load is loading for more than one minute, chances are it's still running stuff. Do NOT close the page (please I don't know what will happen, you might screw up your server).
- Console outputs are actually reversed (`tac`) so the latest is at the top.
- Two gameservers in one user guide:
	- For the LGSM User box, put in the gameserver user as usual
	- For the Game box, put in your `location/gameserver` (e.g. if your script is in `~/1stserver/tf2server-1` then the box must have `1stserver/tf2server-1`)
	- Run commands like usual

### Screenshot
![Web UI](http://i.cubeupload.com/Y96KzB.png)

### Changelog
- May 13th, 2018: Removed Update function as currently there isn't a way for this to run in the background. Also added log-in function. First changelog entry.

### Todo
- [ ] Maybe add Bash coloring support
- [ ] Maybe make it so that the LinuxGSM fast commands don't hang up when bash is not completed, but instead returns the stdout live.
- [ ] Add multiple pages to toggle server (I'm confused as hell right now to be honest)
- [x] Add support for multiple servers in one user
- [x] Add support for other types of RCON
- [x] Add more LinuxGSM actions (will do first)
- [x] Have some non-idiot do the cookies for me because I can't do it :( (no longer need to, configs now stored in `config.php`)
