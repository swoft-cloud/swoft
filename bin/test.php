<?php

system('ab -n 10000 -c 2000  127.0.0.1:18306/redis/et > /dev/null');
