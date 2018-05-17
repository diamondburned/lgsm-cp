# lgsm-cp
This is a PHP web panel made for LinuxGSM, it uses `bash` and `PHP` to do magical stuff with `tmux` and LinuxGSM. The light and messy PHP script has been made to output 32767 lines of `tmux` console, which should be enough. This control panel should be compatible with all LinuxGSM-supported servers.

### Source and instruction
RCON script is written by me, but it shouldn't deserve any attention tbh

To properly use the LinuxGSM Web Panel, you need to add this to `visudo`:

```
www-data ALL=(gameserver-user) NOPASSWD: /home/gameserver-user/gameserver
www-data ALL=(gameserver-user) NOPASSWD: webserver-root/tmux-run
```

(`gameserver-user` is your gameserver username, `/home/gameserver-user/gameserver` is the LinuxGSM location, `gameserver` is the type of gameserver and `webserver-root` is the webserver root)

### Mini-wiki
- **Before trying out the Webpanel, PLEASE edit the `config.php` file.**
- The login method is already implemented. To change the password, run `echo -n "PASSWORD HERE" | sha1sum` under Linux and copy the password to `config.php`. The default password is `admin`.
- While running commands such as Monitor, if the PHP load is loading for more than one minute, chances are it's still running stuff. Do NOT close the page (please I don't know what will happen, you might screw up your server).
- Some console outputs such as Console or Logs are actually reversed (`tac`) so the latest lines are on top.
- Two gameservers in one user guide: **/!/WORK IN PROGRESS/!/**

### Screenshot
![Web UI](http://i.cubeupload.com/VOGIN8.png)

### Changelog
- May 13th, 2018: Removed Update function as currently there isn't a way for this to run in the background. Also added log-in function. First changelog entry.
- May 16th, 2018: Redesigned interface, added more functions such as System Statistics, added back Update.

### Todo
- [ ] Maybe make it so that the LinuxGSM fast commands don't hang up when bash is not completed, but instead returns the stdout live.
- [ ] Add multiple pages to toggle server (I'm confused as hell right now to be honest)
- [ ] Make an easy installer that actually installs requirements
- [ ] Multiple servers in one user support (actually messes up `tmux` horribly)
- [x] Maybe add Bash coloring support (removed the jumbled mess as a workaround instead)
- [x] Add support for other types of RCON (now universal)
- [x] Add more LinuxGSM actions (will do first)
- [x] Have some non-idiot do the cookies for me because I can't do it :( (no longer need to, configs now stored in `config.php`)
