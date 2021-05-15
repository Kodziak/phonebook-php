<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use App\Validator\ShopValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ShopService;

class CustomerController
{
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @Route("/", name="default")
     */
    public function ping()
    {
        return new JsonResponse(['message' => 'pong']);
    }

    /**
     * @Route("/address", name="add_address", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];
        $phoneNumber = $data['phoneNumber'];
        $street = $data['street'];
        $postCode = $data['postCode'];
        $city = $data['city'];

        $shopService = new ShopService();
        $shop = $shopService->getLongLat($street, $postCode, $city);

        $longitude = $shop->lon;
        $latitude = $shop->lat;

        if (empty($name) || empty($phoneNumber) || empty($street) || empty($postCode) || empty($city) || empty($longitude) || empty($latitude)) {
            throw new BadRequestHttpException('Expecting mandatory parameters!');
        }

        $input = [
            'name' => $name,
            'phoneNumber' => $phoneNumber,
            'street' => $street,
            'postCode' => $postCode,
            'city' => $city,
            'longitude' => $longitude,
            'latitude' => $latitude,
        ];

        $shopValidator = new ShopValidator();
        $violations = $shopValidator->validate($input);

        if (count($violations) > 0) {
            throw new BadRequestHttpException($violations);
        }

        $this->customerRepository->saveCustomer($name, $phoneNumber, $street, $postCode, $city, $longitude, $latitude);
        return new JsonResponse(['status' => 'Address created!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/coordinates", name="add_coordinates", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add_coordinates(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];
        $phoneNumber = $data['phoneNumber'];
        $latitude = $data['latitude'];
        $longitude = $data['longitude'];

        $shopService = new ShopService();
        $shop = $shopService->getAddress($latitude, $longitude);

        $houseNumber = $shop['house_number'];
        $road = $shop['road'];
        $city = $shop['city'];
        $postCode = $shop['postcode'];

        $street = "$houseNumber $road";

        if (empty($longitude) || empty($latitude)) {
            throw new BadRequestHttpException('Expecting mandatory parameters!');
        }

        $input = [
            'name' => $name,
            'phoneNumber' => $phoneNumber,
            'street' => $street,
            'postCode' => $postCode,
            'city' => $city,
            'longitude' => $longitude,
            'latitude' => $latitude,
        ];

        $shopValidator = new ShopValidator();
        $violations = $shopValidator->validate($input);

        if (count($violations) > 0) {
            throw new BadRequestHttpException($violations);
        }

        $this->customerRepository->saveCustomer($name, $phoneNumber, $street, $postCode, $city, $longitude, $latitude);
        return new JsonResponse(['status' => 'Address created!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/address/{id}", name="get_one_customer", methods={"GET"})
     * @param $id
     * @return JsonResponse
     */
    public function get($id): JsonResponse
    {
        $customer = $this->customerRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $customer->getId(),
            'name' => $customer->getName(),
            'phoneNumber' => $customer->getPhoneNumber(),
            'street' => $customer->getStreet(),
            'postCode' => $customer->getPostCode(),
            'city' => $customer->getCity(),
            'longitude' => $customer->getLongitude(),
            'latitude' => $customer->getLatitude(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/address", name="get_all_customers", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $customers = $this->customerRepository->findAll();
        $data = [];

        foreach ($customers as $customer) {
            $data[] = [
                'id' => $customer->getId(),
                'name' => $customer->getName(),
                'phoneNumber' => $customer->getPhoneNumber(),
                'street' => $customer->getStreet(),
                'postCode' => $customer->getPostCode(),
                'city' => $customer->getCity(),
                'longitude' => $customer->getLongitude(),
                'latitude' => $customer->getLatitude(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/address/{id}", name="update_customer", methods={"PUT"})
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request): JsonResponse
    {
        $customer = $this->customerRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['name']) ? true : $customer->setName($data['name']);
        empty($data['phoneNumber']) ? true : $customer->setPhoneNumber($data['phoneNumber']);
        empty($data['street']) ? true : $customer->setStreet($data['street']);
        empty($data['postCode']) ? true : $customer->setPostCode($data['postCode']);
        empty($data['city']) ? true : $customer->setCity($data['city']);
        empty($data['longitude']) ? true : $customer->setLongitude($data['longitude']);
        empty($data['latitude']) ? true : $customer->setLatitude($data['latitude']);

        $updatedCostumer = $this->customerRepository->updateCustomer($customer);

        return new JsonResponse($updatedCostumer->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/address/{id}", name="delete_customer", methods={"DELETE"})
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $customer = $this->customerRepository->findOneBy(['id' => $id]);

        $this->customerRepository->removeCustomer($customer);

        return new JsonResponse(['status' => 'Customer deleted'], Response::HTTP_NO_CONTENT);
    }
}