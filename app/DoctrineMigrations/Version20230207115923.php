<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207115923 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dtb_order DROP FOREIGN KEY FK_1D66D80762BB97E8');
        $this->addSql('ALTER TABLE dtb_order DROP FOREIGN KEY FK_1D66D807CF52334D');
        $this->addSql('ALTER TABLE dtb_customer DROP FOREIGN KEY FK_8298BBE3CF6B0AA7');
        $this->addSql('DROP TABLE dtb_call_list_group_member');
        $this->addSql('DROP TABLE dtb_product_icon_image');
        $this->addSql('DROP TABLE dtb_shipping_group');
        $this->addSql('DROP TABLE dtb_voice');
        $this->addSql('DROP TABLE mtb_calllist_status');
        $this->addSql('DROP TABLE mtb_delivery_type');
        $this->addSql('DROP TABLE mtb_order_time_zone');
        $this->addSql('DROP INDEX IDX_8298BBE3CF6B0AA7 ON dtb_customer');
        $this->addSql('ALTER TABLE dtb_customer DROP order_time_zone_id, DROP no_consecutive_order_count, DROP delivery_preferred_time, DROP call_list_note');
        $this->addSql('DROP INDEX IDX_1D66D807CF52334D ON dtb_order');
        $this->addSql('DROP INDEX IDX_1D66D80762BB97E8 ON dtb_order');
        $this->addSql('ALTER TABLE dtb_order DROP shipping_group_id, DROP delivery_type_id');
        $this->addSql('ALTER TABLE dtb_product_extension_item ADD gender_id INT DEFAULT NULL, ADD reason_withdrawal VARCHAR(255) DEFAULT NULL, ADD ec_site_linked_classification_id INT DEFAULT NULL, ADD web_order_permission_classification_id INT DEFAULT NULL, ADD ordering_time INT DEFAULT NULL, ADD deposit_box VARCHAR(255) DEFAULT NULL, ADD delivery_good_group INT DEFAULT NULL, ADD classification_shipping_cost_calculation INT DEFAULT NULL, ADD filling_control_table_output_classification INT DEFAULT NULL, ADD repack_classification INT DEFAULT NULL, ADD processed_product_category_id INT DEFAULT NULL, ADD defrosting_method_id INT DEFAULT NULL, ADD impoverished_area_id INT DEFAULT NULL, ADD pref_id INT DEFAULT NULL, ADD track_no INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE dtb_call_list_group_member (call_list_group_id INT UNSIGNED NOT NULL, member_id INT UNSIGNED NOT NULL, discriminator_type VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, INDEX IDX_9743C5BAF96F27B4 (call_list_group_id), INDEX IDX_9743C5BA7597D3FE (member_id), PRIMARY KEY(call_list_group_id, member_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE dtb_product_icon_image (id INT UNSIGNED AUTO_INCREMENT NOT NULL, product_icon_id INT UNSIGNED DEFAULT NULL, file_name VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, sort_no SMALLINT UNSIGNED NOT NULL, create_date DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz)\', discriminator_type VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, INDEX IDX_71D8CE38560CC82 (product_icon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE dtb_shipping_group (id INT UNSIGNED AUTO_INCREMENT NOT NULL, discriminator_type VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, status INT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetimetz)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE dtb_voice (id INT UNSIGNED AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, customer_initials VARCHAR(5) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, content VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, image_path VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, discriminator_type VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE mtb_calllist_status (id SMALLINT UNSIGNED NOT NULL, name VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, sort_no SMALLINT UNSIGNED NOT NULL, discriminator_type VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE mtb_delivery_type (id SMALLINT UNSIGNED NOT NULL, name VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, sort_no SMALLINT UNSIGNED NOT NULL, discriminator_type VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE mtb_order_time_zone (id SMALLINT UNSIGNED NOT NULL, name VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, sort_no SMALLINT UNSIGNED NOT NULL, discriminator_type VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE dtb_call_list_group_member ADD CONSTRAINT FK_9743C5BA7597D3FE FOREIGN KEY (member_id) REFERENCES dtb_member (id)');
        $this->addSql('ALTER TABLE dtb_call_list_group_member ADD CONSTRAINT FK_9743C5BAF96F27B4 FOREIGN KEY (call_list_group_id) REFERENCES dtb_call_list_group (id)');
        $this->addSql('ALTER TABLE dtb_product_icon_image ADD CONSTRAINT FK_71D8CE38560CC82 FOREIGN KEY (product_icon_id) REFERENCES dtb_product_icon (id)');
        $this->addSql('ALTER TABLE dtb_customer ADD order_time_zone_id SMALLINT UNSIGNED DEFAULT NULL, ADD no_consecutive_order_count INT DEFAULT NULL, ADD delivery_preferred_time VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, ADD call_list_note VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE dtb_customer ADD CONSTRAINT FK_8298BBE3CF6B0AA7 FOREIGN KEY (order_time_zone_id) REFERENCES mtb_order_time_zone (id)');
        $this->addSql('CREATE INDEX IDX_8298BBE3CF6B0AA7 ON dtb_customer (order_time_zone_id)');
        $this->addSql('ALTER TABLE dtb_order ADD shipping_group_id INT UNSIGNED DEFAULT NULL, ADD delivery_type_id SMALLINT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE dtb_order ADD CONSTRAINT FK_1D66D80762BB97E8 FOREIGN KEY (shipping_group_id) REFERENCES dtb_shipping_group (id)');
        $this->addSql('ALTER TABLE dtb_order ADD CONSTRAINT FK_1D66D807CF52334D FOREIGN KEY (delivery_type_id) REFERENCES mtb_delivery_type (id)');
        $this->addSql('CREATE INDEX IDX_1D66D807CF52334D ON dtb_order (delivery_type_id)');
        $this->addSql('CREATE INDEX IDX_1D66D80762BB97E8 ON dtb_order (shipping_group_id)');
        $this->addSql('ALTER TABLE dtb_product_extension_item DROP gender_id, DROP reason_withdrawal, DROP ec_site_linked_classification_id, DROP web_order_permission_classification_id, DROP ordering_time, DROP deposit_box, DROP delivery_good_group, DROP classification_shipping_cost_calculation, DROP filling_control_table_output_classification, DROP repack_classification, DROP processed_product_category_id, DROP defrosting_method_id, DROP impoverished_area_id, DROP pref_id, DROP track_no');
    }
}
