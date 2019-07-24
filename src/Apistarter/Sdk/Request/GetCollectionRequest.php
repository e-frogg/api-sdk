<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 19/02/19
 * Time: 14:48
 */

namespace Apistarter\Sdk\Request;


/**
 * Class GetCollectionAbstractRequest
 * @package Apistarter\Sdk\Request
 *
 * @property int $count
 * @property string $order_by
 * @property string $order_way
 *
 * @method $this setPage(int $page)
 */
abstract class GetCollectionRequest extends GetRequest
{

// ?property[]=foo&property[]=bar
// Search Filter
// http://localhost:8000/api/offers?price=10&description=shirt
// /offers?createdAt[exact, partial, start, end, and word_start]=2018-03-19
//
// Date Filter
// ?property[<after|before|strictly_after|strictly_before>]=value
// /offers?createdAt[after]=2018-03-19
// boolean
// ?property=<true|false|1|0>
// numeric
// Syntax: ?property=<int|bigint|decimal...>
// range
// Syntax: ?property[<lt|gt|lte|gte|between>]=value
// Exists Filter
// Syntax: ?property[exists]=<true|false|1|0>
// Order Filter (Sorting)
// /offers?order[name]=desc&order[id]=asc
// Filtering on Nested Properties
// http://localhost:8000/api/offers?order[product.releaseDate]=desc

// pagination
// items_per_page
// /books?page=1&items_per_page

    const OPERATOR_EQUAL = '=';
    // filter range
    const OPERATOR_LESS_THAN = "lt";
    const OPERATOR_LESS_THAN_OR_EQUAL = "lte";
    const OPERATOR_GREATER_THAN = "gt";
    const OPERATOR_GREATER_THAN_OR_EQUAL = "gte";
    const OPERATOR_BETWEEN = "between";
    // filter date
    const OPERATOR_BEFORE = "before";
    const OPERATOR_AFTER = "after";
    const OPERATOR_STRICTLY_AFTER = "strictly_after";
    const OPERATOR_STRICTLY_BEFORE = "strictly_before";


    protected $order=[];
    protected $itemsPerPage=30;
    protected $pagination;

    private $search_parameters=[];

    public function getQueryParameters()
    {
        return array_merge(
            static::$queryParameters,[
                'order',
                'page',
                'itemsPerPage',
//                'fields',
                'pagination'
            ],$this->search_parameters
        );
    }

    /**
     * @param string $sort_on
     * @param string $sort_way
     * @return $this
     */
    public function addOrder($sort_on, $sort_way): self
    {
        $this->order[$sort_on]=$sort_way;
        return $this;
    }

    /**
     * @param array $sort
     * @return GetCollectionRequest
     */
    public function setOrder(array $sort): GetCollectionRequest
    {
        $this->order = $sort;
        return $this;
    }

    public function setItemsPerPage(int $items_per_page): self
    {
        $this->itemsPerPage = $items_per_page;
        return $this;
    }

    public function addFilter($field_name,$value,$operator=self::OPERATOR_EQUAL) {
        $this->search_parameters[]=$field_name;

        if(self::OPERATOR_EQUAL === $operator) {
            $this->$field_name = $value;
        } else {
            // les propriétés étant magiques, le array_access ne fonctionne pas correctement
            // le code ci-dessous est donc obligatoire
            if(!$this->__isset($field_name)) {
                $this->$field_name=[$operator=>$value];
            } else {
                $this->$field_name=array_merge([$operator=>$value],$this->$field_name);
            }
        }
        return $this;
    }

    /**
     * @param bool $pagination
     * @return GetCollectionRequest
     */
    public function setPagination(bool $pagination): GetCollectionRequest
    {
        $this->pagination = ($pagination?"true":"false");
        return $this;
    }

}