<?php

namespace App\Controllers;

use App\Repositories\TripFilesRepository;
use App\Repositories\TripRepository;
use Core\AlertHelper;
use Core\Auth;
use Core\Http\Controller;
use Core\Http\Response\JsonResponse;
use Core\Http\Response\RedirectResponse;
use Core\Http\Response\Response;
use Core\Language\Language;
use Core\Storage\Storage;

/**
 * Class TripFilesController
 * @package App\Controllers
 * @author Martin Brom
 */
class TripFilesController extends Controller
{
    /** @var TripRepository */
    private $tripRepository;

    /** @var Auth */
    private $auth;

    /** @var AlertHelper */
    private $alertHelper;

    /** @var Language */
    private $lang;

    /** @var TripFilesRepository */
    private $tripFilesRepository;

    /** @var Storage */
    private $storage;

    /**
     * TripFilesController constructor.
     * @param TripRepository $tripRepository
     * @param Auth $auth
     * @param AlertHelper $alertHelper
     * @param Language $lang
     * @param TripFilesRepository $tripFilesRepository
     * @param Storage $storage
     */
    function __construct(
            TripRepository $tripRepository,
            Auth $auth,
            AlertHelper $alertHelper,
            Language $lang,
            TripFilesRepository $tripFilesRepository,
            Storage $storage) {
        $this->tripRepository = $tripRepository;
        $this->auth = $auth;
        $this->alertHelper = $alertHelper;
        $this->lang = $lang;
        $this->tripFilesRepository = $tripFilesRepository;
        $this->storage = $storage;
    }

    /**
     * @param $trip_id
     * @return Response
     */
    public function index($trip_id) {
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == NULL) {
            $this->alertHelper->error($this->lang->get('alerts.trip.missing'), 404);
            return $this->route('dashboard');
        }

        $files = $this->tripFilesRepository->getFiles($trip_id);

        return $this->responseFactory->html('trip/files.html.twig',
            ['trip' => $trip, 'files' => $files, 'isOrganiser' => $this->auth->isOrganiser($trip_id)]
        );
    }

    /**
     * @param $trip_id
     * @param $file_id
     * @return JsonResponse
     */
    public function delete($trip_id, $file_id) {
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == NULL) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.trip.missing'), 'error', 404);
        }

        $file = $this->tripFilesRepository->getFile($file_id);

        if ($file == NULL) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.trip-file.missing'), 'error', 404);
        }

        if (!$this->tripFilesRepository->delete($file_id)) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.trip-file-delete.error'), 'error', 500);
        }

        return $this->responseFactory->json([
            'message' => $this->lang->get('alerts.trip-file-delete.success'),
            'type' => 'success',
            'file_id' => $file_id
        ], 200);
    }

    /**
     * @param $trip_id
     * @return RedirectResponse
     */
    public function create($trip_id) {
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == NULL) {
            $this->alertHelper->error($this->lang->get('alerts.trip.missing'), 404);
            return $this->route('dashboard');
        }

        $title = $_POST['trip_file_title'];
        $file = $_FILES['trip_file'];

        $fileName = $this->storage->store($file);
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $title .= "." . $extension;

        if (!$this->tripFilesRepository->create($trip_id, $title, $fileName)) {
            $this->alertHelper->error($this->lang->get('alerts.trip-file-save.error'));
            return $this->route('trip.show', ['trip_id' => $trip_id]);
        }

        $this->alertHelper->success($this->lang->get('alerts.trip-file-save.success'));
        return $this->tripRoute('files', $trip_id);
    }
}