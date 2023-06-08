<?php

/*
 * This file is part of Monsieur Biz' Menu plugin for Sylius.
 *
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230608124520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Run migration to change messenger_messages dates only if PHP version is >= 8.1.0, cf https://github.com/symfony/doctrine-messenger/commit/d3ded97b5a5303bd160b7c626341e7507fbb5854';
    }

    public function up(Schema $schema): void
    {
        if (version_compare(\PHP_VERSION, '8.1.0', '<')) {
            return;
        }

        // With PHP 8.1, the symfony/doctrine-messenger bundle is installed in version 6.3, and it updates the dates to use DATETIME_IMMUTABLE instead of DATETIME_MUTABLE type
        $this->addSql('ALTER TABLE messenger_messages CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE available_at available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        if (version_compare(\PHP_VERSION, '8.1.0', '<')) {
            return;
        }

        $this->addSql('ALTER TABLE messenger_messages CHANGE created_at created_at DATETIME NOT NULL, CHANGE available_at available_at DATETIME NOT NULL, CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }
}
