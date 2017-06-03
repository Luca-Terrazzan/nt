-- TODO: add indexes
DROP SCHEMA IF EXISTS assignment_terrazzan;
CREATE SCHEMA assignment_terrazzan;
USE assignment_terrazzan;
CREATE TABLE node_tree(
    idNode SMALLINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    level SMALLINT UNSIGNED NOT NULL,
    iLeft SMALLINT UNSIGNED NOT NULL UNIQUE KEY,
    iRight SMALLINT UNSIGNED NOT NULL UNIQUE KEY
) ENGINE=InnoDb;

CREATE TABLE node_tree_names(
    idNode SMALLINT UNSIGNED NOT NULL,
    language ENUM('italian', 'english'),
    nodeName VARCHAR(255) NOT NULL,
    FOREIGN KEY (idNode) REFERENCES node_tree(idNode)
) ENGINE=InnoDb;
