<?php
namespace Kutia\Laravel\Modules;

use Illuminate\Console\Command;
use Kutia\Laravel\Modules\Interfaces\PackageInterface;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuItem\AsciiArtItem;

class Packagist extends Command implements PackageInterface
{

    private array $listPackages = [
        "spatie/laravel-permission" => "Spatie Permission",
        "spatie/laravel-medialibrary:^9.0.0" => "Spatie Media Library",
        "spatie/laravel-sluggable" => "Spatie Sluggable",
    ];
    protected array $packs = [];

    /**
     * Install Packages
     * @return array
     */
    public function install()
    {

        $menu = $this->menu('Install Laravel Packages');
        $menu->addAsciiArt($this->logo(), AsciiArtItem::POSITION_LEFT);
        foreach ($this->listPackages as $key => $value)
        {
            $menu->addCheckboxItem($value, $this->callable());
        }
        $menu->open();

        return $this->getPacks();
    }


    /**
     * Callable function for checkbox
     * @return \Closure
     */
    protected function callable(){
        return function(CliMenu $menu){
            $item   = $menu->getSelectedItem();
            $option = array_search($item->getText(), $this->listPackages);

            if ($this->isSelected($option)) {
                $this->packs = array_values(array_diff($this->packs, [$option]));
            } else {
                $this->packs[] = $option;
            }
        };
    }

    /**
     * Get all selected packages
     * @return array
     */
    protected function getPacks()
    {
        return $this->packs;
    }

    /**
     * Check if is selected
     * @param string $option
     * @return bool
     */
    protected function isSelected(string $option): bool
    {
        return (bool) in_array($option, $this->packs);
    }

    /**
     * Get Logo of menu
     * @return string
     */
    protected function logo()
    {
        return <<<ART
        _ __ _
       / |..| \
       \/ || \/
        |_''_|
      KUTIA INSTALL
        PAKAGIST


ART;
    }
}
