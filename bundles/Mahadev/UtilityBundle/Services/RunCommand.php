<?php

namespace Mahadev\UtilityBundle\Services;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;

class RunCommand
{
    /**
     * @var KernelInterface
     */
    private KernelInterface $kernel;

    public function __construct(KernelInterface $kernel){

        $this->kernel = $kernel;
    }
    /**
     *
     $input = array(
                'command' => 'gomco:linnwork:read_file',
                'store' => $id,
                'reportType' => "_GET_RESERVED_INVENTORY_DATA_",
                '--reportFunction' => "updateReservedInventoryData",
                '--requestReport' => 1,
                '--download' => 1,
                '--updateDB' => 1,
                '--env' => 'prod',
                );
     */

    public function run($inputValues){
        $application = new Application($this->kernel);
        $application->setAutoExit(false);
        $application->getDefinition()->addOption(new InputOption('--host', null, InputOption::VALUE_OPTIONAL, 'The website name', null));

        $input = new ArrayInput($inputValues);
        // You can use NullOutput() || BufferedOutput if you don't need the output
        $output = new NullOutput();
        $application->run($input, $output);
    }

}