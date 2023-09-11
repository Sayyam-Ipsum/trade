<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

/**
 *
 */
interface UserInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function listing($id = null);

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request);

    /**
     * @return mixed
     */
    public function systemUserListing();

    /**
     * @param $requst
     * @param $id
     * @return mixed
     */
    public function storeSystemUser($requst, $id);

    /**
     * @param $request
     * @return mixed
     */
    public function changePassword($request);
}
