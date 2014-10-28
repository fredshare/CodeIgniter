CodeIgniter
===========

## PHP环境安装
Linux、Apache、Mysql、PHP、phpMyAdmin组合：LAMP
详情见 [lampp安装文档](/)

## 开发根目录说明
开发根目录<br />
<pre>
├─system >>				php ci框架的框架代码，不需要修改，升级时直接覆盖该目录 
├─application			项目php代码目录，不可直接访问，被htdocs/index.php调用
│  └─config			    全局配置文件中心
│  	├─dev			    dev环境配置中心
│  	├─beta			    beta环境配置中心
│  	├─gamma		        gamma环境配置中心
│  	└─idc			    idc环境配置中心
│  ├─controllers		业务逻辑组合，对外输出的业务逻辑组合
│  ├─core				核心基类的继承
│  	├─MY_controller		controller基类，cotroller控制器尽量基于次基类开发
│  	└─MY_Model		    model基类，model处理器尽量基于此基类开发
│  ├─helpers			公共基础数库
│  ├─libraries			常用php类库
│  ├─models			    核心models，与数据库交互逻辑
│  ├─log				日志默认存放路径，路径可自行修改，见日志功能说明
│  └─views			    php模板目录
├─htdocs				web根目录，只有这个目录开放给用户，具体的代码是通过目录下的index.php来访问boss下的文件
│  └─sinclude			软连接到static下的文件，一般是软链到static/sinclude目录下
├─static				静态资源目录，对应独立静态web域名
│  ├─css				css目录
│  ├─images			    图片目录
│  ├─js				    js文件存放目录
│  └─sinclude			公共页面片引用目录
│  	├─page			    page文件目录
│      ├─cssi			css文件引用页面片目录
│      └─jsi			js文件引用页面片目录
├─nodejs				nodejs服务框架(预留)
└─shell					定时任务执行shell
</pre>

## 常见功能部署
+ Log日志系统
+ Crontab定时任务
+ xhprof性能检测
+ MY_controller
+ MY_model
+ 直连数据库

#### Log日志系统
+ 引入日志类库
+ 实例化类
+ 打日志，日志路径一般放到/data/log目录下，并再按照controller分子目录管理
![img](http://mulinstudio.qiniudn.com/github_QQ%E5%9B%BE%E7%89%8720141028132821.png)

#### Crontab定时任务
+ 在shell目录中建立shell脚本文件。每隔一分钟执行的脚本放入1min.sh中，每隔十分钟需要执行的放入10min.hs。依次轮推。
+ 在脚本中写入相应的脚本。然后在linux的crontab文件中设置定时任务。
<code>
cd /data/vb2c_lottery/web/htdocs_crontab
#进入htdocs目录
/usr/local/php/bin/php index.php timer tenMinute
#使用ci框架的cli命令行执行timer控制器的tenMinute函数
</code>

+ 在crontab中设置crontab命令。crontab编辑命令：Crontab –e
+ 
