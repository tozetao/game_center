<?php

namespace App\Services\Lock;

interface Lock
{
    function lock($key);

    function release($key);
}