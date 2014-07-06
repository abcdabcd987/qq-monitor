qq-monitor
==========

Watch somebody's network status on QQ and send it to a server.

## How it works? ##

*QQ for Android* and *QQ for iOS* can display users' network status now, for example, using *2G* or using *Wi-Fi*. And my way to watch a certain user's network status is to use an Android emulator running QQ.

I wrote a script for [*An Jian Jing Ling*](http://www.anjian.com) to refresh user's status on Android emulator. The script will also take pictures of user's network status and send it to a server.

The server will record the status and hash the pictures. Then, we can do OCR manually. Since the pictures are taken by the script, they will be the same as long as the network status is the same. So, we only have to do OCR 2~3 times.

