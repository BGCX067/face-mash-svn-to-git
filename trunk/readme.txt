1.Conf/config.php为配置文件
2.数据库中执行mash.sql,然后在f_user表中添加你的数据信息即可，avatar字段为头像地址
3.添加（更新）完f_user表中的数据后删除Runtime目录下的所有文件（缓存）
4.后台管理登录地址：http://xxx/index.php?m=Index&a=adminLogin&grade=all
  学号：admin
  密码：admin
  可以在Conf/config.php中修改
5.如果遇到难以理解的错误，可以尝试删除Runtime目录下的所有文件试试
6.登录后可以添加新的排行榜
