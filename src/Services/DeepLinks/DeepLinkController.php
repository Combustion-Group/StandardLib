<?php

namespace Combustion\StandardLib\Services\DeepLinks;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Combustion\StandardLib\Controller;

/**
 * Class DeepLinkController
 *
 * @package Combustion\StandardLib\Services\DeepLinks
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class DeepLinkController extends Controller
{
    /**
     * @var DeepLinkService
     */
    private $deepLinkService;

    /**
     * DeepLinkController constructor.
     *
     * @param DeepLinkService $deepLinkService
     */
    public function __construct(DeepLinkService $deepLinkService)
    {
        $this->deepLinkService = $deepLinkService;
    }

    /**
     * @param Request $request
     * @return View
     */
    public function handle(Request $request) : View
    {
        $params     = $request->all();
        $headers    = $request->header();
        $view       = 'redirector';
        $data       = [];

        try {
            $data = $this->deepLinkService->handle($params, $headers);
        } catch (\Exception $e) {
            $view = 'error';
        }

        return view($view, $data);
    }
}
