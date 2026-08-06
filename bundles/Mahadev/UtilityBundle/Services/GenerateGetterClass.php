<?php


namespace Mahadev\UtilityBundle\Services;


use Nette\PhpGenerator\PhpFile;

class GenerateGetterClass
{

    public function generate($headers, $className, $nameSpace = 'Foo', $methodNames = null, $addProperties = false): PhpFile
    {
        $phpFile = new PhpFile();
//        $file->addComment('This file is auto-generated.');
//        $file->setStrictTypes();

        $namespace = $phpFile->addNamespace($nameSpace);

        $class = $namespace->addClass($className);
        $class->addProperty('_item', [])
            ->setVisibility('protected');

        $constructor = $class->addMethod('__construct')
            ->setBody('$this->_item = $item;');

        $constructor->addParameter('item')
            ->setType('array'); // scalar type hint
//                ->setNullable(); // nullable type hint

        $method = $class->addMethod('getPropertyValue')
            ->setBody(sprintf('
            if(isset($this->_item[$property])) $data = $this->_item[$property];
        else $data = $defaultValue;

        return $className && $data ? new $className($data): $data;
        '));
        $method->addParameter('property');
        $method->addParameter('defaultValue', null);
        $method->addParameter('className', null);

        if($addProperties){
            foreach ($headers as $index => $header){
                $name = $this->stringToCamelCase($methodNames[$index] ?? $header, capitalizeFirstCharacter: false);
                $class->addProperty($name, null)->setVisibility('protected');
                echo '<field name="'.$name.'" nullable="true" type="string" length="100"/>'.PHP_EOL;

            }
        }

        foreach ($headers as $index => $header){
            $name = 'get'.$this->stringToCamelCase($methodNames[$index] ?? $header);
            $setName = 'set'.$this->stringToCamelCase($methodNames[$index] ?? $header);
//            echo '$vat->'.$setName.'($report->'.$name.'())'.PHP_EOL;
            $method = $class->addMethod($name)
                ->setBody(sprintf('return $this->getPropertyValue("%s");', $header));
        }


        return $phpFile;
    }

    static function stringToCamelCase($string, $delimiters = ['_', '-', ' ', '/'], $capitalizeFirstCharacter = true)
    {
        if(!is_array($delimiters)) $delimiters = [$delimiters];
        $string = strtolower($string);
        foreach ($delimiters as $delimiter){
            $string = str_replace($delimiter, '', ucwords($string, $delimiter));
        }

        if (!$capitalizeFirstCharacter) {
            $string = lcfirst($string);
        }

        return $string;
    }
}