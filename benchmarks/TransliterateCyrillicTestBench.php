<?php

use Acme\TransliterateCyrillicTest;

class TransliterateCyrillicBench
{
    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchIterate()
    {
        $tester = new TransliterateCyrillicTest();
        $tester->iterate();
    }
}