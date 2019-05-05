# Solr - How to create multiconnection in codeigniter using Solarium

Make multiple solr connection with same/different core in codeigniter using solarium.

## Index

1. Setup Apache solr
2. Setup Codeigniter framework
3. Setup Solarium
4. Make Multiple connection in codeigniter

## 1. Setup Apache solr

1. Download solr from [this link](http://mirrors.estointernet.in/apache/lucene/solr/8.0.0/solr-8.0.0.zip
).
2. Unzip the package.
3. Upload the solr folders and files to your server.
4. Set JRE path into solr/bin/solr.cmd
5. Solr commands for start, stop and create core for here,

**For Windows:**

> **Check solr status**

```
solr status
```

> **Start solr connection**

```
solr start (Without port. It default set 8983)

solr start -p 8088 (With port 8088)
```

> **Stop solr connection**

```
solr stop -p 8088 (With port 8088)

solr stop -all (All connection)
```

> **Create and manage core**

```
> Create Core:

solr create -c test_core

> Delete Core:

solr delete -c test_core

> Import Document into the core:

First put the file into solr folder. like here.

cd solr-8.0.0\example\example1.xml
cd solr-8.0.0\example\example2.xml

java -Dc=directors -jar post.jar *.xml // It upload both example1.xml and example2.xml

java -Dc=directors -jar post.jar example1.xml // It upload both example1.xml only
```

**For Ubentu:**

> **Check solr status**

```
solr sudo service solr status
```

> **Start solr connection**

```
sudo service solr start
```

> **Stop solr connection**

```
sudo service solr stop
```

> **Create and manage core**

```
> Create Core:

sudo su - solr -c "/opt/solr/bin/solr create -c test_core -n data_driven_schema_configs"

> Delete Core:

sudo su - solr -c "/opt/solr/bin/solr delete -c test_core -n data_driven_schema_configs"
```

## 2. Setup Codeigniter framework

1. Download codeigniter from [this link](https://codeload.github.com/bcit-ci/CodeIgniter/zip/3.1.10).
2. Unzip the package.
3. Upload the CodeIgniter folders and files to your server. Normally the index.php file will be at your root.
4. Open the application/config/config.php file with a text editor and set your base URL. If you intend to use encryption or sessions, set your encryption key.
5. If you intend to use a database, open the application/config/database.php file with a text editor and set your database settings.
6. run your application via server_url (Example: www.example.com or localhost/project_name).

## 3. Setup Solarium

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

  First load solr client in __construct()
  ```
$this->config->load('solarium');
$this->endpoint1 = new Solarium\Client($this->config->item('endpoint1')); // This is used to make connection with solr using 'endpoint1' config variable.
  ```
  
  And add below function in your controller
  
  ```
$query = $this->endpoint1->createSelect();
$result = $this->endpoint1->select($query);
  ```
  
  The full controller page here,
  
  ```
  <?php defined('BASEPATH') OR exit('No direct script access allowed');
  
     class Test extends CI_Controller {
        public function __construct() {
            parent::__construct();
            $this->config->load('solarium');
            $this->endpoint1 = new Solarium\Client($this->config->item('endpoint1')); // This is used to make connection with solr using 'endpoint1' config variable.
         }

         public function test() {
            $query = $this->endpoint1->createSelect();
            $result = $this->endpoint1->select($query);

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
5. Run the controller and check the outputs. (Example: server_path/Test/test).

## 4. Make multiple solr connection in codeigniter

To implement multiple solr connection is very easy and simple. Just add Another set of connection endpoints into the config file like solarium.php.

For Example,
```
$config['endpoint2'] = array( // endpoint2 is a secound connection variable.
    'endpoint' => array(
      'localhost' => array(
          'host' => 'host_name', // localhost or www.host.com
          'port' => 'port_value', //Default 8983 or 8080 etc,
          'path' => '/solr/',
          'core' => 'solr_core_name' // core1 or movie1 or etc,
      )
    )
);
```
Now your config file is,
```
<?php
    $config['endpoint1'] = array( // endpoint1 is a FIRST connection.
        'endpoint' => array(
            'localhost' => array(
              'host' => 'host_name', // localhost or www.host.com
              'port' => 'port_value', //Default 8983 or 8080 etc,
              'path' => '/solr/',
              'core' => 'solr_core_name' // core1 or movie1 or etc,
            )
        )
    );
    $config['endpoint2'] = array( // endpoint2 is a secound connection.
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

We use this second connection in controller page using following steps,

1. Add New connection endpoints into the __construct()
```
$this->endpoint2 = new Solarium\Client($this->config->item('endpoint1')); // This is used to make connection with solr using 'endpoint2' config variable.
```
2. Add the below function into the controller page,
  ```
$query = $this->endpoint2->createSelect();
$result = $this->endpoint2->select($query);
  ```
3. Full controller page is here,
  ```
  <?php defined('BASEPATH') OR exit('No direct script access allowed');
  
     class Test extends CI_Controller {
        public function __construct() {
            parent::__construct();
            $this->config->load('solarium');
            $this->endpoint1 = new Solarium\Client($this->config->item('endpoint1')); // This is used to make connection with solr using 'endpoint1' config variable.
            $this->endpoint2 = new Solarium\Client($this->config->item('endpoint2')); // This is used to make connection with solr using 'endpoint1' config variable.
         }

         public function test_endpoint1() {
            $query = $this->endpoint1->createSelect();
            $result = $this->endpoint1->select($query);

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
         
         public function test_endpoint2() {
            $query = $this->endpoint2->createSelect();
            $result = $this->endpoint2->select($query);

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
4. Run the controller and check the outputs. (Example: server_path/Test/test_endpoint1/ and server_path/Test/test_endpoint2/)

