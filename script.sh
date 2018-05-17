#!/bin/bash
lgsmuser=$(cat config.php | grep -F '$lgsmuser =' | awk '{print $3}' | sed "s/'//g" | sed 's/;//g')
game=$(cat config.php | grep -F '$game =' | awk '{print $3}' | sed "s/'//g" | sed 's/;//g')
#sed -r "s/\x1B\[([0-9]{1,2}(;[0-9]{1,2})?)?[mGK]//g" to strip colorcodes
if [[ $1 = "details" ]]; then
	/usr/bin/sudo -u $lgsmuser /home/$lgsmuser/$game details | sed -r "s/\x1B\[([0-9]{1,2}(;[0-9]{1,2})?)?[mGK]//g" 2>&1
	echo ""
elif [[ $1 = "monitor" ]]; then
	/usr/bin/sudo -u $lgsmuser /home/$lgsmuser/$game monitor | sed -r "s/\x1B\[([0-9]{1,2}(;[0-9]{1,2})?)?[mGK]//g" 2>&1
	echo ""
elif [[ $1 = "restart" ]]; then
	/usr/bin/sudo -u $lgsmuser /home/$lgsmuser/$game restart | sed -r "s/\x1B\[([0-9]{1,2}(;[0-9]{1,2})?)?[mGK]//g" 2>&1
	echo ""
elif [[ $1 = "update" ]]; then
	echo "This process might take a really long time, so it will run in the background instead."
	echo ""
	echo 'To check the progress, go to Progress or do $(cat /tmp/lgsm-cp-progress.log)'
	nohup "/usr/bin/sudo -u $lgsmuser /home/$lgsmuser/$game update" > /tmp/lgsm-cp-progress.log 2>> /tmp/lgsm-cp-progress.log &
elif [[ $1 = "update-progress" ]]; then
	cat /tmp/lgsm-cp-update.log | sed -r "s/\x1B\[([0-9]{1,2}(;[0-9]{1,2})?)?[mGK]//g"
elif [[ $1 = "stop" ]]; then
	/usr/bin/sudo -u $lgsmuser /home/$lgsmuser/$game stop | sed -r "s/\x1B\[([0-9]{1,2}(;[0-9]{1,2})?)?[mGK]//g" 2>&1
	echo ""
elif [[ $1 = "alert" ]]; then
	cat /home/$lgsmuser/log/script/${game}-alert.log | tac 2>&1
	echo "" | tac
elif [[ $1 = "console" ]]; then
	cat /home/$lgsmuser/log/console/${game}-console.log | tac 2>&1
	echo "" | tac
elif [[ $1 = "sysinfo" ]]; then
	echo "System Statistics for" $(whoami)@$(hostname)
	echo ""
	if type "neofetch" &> /dev/null; then
		neofetch --bold off --underline off --color_blocks off --colors --disable ascii color shell colors | sed -r "s/\x1B\[([0-9]{1,2}(;[0-9]{1,2})?)?[mGK]//g" | sed '1d' | sed '$ d' | sed '$ d' | sed '$ d'
	fi
	echo "CPU usage:" $({ head -n1 /proc/stat;sleep 0.2;head -n1 /proc/stat; } | awk '/^cpu /{u=$2-u;s=$4-s;i=$5-i;w=$6-w}END{print int(0.5+100*(u+s+w)/(u+s+i+w))}')%
	echo "Average load:" $(cat /proc/loadavg | awk '{print $1,$2,$3}')
	echo ""
	echo "Top processes:"
	ps -Ao 'pid,comm,pcpu,user' --sort=-pcpu | head -n 6
	echo ""
	free -h | sed "s/^/\t/" | sed "s/	     /Memory usage:/g" | sed "s/\tMem: /\tMem :/g"
	echo ""
	echo "Kernel:" $(uname -s) $(uname -r)
	echo "Uptime:" $(uptime -p | sed 's/up //g')
	echo ""
	echo "Generated on" $(date)
else 
	echo 'Invalid! Please open an issue on Github.'
	echo 'Usage: ./script.sh [details|monitor|restart|update|update-progress|stop|alert|console|sysinfo]'
fi
