<?php
$cfg['blowfish_secret'] = 'af2949d8b1b31d6349177e69785a203d';
$cfg['Servers'][1]['auth_type'] = 'cookie';
$cfg['Servers'][1]['host'] = '127.0.0.1';
$cfg['Servers'][1]['port'] = '3306';
$cfg['Servers'][1]['compress'] = false;
$cfg['Servers'][1]['AllowNoPassword'] = true;
$cfg['TempDir'] = sys_get_temp_dir() . '/pma-tmp';
@mkdir($cfg['TempDir'], 0700, true);
