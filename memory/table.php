<?php
// 创建内存表
$table = new swoole_table(1024);

// 内存表增加字段
$table->column('id',$table::TYPE_INT,4);
$table->column('name',$table::TYPE_STRING,64);
$table->column('age',$table::TYPE_INT,3);
$table->create();

// 向表中插入一条数据
$table->set('user1',['id'=>1,'name'=>'frank','age'=>18]);
$table['user2'] = [
    'id'=>2,'name'=>'tom','age'=>34
];
$table['user3'] = [
    'id'=>3,'name'=>'lily','age'=>3
];
$table->del('user3');

$table->incr('user1','age',2);
$table->decr('user2','age',3);

print_r($table->get('user1'));
print_r($table['user2']);
print_r($table['user3']);