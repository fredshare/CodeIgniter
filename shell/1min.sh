#!/bin/bash
cd /data/vb2c_lottery/web/htdocs_crontab	#进入htdocs目录
/usr/local/php/bin/php index.php timer oneMinute #使用ci框架的cli命令行执行timer控制器的oneMinute函数

