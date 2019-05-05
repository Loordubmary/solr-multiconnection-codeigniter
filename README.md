# Solr - How to create multiconnection in codeigniter using Solarium

Make solr multiple connection with same/different core in codeigniter using solarium.

## Index

1. Setup Apache solr
2. Setup Codeigniter framework
3. Setup Solarium
4. Make Multiple connection in codeigniter

## Setup Apache solr

## Setup Codeigniter framework

1. Download codeigniter from [this](https://codeload.github.com/bcit-ci/CodeIgniter/zip/3.1.10).
2. Unzip the package.
3. Upload the CodeIgniter folders and files to your server. Normally the index.php file will be at your root.
4. Open the application/config/config.php file with a text editor and set your base URL. If you intend to use encryption or sessions, set your encryption key.
5. If you intend to use a database, open the application/config/database.php file with a text editor and set your database settings.
6. run your application via server_url (Example: www.example.com or localhost/project_name).

## Setup Solarium

Befor setup solarium, we have following requirements,

```
- JRE (Java Runtime Environment)
- Solr
- Composer
- Codeigniter
- WAMP/LAMP/XAMPP
```
1. Install solarium pakage via composer.
  - composer require solarium/solarium
2. Add below autoload.php file into the codeigniter index.php.
  - include_once './vendor/autoload.php';
3. Create config file name solarium.php. And add below code.
```
<?php
  $config['endpoint1'] = array( // endpoint1 is a connection variable. It not a default keyword.
      'endpoint' => array(
          'localhost' => array(
              'host' => 'host_name', // localhost or www.host.com
              'port' => 'port_value', //Default 8983 or 8080 etc,
              'path' => '/solr/',
              'core' => 'solr_core_name' // core1 or movie1 or etc,
          )
      )
  );
?>
```
4. Start solarium in your controller page.
  - First load solr client in __construct()
  ```
    $this->config->load('solarium');
    $this->client = new Solarium\Client($this->config->item('endpoint1')); // This is used to make connection with solr using 'endpoint1' config variable.
  ```
  - And add below function in your controller
  ```
      $query = $this->client->createSelect();
      $result = $client->select($query);
  ```
  - The full controller page is,
  ```
  <?php defined('BASEPATH') OR exit('No direct script access allowed');
  
     class Test extends CI_Controller {
        public function __construct() {
            parent::__construct();
            $this->config->load('solarium');
            $this->client = new Solarium\Client($this->config->item('endpoint1')); // This is used to make connection with solr using 'endpoint1' config variable.
         }

         public function test() {
            $query = $this->client->createSelect();
            $result = $client->select($query);

            echo 'NumFound: '.$result->getNumFound() . PHP_EOL;

            foreach ($result as $document) {
                echo '<hr/><table>';
                foreach($document AS $field => $value)
                {
                    // this converts multivalue fields to a comma-separated string
                    if(is_array($value)) $value = implode(', ', $value);
                    echo '<tr><th>' . $field . '</th><td>' . $value . '</td></tr>';
                }
                echo '</table>';
            }
         }
     }

  ?>
  ```
## Make Multiple connection in codeigniter
