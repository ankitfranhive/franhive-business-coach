<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class CloudTalkSecret implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $expected = env('CT_SECRET');
        $got = $request->getHeaderLine('X-CT-Secret');

        if (!$expected || !$got || !hash_equals($expected, $got)) {
            return Services::response()
                ->setStatusCode(401, 'Unauthorized')
                ->setJSON(['error' => 'invalid secret']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no-op
    }
}
