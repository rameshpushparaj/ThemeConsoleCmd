<?php
namespace Mydons\ThemeConsoleCmd\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Magento\Framework\App\Cache;
use Magento\Theme\Model\Config as ThemeConfig;
use Magento\Store\Model\Store;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Theme\Model\Theme\Registration as ThemeRegistration;
use Magento\Theme\Model\ThemeFactory as ThemeFactory;
use Magento\Framework\Indexer\IndexerRegistry as IndexerRegistry;
use Magento\Theme\Model\Data\Design\Config as DesignConfig;


class SetTheme extends Command
{
	 
     protected $themeFactory;
	 
     protected $themeConfig;
	 
     protected $themeRegistration;
	 	 
     private $cache;
     
     protected $indexerRegistry;

     public function __construct(
	 ThemeFactory $themeFactory,
	 ThemeConfig $themeConfig,
	 ThemeRegistration $themeRegistration, 
	 Cache $cache, 
	 IndexerRegistry $indexerRegistry
     ) {
         $this->themeFactory = $themeFactory;
	 $this->themeConfig = $themeConfig;
	 $this->themeRegistration = $themeRegistration;
         $this->cache = $cache;	
         $this->indexerRegistry = $indexerRegistry;	 
         return parent::__construct();
     }

    protected function configure()
    {
        $this->setName("mydons:settheme");
        $this->setDescription("A Custom Command to set the desired theme in Frontend via CLI ");
	$this->addArgument(
            'theme_code',
            InputArgument::IS_ARRAY | InputArgument::REQUIRED,
            'theme code. Theme Code Should be specified'
            . ' For example, Magento/blank'
        );
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {		
	$themeCode = $input->getArgument('theme_code');
	$theme = $this->themeFactory->create()->load($themeCode, 'code');
        if ($theme->getId()) {
            $this->themeConfig->assignToStore(
                $theme,
                [Store::DEFAULT_STORE_ID],
                ScopeConfigInterface::SCOPE_TYPE_DEFAULT
            );
            $this->cache->clean();
            $this->indexerRegistry->get(DesignConfig::DESIGN_CONFIG_GRID_INDEXER_ID)->reindexAll();
		
	    $output->writeln("<info>Theme " .$theme->getThemeTitle()." is Enabled</info>");
            $output->writeln("<info>Cache Cleared Successfully</info>");
            $output->writeln("<info>Design Grid Reindexed Successfully</info>");
            $output->writeln("<info>Please run setup:static-content:deploy -f command for a newly installed theme</info>");
        }   
	else {
		$output->writeln("Unknown Theme or Package Provided");
	}		
    }
} 
