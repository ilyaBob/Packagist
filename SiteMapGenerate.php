<?php

class SiteMapGenerate
{

   private $typeFile;
   private $arrayMap;
   private $locSave;

   public function __construct($typeFile, $arrayMap, $locSave)
   {
      if (is_string($typeFile) && is_array($arrayMap) && is_string($locSave) && preg_match('/\A\/[a-zA-Z1-9]/', $locSave) != 0) {
         $this->typeFile = $typeFile;
         $this->arrayMap = $arrayMap;
         $this->locSave = __DIR__ . $locSave;
      } else {
         throw new TypeError("Невалидные данные при инициализации парсинга");
      }
   }

   public function createSiteMap()
   {
      try {
         if ($this->typeFile == 'xml') {
            if (!is_dir($this->locSave)) mkdir($this->locSave, 0700, true);
            $this->generateXMLFile($this->locSave . '/sitemap.' . $this->typeFile);
         } else if ($this->typeFile == 'csv') {
            if (!is_dir($this->locSave)) mkdir($this->locSave, 0700, true);
            $this->generateCSVFile($this->locSave . '/sitemap.' . $this->typeFile);
         } else if ($this->typeFile == 'json') {
            if (!is_dir($this->locSave)) mkdir($this->locSave, 0700, true);
            $this->generateJSONFile($this->locSave . '/sitemap.' . $this->typeFile);
         } else {
            throw new Error('Введите корректное имя расширения');
         }
      } catch (ParseError  $e) {
         echo $e->getMessage();
      }

      if ($this->typeFile == 'xml') {
         if (!is_dir($this->locSave)) mkdir($this->locSave, 0700, true);
         $this->generateXMLFile($this->locSave . '/sitemap.' . $this->typeFile);
      } else if ($this->typeFile == 'csv') {
         if (!is_dir($this->locSave)) mkdir($this->locSave, 0700, true);
         $this->generateCSVFile($this->locSave . '/sitemap.' . $this->typeFile);
      } else if ($this->typeFile == 'json') {
         if (!is_dir($this->locSave)) mkdir($this->locSave, 0700, true);
         $this->generateJSONFile($this->locSave . '/sitemap.' . $this->typeFile);
      } else {
         echo $e->getMessage();
      }
   }

   private function generateXMLFile($url)
   {
      $file = fopen($url, "w");
      $text = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n";
      fwrite($file, $text);
      foreach ($this->arrayMap as $item) {
         $text = "\t<url>
         <loc>" . $item['loc'] . "</loc>
         <lastmod>" . $item['lastmod'] . "</lastmod>
         priority>" . $item['priority'] . "</priority>
         <changefreq>" . $item['changefreq'] . "</changefreq>
         </url>\n";
         fwrite($file, $text);
      }
      fwrite($file, "</urlset>");
   }

   private function generateJSONFile($url)
   {
      $file = fopen($url, "w");
      $text = '[';
      fwrite($file, $text);
      for ($i = 0; $i < count($this->arrayMap) - 1; $i++) {
         $text = json_encode($this->arrayMap[$i]) . ", \n";
         fwrite($file, $text);
      }
      $text = json_encode($this->arrayMap[count($this->arrayMap) - 1]);
      fwrite($file, $text . ']');
   }

   private function generateCSVFile($url)
   {
      $file = fopen($url, "w");
      $text = 'loc;lastmod;priority;changefreq' . "\n";
      fwrite($file, $text);
      foreach ($this->arrayMap as $item) {
         fputcsv($file, $item);
      }
   }
}

/*
Пример:
$arrTest = [
   [
      "loc" => 'https://site.ru/',
      "lastmod" => '2020-12-14',
      "priority" => '1',

   ],
   [
      "loc" => 'https://site.ru/news',
      "lastmod" => '2020-12-10',
      "priority" => '0.5',
      "changefreq" => 'daily'
   ],
   [
      "loc" => 'https://site.ru/about',
      "lastmod" => '2020-12-07',
      "priority" => '0.1',
      "changefreq" => 'weekly'
   ],
   [
      "loc" => 'https://site.ru/products',
      "lastmod" => '2020-12-12',
      "priority" => '0.5',
      "changefreq" => 'daily'
   ],
   [
      "loc" => 'https://site.ru/products/ps5',
      "lastmod" => '2020-12-11',
      "priority" => '0.1',
      "changefreq" => 'weekly'
   ],
   [
      "loc" => 'https://site.ru/products/xbox',
      "lastmod" => '2020-12-12',
      "priority" => '0.1',
      "changefreq" => 'weekly'
   ],
   [
      "loc" => 'https://site.ru/products/wii',
      "lastmod" => '2020-12-11',
      "priority" => '0.1',
      "changefreq" => 'weekly'
   ]
];
$test = new SiteMapGenerate("csv", $arrTest, "/qwe");
$test->createSiteMap();

*/

