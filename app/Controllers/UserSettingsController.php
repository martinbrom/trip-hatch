<?php

namespace App\Controllers;

use Core\AlertHelper;
use Core\Http\Controller;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\RedirectResponse;
use Core\Language\Language;

class UserSettingsController extends Controller
{
    /** @var AlertHelper */
    private $alertHelper;

    /** @var Language */
    private $lang;

    /**
     * UserSettingsController constructor.
     * @param AlertHelper $alertHelper
     * @param Language $lang
     */
    function __construct(AlertHelper $alertHelper, Language $lang) {
        $this->alertHelper = $alertHelper;
        $this->lang = $lang;
    }

    /**
     * Returns a html response with profile page content
     * @return HtmlResponse Profile page
     */
    public function profile() {
        return $this->responseFactory->html('settings/profile.html.twig');
    }

    /**
     * @return HtmlResponse
     */
    public function changeDisplayNamePage() {
        // TODO: Add current data to form
        return $this->responseFactory->html('settings/changeDisplayName.html.twig');
    }

    /**
     * @return RedirectResponse
     */
    public function changeDisplayName() {
        // TODO: Change display name
        $this->alertHelper->success($this->lang->get('alerts.change-display-name.success'));
        return $this->responseFactory->redirect('/profile');
    }

    /**
     * @return HtmlResponse
     */
    public function changePasswordPage() {
        // TODO: Add current data to form
        return $this->responseFactory->html('settings/changePassword.html.twig');
    }

    /**
     * @return RedirectResponse
     */
    public function changePassword() {
        // TODO: Change password
        $this->alertHelper->success($this->lang->get('alerts.change-password.success'));
        return $this->responseFactory->redirect('/profile');
    }
}