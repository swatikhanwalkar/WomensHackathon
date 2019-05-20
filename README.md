# Digital Engagement Platform
The platform is built in Drupal 8 to support the culture of open government. The platform conceptualises how can citizens voice can be heard and they can have their say in major policy decisions

## Prerequisites
1. A http server, Mysql and PHP.
2. You can use Apache or Nginx stacks (commonly available as LAMP, LEMP etc for linux )

## How to set up
1. Clone the repository in to your local environment
2. The SQL database dump can be found inside docroot folder
3. Import the sql database into your database. You can use command line mysql commands or use a GUI based tool like workbench
4. Go to sites/default/settings.php and set your database connection settings.
5. Once that is done, open your browser and voila you have a complete running engagement platform. The database comes with some default entries to guide on workflows and capabilities of the system.
