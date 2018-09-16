<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180916185740 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F2B6945EC');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F35F83FFC');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F6061F7CF');
        $this->addSql('DROP INDEX IDX_B6BD307F35F83FFC ON message');
        $this->addSql('DROP INDEX IDX_B6BD307F6061F7CF ON message');
        $this->addSql('DROP INDEX IDX_B6BD307F2B6945EC ON message');
        $this->addSql('ALTER TABLE message ADD room_id INT DEFAULT NULL, ADD sender_id INT DEFAULT NULL, ADD recipient_id INT DEFAULT NULL, DROP room_id_id, DROP sender_id_id, DROP recipient_id_id');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F54177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FE92F8F78 FOREIGN KEY (recipient_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F54177093 ON message (room_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FF624B39D ON message (sender_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FE92F8F78 ON message (recipient_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F54177093');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FE92F8F78');
        $this->addSql('DROP INDEX IDX_B6BD307F54177093 ON message');
        $this->addSql('DROP INDEX IDX_B6BD307FF624B39D ON message');
        $this->addSql('DROP INDEX IDX_B6BD307FE92F8F78 ON message');
        $this->addSql('ALTER TABLE message ADD room_id_id INT DEFAULT NULL, ADD sender_id_id INT DEFAULT NULL, ADD recipient_id_id INT DEFAULT NULL, DROP room_id, DROP sender_id, DROP recipient_id');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F2B6945EC FOREIGN KEY (recipient_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F35F83FFC FOREIGN KEY (room_id_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F6061F7CF FOREIGN KEY (sender_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F35F83FFC ON message (room_id_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F6061F7CF ON message (sender_id_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F2B6945EC ON message (recipient_id_id)');
    }
}
