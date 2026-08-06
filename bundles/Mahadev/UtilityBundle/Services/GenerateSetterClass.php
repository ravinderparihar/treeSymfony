<?php


namespace Mahadev\UtilityBundle\Services;


use Nette\PhpGenerator\PhpFile;

class GenerateSetterClass extends GenerateGetterClass
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

        $constructor = $class->addMethod('__construct');

        $class->addMethod('getItemData')
            ->setBody('return $this->_item;');

        foreach ($headers as $header){
            $name = 'set'.$this->stringToCamelCase($header);
            $method = $class->addMethod($name)
                ->setBody(sprintf('$this->_item[\'%s\'] = $value;', $header));
            $method->addParameter('value');
        }
        return $phpFile;
    }
}