<?php

    use Doctrine\ORM\Tools\Setup;
    use Doctrine\ORM\EntityManager;

    require_once "vendor/autoload.php";
    require_once "models/noark5/v31/Fonds.php";
    require_once 'handler/ArkivstrukturDBImporter.php';

    $isDevMode = true;
    $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/models/noark5/v31/"
    ), $isDevMode);

    $conn = array(
        'driver' => 'pdo_mysql',
        'user' => '****',
        'host' => '127.0.0.1',
        'password' => '****',
        'charset' => 'utf8',
        'driverOptions' => array(1002=>'SET NAMES utf8'),
        'dbname' => '****'
    );

    $entityManager = EntityManager::create($conn, $config);

    // start
    $options = getopt("d:t:s:v:");

    if (isset($options["d"]) == false) {
        print "Path to directory to test. Example usage -d/home/user/noark5 " . PHP_EOL;
        exit;
    }

    if (isset($options["t"]) == false) {
        print "Type test not specified. Example usage -tnoark5" . PHP_EOL;
        exit;
    }

    if (isset($options["v"]) == false) {
        print "No version specified. Example usage -v31" . PHP_EOL;
        exit;
    }

    if (isset($options["s"]) == false) {
        print "Delete existing database (Y=yes, n=no). Example usage -sy | -sn" . PHP_EOL;
        exit;
    }

    $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
    $classes = $entityManager->getMetadataFactory()->getAllMetadata();

    $deleteSchema = $options["s"];
    if (strcasecmp($deleteSchema, "y") == 0) {
        $schemaTool->dropSchema($classes);
    }
    $schemaTool->createSchema($classes);

    $directory = $options["d"];
    $arkivstrukturFilename = "arkivstruktur.xml";
    $onlyParse = true;
    $akivstrukturParser = new ArkivstrukturDBImporter($directory, $entityManager, $onlyParse);
    $parser = xml_parser_create('UTF-8');

    xml_set_object($parser, $akivstrukturParser);
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, false);


    xml_set_element_handler($parser, "startElement", "endElement");
    xml_set_character_data_handler($parser, "cdata");

    $arkivstrukturFile = fopen(join(DIRECTORY_SEPARATOR, array(
        $directory,
        $arkivstrukturFilename
    )), 'r');



    while ($data = fread($arkivstrukturFile, 4096)) {
        xml_parse($parser, $data, feof($arkivstrukturFile));
        flush();
    }
    fclose($arkivstrukturFile);
    xml_parser_free($parser);

?>
