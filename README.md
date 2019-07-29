# swoole-pool
`swoole-pool for swoole4.4`
# install
`composer mecyu/swoole-pool`
# phpunit test
`#: cd Mecyu/SwoolePool`
`SwoolePool#: phpunit`
# run server && ab test
use default mysql db ,to change the db:  
`
SwoolePool#: vim server.php
`  
change '$this->pool   = pool('Mysql');' to '$this->pool   = pool('Redis');'  
in one teminal:  
`SwoolePool#: php server.php`  
and the other teminal:  
`SwoolePool#: ab -c 200 -n 100000 -k http://127.0.0.1:9501/`  
# Notice
In order to make all your step run successed , please make sure you are in   
`
> php7.2 version
> phpunit7.0
>swoole4.4.
`  
Thank you !  
This my swoole learning pro. Thank you for your view and good ideas!  
