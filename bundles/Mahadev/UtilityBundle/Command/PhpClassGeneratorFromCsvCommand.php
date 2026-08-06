<?php

namespace Mahadev\UtilityBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Mahadev\UtilityBundle\Services\GenerateGetterClass;
use Port\Csv\CsvReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class PhpClassGeneratorFromCsvCommand extends Command
{
    use CommandTrait;

    /**
     * @var GenerateGetterClass
     */
    private $generator;


    protected $commandName = 'gomco:utility:generate_class';

    /** @var string */
    protected $type;

    /** @var string */
    protected $source;

    /** @var string */
    protected $target;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->_entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->setName($this->commandName)
            ->setDescription('Update amazon items weight')
            ->addOption('source', null, InputOption::VALUE_REQUIRED, '', false )
            ->addOption('target', null, InputOption::VALUE_OPTIONAL, '', false )
            ->addOption('type', null, InputOption::VALUE_OPTIONAL, '', 'txt' )
            ->addOption('method', null, InputOption::VALUE_OPTIONAL, '', false )
            ->addOption('namespace', null, InputOption::VALUE_OPTIONAL, '', 'Foo' )
            ->addOption('generatorType', null, InputOption::VALUE_OPTIONAL, '', 'GenerateGetterClass' )
            ->addOption('addProperties', null, InputOption::VALUE_OPTIONAL, '', false )

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->loadBasicConfig($input, $output);
        $this->type = $input->getOption('type');
        $this->source = $input->getOption('source');
        $this->target = $input->getOption('target') ? $input->getOption('target'): $this->source;

        $generatorType = "Mahadev\UtilityBundle\Services\\".$input->getOption('generatorType');
        $this->generator = new $generatorType();

        $className = sprintf('generate%sClasses', ucfirst($this->type));
        $finder = new Finder();
        $finder->depth('< 1');
        $finder->files()->name(sprintf('*.%s', $this->type))->in(sprintf('%s%s', getenv('ROOT_DIR'), $this->source));

        foreach ($finder as $file) {
            try{
                $this->$className($file);
            }
            catch (\Exception $exception){
                $this->_output->writeln($exception->getMessage());
            }
        }

        return 1;

    }

    public function generateCsvClasses(SplFileInfo $file){
        $csvFile = new \SplFileObject($file->getRealPath());
        $csvReader = new CsvReader($csvFile);
        $csvReader->setStrict(false);
        $headers = $csvReader->getRow(0);
        $method = null;
        if($this->_input->getOption('method')){
            $method = $csvReader->getRow(1);
        }
//        print_r($headers);
//        print_r($method);
        $this->saveClass($file, $headers, $method);
    }

    public function generateTxtClasses(SplFileInfo $file){
        $csvFile = new \SplFileObject($file->getRealPath());
        $csvReader = new CsvReader($csvFile, "\t");
        $csvReader->setStrict(false);
        $headers = $csvReader->getRow(0);
        $this->saveClass($file, $headers);
    }

    public function generateJsonClasses(SplFileInfo $file){
        $content = $file->getContents();
        $headers = json_decode($content, true);
        $this->saveClass($file, array_keys($headers));
    }

    public function generateXmlClasses(SplFileInfo $file){
        $content = $file->getContents();
        $headers = json_decode(json_encode(simplexml_load_string($content)), true);
        $this->saveClass($file, array_keys($headers));
    }

    public function saveClass(SplFileInfo $file, $data, $method = null){
        $reportName = $file->getBasename(sprintf('.%s', $this->type));
        $className = $this->generator->stringToCamelCase($reportName);
        $phpFile = $this->generator->generate($data, $className, $this->_input->getOption('namespace'), $method, addProperties: $this->_input->getOption('addProperties'));
        $classFile = getenv('ROOT_DIR').$this->target.DIRECTORY_SEPARATOR.$className.'.php';
        file_put_contents($classFile, $phpFile);
        unlink($file->getRealPath());
    }
}