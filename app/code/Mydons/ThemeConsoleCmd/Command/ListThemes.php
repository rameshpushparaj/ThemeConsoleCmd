<?php
namespace Mydons\ThemeConsoleCmd\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Theme\Model\Theme\Registration as ThemeRegistration;
use Magento\Theme\Model\ResourceModel\Theme\Collection as ThemeCollectionFactory;

class ListThemes extends Command
{
	
	const EXCLUDE_ADMINTHEME_OPTION = 'exclude-admintheme';
     
	 protected $themeCollectionFactory;
	 
	 protected $themeRegistration;

     public function __construct(
	     ThemeCollectionFactory $themeCollectionFactory,
		 ThemeRegistration $themeRegistration
	 )
     {
         $this->themeCollectionFactory = $themeCollectionFactory;
		 $this->themeRegistration = $themeRegistration;
         return parent::__construct();
     }	
	
    protected function configure()
    {
        $this->setName("mydons:listthemes");
        $this->setDescription("A custom command to retrieve the list of available themes in Magento2 admin");
		$this->addOption(
            self::EXCLUDE_ADMINTHEME_OPTION,
            'exclude-admintheme',
            InputOption::VALUE_NONE,
            'Excludes Admin Theme from the theme listing '
        );
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$this->themeRegistration->register();
        $output->writeln("Available Themes"); 
		// Get the Input option from the command line
		$excludeAdminTheme = $input->getOption(self::EXCLUDE_ADMINTHEME_OPTION);

		if($excludeAdminTheme){
   		   // Apply the Frontend Area filter
		   $themeItems = $this->themeCollectionFactory->addAreaFilter('frontend');
		}
	    else {
		   $themeItems = $this->themeCollectionFactory;
		}
		
		foreach($themeItems as $theme) {
		  // Console output with theme details
		  $output->writeln(print_r($theme->getData(),true));
		}
			
    }
} 