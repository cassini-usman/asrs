class Gosign
  module Structure

    # This method encapsulates the commands needed to set up the project structure
    # according to the instructions located in
    # https://github.com/gosign-media/DefaultProjekt/blob/master/README.md
    # If changes are needed, you should also make these changes in the README file.
    def self.setup(path, local=true)

      Gosign::Util.exec("Cloning default project structure: ", local) do
        <<-eos
          git clone -q git://github.com/gosign-media/DefaultProjektStruktur.git #{path}DefaultProjektStruktur &&
          rm -Rf #{path}DefaultProjektStruktur/.git &&
          rm -Rf #{path}DefaultProjektStruktur/htdocs &&
          find #{path}DefaultProjektStruktur -mindepth 1 -maxdepth 1 -prune -exec mv {} #{path} \\; &&
          rm -Rf #{path}DefaultProjektStruktur
        eos
      end

      Gosign::Util.exec("Cloning data folder structure: ", local) do
        <<-eos
          rm -Rf #{path}data/ &&
          git clone -q git@github.com:gosign-media/DataDummy.git #{path}data &&
          rm -Rf #{path}data/.git
        eos
      end

      Gosign::Util.exec("Cleaning up: ", local) do
        <<-eos
          rm #{path}README.md &&
          rm #{path}data/README.md &&
          rm #{path}.gitignore &&
          rm #{path}data/.gitignore
        eos
      end

    end

    # Checks if the correct project structure has been set up already (e.g. through
    # the "local:setup" task).
    def self.correct?
      Dir.exists?("../data") and Dir.exists?("../htdocs") and
      File.symlink?("../src") and Dir.exists?("../local")
    end

  end
end