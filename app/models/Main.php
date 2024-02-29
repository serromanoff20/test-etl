<?php namespace app\models;

require_once ('../db/Connect.php');

use db\Connect;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

class Main
{
//  private const KEY = 'ZrUpnzsTHvC43A_Gk_m5FGghrpRkXtnJg42hHOLP-tMV0LT5cXeOi97Spmuv2qwZa-4PPtVZ2C0GDs1vs0KvhCxed1vT4A3YcIbzGDkCAB-bPVRgRW6Yezepk5s04G-z';

  public static function getFunction(): string
  {
      try {
          $connect = new Connect();
          $rows = $connect->connection->query('SELECT * FROM prices')->fetchAll();

//          foreach ($rows as $row){
//              $arr = $row['price_code'];
//          }
      } catch (\PDOException $e) {
          return Connect::ERROR .'-'. $e->getMessage();
      }

      return json_encode($rows);
  }

  public function postFunction(string $key): string
  {
      $dataProd = [];
      $InfoArray = [];


      $data = json_decode($_POST['data']);

      if ($key === self::KEY && is_array($data)){
          for ($i = 0; $i < count($data); $i++) {
              $TmpArray[$i] = (array)$data[$i]->prices;

              for ($j = 1; $j < count($TmpArray[$i]); $j++) {
                  $dataProd[$i][$j] = $data[$i]->prices->$j;

                  $InfoArray[$i] = $this->insertProductId($data[$i]->product_id, $j);
                  $InfoArray[$i][$j] = $this->insertPrices($j, (array)$dataProd[$i][$j]);
              }
          }

      } else {

          return self::error();
      }
      return json_encode($InfoArray, JSON_UNESCAPED_UNICODE);
  }

  private function insertProductId(int $product_id, int $price_code): array
  {
      $connect = new Connect();
      $dataTmp = [
          ':id' => $product_id,
          ':price' => (string)$price_code
      ];

      try {
          $res = $connect->connection->prepare("
                INSERT INTO prod(prodact_id, price_code)
                VALUES (:id, :price)
          ");
          if (!$res->execute($dataTmp)){
              $infoProduct = $res->errorInfo();
          } else {
              $infoProduct = array(
                  'message'=>'Успешно!',
                  'code'=>http_response_code()
              );
          }
      }catch(\PDOException $e){
          $infoProduct = array(
              'message'=>Connect::ERROR .'-'.Connect::MassageProd.'-'. $e->getMessage(),
              'code'=>400
          );
      }

      return $infoProduct;
  }

  private function insertPrices(int $price_code, array $typePrices): array
  {
      $connect = new Connect();

      $dataTmp = [
            ':price_code'=>(string)$price_code,
            ':purchase'=>(float)$typePrices['price_purchase'],
            ':selling'=>(float)$typePrices['price_selling'],
            ':discount'=>(float)$typePrices['price_discount']
      ];

      try {
          $res = $connect->connection->prepare("
              INSERT INTO prices(price_code ,price_purchase, price_selling,price_discount)
              VALUES (:price_code, :purchase, :selling, :discount)
          ");
          if (!$res->execute($dataTmp)){
              $infoPrices = $res->errorInfo();
          } else {
              $infoPrices = array(
                  'message' => 'Успешно!',
                  'code' => http_response_code()
              );
          }
      }catch (\PDOException $e){
          $infoPrices = array(
              'message'=>Connect::ERROR .'-'.Connect::MassagePrices.'-'. $e->getMessage(),
              'code'=>400
          );
      }

      return $infoPrices;
  }

  public function error()
  {
      $InfoArray = array(
          'message'=>'Не заполнены или неправильно заполнены обязательные параметры',
          'code'=>400
      );

      return json_encode($InfoArray, JSON_UNESCAPED_UNICODE);
  }
}
