<?php declare(strict_types=1);


/**
 * Interface ISearchContent
 *
 * @author  geniv
 */
interface ISearchContent
{

    /**
     * Get list category.
     *
     * @return array
     */
    public function getListCategory(): array;


    /**
     * Get list.
     *
     * @return array
     */
    public function getList(): array;
}
