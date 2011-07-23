
* Dieser Ordner dient dazu, die Konfigurationsdateien, welche sich auf den verschiedenen Servern und Entwicklungsumgebungen unterscheiden, zu kapseln, um die Konfigurations zu vereinfachen. Da diese Dateien normalerweise an bestimmten Orten liegen müssen, sind an diesen Stellen Symlinks erstellt worden, welche auf `config/local` zeigen.

* Die Dateien in diesem Ordner, mit Ausnahme von denen die in `.sample` enden, werden von Git ignoriert. So kann es nicht zu Konflikten zwischen den Server-Konfigurationen kommen.