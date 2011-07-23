# Default Projekt

## Anpassen eines alten Projektes auf die neue Struktur

### Dinge die es zu beachten gilt

* `templavoila/` muss im `typo3conf/ext/`-Ordner vorhanden sein, da es nicht in den Sources mitgeliefert wird.

## Aufsetzen eines neuen Projektes

Um ein neues Projekt auf Basis des Default-Projektes aufzusetzen, müssen folgende Schritte durchgeführt werden:

1. Klonen der [DefaultProjektStruktur](https://github.com/gosign-media/DefaultProjektStruktur).

2. Löschen des `.git/`-Ordners des DefaultProjektStruktur-Repositories (da es nur zum Aufsetzen dient, und keiner weiteren Änderungen bedarf).

3. Clonen des [Default-Projektes](https://github.com/gosign-media/DefaultProjekt) in den Ordner `htdocs/` (zuvor muss die `index.html` in diesem Ordner gelöscht werden, damit man dorthin clonen kann) der DefaultProjektStruktur.  **Hier sollte der `.git`-Ordner NICHT gelöscht werden, allerdings sollte die README.md gelöscht bzw. geleert werden!**

4. Clonen des [DataDummies](https://github.com/gosign-media/DataDummy) in den `data/`-Unterordner der DefaultProjekteStruktur (auch hier muss vorher die `data/index.html` gelöscht werden).

5. Löschen des `data/.git` Ordners! Die User-Daten stehen nicht unter Versionskontrolle.

6. Setzen des korrekten Symlinks für `src/`. Dieser muss auf einen Klon des [Typo3Source](https://github.com/gosign-media/Typo3Source) Repositories zeigen.

Diese Punkte werden vom Capistrano task `local:setup` für lokale Projekte bzw. von `deploy:setup` automatisch ausgeführt und sind hier nur zur Referenz noch einmal aufgeführt. Änderungen die hier vorgenommen werden sollten entsprechend auch in der Capistrano Konfiguration unter `config/` geschehen.

---

**Folgende Punkte müssen von Hand ausgeführt werden:**

7. Ändern des Remotes des geklontes DefaultProjektes auf das neue Projekt-Remote (damit ist das tatsächlich Projekt-Repository gemeint). **Natürlich nur beim ersten Erstellen des Projektes, kann sonst übersprungen werden.**:

    `git remote set-url origin <projekt-remote-url>`

8. Einspielen der Default-Datenbank aus `htdocs/sql/` (bzw. übertragen der Datenbank vom Entwicklungsserver).

9. Erstellen der `localconf.php` (anhand einer Kopie der `localconf.php.sample`) im Ordner `htdocs/typo3conf` und setzen der Datenbank-Einstellungen in dieser Datei.


## Struktur

* `data` : Enthält aller User- und Content-Daten der Webseite. Diesen Daten dürfen unter keinen Umständen in das VCS gelangen, deswegen sind sie in der .gitignore komplett blacklisted. Die Grundstruktur kann dem `DataDummy` Repository of GitHub entnommen werden.

* `htdocs` : Enthält den Projektspezifischen Source-Code (insbesondere Extensions) sowieso alle Templates und Grafiken die für das Projekt wichtig sind. Die Grundstruktur für diesen Ordner ist bereits in diesem Repository enthalten.

* `htdocs/sql` : Enthält eine SQL-Datei welche zum aufsetzen eines neuen Projektes genutzt wird.

* `htdocs/config/local/` : Enth�lt alle Dateien welche lokale Konfigurationen enthalten. Mehr Informationen dazu in der README-Datei des Ordners.

* `src` : Enthält den Typo3-Sourcecode (`t3lib/` und `typo3/`) und somit auch alle globalen Extensions (e.g. `go_pibase`, `dam`, etc...) welche in `typo3/ext/` liegen. Der aktuelle Inhalt für diesen Ordner kann dem Repository `Typo3Source` entnommen werden.


## Wie update ich eine Extension?

Extensions aus dem TER (Typo3 Extensions Repository) müssen *zwingend* über den Extensions-Manager geupdated werden, besonders dann wenn es sich um globale Extensions handelt (d.h. solche die in typo3/ext/ und nicht in typo3conf/ext/ liegen). Dies liegt daran, dass in den Modul-Konfigurationsdateien Hardcoded-Pfade drin sind, welche
vom Extensions-Manager an den jeweiligen Ablageort angepasst werden. Also wenn ihr globale Extensions updated (was generell nur im Default-Projekt selbst passieren darf, nicht aus irgendwelchen anderen Projekten heraus!), bitte darauf achten dass ihr dies über den Extensions Manager tut und beim installieren bei "...to location:" Global auswählt.
