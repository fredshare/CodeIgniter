CodeIgniter
===========

## PHP环境安装
Linux、Apache、Mysql、PHP、phpMyAdmin组合：LAMP
详情见 [lampp安装文档](/)

## 开发根目录说明
开发根目录<br />
<pre>
├─system >>				php ci框架的框架代码，不需要修改，升级时直接覆盖该目录 <br />
├─application			项目php代码目录，不可直接访问，被htdocs/index.php调用<br />
│  └─config			    全局配置文件中心<br />
│  	├─dev			    dev环境配置中心<br />
│  	├─beta			    beta环境配置中心<br />
│  	├─gamma		        gamma环境配置中心<br />
│  	└─idc			    idc环境配置中心<br />
│  ├─controllers		业务逻辑组合，对外输出的业务逻辑组合<br />
│  ├─core				核心基类的继承<br />
│  	├─MY_controller		controller基类，cotroller控制器尽量基于次基类开发<br />
│  	└─MY_Model		    model基类，model处理器尽量基于此基类开发<br />
│  ├─helpers			公共基础数库<br />
│  ├─libraries			常用php类库<br />
│  ├─models			    核心models，与数据库交互逻辑<br />
│  ├─log				日志默认存放路径，路径可自行修改，见日志功能说明<br />
│  └─views			    php模板目录<br />
├─htdocs				web根目录，只有这个目录开放给用户，具体的代码是通过目录下的index.php来访问boss下的文件<br />
│  └─sinclude			软连接到static下的文件，一般是软链到static/sinclude目录下<br />
├─static				静态资源目录，对应独立静态web域名<br />
│  ├─css				css目录<br />
│  ├─images			    图片目录<br />
│  ├─js				    js文件存放目录<br />
│  └─sinclude			公共页面片引用目录<br />
│  	├─page			    page文件目录<br />
│      ├─cssi			css文件引用页面片目录<br />
│      └─jsi			js文件引用页面片目录<br />
├─nodejs				nodejs服务框架(预留)<br />
└─shell					定时任务执行shell<br />
</pre>

## 常见功能部署
+ Log日志系统
+ Crontab定时任务
+ xhprof性能检测
+ MY_controller
+ MY_model
+ 直连数据库
