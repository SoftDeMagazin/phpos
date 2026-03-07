<?php
function f()
{
    echo "f";
}
function g()
{
    echo "g";
}
$c = 2;
$b = 2;

($b == $c) ? f() : g();
