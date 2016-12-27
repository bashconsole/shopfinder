<?php
declare(strict_types=1);

namespace Bashconsole\Shopfinder\Api\Data;

/**
 * @api
 */
interface ShopfinderInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const STOCKIST_ID         = 'entity_id';
    const STORE_ID            = 'store_id';
    const SHOP_ID         = 'shop_id';
    const NAME                = 'name';
    const STATUS              = 'status';
    const COUNTRY             = 'country';
    const IMAGE               = 'image';
    const CREATED_AT          = 'created_at';
    const UPDATED_AT          = 'updated_at';


    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get ShopID
     *
     * @return string
     */
    public function getShopId();

    /**
     * Get name
     *
     * @return string
     */
    public function getName();
    
    
    /**
     * Get image
     *
     * @return string
     */
    public function getImage();
    

    /**
     * Get is active
     *
     * @return bool|int
     */
    public function getStatus();


    /**
     * Get country
     *
     * @return string
     */
    public function getCountry();

    /**
     * set id
     *
     * @param $id
     * @return ShopfinderInterface
     */
    public function setId($id);

    /**
     * set shop_id
     *
     * @param $id
     * @return ShopfinderInterface
     */
    public function setShopId($id);

    /**
     * set name
     *
     * @param $name
     * @return ShopfinderInterface
     */
    public function setName($name);
    
    /**
     * set image
     *
     * @param $image
     * @return AuthorInterface
     */
    public function setImage($image);


    /**
     * Set country
     *
     * @param $country
     * @return ShopfinderInterface
     */
    public function setCountry($country);

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * set created at
     *
     * @param $createdAt
     * @return ShopfinderInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt();

    /**
     * set updated at
     *
     * @param $updatedAt
     * @return ShopfinderInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * @param $storeId
     * @return ShopfinderInterface
     */
    public function setStoreId($storeId);

    /**
     * @return int[]
     */
    public function getStoreId();

}
