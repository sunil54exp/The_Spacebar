<?php
namespace App\EventListener;

class KernelException
{
    public function onKernelException()
    {
        die("i am a litener");
    }
}