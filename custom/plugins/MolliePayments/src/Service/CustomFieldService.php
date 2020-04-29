<?php declare(strict_types=1);

namespace Kiener\MolliePayments\Service;

use Exception;
use Psr\Log\LoggerInterface;
use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Checkout\Order\OrderDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\System\CustomField\CustomFieldTypes;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CustomFieldService
{
    /** @var ContainerInterface */
    protected $container;

    /** @var EntityRepositoryInterface */
    protected $customFieldSetRepository;

    /**
     * CustomFieldService constructor.
     *
     * @param ContainerInterface $container
     * @param EntityRepositoryInterface $customFieldSetRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        $container,
        EntityRepositoryInterface $customFieldSetRepository
    )
    {
        $this->container = $container;
        $this->customFieldSetRepository = $customFieldSetRepository;
    }

    public function addCustomFields(Context $context)
    {
        try {
            $customFieldSetId = 'cfc5bddd41594779a00cd4aa31885530';
            $mollieOrderFieldId = '14cf2e774a67a3b3374b187948046038';
            $iDealIssuerFieldId = '486a390718f043a28bc6434be6f36aec';

            $this->customFieldSetRepository->upsert([[
                'id' => $customFieldSetId,
                'name' => 'mollie_payments',
                'config' => [
                    'label' => [
                        'en-GB' => 'Mollie'
                    ]
                ],
                'customFields' => [
                    [
                        'id' => $mollieOrderFieldId,
                        'name' => 'order_id',
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'componentName' => 'sw-field',
                            'customFieldType' => 'text',
                            'customFieldPosition' => 1,
                            'label' => [
                                'en-GB' => 'Mollie transaction ID',
                                'nl-NL' => 'Mollie transactienummer'
                            ]
                        ]
                    ],
                    [
                        'id' => $iDealIssuerFieldId,
                        'name' => 'preferred_ideal_issuer',
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'componentName' => 'sw-field',
                            'customFieldType' => 'text',
                            'customFieldPosition' => 1,
                            'label' => [
                                'en-GB' => 'Preferred iDeal issuer',
                                'nl-NL' => 'iDeal bankvoorkeur'
                            ]
                        ]
                    ]
                ],
                'relations' => [
                    [
                        'id' => $mollieOrderFieldId,
                        'entityName' => $this->container->get(OrderDefinition::class)->getEntityName()
                    ],
                    [
                        'id' => $iDealIssuerFieldId,
                        'entityName' => $this->container->get(CustomerDefinition::class)->getEntityName()
                    ]
                ]
            ]], $context);
        } catch (Exception $e) {
            // @todo Handle Exception
        }
    }
}