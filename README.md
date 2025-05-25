# account_receivable_history
manarom account receivable history

### crontab at 30 minutes befor midnight every day

0 2 * * * php main.php

find duplicate with simple array in php
<?php
$a = ['A', 'B', 'C', 'AA', 'A', 'A'];

$duplicate = array_filter($a, function($item) use ($a) {
    return array_count_values($a)[$item] > 1;
});

print_r($duplicate);
// Output: Array ( [0] => A [4] => A [5] => A )

To get all duplicate entries by the name key (including all their occurrences) from array
<?php
$a = [
    ['name' => 'A', 'value'=> 'X'],
    ['name' => 'B', 'value'=> 'Y'],
    ['name' => 'C', 'value'=> 'Z'],
    ['name' => 'AA', 'value'=> 'AX'],
    ['name' => 'A', 'value'=> 'XA'],
    ['name' => 'A', 'value'=> 'XQ'],
];

$name_counts = array_count_values(array_column($a, 'name'));

$duplicates = array_values(array_filter($a, function($item) use ($name_counts) {
    return $name_counts[$item['name']] > 1;
}));

print_r($duplicates);
/*
Output:
Array
(
    [0] => Array ( [name] => A [value] => X )
    [1] => Array ( [name] => A [value] => XA )
    [2] => Array ( [name] => A [value] => XQ )
)
*/

If you want to remove all entries with duplicate name values from $a (so only unique name entries remain)

<?php
$a = [
    ['name' => 'A', 'value'=> 'X'],
    ['name' => 'B', 'value'=> 'Y'],
    ['name' => 'C', 'value'=> 'Z'],
    ['name' => 'AA', 'value'=> 'AX'],
    ['name' => 'A', 'value'=> 'XA'],
    ['name' => 'A', 'value'=> 'XQ'],
];

$name_counts = array_count_values(array_column($a, 'name'));

$unique = array_values(array_filter($a, function($item) use ($name_counts) {
    return $name_counts[$item['name']] === 1;
}));

print_r($unique);
/*
Output:
Array
(
    [0] => Array ( [name] => B [value] => Y )
    [1] => Array ( [name] => C [value] => Z )
    [2] => Array ( [name] => AA [value] => AX )
)
*/
