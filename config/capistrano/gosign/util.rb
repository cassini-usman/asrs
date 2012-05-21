class Gosign
  module Util

    # Creates a mysqldump command that does not dump Typo3 temporary tables
    # See https://github.com/gosign-media/t3-scripts for more information
    #
    def self.mysqldump(dbName)
      blacklist = [
        "cache_extensions",
        "cache_hash",
        "cache_imagesizes",
        "cache_md5params",
        "cache_pages",
        "cache_pagesection",
        "cache_sys_dmail_stat",
        "cache_typo3temp_log",
        "sys_log",
        "tx_realurl_chashcache",
        "tx_realurl_errorlog",
        "tx_realurl_pathcache",
        "tx_realurl_urldecodecache",
        "tx_realurl_urlencodecache"
      ]

      cmd = "mysqldump #{dbName} "
      blacklist.each { |table| cmd += "--ignore-table=#{dbName}.#{table} " }

      cmd
    end


    # This method will convert any string into a string that can be used for a filename
    def self.filename_friendly(string)
      string.gsub(/[^\w\s_-]+/, '')
            .gsub(/(^|\b\s)\s+($|\s?\b)/, '\\1\\2')
            .gsub(/\s+/, '_')
    end


    def self.msg(msg, linebreak=true, level=0)
      out = (self.prefix(level) + msg).foreground(:green)
      if linebreak
        puts out
      else
        print out
      end
    end


    def self.notice(msg, linebreak=true, level=0)
      out = (self.prefix(level) + msg).foreground(:yellow)
      if linebreak
        puts out
      else
        print out
      end
    end


    def self.error(msg, linebreak=true, level=0)
      out = (self.prefix(level) + msg).foreground(:red)
      if linebreak
        puts out
      else
        print out
      end
    end


    def self.prefix(level)
      return "" if level <= 0
      ("-" * level) + "> "
    end


    # Execute a shell command, either locally or remotely according to the
    # "local" flag. When executing locally, a check whether or not a command
    # succeeded is made, this check is done automatically by capistrano when
    # executing remotely.
    def self.exec(msg, local=true, &block)
      x = block.call
      Gosign::Util.notice(msg, false)

      if local
        out = %x[#{x}]

        if($? != 0)
          Gosign::Util.error(" failed")
          Gosign::Util.error("Aborting...")
          exit
        end

        Gosign::Util.msg(" success")

        out
      else
        Gosign.cap.run(x)
      end
    end


  end
end
