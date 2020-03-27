<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\Production\LocalDelivery\Controller;

use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Production\LocalDelivery\Services\DeliverBoyLoginService;
use Shopware\Production\LocalDelivery\Services\DeliveryBoyRegisterService;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Storefront\Framework\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @RouteScope(scopes={"storefront"})
 */
class DeliveryBoyStorefrontController extends StorefrontController
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var DeliveryBoyRegisterService
     */
    private $deliveryBoyRegisterService;

    /**
     * @var DeliverBoyLoginService
     */
    private $deliverBoyLoginService;

    /**
     * @var Router
     */
    private $router;

    public function __construct(
        Environment $twig,
        DeliveryBoyRegisterService $deliveryBoyRegisterService,
        DeliverBoyLoginService $deliverBoyLoginService,
        Router $router
    ) {
        $this->twig = $twig;
        $this->deliveryBoyRegisterService = $deliveryBoyRegisterService;
        $this->deliverBoyLoginService = $deliverBoyLoginService;
        $this->router = $router;
    }

    /**
     * @Route("/deliveryboy/loginform", name="delivery.boy.login.form", methods={"GET"})
     */
    public function deliveryBoyLoginForm(): Response
    {
        $html = $this->renderStorefront(
            '@LocalDelivery/page/login.html.twig', [
                'registerUrl' => $this->router->generate('delivery.boy.form'),
            ]
        );

        return new Response($html);
    }

    /**
     * @Route("/deliveryboy/form", name="delivery.boy.form", methods={"GET"})
     */
    public function deliveryBoyRegistrationForm(): Response
    {
        $html = $this->renderStorefront('@LocalDelivery/page/form.html.twig');

        return new Response($html);
    }

    /**
     * @Route("/deliveryboy/login", name="delivery.boy.login", methods={"POST"})
     */
    public function deliveryBoyLogin(Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $loginData = $this->deliverBoyLoginService->getLoginDataFromRequest($request);
        $violations = $this->deliverBoyLoginService->validateLoginData($loginData);

        if (count($violations) > 0) {
            unset($loginData['password']);

            return new Response(
                $this->renderStorefront('@LocalDelivery/page/login.html.twig',
                    [
                        'errors' => ['Email or password is wrong'],
                        'data' => $loginData,
                    ]
                )
            );
        }

        $deliveryBoy = $this->deliverBoyLoginService->getDeliveryBoy($loginData['email'], $salesChannelContext->getContext());

        if ($deliveryBoy === null) {
            unset($loginData['password']);

            return new Response(
                $this->renderStorefront(
                    '@LocalDelivery/page/login.html.twig',
                    [
                        'errors' => ['Email or password is wrong'],
                        'data' => $loginData,
                    ]
                )
            );
        }

        $this->deliverBoyLoginService->loginDeliveryBoy($deliveryBoy, $loginData['password'], $salesChannelContext->getContext());

        return new RedirectResponse(
            $this->router->generate('delivery.boy.package.overview')
        );
    }

    /**
     * @Route("/deliveryboy/register", name="delivery.boy.register", methods={"POST"})
     */
    public function deliveryBoyRegistration(Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $data = $this->deliveryBoyRegisterService->getDeliveryBoyDataFromRequest($request);
        $violations = $this->deliveryBoyRegisterService->validateDeliveryBoyData($data);

        if (count($violations) > 0) {
            $errorMessages = $this->deliveryBoyRegisterService->getViolationMessages($violations);

            unset($data['password'], $data['password_confirm']);

            return new Response(
                $this->renderStorefront('@LocalDelivery/page/form.html.twig', ['errors' => $errorMessages, 'data' => $data])
            );
        }

        $this->deliveryBoyRegisterService->saveDeliveryBoy([$data], $salesChannelContext->getContext());

        return new Response(
            $this->renderStorefront('@LocalDelivery/page/successfully_registered.html.twig')
        );
    }

    /**
     * @Route("/deliveryboy/packageoverview", name="delivery.boy.package.overview", methods={"GET"})
     */
    public function deliveryBoyPackageOverview(Request $request, SalesChannelContext $salesChannelContext)
    {
        if (!$this->deliverBoyLoginService->isDeliveryBoyLoggedIn($salesChannelContext->getContext())) {
            return new RedirectResponse(
                $this->router->generate('delivery.boy.login.form')
            );
        }

        return new Response(
            $this->renderStorefront('@LocalDelivery/page/delivery_boy_package_overview.html.twig')
        );
    }
}
