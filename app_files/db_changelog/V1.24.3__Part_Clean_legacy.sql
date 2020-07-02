ALTER TABLE `Part Dimension` DROP `Part Made in Production`;
ALTER TABLE `Part Dimension`
    DROP `Part Number Components`,
    DROP `Part Number Production Tasks`,
    DROP `Part Number Production Links`;
ALTER TABLE `Production Part Dimension` ADD `Production Part Components Number` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Production Part Supplier Part Key`, ADD `Production Part Tasks Number` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Production Part Components Number`, ADD `Production Part Links Number` SMALLINT UNSIGNED NOT NULL DEFAULT '0' AFTER `Production Part Tasks Number`;
