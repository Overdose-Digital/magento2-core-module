<?php

namespace Overdose\Core\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\ObjectManagerInterface;

class ClearFastly extends Command
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Clear fastly cache
     *
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager
    ) {
        parent::__construct();
        $this->objectManager = $objectManager;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('core:clear-fastly');
        $this->setDescription('Clear Fastly cache');

        parent::configure();
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $message = __('Vendor fastly doesn\'t exist.');

        if (class_exists('\Fastly\Cdn\Model\Api')) {

            $api = $this->objectManager->create(\Fastly\Cdn\Model\Api::class);
            $message = __('Fastly Cache was not cleaned successfully.');

            // Purge everything from Fastly
            $result = $api->cleanAll();

            if ($result === true) {
                $message = __('Fastly Cache has been cleaned.');
            }
        }

        $output->writeln($message);
    }
}
