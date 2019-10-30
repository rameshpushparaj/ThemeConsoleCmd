# ThemeConsoleCmd

A Custom Console Command to Switch/Set Theme via Command Line in Magento 2.

# How to use

Get help for a specific command with

    php bin/magento help mydons:listthemes
    
    php bin/magento help mydons:settheme

   ## List Themes Command

   A custom command to retrieve the list of available themes in Magento2 Site
  
    php bin/magento mydons:listthemes --exclude-admintheme
   
   ## Set Theme Command
   
   A Custom Command to set the desired theme in Frontend via CLI
   
    php bin/magento mydons:settheme theme_code
    
   ### Example
   
    php bin/magento mydons:settheme Magento/blank
