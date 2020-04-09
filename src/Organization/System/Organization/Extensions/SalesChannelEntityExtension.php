<?php declare(strict_types=1);

namespace Shopware\Production\Organization\System\Organization\Extensions;

use Shopware\Core\Framework\DataAbstractionLayer\EntityExtensionInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;
use Shopware\Production\Merchants\Content\Merchant\MerchantDefinition;
use Shopware\Production\Organization\System\Organization\OrganizationDefinition;

class SalesChannelEntityExtension implements EntityExtensionInterface
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            new OneToOneAssociationField('organization', 'id', 'sales_channel_id', OrganizationDefinition::class, false)
        );

        $collection->add(
            new OneToManyAssociationField('merchants', MerchantDefinition::class, 'sales_channel_id')
        );
    }

    public function getDefinitionClass(): string
    {
        return SalesChannelDefinition::class;
    }
}
