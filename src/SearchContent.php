<?php declare(strict_types=1);

use Nette\Neon\Neon;
use Nette\Utils\Finder;
use Nette\Utils\Strings;


/**
 * Class SearchContent
 *
 * @author  geniv
 */
class SearchContent implements ISearchContent
{
    /** @var array */
    private $searchMask, $searchPath, $excludePath, $listCategory, $list;
    /** @var bool */
    private $onlyTranslation;


    /**
     * SearchContent constructor.
     *
     * @param array $searchMask
     * @param array $searchPath
     * @param array $excludePath
     * @param bool  $onlyTranslation
     */
    public function __construct(array $searchMask, array $searchPath, array $excludePath, bool $onlyTranslation = false)
    {
        $this->searchMask = $searchMask;
        $this->searchPath = $searchPath;
        $this->excludePath = $excludePath;

        $this->onlyTranslation = $onlyTranslation;
    }


    /**
     * Process.
     */
    private function process()
    {
        if ($this->searchMask && $this->searchPath && !$this->list) {
            $files = [];
            foreach ($this->searchPath as $path) {
                // insert dirs
                if (is_dir($path)) {
                    $fil = [];
                    foreach (Finder::findFiles($this->searchMask)->exclude($this->excludePath)->from($path) as $file) {
                        $fil[] = $file;
                    }
                    natsort($fil);  // natural sorting path
                    $files = array_merge($files, $fil);  // merge sort array
                }
                // insert file
                if (is_file($path)) {
                    $files[] = new SplFileInfo($path);
                }
            }

            // load all default content files
            foreach ($files as $file) {
                $lengthPath = strlen(dirname(__DIR__, 4));
                $partPath = substr($file->getRealPath(), $lengthPath + 1);
                // load neon file
                $fileContent = (array) Neon::decode(file_get_contents($file->getPathname()));
                // prepare empty row
                $this->listCategory[$partPath] = [];
                // decode type logic
                $defaultType = 'translation';
                foreach ($fileContent as $index => $item) {
                    $prepareType = Strings::match($index, '#@[a-z]+@#');
                    // content type
                    $contentType = Strings::trim(implode((array) $prepareType), '@');
                    // content index
                    $contentIndex = Strings::replace($index, ['#@[a-z]+@#' => '']);
                    $value = ['type' => $contentType ?: $defaultType, 'value' => $item];
                    if ($this->onlyTranslation) {
                        // select except translation
                        $this->listCategory[$partPath][$contentIndex] = $value;
                        $this->list[$contentIndex] = $value;
                    }
                }
            }
        }
    }


    /**
     * Get list category.
     *
     * @return array
     */
    public function getListCategory(): array
    {
        $this->process();
        return $this->listCategory ?? [];
    }


    /**
     * Get list.
     *
     * @return array
     */
    public function getList(): array
    {
        $this->process();
        return $this->list ?? [];
    }
}
