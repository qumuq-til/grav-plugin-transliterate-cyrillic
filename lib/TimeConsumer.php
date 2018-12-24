<?php

namespace Acme;

class TimeConsumer
{
    public function consume()
    {
        usleep(100);
    }
}