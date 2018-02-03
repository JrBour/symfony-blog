<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180203163651 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP INDEX UNIQ_9474526CDAE07E97, ADD INDEX IDX_9474526CDAE07E97 (blog_id)');
        $this->addSql('ALTER TABLE comment DROP INDEX UNIQ_9474526CF675F31B, ADD INDEX IDX_9474526CF675F31B (author_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP INDEX IDX_9474526CDAE07E97, ADD UNIQUE INDEX UNIQ_9474526CDAE07E97 (blog_id)');
        $this->addSql('ALTER TABLE comment DROP INDEX IDX_9474526CF675F31B, ADD UNIQUE INDEX UNIQ_9474526CF675F31B (author_id)');
    }
}
