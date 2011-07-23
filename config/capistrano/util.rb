module Gosign
  module Util

    def Util.msg(msg, linebreak=true, level=0)
      out = (self.prefix(level) + msg).foreground(:green)
      if linebreak
        puts out
      else
        print out
      end
    end

    def Util.notice(msg, linebreak=true, level=0)
      out = (self.prefix(level) + msg).foreground(:yellow)
      if linebreak
        puts out
      else
        print out
      end
    end

    def Util.error(msg, linebreak=true, level=0)
      out = (self.prefix(level) + msg).foreground(:red)
      if linebreak
        puts out
      else
        print out
      end
    end

    def Util.prefix(level)
      return "" if level <= 0
      ("-" * level) + "> "
    end

    def Util.exec(msg, &block)
      x = block.call
      self.notice(msg, false)
      out = %x[#{x}]
      if($? != 0)
        self.error(" failed")
        self.error("Aborting...")
        exit
      end

      self.msg(" success")
      out
    end

  end
end