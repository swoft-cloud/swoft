<?php
use \Swoft\App;

App::setAlias('@root', BASE_PATH);
App::setAlias('@app', '@root/app');
App::setAlias('@runtime', '@root/runtime/'.SYSTEM_NAME);
App::setAlias('@settings', '@root/bin/swoft.ini');