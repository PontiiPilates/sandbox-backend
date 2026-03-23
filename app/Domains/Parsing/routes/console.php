<?php

use Illuminate\Support\Facades\Schedule;

// UTC > KRSK
//  00 > 07
//  01 > 08
//  02 > 09 -
//  03 > 10
//  04 > 11
//  05 > 12
//  06 > 13
//  07 > 14
//  08 > 15 -
//  09 > 16
//  10 > 17
//  11 > 18
//  12 > 19
//  13 > 20
//  14 > 21
//  15 > 22
//  16 > 23
//  17 > 00
//  18 > 01
//  19 > 02
//  20 > 03
//  21 > 04
//  22 > 05
//  23 > 06

Schedule::command('parsing:tg-events-extract 1')->twiceDailyAt(1, 7, 45);
Schedule::command('parsing:extraction-preparation')->twiceDailyAt(1, 7, 50);
Schedule::command('parsing:update-events')->twiceDaily(1, 7, 55);
