<?php declare(strict_types=1);

namespace Shopware\Production\Merchants\Test;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Customer\SalesChannel\AccountService;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextService;
use Shopware\Production\Merchants\Content\Merchant\Api\ProfileController;
use Shopware\Production\Merchants\Content\Merchant\MerchantEntity;
use Shopware\Production\Merchants\Content\Merchant\SalesChannelContextExtension;

class MerchantTest extends TestCase
{
    use IntegrationTestBehaviour;

    public function testMerchantCreateAndLoginAndProfileGetAndProfileUpdate()
    {
        $merchantData = $this->getMinimalMerchantData();

        $this->getContainer()
            ->get('merchant.repository')
            ->create([$merchantData], Context::createDefaultContext());

        $result = $this->getContainer()->get('merchant.repository')
            ->search(new Criteria([$merchantData['id']]), Context::createDefaultContext());

        self::assertSame(1, $result->count());
        self::assertNotEmpty($result->first()->getCustomerId());

        $this->getContainer()->get('customer.repository')->update([[
            'id' => $result->first()->getCustomerId(),
            'active' => true,
        ]], Context::createDefaultContext());

        $token = $this->getContainer()
            ->get(AccountService::class)
            ->loginWithPassword(
                new DataBag([
                    'username' => $merchantData['email'],
                    'password' => $merchantData['password'],
                ]),
                $this->getContainer()->get(SalesChannelContextService::class)->get(Defaults::SALES_CHANNEL, Uuid::randomHex())
            );

        self::assertNotEmpty($token);

        $salesChannelContext = $this->getContainer()
            ->get(SalesChannelContextService::class)
            ->get(Defaults::SALES_CHANNEL, $token);

        self::assertInstanceOf(MerchantEntity::class, SalesChannelContextExtension::extract($salesChannelContext));


        $response = $this->getContainer()
            ->get(ProfileController::class)
            ->profile($salesChannelContext);

        self::assertSame($merchantData['id'], json_decode($response->getContent())->id);
        self::assertSame('FOO', json_decode($response->getContent())->publicCompanyName);

        $response = $this->getContainer()
            ->get(ProfileController::class)
            ->save(new DataBag($this->getUpdateMerchantData()), $salesChannelContext);

        self::assertTrue(json_decode($response->getContent())->public);
    }

    public function testCustomerDelete(): void
    {
        $merchantData = $this->getMinimalMerchantData();

        $merchantRepository = $this->getContainer()->get('merchant.repository');

        $merchantRepository
            ->create([$merchantData], Context::createDefaultContext());

        $result = $merchantRepository
            ->search(new Criteria([$merchantData['id']]), Context::createDefaultContext());

        $merchantId = $result->first()->getId();
        $customerId = $result->first()->getCustomerId();

        $customerResult = $this->getContainer()->get('customer.repository')
            ->search(new Criteria([$customerId]), Context::createDefaultContext());
        self::assertSame(1, $customerResult->count());

        $merchantRepository->delete([['id' => $merchantId]], Context::createDefaultContext());

        $result = $merchantRepository
            ->search(new Criteria([$merchantData['id']]), Context::createDefaultContext());
        self::assertSame(0, $result->count());

        $customerResult = $this->getContainer()->get('customer.repository')
            ->search(new Criteria([$customerId]), Context::createDefaultContext());
        self::assertSame(0, $customerResult->count());
    }

    private function getMinimalMerchantData(): array
    {
        $merchantId = Uuid::randomHex();

        return [
            'id' => $merchantId,

            'publicCompanyName' => 'FOO',
            'email' => 'BAR@BAZ.COM',
            'password' => 'ABC',

            'salesChannelId' => Defaults::SALES_CHANNEL,
        ];
    }

    private function getUpdateMerchantData(): array
    {
        return [
            'public' => true,
            'publicCompanyName' => 'publicCompanyName',
            'publicPhoneNumber' => 'publicPhoneNumber',
            'publicEmail' => 'publicEmail',
            'publicOpeningTimes' => 'publicOpeningTimes',
            'publicDescription' => 'publicDescription',
            'publicWebsite' => 'publicWebsite',
            'categoryId' => $this->getRandomCategoryId(),
            'firstName' => 'firstName',
            'lastName' => 'lastName',
            'street' => 'street',
            'zip' => 'zip',
            'city' => 'city',
            'country' => 'country',
            'email' => 'email',
            'password' => 'password',
            'phoneNumber' => 'phoneNumber',
        ];
    }

    private function getRandomCategoryId(): string
    {

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('typeId', Defaults::SALES_CHANNEL_TYPE_STOREFRONT));

        return $this->getContainer()
            ->get('sales_channel.repository')
            ->search($criteria, Context::createDefaultContext())
            ->first()
            ->getNavigationCategoryId();
    }
}
