<?php

namespace Mahadev\UtilityBundle\Command;

use Doctrine\Persistence\ManagerRegistry;
use Mahadev\UtilityBundle\Utility\TimeConsumed;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\LockableTrait;

trait CommandTrait
{

    use LockableTrait;
    use TimeConsumed;

    /** @var  ManagerRegistry */
    protected $_em;

    /** @var EntityManager */
    protected $_entityManager;

    /** @var  OutputInterface */

    protected $_output;

    /** @var  InputInterface */

    protected $_input;

    /** @var  integer */
    protected $_recordProcessed = 0;

    public function loadBasicConfig(InputInterface $input, OutputInterface $output){
        $this->_output = $output;
        $this->_input = $input;

        $processName = sprintf('%s_%s_%s', getenv('APP_NAME'), $this->getName(), getenv('WEBSITE'));

        if (!$this->lock($processName)) {
            $output->writeln('The command '.$processName.' is already running in another process.');
            if($input->hasOption('lock')){
                if($input->getOption('lock')) exit("process ".$this->getName()." locked");
            }
            else exit("process ".$this->getName()." locked");;

        }

        if($this->_em) $this->_entityManager = $this->_em->getManager();
    }

}
