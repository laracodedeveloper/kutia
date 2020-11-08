<?php
/**
 * File LaravelCommand.php.
 * @copyright 2020
 * @version 1.0
 */

namespace Kutia\Laravel;


use Illuminate\Console\Command;
use Kutia\Laravel\Modules\Interfaces\PackageInterface;

abstract class LaravelCommand extends Command
{

    /**
     * @var PackageInterface
     */
    protected PackageInterface $packagist;

    public function __construct(PackageInterface $packagist)
    {
        parent::__construct();
        $this->packagist = $packagist;
    }
}
