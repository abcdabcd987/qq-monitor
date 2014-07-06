[General]
SyntaxVersion=2
BeginHotkey=121
BeginHotkeyMod=0
PauseHotkey=0
PauseHotkeyMod=0
StopHotkey=123
StopHotkeyMod=0
RunOnce=1
EnableWindow=
MacroID=e74f438b-d1bd-4825-8475-cc54272d5b5c
Description=Monitor
Enable=1
AutoRun=0
[Repeat]
Type=0
Number=1
[SetupUI]
Type=2
QUI=
[Relative]
SetupOCXFile=
[Comment]

[Script]
Rem Start
Rem 切换至BlueStacks
BlueStacks = lib.API.查找窗口句柄("WindowsForms10.Window.8.app.0.ea7f4a_r11_ad1", "BlueStacks App Player")
Call Lib.API.激活窗口并置前(BlueStacks)
Delay 500
Rem 【消息】面板下拉刷新
MoveTo 800, 400
Delay 200
LeftDown 1
Delay 200
MoveTo 800, 500
Delay 200
MoveTo 800, 600
Delay 1000
LeftUp 1
Delay 3000
Rem 【消息】面板【搜索】
MoveTo 800, 190
Delay 200
LeftClick 1
Delay 3000
MoveTo 800, 270
Delay 200
LeftClick 1
Delay 1000
Rem 截图
Plugin.Pic.PrintScreen 800,120,1050,162,"C:\Users\abcdabcd987\Desktop\monitor.jpg"
Rem 上传
RunApp "C:\Users\abcdabcd987\Desktop\monitor_upload.bat"
Delay 5000
Rem 切换到【消息】面板
Call Lib.API.激活窗口并置前(BlueStacks)
MoveTo 165, 150
LeftClick 1
Rem 结束
Delay 60000
Goto Start
