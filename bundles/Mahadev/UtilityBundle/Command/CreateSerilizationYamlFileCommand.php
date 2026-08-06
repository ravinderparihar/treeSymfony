<?php

namespace Mahadev\UtilityBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PropertyInfo\PropertyAccessExtractorInterface;
use Symfony\Component\PropertyInfo\PropertyListExtractorInterface;
use Symfony\Component\Yaml\Yaml;

class CreateSerilizationYamlFileCommand extends Command
{
    /**
     * @var PropertyListExtractorInterface
     */
    private $extractor;
    /**
     * @var PropertyAccessExtractorInterface
     */
    private $accessExtractor;


    protected $commandName = 'gomco:utility:create_serilization_yaml';


    public function __construct(PropertyListExtractorInterface $extractor, PropertyAccessExtractorInterface $accessExtractor)
    {
        parent::__construct($this->commandName);
        $this->extractor = $extractor;
        $this->accessExtractor = $accessExtractor;
    }


    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->setName($this->commandName)
            ->setDescription('Update amazon items weight')
            ->addArgument('domain', InputArgument::OPTIONAL, 'domain', 'com')
            ->addArgument('ignoreLastUpdate', InputArgument::OPTIONAL, 'ignoreLastUpdate', false)
            ->addOption('class', null, InputOption::VALUE_REQUIRED, '', false )
            ->addOption('target', null, InputOption::VALUE_REQUIRED, '', false )
            ->addOption('lock', null, InputOption::VALUE_OPTIONAL, '', true )

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $class = $input->getOption('class');
        foreach ($this->extractor->getProperties($class) as $property){
            if($this->accessExtractor->isReadable($class, $property)){
                $propertyData[$property]['groups'] = ['read', 'export', 'write'];
            }
        }
        $data[$class]['attributes'] = $propertyData;
        $yaml = Yaml::dump($data, 10);

        $ref = new \ReflectionClass($class);
        $name = strtolower($ref->getShortName()).".yaml";
        file_put_contents(getenv('ROOT_DIR').$input->getOption('target').$name, $yaml);
        return 1;
    }
}