# This namespaces contains database related tasks
#
namespace :db do

  # Download the remote database of the selected stage
  #
  task :download do
    getDatabaseAccessCmd = 'cd ' + deploy_to + '; ls `pwd`'



    # The PHP command extracts the database credentials from the localconf.php file
    phpCmd = <<-PHP
error_reporting(E_ERROR);
define("PATH_site", getcwd() . "/htdocs/");
require "local/localconf.php";
echo "-DB-".PHP_EOL.$typo_db.PHP_EOL.$typo_db_username.PHP_EOL.$typo_db_password.PHP_EOL.$typo_db_host;
    PHP

    phpCmd.gsub!("\n", " ")

    getDatabaseAccessCmd = "cd #{deploy_to}; php -r '#{phpCmd}'"

    databaseAccessData = ""
    run getDatabaseAccessCmd do |channel, stream, data|
      databaseAccessData += "\n#{data}"
    end

    databaseAccessData = databaseAccessData.split("\n")

    # Indicates where our data begins, because there may be
    # PHP warnings before it, which cannot be disabled
    start = databaseAccessData.index('-DB-')

    db = databaseAccessData[start+1]
    dbuser = databaseAccessData[start+2]
    pwd = databaseAccessData[start+3]
    host = databaseAccessData[start+4]



    # Dump the database into a temporary file (gzipped)
    mysqldumpCmd = Gosign::Util.mysqldump(db)
    mysqlCmd = "TMPFILE=`mktemp` && #{mysqldumpCmd} -h #{host} -u #{dbuser} --skip-lock-tables -p | gzip > $TMPFILE && echo $TMPFILE"

    result = []
    run mysqlCmd do |channel, stream, data|
      channel.send_data("#{pwd}\n")
      result.push(data)
    end

    tempFile = result.last.gsub("\n", "")

    # Download the database dump
    roles[:app].servers.each do |server|
      mysqlDumpFilename = "#{Dir.pwd}/#{application}_#{stage}_#{Gosign::Util.filename_friendly(Time.now.to_s)}_mysql.dump.gz"
      retrieveFileCmd = "scp #{user}@#{server}:#{tempFile} #{mysqlDumpFilename}"
      system(retrieveFileCmd)
    end



    # Delete the temporary file on the remote server
    run "rm #{tempFile}"
  end

end

