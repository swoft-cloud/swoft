<?php

$envVal = env('WAREHOUSE_CODE');

return [
    // app.warehouseCode
    // 'warehouseCode' => ['a', 'b'],
    'warehouseCode' => $envVal ? explode(',', $envVal) : [],
];
