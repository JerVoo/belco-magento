<?php

/**
 * Class Belco_Widget_Helper_Data
 */
class Belco_Widget_Helper_Data extends Mage_Core_Helper_Abstract
{

  /**
   * @var Belco_Widget_Model_Api
   */
  private $api;

  /**
   * @var Belco_Widget_Model_Logger
   */
  private $logger;

  /**
   * Gets and sets the dependency's
   */
  public function __construct(){
    $this->api = Mage::getModel('belco/api');
    $this->logger = Mage::getModel('belco/logger');
  }

  /**
   * Sends all orders to the Belco API.
   */
  public function sendOrders(){
    $collection = Mage::getModel('sales/order')->getCollection();
    $this->api->syncOrders($collection);
  }

  /**
   * Sends all customers to the Belco API.
   */
  public function sendCustomers(){
    $collection = Mage::getResourceModel('customer/customer_collection')
      ->addNameToSelect()
      ->addAttributeToSelect('email')
      ->addAttributeToSelect('created_at');

    $this->api->syncCustomers($collection);
  }
    
  public function connectShop()
  {
    $result = $this->api->connect();
  }

  /**
   * @param $message
   */
  public function log($message){
    $this->logger->log($message);
  }

  /**
   * @return Belco_Widget_Model_Api
   */
  public function getApi(){
    return $this->api;
  }

  public function getLogger(){
    return $this->logger;
  }

  public static function warnAdmin($warning){
    Mage::getSingleton('adminhtml/session')->addWarning("Belco: " . $warning);
  }

  public static function noticeAdmin($notice){
    Mage::getSingleton('adminhtml/session')->addSuccess("Belco: " . $notice);
  }

  public static function formatPrice($price){
    return Mage::helper('core')->currency($price, true, false);
  }
}
   